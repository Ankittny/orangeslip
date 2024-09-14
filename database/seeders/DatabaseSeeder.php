<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
 use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run():void
    {
        //  \App\Models\User::factory(1)->create();

        //  \App\Models\User::factory()->create([
        //     'first_name' => 'Truecv',
        //     'email' => 'test@example.com',
        //     'password' => Hash::make('12345678'),
        //     'account_type' => 'superadmin',
        //  ]);
        DB::table('users')->delete();
        DB::table('state')->delete();
        DB::table('abilities')->delete();
        DB::table('city')->delete();
        DB::table('countries')->delete();
        DB::table('user_access_master')->delete();
        DB::table('roles')->delete();
        DB::table('profiles')->delete();
        DB::table('assigned_roles')->delete();
        DB::table('permissions')->delete();
        //$this->call('UserTableSeeder');
        $this->call([
            
            StateSeeder::class,
            AbilitiesSeeder::class,
            CitySeeder::class,
            CountrySeeder::class,
            UserAccessMasterSeeder::class,
            RoleSeeder::class,
            UserTableSeeder::class,
           
        ]);
    }
}
