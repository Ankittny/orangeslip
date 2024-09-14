<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
 
use App\Models\City;

class CitySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run():void
    {
        City::truncate();
        $csvData = fopen(base_path('database/csv/cities.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 555, ',')) !== false) {
            if (!$transRow) {
                City::create([
                    'name' => $data['0'],
                    'districtid' => $data['1'],
                    'state_id' => $data['2'],                   
                ]);
            }
            $transRow = false;
        }
        fclose($csvData);
       
        
         
    }
}


