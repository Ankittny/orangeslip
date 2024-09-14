<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
 
use App\Models\Abilities;

class AbilitiesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run():void
    {
        Abilities::create([
            'name' => 'access-manage-role', 
            'title' => 'Access | Manage Role', 
        ]);
        Abilities::create([
            'name' => 'access-create-role', 
            'title' => 'Access | Create Role', 
        ]);
        Abilities::create([
            'name' => 'access-manage-permission-set', 
            'title' => 'Access | Manage Permission Set', 
        ]);
        Abilities::create([
            'name' => 'access-manage-member-role', 
            'title' => 'Access | Manage Member Role', 
        ]);
        Abilities::create([
            'name' => 'access-manage-member', 
            'title' => 'Access | Manage Member', 
        ]);
        Abilities::create([
            'name' => 'access-manage-settings', 
            'title' => 'Access | Manage Settings', 
        ]);
        Abilities::create([
            'name' => 'access-manage-notification', 
            'title' => 'Access | Manage Notification', 
        ]);
        Abilities::create([
            'name' => 'access-manage-create-notification', 
            'title' => 'Access | Manage Create Notification', 
        ]);
        Abilities::create([
            'name' => 'access-manage-view-notification', 
            'title' => 'Access | Manage View Notification', 
        ]);
        Abilities::create([
            'name' => 'access-manage-edit-notification', 
            'title' => 'Access | Manage Edit Notification', 
        ]);
        Abilities::create([
            'name' => 'access-manage-delete-notification', 
            'title' => 'Access | Manage Delete Notification', 
        ]);
        Abilities::create([
            'name' => 'access-manage-business', 
            'title' => 'Access|Manage Business', 
        ]);
        Abilities::create([
            'name' => 'access-manage-candidate', 
            'title' => 'Access|Manage Candidate', 
        ]);
        Abilities::create([
            'name' => 'access-manage-hr-list', 
            'title' => 'Access|Manage HR List', 
        ]);
        Abilities::create([
            'name' => 'access-manage-verification-staff', 
            'title' => 'Access|Manage Verification Staff', 
        ]);
        Abilities::create([
            'name' => 'access-manage-verification', 
            'title' => 'Access|Manage Verification', 
        ]);
        Abilities::create([
            'name' => 'access-manage-lead', 
            'title' => 'Access Manage Lead', 
        ]);
        Abilities::create([
            'name' => 'access-manage-agent', 
            'title' => 'Access Manage Agent', 
        ]);
        Abilities::create([
            'name' => 'access-manage-transaction', 
            'title' => 'Access Manage Transaction', 
        ]);
        Abilities::create([
            'name' => 'access-manage-deposit', 
            'title' => 'Access Manage Deposit', 
        ]);
       
       
         
    }
}
