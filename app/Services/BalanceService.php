<?php

namespace App\Services;

use App\Models\Colocation;

class BalanceService
{
    public function summary(Colocation $colocation): array
    {
        // أعضاء مازالين فـ colocation (left_at = null)
        $members = $colocation->members()
            ->wherePivotNull('left_at')
            ->get();

        $expenses = $colocation->expenses()
            ->with(['payer', 'category'])
            ->get();

        $n = $members->count();
        $total = (float) $expenses->sum('amount');
        $share = $n > 0 ? round($total / $n, 2) : 0;

        // total paid per member
        $paid = [];
        foreach ($members as $m) {
            $paid[$m->id] = 0.0;
        }
        foreach ($expenses as $e) {
            if (isset($paid[$e->payer_id])) {
                $paid[$e->payer_id] += (float) $e->amount;
            }
        }

        // balance = paid - share
        $balances = [];
        foreach ($members as $m) {
            $balances[$m->id] = round($paid[$m->id] - $share, 2);
        }

        // who owes who (simplified)
        $settlements = $this->buildSettlements($members, $balances);

        return compact('members', 'expenses', 'total', 'share', 'paid', 'balances', 'settlements');
    }

    private function buildSettlements($members, array $balances): array
    {
        $nameById = $members->pluck('name', 'id')->toArray();

        $creditors = [];
        $debtors = [];

        foreach ($balances as $userId => $bal) {
            if ($bal > 0) $creditors[] = ['id' => $userId, 'amount' => $bal];
            if ($bal < 0) $debtors[] = ['id' => $userId, 'amount' => abs($bal)];
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