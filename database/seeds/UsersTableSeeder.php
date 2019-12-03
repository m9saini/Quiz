<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//Add Admin record Assign with Administrator Role
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin1@yopmail.com',
            'password' => bcrypt(12345678),
            'is_activated' => 1,
        ]);
        $adminRole = Role::findByName('admin');
        if(!empty($adminRole))
        {
            $admin->roles()->sync([$adminRole->id]);
        }

        //Add User record Assign with User Role
        $user = User::create(['name' => 'user',
            'email' => 'user@yopmail.com',
            'password' => bcrypt(12345678),
            'is_activated' => 1,
        ]);
        $userRole = Role::findByName('user');
        if(!empty($userRole))
        {
            $user->roles()->sync([$userRole->id]);
        }
    }
}
