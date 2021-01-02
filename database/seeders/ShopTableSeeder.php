<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Shop;

class ShopTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Shop::create([
    		'app_id' => 14030,
    		'client_id' => '11e943aa74be4fafbd284a4cc17d3f6f',
			'client_secret' => 'e07e1e8fde9640129cc4eba7e4f105bb',
    	]);
    }
}
