<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\CheckStatusOfOrders',
        'App\Console\Commands\CheckStatusOfOrdersUkrPoshta',
        'App\Console\Commands\SetExchangeRate',
        'App\Console\Commands\CheckNotifiedUserProducts',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        if ($jobs = app(
            'App\Http\Controllers\SchedulerController'
        )->getSchedulerFile()
        ) {
            foreach ($jobs as $key => $job) {
                if ($job->active) {
                    if ($job->scheduleRule) {
                        $schedule->command($key)
                            ->{$job->scheduleRule}(
                                $job->scheduleRuleParameter
                            );
                    } else {
                        $schedule->command($key);
                    }

                }
            }
        }
    }
}