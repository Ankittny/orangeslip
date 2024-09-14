<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
 
use App\Models\UserAccessMaster;

class UserAccessMasterSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run():void
    {
        UserAccessMaster::create([
            'name' => 'add_candidate', 
            'title' => 'Add Candidate', 
        ]);
        UserAccessMaster::create([
            'name' => 'edit_candidate', 
            'title' => 'Edit Candidate', 
        ]);
        UserAccessMaster::create([
            'name' => 'bulk_upload', 
            'title' => 'Bulk Upload', 
        ]);
        UserAccessMaster::create([
            'name' => 'create_offer_letter', 
            'title' => 'Create Offer Letter', 
        ]);
        UserAccessMaster::create([
            'name' => 'personal', 
            'title' => 'Personal Verification', 
        ]);
        UserAccessMaster::create([
            'name' => 'educational', 
            'title' => 'Educational Verification', 
        ]);
        UserAccessMaster::create([
            'name' => 'professional', 
            'title' => 'Professional Verification', 
        ]);
         
       
       
       
         
    }
}
