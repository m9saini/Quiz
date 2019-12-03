<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'admin',
            'display_name' => 'admin',
            'description' => 'Adminisrator role',
            'status' => 1,
        ]);
        Role::create([ 'name' => 'user',
            'display_name' => 'user',
            'description' => 'User role',
            'status' => 1,
        ]);
    }
}
