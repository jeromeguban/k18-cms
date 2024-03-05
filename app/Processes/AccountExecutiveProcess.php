<?php

namespace App\Processes;

use App\Models\AccountExecutive;
use Illuminate\Support\Facades\DB;

class AccountExecutiveProcess
{

    protected $request, $account_executive, $user;

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
     * Execute find process.
     *
     * @return void
     */

    public function find($id)
    {

        $this->account_executive = AccountExecutive::findOrFail($id);

        return $this;
    }

    /**
     * Retrive Account Executive.
     *
     * @return $account_executive
     */

    public function accountExecutive()
    {
        return $this->account_executive;
    }

    /**
     * Execute create process.
     *
     * @return void
     */

    public function create()
    {
        DB::transaction(function () {
            $this->createAccountExecutive();
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
            $this->updateAccountExecutive();
        });
    }

    /**
     * Create Account Executive.
     *
     * @return void
     */

    private function createAccountExecutive()
    {
        $this->account_executive = AccountExecutive::create([
            'first_name' => $this->request->first_name,
            'last_name' => $this->request->last_name,
            'user_id' => $this->request->user_id,
            'created_by' => auth()->id(),
            'modified_by' => auth()->id(),
        ]);

        return $this;
    }

    /**
     * Update user accounts for Account Executive.
     *
     * @return void
     */

    private function updateAccountExecutive()
    {
        $this->account_executive->update([
            'first_name' => $this->request->first_name,
            'last_name' => $this->request->last_name,
            'user_id' => $this->request->user_id,
            'modified_by' => auth()->id(),
        ]);

        return $this;
    }
}
