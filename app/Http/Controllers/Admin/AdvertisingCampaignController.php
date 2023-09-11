<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdvertisingCampaign;
use App\Models\ItemAdvertisingCampaign;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use File;
use Auth;

class AdvertisingCampaignController extends BaseController
{

    /**
     * SettingController constructor.
     */
    public function __construct()
    {
        $this->module_name = 'Advertising campaign';
        $this->module_alias = 'advertisingCampaign';
    }

    /**
     * @return string
     */
    public function getModelClass()
    {
        return AdvertisingCampaign::class;
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function filter()
    {
        $input = Input::all();

        $class = $this->getModelClass();
        $records = (new $class)->getFilteredModelEntries($input);
        $title = "All Entries in {$this->module_name}";

        return view("admin.{$this->module_alias}.index", compact(
            'records',
            'title'
        ));
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($id)
    {
        $records = ItemAdvertisingCampaign::where('id_campaign', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(500);

        $records_grouped = $records->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('d m Y'); // grouping by days
        })->sortByDesc(function ($group, $key) {
            return collect($group)->first()->created_at;
        });

        $title = 'Details';

        return view('admin.advertisingCampaign.view', compact('records', 'title', 'records_grouped'));
    }

}
