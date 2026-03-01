<?php

namespace App\Services;

use App\Models\Colocation;
use Carbon\Carbon;

class BalanceService
{
    public function summary(Colocation $colocation): array
    {
        $membersAll = $colocation->members()
            ->withPivot(['role', 'joined_at', 'left_at'])
            ->withTimestamps()
            ->get();

        $expenses = $colocation->expenses()
            ->with(['payer', 'category'])
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        // balances in cents
        $balancesCents = [];
        foreach ($membersAll as $m) {
            $balancesCents[$m->id] = 0;
        }

        $totalCents = 0;

        // 1) حساب expenses
        foreach ($expenses as $e) {
            $expenseMoment = $this->toCarbon($e->created_at);

            $participants = $membersAll->filter(function ($m) use ($expenseMoment) {
                $joinMoment = $m->pivot->joined_at ?: $m->pivot->created_at;
                $leaveMoment = $m->pivot->left_at;

                return $this->isActiveAt($joinMoment, $leaveMoment, $expenseMoment);
            })->values();

            $count = $participants->count();
            if ($count === 0) continue;

            $amountCents = $this->toCents($e->amount);
            $totalCents += $amountCents;

            // split cents exactly
            $base = intdiv($amountCents, $count);
            $rem  = $amountCents - ($base * $count);

            foreach ($participants as $p) {
                $balancesCents[$p->id] -= $base;
            }
            for ($i = 0; $i < $rem; $i++) {
                $balancesCents[$participants[$i]->id] -= 1;
            }

            // payer gets full amount
            if (isset($balancesCents[$e->payer_id])) {
                $balancesCents[$e->payer_id] += $amountCents;
            }
        }

        // ✅ 2) حساب payments (خاص يجي قبل settlements)
        $payments = $colocation->payments()
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        foreach ($payments as $p) {
            $amountCents = $this->toCents($p->amount);

            // from paid => balance increases (less debt)
            if (isset($balancesCents[$p->from_user_id])) {
                $balancesCents[$p->from_user_id] += $amountCents;
            }

            // to received => balance decreases (less credit)
            if (isset($balancesCents[$p->to_user_id])) {
                $balancesCents[$p->to_user_id] -= $amountCents;
            }
        }

        // active members now
        $members = $membersAll->filter(fn($m) => empty($m->pivot->left_at))->values();

        // 3) floats AFTER payments
        $balances = [];
        foreach ($balancesCents as $id => $c) {
            $balances[$id] = $this->fromCents($c);
        }

        // 4) settlements AFTER payments
        $settlements = $this->buildSettlements($members, $balancesCents);

        return [
            'members' => $members,
            'membersAll' => $membersAll,
            'expenses' => $expenses,
            'payments' => $payments, // (اختياري) إلى بغيتي تعرض history
            'total' => $this->fromCents($totalCents),
            'balances' => $balances,
            'settlements' => $settlements,
        ];
    }

    private function isActiveAt($joinedAt, $leftAt, Carbon $moment): bool
    {
        $joined = $this->toCarbonOrNull($joinedAt);
        $left   = $this->toCarbonOrNull($leftAt);

        $joinedOk = !$joined || $joined->lessThanOrEqualTo($moment);
        $leftOk   = !$left || $left->greaterThanOrEqualTo($moment);

        return $joinedOk && $leftOk;
    }

    private function buildSettlements($members, array $balancesCents): array
    {
        $nameById = $members->pluck('name', 'id')->toArray();

        $creditors = [];
        $debtors = [];

        foreach ($members as $m) {
            $bal = $balancesCents[$m->id] ?? 0;
            if ($bal > 0) $creditors[] = ['id' => $m->id, 'amount' => $bal];
            if ($bal < 0) $debtors[] = ['id' => $m->id, 'amount' => abs($bal)];
        }

        $i = 0; $j = 0; $out = [];

        while ($i < count($debtors) && $j < count($creditors)) {
            $pay = min($debtors[$i]['amount'], $creditors[$j]['amount']);

            $out[] = [
                'from_id' => $debtors[$i]['id'],
                'from' => $nameById[$debtors[$i]['id']] ?? '—',
                'to_id' => $creditors[$j]['id'],
                'to' => $nameById[$creditors[$j]['id']] ?? '—',
                'amount' => $this->fromCents($pay),
            ];

            $debtors[$i]['amount'] -= $pay;
            $creditors[$j]['amount'] -= $pay;

            if ($debtors[$i]['amount'] <= 0) $i++;
            if ($creditors[$j]['amount'] <= 0) $j++;
        }

        return $out;
    }

    private function toCents($amount): int
    {
        return (int) round(((float)$amount) * 100);
    }

    private function fromCents(int $cents): float
    {
        return round($cents / 100, 2);
    }

    private function toCarbon($v): Carbon
    {
        return $v instanceof Carbon ? $v : Carbon::parse($v);
    }

    private function toCarbonOrNull($v): ?Carbon
    {
        if (empty($v)) return null;
        return $v instanceof Carbon ? $v : Carbon::parse($v);
    }
}