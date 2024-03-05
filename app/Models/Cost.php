<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Cost extends Model
{
    use SoftDeletes;

    protected $primaryKey = "id";
    protected $table = "costs";
    protected $searchable_fields = [
        'costs.store_company_code',

    ];

    public function scopeJoinStore($query)
    {
        $query->addSelect([
            'stores.company_id',
            'stores.code',
            'stores.store_company_name',
            'stores.store_name',
            'stores.store_company_code',
            'stores.store_type',
        ]);

        $query->join('stores', 'stores.id', '=', 'costs.store_id');

        return $query;
    }

    public function scopeJoinCostType($query)
    {
        $query->addSelect([
            'cost_types.type'

        ]);

        $query->join('cost_types', 'costs.cost_type_id', '=', 'cost_types.id');

        return $query;
    }

    public function scopeJoinCompany($query)
    {
        $query->addSelect([
            'companies.company_name',
            'companies.company_name',
            'companies.default_commission',
    

        ]);

        $query->join('companies', 'companies.id', '=', 'costs.company_id');

        return $query;
    }

    public function scopeLeftJoinPayable($query)
    {
        $query->addSelect([
            'payables.id',
            'payables.commission',
            'payables.generate_date',
            'payables.remarks',
        ]);

        $query->leftJoin('payables', 'payables.id', '=', 'costs.payable_id');

        return $query;
    }


    public function scopeJoinPayable($query)
    {
        $query->addSelect([
            'payables.id',
            'payables.commission',
            'payables.generate_date',
            'payables.remarks',
        ]);

        $query->Join('payables', 'payables.id', '=', 'costs.payable_id');

        return $query;
    }

    public function scopeWhereForPayables($query)
    {
        $query->whereNull('costs.payable_id');
    }

  
}
