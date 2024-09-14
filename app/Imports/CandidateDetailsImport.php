<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Illuminate\Validation\ValidationException;

class CandidateDetailsImport implements WithMultipleSheets,SkipsUnknownSheets
{
    public function sheets(): array
    {
       
        return [
            'Candidate' => new CandidatesImport()
            //'state' => new StateImport(),
        ];
    
   
    }
    public function onUnknownSheet($sheetName)
    {
       
        info("Sheet {$sheetName} was skipped");
    }
}
