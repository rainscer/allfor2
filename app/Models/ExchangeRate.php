<?php namespace App\Models;

class ExchangeRate {

    public $exchange_url =
        'http://bank-ua.com/export/currrate.xml';
    public $xml;

    /**
     *
     */
    public function __construct(){

        return $this->xml =
            simplexml_load_file($this->exchange_url);
    }

    /**
     * @param $code
     * @param bool $size
     * @return mixed
     */
    public function getExchangeRateByCode($code, $size = false){
        $result = null;
        if ($this->xml !== false) {

            foreach($this->xml->children() as $item){
                $row = simplexml_load_string($item->asXML());
                $v = $row->xpath('//char3[. ="' . $code . '"]');

                if(isset($v[0])){
                    $result = $item;
                    break;
                }
            }
        }

        if($size){

            return $this->getExchangeForOne($result);
        }

        return $result;
    }

    /**
     * @param $data
     * @return float
     */
    public function getExchangeForOne($data)
    {
        if($data){
            return round(($data->rate / $data->size), 2);
        }

        return null;
    }
}