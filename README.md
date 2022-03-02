Export order details app.

Folder structure

`app/Console/Commands` - Holds the console application (`SendDataToLogistics.php`)
This command has the signature `logistics:send {date?}` with an optional date, if the date is not provided, a prompt is
presented to ask for the date.

If an invalid date is given, a message is returned saying the date is invalid. If no orders exist for the given date, a
message is returned too.

`app/Contracts` - Holds all interface declarations (`Repositories and Services`)

`app/Repositories` - Holds all repositories

`app/Services` - Holds all services

`app/Exceptions` - Holds the thrown exceptions

`app/Providers` - Providers for binding interfaces to implementations

`app/tests/Unit` - Tests for Repositories and Services

`app/tests/Feature/Console` - Tests for the console application

`app/tests/Helpers` - Helper methods for faking http requests

The `CSVOrdersExportService::class` uses `League\Csv\Writer` to write the order data to csv.

Rename `.env.example` to `.env`

Use `./vendor/bin/sail up -d` to start the laravel sail docker services

Run `./vendor/bin/sail artisan logistics:send` to test command.

Run `./vendor/bin/sail artisan test` to run tests.
