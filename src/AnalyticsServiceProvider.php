<?php

namespace NguyenHuy\Analytics;

use NguyenHuy\Analytics\Exceptions\InvalidConfiguration;
use Illuminate\Support\ServiceProvider;

class AnalyticsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->bind(AnalyticsClient::class, function () {
            $analyticsConfig = config('analytics');

            return AnalyticsClientFactory::createForConfig($analyticsConfig);
        });

        $this->app->singleton('laravel-analytics', function () {
            $analyticsConfig = config('analytics');

            $this->guardAgainstInvalidConfiguration($analyticsConfig);

            $client = app(AnalyticsClient::class);

            return new Analytics($client, $analyticsConfig['property_id']);
        });
    }

    protected function guardAgainstInvalidConfiguration(array $analyticsConfig = null): void
    {
        if (empty($analyticsConfig['property_id'])) {
            throw InvalidConfiguration::propertyIdNotSpecified();
        }

        if (is_array($analyticsConfig['service_account_credentials_json'])) {
            return;
        }

        if (! file_exists($analyticsConfig['service_account_credentials_json'])) {
            throw InvalidConfiguration::credentialsJsonDoesNotExist($analyticsConfig['service_account_credentials_json']);
        }
    }
}
