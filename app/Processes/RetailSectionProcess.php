<?php

namespace App\Processes;

use App\Models\RetailSection;
// use App\Processes\RetailSectionImageProcess;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Js;
use Illuminate\Support\Str;

class RetailSectionProcess
{
    protected $request, $retail_section;

    /**
     * Create a new process instance.
     *
     * @return void
     */
    public function __construct($request = null)
    {
        $this->request = $request ? (object) $request : request();
    }
    /**
     * Retrive Adds.
     *
     * @return
     */

    public function find($id)
    {

        $this->retail_section = RetailSection::findOrFail($id);

        return $this;
    }

    public function retail_section()
    {
        return $this->retail_section;
    }

    /**
     * Execute create process.
     *
     * @return void
     */

    public function create()
    {

        DB::transaction(function () {

            $this->createRetailSection();
        });
    }

    /**
     * Execute update process.
     *
     * @return void
     */

    public function update()
    {

        DB::transaction(function () {
            $this->updateRetailSection();
        });
    }

    public function createRetailSection()
    {

        $this->retail_section = RetailSection::create([
            'name'         => $this->request->name,
            'activated'    => $this->request->activated ? 1 : 0,
            'properties'   => json_encode($this->request->properties),
            'created_by'   => auth()->id(),
            'modified_by'  => auth()->id(),
        ]);

        return $this;
    }

    private function updateRetailSection()
    {

        $this->retail_section->update([
            'name'         => $this->request->name,
            'activated'    => $this->request->activated ? 1 : 0,
            'properties'   => $this->request->properties,
            'modified_by'  => auth()->id(),
        ]);

        return $this;
    }
}
