<?php

namespace App\Services;

use App\Models\Colocation;

class BalanceService
{
    public function summary(Colocation $colocation): array
    {
        $membersAll = $colocation->members()
            ->withPivot('joined_at', 'left_at', 'role')
            ->get();

        $expenses = $colocation->expenses()
            ->with(['payer', 'category'])
            ->orderBy('date')
            ->get();

        $balances = [];
        foreach ($membersAll as $m) {
            $balances[$m->id] = 0.0;
        }

        foreach ($expenses as $e) {
            $expenseDate = $e->date;

            $participants = $membersAll->filter(function ($m) use ($expenseDate) {
                $joined = optional($m->pivot->joined_at);
                $left = optional($m->pivot->left_at);

                $joinedOk = $joined ? $joined->toDateString() <= $expenseDate->toDateString() : true;

                $leftOk = !$left || $left->toDateString() >= $expenseDate->toDateString();

                return $joinedOk && $leftOk;
            });

            $count = $participants->count();
            if ($count === 0) continue;

            $amount = (float) $e->amount;
            $share = round($amount / $count, 2);

            foreach ($participants as $p) {
                $balances[$p->id] -= $share;
            }

            $balances[$e->payer_id] += $amount;
        }

        foreach ($balances as $id => $b) {
            $balances[$id] = round($b, 2);
        }

        $membersActiveNow = $membersAll->filter(fn($m) => $m->pivot->left_at === null)->values();
        $settlements = $this->buildSettlements($membersActiveNow, $balances);

        $total = round((float) $expenses->sum('amount'), 2);

        return [
            'members' => $membersActiveNow,
            'membersAll' => $membersAll,
            'expenses' => $expenses,
            'total' => $total,
            'balances' => $balances,
            'settlements' => $settlements,
        ];
    }

    private function buildSettlements($members, array $balances): array
    {
        $nameById = $members->pluck('name', 'id')->toArray();

        $creditors = [];
        $debtors = [];

        foreach ($members as $m) {
            $bal = $balances[$m->id] ?? 0;

            if ($bal > 0) $creditors[] = ['id' => $m->id, 'amount' => $bal];
            if ($bal < 0) $debtors[] = ['id' => $m->id, 'amount' => abs($bal)];
        }

        $i = 0; $j = 0;
        $result = [];

        while ($i < count($debtors) && $j < count($creditors)) {
            $pay = min($debtors[$i]['amount'], $creditors[$j]['amount']);

            $result[] = [
                'from_id' => $debtors[$i]['id'],
                'from' => $nameById[$debtors[$i]['id']] ?? '—',
                'to_id' => $creditors[$j]['id'],
                'to' => $nameById[$creditors[$j]['id']] ?? '—',
                'amount' => round($pay, 2),
            ];

            $debtors[$i]['amount'] -= $pay;
            $creditors[$j]['amount'] -= $pay;

            if ($debtors[$i]['amount'] <= 0.0001) $i++;
            if ($creditors[$j]['amount'] <= 0.0001) $j++;
        }

        return $result;
    }
}