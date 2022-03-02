<?php

namespace Tests\Feature\Console;

use Illuminate\Support\Facades\Storage;
use Tests\Helpers\Helpers;
use Tests\TestCase;
use function storage_path;

class SendDataToLogisticsTest extends TestCase
{
    use Helpers;

    public function setUp(): void
    {
        parent::setUp();

        $this->fakeHttpRequests();

        Storage::fake('local');
    }

    public function test_it_can_interact_from_the_console()
    {
        $date = '2019-07-07';
        $path = storage_path("exports/$date.csv");

        $this->artisan('logistics:send')
            ->expectsQuestion('Enter date you want report for', $date)
            ->expectsOutput("Report has been generated for $date")
            ->expectsOutput("Report can be found in $path")
            ->assertExitCode(0);

        $this->artisan('logistics:send')
            ->expectsQuestion('Enter date you want report for', 'someinvaliddate')
            ->expectsOutput('someinvaliddate is not a valid date')
            ->assertExitCode(1);
    }


    public function test_it_can_interact_from_the_console_with_default_options()
    {
        $date = '2019-07-07';

        $this->artisan("logistics:send $date")
            ->expectsOutput("Report has been generated for $date")
            ->assertExitCode(0);

        $this->artisan('logistics:send someinvaliddate')
            ->expectsOutput('someinvaliddate is not a valid date')
            ->assertExitCode(1);
    }

    public function test_it_can_interact_from_the_console_and_present_a_message_if_no_report_exists()
    {
        $date = '2090-07-07';

        $this->artisan("logistics:send $date")
            ->expectsOutput("No orders found for $date")
            ->assertExitCode(0);
    }
}
