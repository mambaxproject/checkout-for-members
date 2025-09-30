<?php

namespace App\Services\GoogleAnalyticsService;

use Carbon\Carbon;
use Google\Analytics\Data\V1beta\{DateRange,
    Dimension,
    Filter,
    FilterExpression,
    Metric,
    RunRealtimeReportRequest,
    RunReportRequest};

class GoogleAnalyticsCheckoutService extends GoogleAnalytics
{
    public function __construct()
    {
        $credentialsPath = __DIR__ . '/credentials.json';

        $propertyId = config('services.google_analytics_checkout.view_id');

        parent::__construct($credentialsPath, $propertyId);
    }

    public function checkoutPurchasePerDay($startDate, $endDate): array
    {
        $eventFilter = new FilterExpression([
            'filter' => new Filter([
                'field_name'    => 'eventName',
                'string_filter' => new Filter\StringFilter([
                    'match_type' => Filter\StringFilter\MatchType::EXACT,
                    'value'      => 'purchase',
                ]),
            ]),
        ]);

        $request = (new RunReportRequest)
            ->setProperty("properties/$this->propertyId")
            ->setDateRanges([
                new DateRange([
                    'start_date' => $startDate,
                    'end_date'   => $endDate,
                ]),
            ])
            ->setMetrics([
                new Metric(['name' => 'eventCount']),
            ])
            ->setDimensions([
                new Dimension(['name' => 'date']),
            ])
            ->setDimensionFilter($eventFilter);

        $response = $this->client->runReport($request);

        $data = [];

        foreach ($response->getRows() as $row) {
            $date = Carbon::createFromFormat('Ymd', (string) $row->getDimensionValues()[0]->getValue());

            $users  = $row->getMetricValues()[0]->getValue();
            $data[] = [
                'date'  => $date->format('Y-m-d'),
                'day'   => $date->format('d/m'),
                'count' => $users,
            ];
        }

        return collect($data)->sortBy('date')->values()->all();
    }

    public function beginCheckoutPerDay($startDate, $endDate): array
    {
        $eventFilter = new FilterExpression([
            'filter' => new Filter([
                'field_name'    => 'eventName',
                'string_filter' => new Filter\StringFilter([
                    'match_type' => Filter\StringFilter\MatchType::EXACT,
                    'value'      => 'begin_checkout',
                ]),
            ]),
        ]);

        $request = (new RunReportRequest)
            ->setProperty("properties/$this->propertyId")
            ->setDateRanges([
                new DateRange([
                    'start_date' => $startDate,
                    'end_date'   => $endDate,
                ]),
            ])
            ->setMetrics([
                new Metric(['name' => 'eventCount']),
            ])
            ->setDimensions([
                new Dimension(['name' => 'date']),
            ])
            ->setDimensionFilter($eventFilter);

        $response = $this->client->runReport($request);

        $data = [];

        foreach ($response->getRows() as $row) {
            $date = Carbon::createFromFormat('Ymd', (string) $row->getDimensionValues()[0]->getValue());

            $users  = $row->getMetricValues()[0]->getValue();
            $data[] = [
                'date'  => $date->format('Y/m/d'),
                'day'   => $date->format('d/m'),
                'count' => $users,
            ];
        }

        return collect($data)->sortBy('date')->values()->all();
    }

    public function checkoutUniqueUserAccessPerDay($startDate, $endDate): array
    {
        $request = (new RunReportRequest)
            ->setProperty("properties/$this->propertyId")
            ->setDateRanges([
                new DateRange([
                    'start_date' => $startDate,
                    'end_date'   => $endDate,
                ]),
            ])
            ->setMetrics([
                new Metric(['name' => 'activeUsers']),
            ])
            ->setDimensions([
                new Dimension(['name' => 'date']),
            ]);

        $response = $this->client->runReport($request);

        $data = [];

        foreach ($response->getRows() as $row) {
            $date = Carbon::createFromFormat('Ymd', (string) $row->getDimensionValues()[0]->getValue());

            $users  = $row->getMetricValues()[0]->getValue();
            $data[] = [
                'date'  => $date->format('Y-m-d'),
                'day'   => $date->format('d/m'),
                'count' => $users,
            ];
        }

        return collect($data)->sortBy('date')->values()->all();
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
