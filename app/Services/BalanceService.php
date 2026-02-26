<?php

namespace App\Services;

use App\Models\Colocation;

class BalanceService
{
    public function balances(Colocation $colocation): array
    {
        $members = $colocation->activeMembers()->get();

        $total = $colocation->expenses()->sum('amount');
        $count = max(1, $members->count());
        $share = $total / $count;

        // total paid per user
        $paidMap = [];
        foreach ($members as $m) $paidMap[$m->id] = 0.0;

        $expenses = $colocation->expenses()->get();
        foreach ($expenses as $e) {
            $paidMap[$e->payer_id] = ($paidMap[$e->payer_id] ?? 0) + (float)$e->amount;
        }

        // balance = paid - share
        $balances = [];
        foreach ($members as $m) {
            $balances[$m->id] = round(($paidMap[$m->id] ?? 0) - $share, 2);
        }

        return $balances;
    }

    public function settlements(Colocation $colocation): array
    {
        $balances = $this->balances($colocation);

        $debtors = [];
        $creditors = [];

        foreach ($balances as $userId => $bal) {
            if ($bal < 0) $debtors[] = ['user_id' => $userId, 'amount' => abs($bal)];
            if ($bal > 0) $creditors[] = ['user_id' => $userId, 'amount' => $bal];
        }

        $i = 0; $j = 0;
        $settlements = [];

        while ($i < count($debtors) && $j < count($creditors)) {
            $pay = min($debtors[$i]['amount'], $creditors[$j]['amount']);

            $settlements[] = [
                'from_user_id' => $debtors[$i]['user_id'],
                'to_user_id'   => $creditors[$j]['user_id'],
                'amount'       => round($pay, 2),
            ];

            $debtors[$i]['amount'] -= $pay;
            $creditors[$j]['amount'] -= $pay;

            if ($debtors[$i]['amount'] <= 0.0001) $i++;
            if ($creditors[$j]['amount'] <= 0.0001) $j++;
        }

        return $settlements;
    }
}