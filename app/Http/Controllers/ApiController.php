<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\CatalogCategory;
use App\Models\CatalogProduct;
use App\Models\Order;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use stdClass;

class ApiController extends Controller {

	use SluggableTrait;
	/**
	 * @var
	 */
	private $response;

	/**
	 *
	 */
	public function __construct()
	{
		$this->response = new stdclass;
		$this->response->status = 'OK';
		$this->response->message = 'Success';
	}



	/**
	 * @return Redirect
	 * Api for catalog categories from remote site
	 */
	public function callApiImportCategories()
	{
        return Redirect::to('administrator/settings')
            ->with('error', trans('admin.errorImportCategories'));

		$service_url = config('app.api_getCategories');
		$decoded = remote($service_url);

		if (isset($decoded->status) && $decoded->status == 'ERROR') {

			return Redirect::to('administrator/settings')
				->withErrors(trans('admin.errorImportCategories'));
		} else {
			$array_id = [];
			$collection = CatalogCategory::all();
			$category_images = $collection
				->lists('image','id');
			$category_icons = $collection
				->lists('icon','id');
			$category_sort = $collection
				->lists('sort','id');

			DB::beginTransaction();
			DB::table('catalog_categories')
				->truncate();

			foreach ($decoded->value as $catalog_category) {

				// search for unique slug
				$slug = $this->generateSlug($catalog_category->n_en);
				if(!$slug){
					continue;
				}
				$unique_slug = DB::table('catalog_categories')
					->where('slug', 'like', $slug)
					->orWhere('id', '=', $catalog_category->id)
					->first();

				if ($unique_slug) {
					// if found - get parent slug and plus here child slug
					$parent_slug = CatalogCategory::getParentSlug($catalog_category->ll, $catalog_category->lt, $catalog_category->rt);
					$slug = $this->generateSlug($parent_slug->slug . '-' . $catalog_category->n_en);
				}

				isset($category_images[$catalog_category->id]) ? $tmp_image = $category_images[$catalog_category->id] : $tmp_image = null;
				isset($category_icons[$catalog_category->id]) ? $tmp_icon = $category_icons[$catalog_category->id] : $tmp_icon = null;
				isset($category_sort[$catalog_category->id]) ? $tmp_sort = $category_sort[$catalog_category->id] : $tmp_sort = null;

				// insert data
				DB::table('catalog_categories')
					->insert(
						[
							'id' 			=> $catalog_category->id,
							'left_key' 		=> $catalog_category->lt,
							'right_key' 	=> $catalog_category->rt,
							'level'	 		=> $catalog_category->ll,
							'name_ua' 		=> $catalog_category->n_ua,
							'name_ru' 		=> $catalog_category->n_ru,
							'name_en' 		=> $catalog_category->n_en,
							'description_ua'=> $catalog_category->d_ua,
							'description_ru'=> $catalog_category->d_ru,
							'description_en'=> $catalog_category->d_en,
							'slug' 			=> $slug,
							'image' 		=> $tmp_image,
							'icon' 			=> $tmp_icon,
							'sort' 			=> $tmp_sort,
							'created_at' 	=> Carbon::now(),
							'updated_at' 	=> Carbon::now(),
						]
					);

				$array_id[] = $catalog_category->id;
			}

			// delete all categories and relations with products that not import
			DB::table('register_product_categories')
				->whereNotIn('category_id', $array_id)
				->delete();

			DB::table('catalog_categories')
				->whereNotIn('id', $array_id)
				->delete();

			DB::commit();

			return Redirect::to('administrator/settings')
				->with('success', trans('admin.successImportCategories'));
		}

	}

