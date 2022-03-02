<?php

namespace App\Console\Commands;

use App\Contracts\Services\ExportOrdersServiceInterface;
use App\Exceptions\NoOrdersAvailableException;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Console\Command;

class SendDataToLogistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logistics:send {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends data to logistics';
    private ExportOrdersServiceInterface $exportOrdersService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ExportOrdersServiceInterface $exportOrdersService)
    {
        parent::__construct();
        $this->exportOrdersService = $exportOrdersService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = $this->argument('date');
        if (is_null($date)) {
            $date = $this->ask('Enter date you want report for');
        }
        try {
            $date = Carbon::parse($date);
            $path = $this->exportOrdersService->export($date);
            $this->info("Report has been generated for {$date->toDateString()}");
            $this->info("Report can be found in $path");
            return 0;
        } catch (InvalidFormatException $exception) {
            $this->error("$date is not a valid date");
            return 1;
        } catch (NoOrdersAvailableException $exception) {
            $this->info($exception->getMessage());
            return 0;
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            return 1;
        }
    }
}
