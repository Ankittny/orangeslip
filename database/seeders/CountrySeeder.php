<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
 
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run():void
    {
        Country::truncate();
        $csvData = fopen(base_path('database/csv/countries.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 555, ',')) !== false) {
            if (!$transRow) {
                Country::create([
                    'code' => $data['0'],
                    'name' => $data['1'],
                    'nationality' => $data['2'],                   
                    'flag_icon' => $data['3'],                   
                    'calling_code' => $data['4'],                   
                ]);
            }
            $transRow = false;
        }
        fclose($csvData);
       
        
         
    }
}


