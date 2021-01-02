<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\MarketplaceLogo;

class MarketplaceLogoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $logos = [
        	[
        		'marketplace_name' => 'tokopedia',
				'logo' => 'https://ecs7.tokopedia.net/img/logo-tokopedia-32.png',
        	]
        ];

        MarketplaceLogo::insert($logos);
    }
}