	/**
	 * @param Request $request
	 * @return Redirect
	 * Api for catalog product from remote site from shelf id
	 */
	public function callApiImportProducts(Request $request)
	{
        return Redirect::to('administrator/settings')
            ->with('error', trans('admin.errorImportProducts'));

		$service_url_variant = config('app.api_UpiVariant');
		$decoded_variant = remote($service_url_variant);

		$post_data = ['shelf_id' => $request::get('shelf_id')];
		$service_url = config('app.api_getProducts');
		$decoded = remote($service_url,$post_data);

		if ((isset($decoded->status) && $decoded->status == 'ERROR') ||
			(isset($decoded_variant->status) && $decoded_variant->status == 'ERROR')) {

			return Redirect::to('administrator/settings')
				->withErrors(trans('admin.errorImportProducts'));
		} else {
			DB::transaction(function() use ($decoded, $decoded_variant)
			{
				DB::table('register_product_categories')
					->update(['active' => 0]);

				$product_ids = CatalogProduct::lists('upi_id');
				$prod_slug = CatalogProduct::lists('slug');

				foreach ($decoded->value as $product) {
					if(is_null($product->price)){
						$product->price = 1;
					}
					if($product->name_ru == ''){
						continue;
					}
					else
					{
						if (in_array($product->upi_id, $product_ids)) {

							$prod_temp = CatalogProduct::where('upi_id', $product->upi_id)->first();

							$prod_temp->price = $product->price;
							$prod_temp->weight = $product->weight;
							$prod_temp->name_ua = $product->name_ua;
							$prod_temp->name_ru = $product->name_ru;
							$prod_temp->name_en = $product->name_en;
							$prod_temp->description_ua = $product->description_ua;
							$prod_temp->description_ru = $product->description_ru;
							$prod_temp->description_en = $product->description_en;
							$prod_temp->meta_keywords_ua = $product->keywords_ua;
							$prod_temp->meta_keywords_ru = $product->keywords_ru;
							$prod_temp->meta_keywords_en = $product->keywords_en;
							$prod_temp->updated_at = Carbon::now();

							$prod_temp->save();

							$prod_id = $prod_temp->id;

							DB::table('register_product_categories')
								->where('product_id', '=', $prod_id)
								->delete();

							foreach ($product->category_id as $key => $reg_product) {
								DB::table('register_product_categories')
									->insert(
										[
											'category_id' => $reg_product,
											'product_id' => $prod_id,
											'active' => $product->active[$key]
										]
									);
							}

							DB::table('catalog_product_images')
								->where('owner_id', '=', $prod_id)
								->delete();

							foreach ($product->image_url as $reg_product) {
								DB::table('catalog_product_images')
									->insert(
										[
											'owner_id' => $prod_id,
											'image_url' => $reg_product
										]
									);
							}

						} else {
							$slug = str_slug($product->name_ru);

							if (in_array($slug,$prod_slug)) {
								$slug = $slug . '-' . $product->upi_id;
							}
							$prod_slug[] = $slug;

							$rand1 = rand(20,200);
							$rand2 = rand(20,200);

							$prod_id = DB::table('catalog_products')
								->insertGetId(
									[
										'upi_id' => $product->upi_id,
										'sku' => $product->upi_code,
										'price' => $product->price,
										'weight' => $product->weight,
										'name_ua' => $product->name_ua,
										'name_ru' => $product->name_ru,
										'name_en' => $product->name_en,
										'description_ua' => $product->description_ua,
										'description_ru' => $product->description_ru,
										'description_en' => $product->description_en,
										'meta_keywords_ua' => $product->keywords_ua,
										'meta_keywords_ru' => $product->keywords_ru,
										'meta_keywords_en' => $product->keywords_en,
										'views' => max($rand1, $rand2),
										'sold' => min($rand1, $rand2),
										'slug' => $slug,
										'created_at' => Carbon::now(),
										'updated_at' => Carbon::now()
									]
								);

							foreach ($product->category_id as $key => $reg_product) {
								DB::table('register_product_categories')
									->insert(
										[
											'category_id' => $reg_product,
											'product_id' => $prod_id,
											'active' => $product->active[$key]
										]
									);
							}
							foreach ($product->image_url as $reg_product) {
								DB::table('catalog_product_images')
									->insert(
										[
											'owner_id' => $prod_id,
											'image_url' => $reg_product
										]
									);
							}
						}
					}

				}
				//UpiVariant
				DB::table('attributes')
					->truncate();
				DB::table('product_attributes')
					->truncate();

				foreach ($decoded_variant->value as $product) {

					foreach ($product as $key => $value) {
						$variant_id = DB::table('attributes')
							->insertGetId(
								[
									'name' => $key
								]
							);
						foreach ($value as $key_upi => $value_name) {
							DB::table('product_attributes')
								->insert(
									[
										'upi_id' => $key_upi,
										'reference_id' => $variant_id,
										'attribute_value_name' => $value_name
									]
								);
						}
					}
				}

			});

			return Redirect::to('administrator/settings')
				->with('success', trans('admin.successImportProducts'));
		}

	}


