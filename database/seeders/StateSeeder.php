<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
 
use App\Models\State;

class StateSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run():void
    {
        State::truncate();
        $csvData = fopen(base_path('database/csv/states.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 555, ',')) !== false) {
            if (!$transRow) {
                State::create([
                    'state_title' => $data['0'],                   
                   
                ]);
            }
            $transRow = false;
        }
        fclose($csvData);
    }
}
