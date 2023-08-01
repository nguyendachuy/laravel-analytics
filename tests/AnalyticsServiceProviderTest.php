<?php

use Illuminate\Support\Facades\Storage;
use NguyenHuy\Analytics\Exceptions\InvalidConfiguration;
use NguyenHuy\Analytics\Facades\Analytics;
use NguyenHuy\Analytics\Period;

it('will throw an exception if the property id is not set', function () {
    config()->set('analytics.property_id', '');

    Analytics::fetchVisitorsAndPageViews(Period::days(7), now());
})->throws(InvalidConfiguration::class);

it('allows credentials json file', function () {
    Storage::fake('testing-storage');

    Storage::disk('testing-storage')
        ->put('test-credentials.json', json_encode(credentials()));

    $credentialsPath = storage_path('framework/testing/disks/testing-storage/test-credentials.json');

    config()->set('analytics.property_id', '123456');

    config()->set('analytics.service_account_credentials_json', $credentialsPath);

    $analytics = $this->app['laravel-analytics'];

    expect($analytics)->toBeInstanceOf(\NguyenHuy\Analytics\Analytics::class);
});

it('will throw an exception if the credentials json does not exist', function () {
    config()->set('analytics.property_id', '123456');

    config()->set('analytics.service_account_credentials_json', 'bogus.json');

    Analytics::fetchVisitorsAndPageViews(now()->subDay(), now());
})->throws(InvalidConfiguration::class);

it('allows credentials json to be array', function () {
    config()->set('analytics.property_id', '123456');

    config()->set('analytics.service_account_credentials_json', credentials());

    $analytics = $this->app['laravel-analytics'];

    expect($analytics)->toBeInstanceOf(\NguyenHuy\Analytics\Analytics::class);
});

function credentials(): array
{
    return [
        'type' => 'service_account',
        'project_id' => 'bogus-project',
        'private_key_id' => 'bogus-id',
        'private_key' => 'bogus-key',
        'client_email' => 'bogus-user@bogus-app.iam.gserviceaccount.com',
        'client_id' => 'bogus-id',
        'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
        'token_uri' => 'https://accounts.google.com/o/oauth2/token',
        'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
        'client_x509_cert_url' => 'https://www.googleapis.com/robot/v1/metadata/x509/bogus-ser%40bogus-app.iam.gserviceaccount.com',
    ];
}
