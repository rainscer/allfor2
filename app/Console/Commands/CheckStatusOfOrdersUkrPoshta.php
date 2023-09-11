<?php namespace App\Console\Commands;

use App\Models\Order;
use App\Models\SchedulerLog;
use Illuminate\Console\Command;

/**
 * Class CheckStatusOfOrdersUkrPoshta
 * @package App\Console\Commands
 */
class CheckStatusOfOrdersUkrPoshta extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'CheckStatusOfOrdersUkrPoshta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check status of orders on ukrposhta';

    /**
     * @return bool
     */
    public function handle()
    {

        $result = Order::processCheckStatusOfOrdersUkrPoshta();

        SchedulerLog::write(
            $this->name,
            $result
        );

        return true;
    }
}
