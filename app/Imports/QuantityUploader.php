<?php

namespace App\Imports;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 3600);
use App\Models\Posting;
use App\Models\PostingItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuantityUploader implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {

           try {

                  $posting_item = PostingItem::where('posting_items.sku', $row['sku'])
                                    ->where('posting_items.store_id', $row['store_id'])
                                    ->firstOrFail();


                  if($posting_item){

                      $posting_item->update([

                        'quantity' => $row['quantity'],

                      ]);


                      $posting = Posting::where('postings.posting_id', $posting_item->posting_id)
                                    ->where('postings.category', 'Retail')
                                    ->firstOrFail();

                      $item_quantity = PostingItem::select([
                                              \DB::raw('SUM(quantity) as total')
                                          ])
                                          ->where('posting_items.posting_id',$posting->posting_id)
                                          ->groupBy(['posting_items.posting_id'])
                                          ->first();

                      $posting->update([

                        'quantity' =>  $item_quantity->total,

                      ]);
                  }  

          
          } catch (\Exception $e) {
              echo $e->getMessage();
          } 
        }

    }
}

