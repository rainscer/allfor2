<?php namespace App\Http\Controllers\Payment;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\JobLog;
use App\Models\Order;
use App\Models\Settings;
use App\Models\SystemError;
use Illuminate\Http\Request;

class LiqPayController extends Controller {

	/*
	 *
	 */
	protected $name = 'Liqpay';
	/*
     *
     */
	const SETTING_NAME_LIQPAY = 'liqpay_live';

	/**
	 * @param Request $request
	 * @return string
	 */
	public function getPaymentStatus(Request $request)
	{
		$required = [
			'data',
			'signature'
		];

		if (!$this->validateRequest($request, $required)) {
			return 'Error validation';
		}

		if($this->checkSignature($request->get('data')) == $request->get('signature')){
			$data_json = base64_decode($request->get('data'));
			$data = json_decode($data_json);

			if(isset($data->status) && isset($data->payment_id)){
				// put payment_id to order
				Order::where('id',$data->order_id)
					->update([
						'payment_id' => $data->payment_id
					]);

				if($data->status === Order::STATUS_SUCCESS){
					Order::changeStatusOrder($data->order_id, Order::STATUS_PAID);
					Order::success($data->order_id);

					JobLog::writeJob(
						$this->name,
						$data->order_id,
						$data->status,
						$data->amount,
						true
					);

				}elseif(Settings::checkSetting(self::SETTING_NAME_LIQPAY,false)) {
					Order::changeStatusOrder($data->order_id, Order::STATUS_PAID);
					Order::success($data->order_id);

					JobLog::writeJob(
						$this->name,
						$data->order_id,
						'success',
						$data->amount
					);
				}
				else {
					JobLog::writeJob(
						$this->name,
						$data->order_id,
						$data->status,
						$data->amount
					);
				}
			}
		}
	}

	/**
	 * @param $request
	 * @param $required
	 * @return bool
	 */
	private function validateRequest(Request $request, $required)
	{
		if (!is_array($required) || !count($required)) {
			return false;
		}
		foreach ($required as $field) {

			if (!$request->has($field)) {

				return false;
			}
		}

		return true;
	}

	/**
	 * cnb_signature
	 *
	 * @param array $params
	 *
	 * @return string
	 */
	public function checkSignature($params)
	{
		$private_key = config('app.private_key_liqpay');

		$signature = base64_encode(sha1($private_key . $params . $private_key,1));;

		return $signature;
	}

}
