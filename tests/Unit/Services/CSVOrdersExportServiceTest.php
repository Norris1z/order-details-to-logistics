<?php

namespace Tests\Unit\Services;

use App\Contracts\Services\ExportOrdersServiceInterface;
use App\Exceptions\NoOrdersAvailableException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Helpers\Helpers;

class CSVOrdersExportServiceTest extends TestCase
{
    use Helpers;

    /**
     * @var ExportOrdersServiceInterface
     */
    private $repo;

    public function setUp(): void
    {
        parent::setUp();

        $this->fakeHttpRequests();

        $this->repo = app(ExportOrdersServiceInterface::class);

        Storage::fake('local');
    }

    public function test_it_can_export_data_to_csv()
    {
        $date = Carbon::parse("2019-07-07 08:46:46");
        $file = "exports/{$date->toDateString()}.csv";

        $path = $this->repo->export($date);

        Storage::disk('local')->assertExists($file);
        $this->assertSame(storage_path($file), $path);
    }

    public function test_it_can_throw_an_exception_if_no_reports_are_found_for_the_given_date()
    {
        $this->expectException(NoOrdersAvailableException::class);
        $this->repo->export(now()->addYear());
    }
}
