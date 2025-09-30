<?php

namespace App\Services\GoogleAnalyticsService;

use Google\Analytics\Data\V1beta\{
    Metric,
    RunRealtimeReportRequest};

class GoogleAnalyticsSalesService extends GoogleAnalytics
{
    public function __construct()
    {
        $credentialsPath = __DIR__ . '/suit-sales-credentials.json';

        $propertyId = config('services.google_analytics_sales.view_id');

        parent::__construct($credentialsPath, $propertyId);
    }

    public function activeUsers(): int
    {
        $request = (new RunRealtimeReportRequest)
            ->setProperty('properties/' . $this->propertyId)
            ->setMetrics([
                new Metric(['name' => 'activeUsers']),
            ]);

        $response = $this->client->runRealtimeReport($request);

        $count = 0;

        foreach ($response->getRows() as $row) {
            $count += $row->getMetricValues()[0]->getValue();
        }

        return $count;
    }
}
