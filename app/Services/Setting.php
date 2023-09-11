<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;


/**
 * Class Setting
 * @package App\Services
 */
class Setting
{
    /**
     * @var
     */
    protected $instance;
    /**
     * @var \Illuminate\Support\Collection
     */
    private $collection;

    /**
     *
     */
    public function __construct()
    {
        if (! $this->instance) {
            $this->init();
        }
    }

    /**
     *
     */
    public function init()
    {
        $this->collection = new Collection(
            DB::table('settings')
                ->orderBy('created_at')
                ->get()
        );
    }


    /**
     * @return mixed
     */
    public function getNewCount()
    {
        return count(
            $this->collection->filter(function ($order) {
                if ($order->new && ! $order->deletion_mark) {
                    return $order;
                }
            })
        );
    }

    /**
     * @param     $name
     * @param int $default
     * @return mixed
     */
    public function getSettingValue($name, $default = 0)
    {
        $setting = $this->collection->first(function ($key, $setting) use ($name) {
            return $setting->key_name === $name;
        });

        if ($setting) {
            return $setting->value;
        }

        return $default;
    }

    /**
     * @param $keyName
     * @param $value
     * @return mixed
     */
    public function checkSetting($keyName, $value)
    {
        return $this->collection->first(function ($key, $setting) use ($keyName, $value) {
            if ($setting->key_name === $keyName && $setting->value === $value) {
                return $setting;
            }

            return null;
        });
    }
}