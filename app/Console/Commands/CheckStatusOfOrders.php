<?php namespace App\Console\Commands;

use App\Models\Order;
use App\Models\SchedulerLog;
use Illuminate\Console\Command;

/**
 * Class CheckStatusOfOrders
 * @package App\Console\Commands
 */
class CheckStatusOfOrders extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'CheckStatusOfOrders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check status of orders';

    /**
     * @return bool
     */
    public function handle()
    {

        $result = Order::processCheckStatusesOfOrders();

        SchedulerLog::write(
            $this->name,
            $result
        );

        return true;
    }
}
