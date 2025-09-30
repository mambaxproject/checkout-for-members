<?php

namespace App\Services\Reports\Subscription;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReportSubscriptionService
{
    public function processSubscriptionMetrics(array $subscriptionsData, Collection $paidSubscriptions): array
    {
        $transactionsSubscriptions = collect($subscriptionsData)->pluck('transactions')->flatten(1);

        $billingMetrics = $this->calculateBillingMetrics($subscriptionsData);

        $conversionMetrics = $this->calculateConversionMetrics($subscriptionsData, $paidSubscriptions);

        $successRate = $this->calculateSuccessRate(
            $billingMetrics['totalProcessed'],
            $billingMetrics['totalSuccessful']
        );

        $conversionRate = $this->calculateConversionRate(
            $conversionMetrics['totalLinksGenerated'],
            $conversionMetrics['totalPlanChanges']
        );

        $upgradePercentage = $this->calculatePercentage(
            $conversionMetrics['totalPlanChanges'],
            $conversionMetrics['totalUpgrades']
        );

        $downgradePercentage = $this->calculatePercentage(
            $conversionMetrics['totalPlanChanges'],
            $conversionMetrics['totalDowngrades']
        );

        $successRateChange    = $this->getSuccessRateChange($transactionsSubscriptions->toArray());
        $conversionRateChange = $this->getConversionRateChange($subscriptionsData);

        return [
            'successRate' => [
                'value'  => number_format($successRate, 1),
                'change' => $successRateChange,
                'total'  => [
                    'processed'  => $billingMetrics['totalProcessed'],
                    'successful' => $billingMetrics['totalSuccessful'],
                    'failed'     => $billingMetrics['totalFailed'],
                    'amount'     => $billingMetrics['totalAmount'],
                ],
            ],
            'conversionRate' => [
                'value'  => number_format($conversionRate, 1),
                'change' => $conversionRateChange,
                'total'  => [
                    'links'               => $conversionMetrics['totalLinksGenerated'],
                    'conversions'         => $conversionMetrics['totalPlanChanges'],
                    'upgrades'            => $conversionMetrics['totalUpgrades'],
                    'downgrades'          => $conversionMetrics['totalDowngrades'],
                    'upgradePercentage'   => number_format($upgradePercentage, 1),
                    'downgradePercentage' => number_format($downgradePercentage, 1),
                ],
            ],
        ];
    }

    public function calculateBillingMetrics(array $subscriptionsData): array
    {
        $totalProcessed = $totalSuccessful = $totalFailed = $totalAmount = 0;

        foreach ($subscriptionsData as $details) {
            $transactions = collect($details['transactions'] ?? []);

            $totalProcessed += $transactions->count();

            $totalSuccessful += $transactions->filter(function ($transaction) {
                return in_array(strtolower($transaction['statusTransaction']), array_map('strtolower', Order::$statusForPaid));
            })->count();

            $totalFailed += $transactions->filter(function ($transaction) {
                return in_array(strtolower($transaction['statusTransaction']), array_map('strtolower', Order::$statusForFailed));
            })->count();

            $totalAmount += $transactions->filter(function ($transaction) {
                return in_array(strtolower($transaction['statusTransaction']), array_map('strtolower', Order::$statusForPaid));
            })->sum('value');
        }

        return [
            'totalProcessed'  => $totalProcessed,
            'totalSuccessful' => $totalSuccessful,
            'totalFailed'     => $totalFailed,
            'totalAmount'     => $totalAmount,
        ];
    }
    public function calculateConversionMetrics(array $subscriptionsData, Collection $paidSubscriptions): array
    {
        $totalUpgrades = $totalDowngrades = $totalPlanChanges = 0;

        foreach ($subscriptionsData as $details) {
            $transactions = collect($details['transactions'] ?? []);

            if ($transactions->count() > 1) {
                $values = $transactions->pluck('value')->toArray();

                if (count(array_unique($values)) > 1) { // mudança de valores
                    $totalPlanChanges++;
                    // Compara primeiro valor com o último para determinar se foi upgrade ou downgrade
                    // Considera o segundo pagamento como referência inicial, pois o primeiro pode ter configurado um valor diferente
                    $firstValue = $values[1] ?? $values[0];
                    $lastValue  = $values[count($values) - 1] ?? 0;

                    if ($lastValue > $firstValue) {
                        $totalUpgrades++;
                    } elseif ($lastValue < $firstValue) {
                        $totalDowngrades++;
                    }
                }
            }
        }

        $totalLinksGenerated = $this->countLinksGenerated($paidSubscriptions);

        return [
            'totalPlanChanges'    => $totalPlanChanges,
            'totalUpgrades'       => $totalUpgrades,
            'totalDowngrades'     => $totalDowngrades,
            'totalLinksGenerated' => $totalLinksGenerated,
        ];
    }

    public function countLinksGenerated(Collection $paidSubscriptions): int
    {
        return $paidSubscriptions->sum(function ($subscription) {
            return $subscription->comments->filter(function ($comment) {
                return str_contains($comment->comment, 'enviou o link de atualização');
            })->count();
        });
    }

