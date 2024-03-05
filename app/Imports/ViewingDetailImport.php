<?php

namespace App\Imports;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 3600);
use App\Models\Posting;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ViewingDetailImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {

          // $detail = json_decode($row['viewing_details']);

          $posting = Posting::where('postings.posting_id', $row['hmr_posting_id'])->firstOrFail();

          $posting->update([
            'viewing_details' => $row['viewing_details'],
          ]);
        }
    }
}

