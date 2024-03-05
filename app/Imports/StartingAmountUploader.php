<?php

namespace App\Imports;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 3600);
use App\Models\Posting;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StartingAmountUploader implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {

           try {

                  $posting = Posting::where('postings.lot_number', $row['lot_number'])
                                    ->where('postings.auction_id', $row['hmr_auction_id'])
                                    ->firstOrFail();


                  if($posting){

                      $posting->update([

                        'starting_amount' => $row['starting_amount'],

                      ]);
                  }  

          
          } catch (\Exception $e) {
              echo $e->getMessage();
          } 
        }

    }
}

