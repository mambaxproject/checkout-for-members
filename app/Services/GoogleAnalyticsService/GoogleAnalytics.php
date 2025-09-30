<?php

namespace App\Services\GoogleAnalyticsService;

use Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient;

class GoogleAnalytics
{
    protected BetaAnalyticsDataClient $client;
    protected string $propertyId;

    public function __construct(string $credentialsPath, string $propertyId)
    {
        $this->propertyId = $propertyId;

        $this->client = new BetaAnalyticsDataClient([
            'credentials' => $credentialsPath,
        ]);
    }
}
