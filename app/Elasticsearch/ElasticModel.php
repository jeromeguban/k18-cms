<?php

namespace App\Elasticsearch;

use App\Elasticsearch\QueryBuilder;
use App\Models\Model;
use Illuminate\Support\Str;

class ElasticModel
{
	protected $model, $builder, $fields = null, $sort_fields = [], $limit = 10, $operator = 'AND';

	public function __construct(Model $model)
	{

		$this->builder = (new QueryBuilder(
								request()->q && !request()->bypass_search ? request()->q : '*' , 
								request()->random ?? false,
								$model->searchable_fields ? collect($model->searchable_fields)->flatten()->toArray() : []
							)
						);



		$this->model = $model;
	}

	public function __call($method, $arguments)
	{
		$elastic_method = 'elastic'.Str::title($method);

		if(!method_exists($this->model, $elastic_method))
			throw new \Exception("Method {$method} not found.");

		$this->model->$elastic_method($this, $arguments);

		return $this;
	}

	public function search($query, $random = false)
	{
		$this->builder = (new QueryBuilder(
							$query, 
							$random,
							$this->model->searchable_fields ? collect($this->model->searchable_fields)->flatten()->toArray() : []
						));

		return $this;
	}

	public function select(array $fields)
	{
		$this->fields = $fields;
		return $this;
	}

	public function setOperator(string $operator)
	{
		$this->builder->setOperator($operator);
		return $this;
	}

	public function orderBy($column_name, $action = 'asc')
	{
		$this->sort_fields[] = [
			$column_name => $action
		];

		return $this;
	}

	public function limit($limit)
	{
		$this->limit = $limit;
		return $this;
	}

	public function get()
	{
		$this->model = $this->model->searchQuery($this->builder);

		$this->setFields();
		$this->setOrderBy();

		$this->model->size((request()->row_per_page ?? request()->limit) ?? $this->limit);

		$this->model = $this->model->execute();

		return $this->getDocuments();
	}

	public function first()
	{
		$this->model = $this->model->searchQuery($this->builder);

		$this->setFields();
		$this->setOrderBy();

		$this->model->size(1);

		$this->model = $this->model->execute();

		return $this->getDocuments()->first();
	}

	public function pagination($paginate = 5)
	{
		$this->model = $this->model->searchQuery($this->builder);

		$this->setFields();
		$this->setOrderBy();

		$this->model->size(5000);

		$this->model = $this->model->paginate(((request()->row_per_page ?? request()->limit) ?? $this->limit) ?? $paginate);

		return $this->model->setCollection($this->getDocuments());
	}

	private function setFields()
	{
		if(!$this->fields)
			return $this;

		$this->model->source($this->fields);

		return $this;
	}

	private function setOrderBy()
	{
		if(!count($this->sort_fields))
			return $this;

		$this->model->sortRaw($this->sort_fields);

		return $this;
	}

	private function getDocuments()
	{
		return $this->model->documents()
					->map(function($model){
						return (object)$model->content();
					});
	}

	public function whereNotNull($column)
	{
		if(!$this->builder) throw new \Exception("Invalid instance of ElasticScoutDriverPlus\\Builders\\SearchRequestBuilder");

		$this->builder->whereNotNull($column);

		return $this;
	}

	public function whereNull($column)
	{
		if(!$this->builder) throw new \Exception("Invalid instance of ElasticScoutDriverPlus\\Builders\\SearchRequestBuilder");

		$this->builder->whereNull($column);

		return $this;
	}

	public function whereIn($column, $value)
	{
		if(!$this->builder) throw new \Exception("Invalid instance of ElasticScoutDriverPlus\\Builders\\SearchRequestBuilder");

		$this->builder->whereIn($column, $value);

		return $this;
	}

	public function orWhereIn($column, $value)
	{
		if(!$this->builder) throw new \Exception("Invalid instance of ElasticScoutDriverPlus\\Builders\\SearchRequestBuilder");

		$this->builder->orWhereIn($column, $value);

		return $this;
	}

	public function whereBetween($column, $value)
	{
		if(!$this->builder) throw new \Exception("Invalid instance of ElasticScoutDriverPlus\\Builders\\SearchRequestBuilder");

		$this->builder->whereBetween($column, $value);

		return $this;
	}

	public function orWhereBetween($column, $value)
	{
		if(!$this->builder) throw new \Exception("Invalid instance of ElasticScoutDriverPlus\\Builders\\SearchRequestBuilder");

		$this->builder->orWhereBetween($column, $value);

		return $this;
	}

	public function where(string $column, $operator = null, $value = null): self
    {
    	if(!$this->builder) throw new \Exception("Invalid instance of ElasticScoutDriverPlus\\Builders\\SearchRequestBuilder");
    

    	// Here we will make some assumptions about the operator. If only 2 values are
        // passed to the method, we will assume that the operator is an equals sign
        // and keep going. Otherwise, we'll require the operator to be passed in.
        [$value, $operator] = $this->builder->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );
 
        if($operator === '=' && !is_array($value)){
            $this->builder->where($column, $value);
        } else {
            $this->builder->where($column, $operator, $value);
        }
       
        return $this;

    }

    public function whereRaw(string $query): self
    {
    	if(!$this->builder) throw new \Exception("Invalid instance of ElasticScoutDriverPlus\\Builders\\SearchRequestBuilder");
    	
    	[$column, $operator, $value] = explode(' ', $query);

    	// Here we will make some assumptions about the operator. If only 2 values are
        // passed to the method, we will assume that the operator is an equals sign
        // and keep going. Otherwise, we'll require the operator to be passed in.
        [$value, $operator] = $this->builder->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );
 
        if($operator === '=' && !is_array($value)){
            $this->builder->whereRaw($column, $value);
        } else {
            $this->builder->whereRaw($column, $operator, $value);
        }
       
        return $this;

    }

    public function orWhereRaw(string $query): self
    {
    	if(!$this->builder) throw new \Exception("Invalid instance of ElasticScoutDriverPlus\\Builders\\SearchRequestBuilder");
    	
    	[$column, $operator, $value] = explode(' ', $query);

    	// Here we will make some assumptions about the operator. If only 2 values are
        // passed to the method, we will assume that the operator is an equals sign
        // and keep going. Otherwise, we'll require the operator to be passed in.
        [$value, $operator] = $this->builder->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );
 
        if($operator === '=' && !is_array($value)){
            $this->builder->orWhereRaw($column, $value);
        } else {
            $this->builder->orWhereRaw($column, $operator, $value);
        }
       
        return $this;

    }

    public function orWhere(string $column, $operator = null, $value = null): self
    {
    	if(!$this->builder) throw new \Exception("Invalid instance of ElasticScoutDriverPlus\\Builders\\SearchRequestBuilder");
    

    	// Here we will make some assumptions about the operator. If only 2 values are
        // passed to the method, we will assume that the operator is an equals sign
        // and keep going. Otherwise, we'll require the operator to be passed in.
        [$value, $operator] = $this->builder->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );
 
        if($operator === '=' && !is_array($value)){
            $this->builder->orWhere($column, $value);
        } else {
            $this->builder->orWhere($column, $operator, $value);
        }
       
        return $this;

    }

}