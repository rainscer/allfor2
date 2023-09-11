<?php

use App\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		// $this->call('UserTableSeeder');
		$this->call('CatalogsSeeder');
	}

}
class ProductsSeeder extends Seeder {

	public function run()
	{
		//DB::table('catalogs')->delete();

		Product::create([
			'name_ru' => 'США переменного тока адаптер питания USB для смартфонов Белый',
			'name_en' => 'US AC to USB power adapter for smartphones White',
			'slug' => 'us-ac-to-usb-power-adapter-for-smartphones-white2',
			'sku' => '2001044',
			'price' => '12',
			'weight' => '10',
			'new' => '1',
			'image_local' => './public/images/product/dando4.jpg',
			'image_file' =>'',
		]);

		Product::create([
			'name_ru' => 'США переменного тока адаптер питания USB для смартфонов Черный',
			'name_en' => 'US AC to USB power adapter for smartphones Black',
			'slug' => 'us-ac-to-usb-power-adapter-for-smartphones-black2',
			'sku' => '2001024',
			'price' => '12',
			'weight' => '10',
			'new' => '1',
			'image_local' => './public/images/product/dando6.jpg',
			'image_file' =>'',
		]);
		Product::create([
			'name_ru' => '10 Galaxy Tab комплект подключения и картридер Черный',
			'name_en' => '10 Galaxy Tab conection kit and Cardreader Black',
			'slug' => '10-galaxy-tab-conection-kit-and-cardreader-black2',
			'sku' => '2001070',
			'price' => '16',
			'weight' => '215',
			'new' => '1',
			'image_local' => './public/images/product/dando7.jpg',
			'image_file' =>'',
		]);
	}
}