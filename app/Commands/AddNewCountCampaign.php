<?php namespace App\Commands;

use App\Commands\Command;
use App\Models\AdvertisingCampaign;
use App\Models\ItemAdvertisingCampaign;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Http\Request;

class AddNewCountCampaign extends Command implements SelfHandling {

    /**
     * @var AdvertisingCampaign
     */
    protected $campaign;

    /**
     * @var
     */
    protected $type;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var int
     */
    protected $product;

    /**
     * Create a new job instance.
     *
     * @param Request $request
     * @param int $campaign
     * @param int $product
     * @param $type
     */
    public function __construct(Request $request, $campaign = 0, $product = 0, $type)
    {
        $this->campaign = $campaign;
        $this->type = $type;
        $this->request = $request;
        $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $campaign = $this->campaign;
        $type = $this->type;
        $request = $this->request;
        $product = $this->product;

        ItemAdvertisingCampaign::addNewCount($request, $campaign, $product, $type);
    }

}