    public function calculateSuccessRate(int $totalProcessed, int $totalSuccessful): float
    {
        return $totalProcessed > 0 ? ($totalSuccessful / $totalProcessed) * 100 : 0;
    }

    public function calculateConversionRate(int $totalLinks, int $totalConversions): float
    {
        return $totalLinks > 0 ? ($totalConversions / $totalLinks) * 100 : 0;
    }

    public function calculatePercentage(int $total, int $part): float
    {
        return $total > 0 ? ($part / $total) * 100 : 0;
    }

    public function getSuccessRateChange(array $transactionsSubscriptions = []): array
    {
        $currentMonth  = now()->format('Y-m');
        $previousMonth = now()->subMonth()->format('Y-m');

        $processedCurrent = $successfulCurrent = 0;
        $processedPrev    = $successfulPrev = 0;

        foreach ($transactionsSubscriptions as $transaction) {
            $date   = Carbon::parse($transaction['transactionDate']);
            $status = strtolower($transaction['statusTransaction']);

            if ($date->format('Y-m') === $currentMonth) {
                $processedCurrent++;

                if (in_array($status, array_map('strtolower', Order::$statusForPaid))) {
                    $successfulCurrent++;
                }
            } elseif ($date->format('Y-m') === $previousMonth) {
                $processedPrev++;

                if (in_array($status, array_map('strtolower', Order::$statusForPaid))) {
                    $successfulPrev++;
                }
            }
        }

        $successRateCurrent = $processedCurrent > 0 ? ($successfulCurrent / $processedCurrent) * 100 : 0;
        $successRatePrev    = $processedPrev > 0 ? ($successfulPrev / $processedPrev) * 100 : 0;

        $successRateChange = $successRatePrev > 0
            ? (($successRateCurrent - $successRatePrev) / $successRatePrev) * 100
            : 0;
        $successRateChange = number_format($successRateChange, 1);

        return [
            'successRateCurrent' => $successRateCurrent,
            'successRatePrev'    => $successRatePrev,
            'successRateChange'  => $successRateChange,
            'successRateTrend'   => $successRateChange > 0 ? '+' : ($successRateChange < 0 ? '-' : ''),
        ];
    }

    public function getConversionRateChange(array $subscriptionsData = []): array
    {
        $currentMonth  = now()->format('Y-m');
        $previousMonth = now()->subMonth()->format('Y-m');

        $links       = ['current' => 0, 'prev' => 0];
        $planChanges = ['current' => 0, 'prev' => 0];

        foreach ($subscriptionsData as $details) {
            $transactions = collect($details['transactions'] ?? []);
            $comments     = collect($details['comments'] ?? []);

            foreach ($comments as $comment) {
                if (isset($comment['comment'])) {
                    $date = Carbon::parse($comment['createdAt'] ?? $comment['created_at']);

                    if (str_contains($comment['comment'], 'enviou o link de atualização')) {
                        $month = $date->format('Y-m');

                        if ($month === $currentMonth) {
                            $links['current']++;
                        } elseif ($month === $previousMonth) {
                            $links['prev']++;
                        }
                    }
                }
            }

            if ($transactions->count() > 1) {
                $values = $transactions->pluck('value')->toArray();

                if (count(array_unique($values)) > 1) {
                    $firstValue = $values[1] ?? $values[0];
                    $lastValue  = $values[count($values) - 1] ?? 0;
                    $lastDate   = Carbon::parse($transactions->last()['transactionDate']);
                    $month      = $lastDate->format('Y-m');

                    if ($month === $currentMonth) {
                        $planChanges['current']++;
                    } elseif ($month === $previousMonth) {
                        $planChanges['prev']++;
                    }
                }
            }
        }

        $conversionRate = [
            'current' => $links['current'] > 0 ? ($planChanges['current'] / $links['current']) * 100 : 0,
            'prev'    => $links['prev'] > 0 ? ($planChanges['prev'] / $links['prev']) * 100 : 0,
        ];

        $conversionRateChange = $conversionRate['prev'] > 0
            ? (($conversionRate['current'] - $conversionRate['prev']) / $conversionRate['prev']) * 100
            : 0;

        return [
            'conversionRateCurrent' => $conversionRate['current'],
            'conversionRatePrev'    => $conversionRate['prev'],
            'conversionRateChange'  => number_format($conversionRateChange, 1),
            'conversionRateTrend'   => $conversionRateChange > 0 ? '+' : ($conversionRateChange < 0 ? '-' : ''),
        ];
    }

    public function getTotalRevenue(array $subscriptionsData): float
    {
        $totalRevenue = 0;

        foreach ($subscriptionsData as $details) {
            $transactions = collect($details['transactions'] ?? []);

            $totalRevenue += $transactions->filter(function ($transaction) {
                return in_array(strtolower($transaction['statusTransaction']), array_map('strtolower', Order::$statusForPaid));
            })->sum('value');
        }

        return $totalRevenue;
    }
}
