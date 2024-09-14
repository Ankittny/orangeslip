<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
 
use App\Models\Role;
use App\Models\User;
use App\Models\Profile;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run():void
    {
         

        Role::create([
            'name' => 'superadmin',
            'title' => 'Super Admin',
           
         ]);
         
        Role::create([
            'name' => 'hr',
            'title' => 'HR',
           
         ]);
        Role::create([
            'name' => 'business',
            'title' => 'Business',
           
         ]);
        Role::create([
            'name' => 'candidate',
            'title' => 'Candidate',
           
         ]);
        Role::create([
            'name' => 'verification staff',
            'title' => 'Verification Staff',
           
         ]);
        Role::create([
            'name' => 'verification head',
            'title' => 'Verification Head',
           
         ]);
        Role::create([
            'name' => 'agent',
            'title' => 'Agent',
           
         ]);
       
    }
}
