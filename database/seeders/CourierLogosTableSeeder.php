<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\CourierLogo;

class CourierLogosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {	
        CourierLogo::insert([
        	['courier_name' => 'sicepat', 		'logo' => 'https://ecs7.tokopedia.net/img/kurir-sicepat.png'],
			['courier_name' => 'anteraja', 		'logo' => 'https://ecs7.tokopedia.net/img/kurir-anteraja.png'],
			['courier_name' => 'lionparcel', 	'logo' => 'https://ecs7.tokopedia.net/img/kurir-lionparcel.png'],
			['courier_name' => 'jnt', 			'logo' => 'https://ecs7.tokopedia.net/img/kurir-jnt.png'],
			['courier_name' => 'jne', 			'logo' => 'https://ecs7.tokopedia.net/img/kurir-jne.png'],
			['courier_name' => 'tiki', 			'logo' => 'https://ecs7.tokopedia.net/img/kurir-tiki.png'],
			['courier_name' => 'wahana', 		'logo' => 'https://ecs7.tokopedia.net/img/kurir-wahana.png'],
			['courier_name' => 'grab', 			'logo' => 'https://ecs7.tokopedia.net/img/kurir-grab.png'],
			['courier_name' => 'gosend', 		'logo' => 'https://ecs7.tokopedia.net/img/kurir-gosend.png'],
			['courier_name' => 'ninja', 		'logo' => 'https://ecs7.tokopedia.net/img/kurir-ninja.png'],
			['courier_name' => 'pos', 			'logo' => 'https://ecs7.tokopedia.net/img/kurir-pos.png'],
        ]);
    }
}
