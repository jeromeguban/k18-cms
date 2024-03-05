<?php

namespace App\Processes;

use App\Models\AuctionSection;
// use App\Processes\AuctionSectionImageProcess;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Js;
use Illuminate\Support\Str;

class AuctionSectionProcess
{
    protected $request, $auction_section;

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

        $this->auction_section = AuctionSection::findOrFail($id);

        return $this;
    }

    public function auction_section()
    {
        return $this->auction_section;
    }

    /**
     * Execute create process.
     *
     * @return void
     */

    public function create()
    {

        DB::transaction(function () {

            $this->createAuctionSection();
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
            $this->updateAuctionSection();
        });
    }

    public function createAuctionSection()
    {

        $this->auction_section = AuctionSection::create([
            'name'         => $this->request->name,
            'activated'    => $this->request->activated ? 1 : 0,
            'properties'   => json_encode($this->request->properties),
            'created_by'   => auth()->id(),
            'modified_by'  => auth()->id(),
        ]);

        return $this;
    }

    private function updateAuctionSection()
    {

        $this->auction_section->update([
            'name'         => $this->request->name,
            'activated'    => $this->request->activated ? 1 : 0,
            'properties'   => $this->request->properties,
            'modified_by'  => auth()->id(),
        ]);

        return $this;
    }
}
