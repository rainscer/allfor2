<?php namespace App\Console\Commands;

use App\Models\Order;
use App\Models\SchedulerLog;
use App\Models\WaitingProductUser;
use Illuminate\Console\Command;

/**
 * Class CheckNotifiedUserProducts
 * @package App\Console\Commands
 */
class CheckNotifiedUserProducts extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'CheckNotifiedUserProducts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check waiting user product';

    /**
     * @return bool
     */
    public function handle()
    {

        $result = WaitingProductUser::processCheckNotifiedUserProducts();

        SchedulerLog::write(
            $this->name,
            $result
        );

        return true;
    }
}
