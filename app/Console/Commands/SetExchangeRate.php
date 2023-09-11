<?php namespace App\Console\Commands;

use App\Models\ExchangeRate;
use App\Models\SchedulerLog;
use App\Models\Settings;
use Illuminate\Console\Command;

/**
 * Class SetExchangeRate
 * @package App\Console\Commands
 */
class SetExchangeRate extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'SetExchangeRate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set Exchange rate from national bank of Ukraine';

    /**
     * @return bool
     */
    public function handle()
    {
        $ex = new ExchangeRate();
        $data = $ex->getExchangeRateByCode('USD',true);

        if($data){
            $curencies = [
                'curency_ua',
                'curency_ru'
            ];

            Settings::whereIn('key_name', $curencies)
                ->update([
                    'value' => $data
                ]);
            $result = 'Current exchange rate = ' . $data;
            SchedulerLog::write(
                $this->name,
                $result
            );
        }

        return true;
    }
}
