<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
 use DB;
use App\Models\User;
use App\Models\Profile;
use App\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run():void
    {
         

    $user=User::create([
            'first_name' => 'Recrueet',
            'email' => 'admin@recrueet.com',
            'password' => '$2y$10$/YTVQpwFO3SZUCgD8ggMXePwjv1n8JxjA8ogtpDLsA3qRsLmD5hKK',
            'account_type' => 'superadmin',
         ]);
        Profile::create([
            'user_id' => $user->id,
            'mobile_no' => '1234567890',
            'country' => 69
                        
         ]);


         $role_id=Role::where('name','=','superadmin')->pluck('id')->first();
         $assign=DB::table('assigned_roles')->insert([
            'role_id' => $role_id,
            'entity_id' => $user->id,
            'entity_type' => 'App\Models\User',
         ]);

         $abilities=DB::table('abilities')->get();
         foreach($abilities as $abl){
            $permission=DB::table('permissions')->insert([
                'ability_id' => $abl->id,
                'entity_id' => $role_id,
                'entity_type' => 'roles',
             ]);
         }

         $oauth_personal_access_clients=DB::table('oauth_personal_access_clients')->insert([
            'client_id' => 1,
            
         ]);
         $oauth_clients=DB::table('oauth_clients')->insert([
            'name' => 'Recrueet Personal Access Client',
            'secret' => 'D6s6fxAd8kEgEAibHxaMFwM4fPXmaCjHW8v7d074',
            'redirect' => 'http://localhost',
            'personal_access_client' => 1,
            
         ]);
         
    }
}
