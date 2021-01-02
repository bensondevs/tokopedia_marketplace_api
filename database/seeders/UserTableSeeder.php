<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new User([
        	'name' => 'Admin Dashboard',
			'email' => 'admin@setokoo.com',
			'password' => bcrypt('admin'),
        ]);
        $admin->save();
    }
}