	/**
	 * @param Request $request
	 * @return string
	 * Confirm orders for api from remote site for exclude its for next call api
	 */
	public function confirmOrders(Request $request)
	{
		$required = [
			'order_ids'
		];

		if (!$this->validateRequest($request, $required)) {
			return $this->sendFail();
		}

		$content = $request::get('order_ids');
		$json_content = json_decode($content);

		foreach($json_content as $order){
			$order_id = str_replace(config('app.shop_code').'-','',$order);

			Order::where('id',$order_id)
				->update(['api' => true]);
		}

	}

	/**FIXME
	 * @return string
	 * Orders for remote site
	 */
	public function getOrders()
	{
		$data = Order::with('order_item')
			->where('order_status', '=', Order::STATUS_PAID)
			->where('api', '=', false)
			->notDeleted()
			->get();

		$arr = [];

		foreach ($data as $c) {
			$items = [];
			foreach ($c->order_item as $order_item) {
				$items[] = [
					'quantity' => $order_item->product_quantity,
					'upi_id' => $order_item->product_upi
				];
			}

			$c->contacts = unserialize($c->contacts);
			$c->contacts = (array)$c->contacts;

			foreach ($c->contactFields as $field) {
				isset($c->contacts[$field]) ? $c->$field = $c->contacts[$field] : $c->$field = '';
			}

			$user_name = rusToTranslit($c->d_user_name);
			$region = rusToTranslit($c->d_user_region);
			$city = rusToTranslit($c->d_user_city);
			$address = rusToTranslit($c->d_user_address);

			$order = new stdclass();
			$order->order_id = config('app.shop_code').'-'.$c->id;
			$contacts = [];
			$contacts['buyer-email'] = $c->d_user_email;
			$contacts['buyer-name'] = $user_name;
			$contacts['buyer-phone-number'] = $c->d_user_phone;
			$contacts['recipient-name'] = $user_name;
			$contacts['ship-address-1'] = $address;
			$contacts['ship-address-2'] = '';
			$contacts['ship-address-3'] = '';
			$contacts['ship-city'] = $city;
			$contacts['ship-state'] = $region;
			$contacts['ship-postal-code'] = $c->d_user_index;
			$contacts['ship-country'] = 'UA';
			$contacts['ship-phone-number'] = $c->d_user_phone;
			$order->contacts = $contacts;
			$order->upis = (object)$items;
			$arr[] = $order;
		}

		$this->response->value = $arr;

		return $this->prepareResponse();
	}

	/**
	 * @return string
	 */
	private function prepareResponse()
	{
		return response()->json($this->response);
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

			if (!$request::has($field)) {

				return false;
			}
		}

		return true;
	}

	/**
	 * @param string $message
	 * @return string
	 * @internal param string $status
	 */
	private function sendFail($message = '')
	{
		$this->response->status = 'ERROR';
		if ($message) {
			$this->response->message = $message;
		}

		return $this->prepareResponse();
	}

}
