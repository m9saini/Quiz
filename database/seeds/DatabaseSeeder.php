<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
     $this->call(RoleTableSeeder::class);
     $this->call(UsersTableSeeder::class);
     $this->call(CountriesTableSeeder::class);
     if(Module::has('EmailTemplates')){
        $this->call(Modules\EmailTemplates\Database\Seeders\EmailTemplatesDatabaseSeeder::class);
     }
    }
}
