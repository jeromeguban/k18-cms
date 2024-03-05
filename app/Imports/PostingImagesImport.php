<?php

namespace App\Imports;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 3600);

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Intervention\Image\Facades\Image as Image;

class PostingImagesImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $images = explode("|",$row['images']);

            foreach ($images as $image) 
            {
                try {

                    $url= trim($image);

                    $contents = file_get_contents($url);
    
                    $name = substr( $url, strrpos($url, '/') + 1);
            
                    $location = public_path('images/postings/'.gmdate("Y/m/",($row['post_date'] - 25569) * 86400).$name);
                    $location_path = public_path('images/postings/'.gmdate("Y/m/",($row['post_date'] - 25569) * 86400));
            
                    if (!file_exists($location_path)) {
                        mkdir($location_path, 0755 , true);
                    }
                
                    $image = Image::make($contents);

                    $image->save($location);

                }
                catch (\Exception $e) {
                    continue;
                }
            }

        }
    }

}

