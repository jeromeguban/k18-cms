<?php 

namespace App\Elasticsearch;

use Carbon\Carbon;
use ElasticScoutDriverPlus\Builders\QueryBuilderInterface;
use InvalidArgumentException;

class QueryBuilder implements QueryBuilderInterface
{
    /**
     * All of the available clause operators.
     *
     * @var array
     */
    private $operators = [
        '<'     => 'lt', 
        '>'     => 'gt', 
        '<='    => 'lte', 
        '>='    => 'gte',
    ];

    /**
     * @var array
     */
    private $terms = [
        'must' => [],
        'should' => [],
        'must_not'  => []
    ];

    /**
     * @var array
     */
    private $ranges = [
        'must' => [],
        'should' => [],
        'must_not'  => []
    ];

    private $operator = 'AND';

    /**
     * @var string
     */
    private $query;

    /**
     * @var boolean
     */
    private $random;

    /**
     * @var array
     */
    private $fields = [];

     /**
     * @var array
     */
    private $scripts = [
        'script'    => [
            'inline'    =>  null,
            'lang'      => 'painless',
        ],
    ];

    public function __construct($query, $random = false, $fields)
    {
        $this->query = $query;
        $this->random = $random;
        $this->fields = $fields;
    }

    public function buildQuery(): array
    {
        // dd($this->getQuery());
        return [
            'bool' => $this->getQuery(),
        ];
    }

    private function getQuery(): array
    {
        $query = [
            'must'  => $this->query == '*' ? $this->getAllQuery() : $this->getSearchQuery(),
        ];

        if(count($this->getTerms()['should']) 
            || count($this->getTerms()['must']) 
            || count($this->getTerms()['must_not']) 
            || count($this->getRanges()['should']) 
            || count($this->getRanges()['must'])
            || $this->getScripts()['script']['inline']) {

            $query['filter'] = [
                'bool' => []
            ];

            if( count($this->getTerms()['must']) || count($this->getRanges()['must']) )
                $query['filter']['bool']['must'] = array_merge($this->getTerms()['must'], $this->getRanges()['must']);

            if( count($this->getTerms()['should']) || count($this->getRanges()['should']) )
                $query['filter']['bool']['should'] = array_merge($this->getTerms()['should'], $this->getRanges()['should']);

            if( count($this->getTerms()['must_not']) || count($this->getRanges()['must_not']) )
                $query['filter']['bool']['must_not'] = array_merge($this->getTerms()['must_not'], $this->getRanges()['must_not']);

            // dd($this->getScripts());
            if( $this->getScripts()['script']['inline'] )
                $query['filter']['bool']['must'][]['script'] = $this->getScripts();


        }



 
        return $query;
    }

    private function getAllQuery(): array
    {
        if($this->random)
            return [
                // 'match_all' => new \stdClass(),
                "function_score" => [
                    'query' => [
                        'match_all' => new \stdClass(),
                    ],
                    "random_score" => new \stdClass(),
                ],
            ];

        return [
            'match_all' => new \stdClass(),   
        ];

    }

    private function getSearchQuery(): array
    {
        return [

            'multi_match' => [
                'query' => $this->query,
                'boost' => 10,
                // 'fuzziness' => 5,
                'fields'=> $this->fields,
                'operator'  => $this->operator,
            ],

                    
        ];
    }

    private function getTerms()
    {
        return $this->terms;
    }

    private function setTerms(string $column, $value, $term_type = 'must'): self
    {
        $type = is_array($value) ? 'terms' : 'term';

        $term = [
            $type => [
                $column => $value,
                
            ]
        ];
        
        if($type == 'terms')
            $term[$type]['boost'] = 10.0;


        array_push($this->terms[$term_type], $term);

        return $this;
    }

    private function getScripts()
    {
        return $this->scripts;
    }

    private function setScripts(string $column, $operator ,$value, $operation = "&&"): self
    {

        $this->scripts['script']['inline'] = $this->scripts['script']['inline'] ? $this->scripts['script']['inline']." $operation doc['$column'].value $operator doc['$value'].value" : "doc['$column'].value $operator doc['$value'].value";

        return $this;
    }

    private function getRanges()
    {
        return $this->ranges;
    }

    private function setRanges(string $column, $operator, $value, $range_type = 'must'): self
    {

        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );

        $operations = is_array($value) ? [ 
                'gte'=> $value[0], 
                'lte'   => $value[1] 
            ] : [ 
                $this->operators[$operator] => $value,
                'boost' => 10.0
            ];

        $range = [  
            'range' => [
                $column   => $operations,
            ],
        ];

        array_push($this->ranges[$range_type], $range);

        return $this;
    }

    public function setOperator(string $operator)
    {
        $this->operator = $operator;
        return $this;
    }

    public function setFields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }

    public function whereIn(string $column, array $value): self
    {

        $this->setTerms($column, $value);

        return $this;
    }

    public function orWhereIn(string $column, array $value): self
    {

        $this->setTerms($column, $value, 'should');

        return $this;
    }

    public function where(string $column, $operator = null, $value = null): self
    {

        // Here we will make some assumptions about the operator. If only 2 values are
        // passed to the method, we will assume that the operator is an equals sign
        // and keep going. Otherwise, we'll require the operator to be passed in.
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );
        
        if($operator === '=' && !is_array($value)){
            $this->setTerms($column, $value);
        } else {
            $this->setRanges($column, $operator, $value);
        }

       
        return $this;
    }

    public function whereNotNull(string $column)
    {
        $term = [
            "exists" => [
                "field" => $column,
            ]
        ];
        

        array_push($this->terms['must'], $term);

        return $this;
    }

    public function whereNull(string $column)
    {
        $term = [
            "exists" => [
                "field" => $column,
            ]
        ];
        

        array_push($this->terms['must_not'], $term);

        return $this;
    }

    public function whereRaw(string $column, $operator = null, $value = null): self
    {

        // Here we will make some assumptions about the operator. If only 2 values are
        // passed to the method, we will assume that the operator is an equals sign
        // and keep going. Otherwise, we'll require the operator to be passed in.
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );
 
        if($operator === '=' && !is_array($value)){
            $this->setScripts($column, '==', $value);
        } else {
            $this->setScripts($column, $operator, $value);
        }

       
        return $this;
    }

    public function orWhereRaw(string $column, $operator = null, $value = null): self
    {

        // Here we will make some assumptions about the operator. If only 2 values are
        // passed to the method, we will assume that the operator is an equals sign
        // and keep going. Otherwise, we'll require the operator to be passed in.
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );
 
        if($operator === '=' && !is_array($value)){
            $this->setScripts($column, '==', $value, '||');
        } else {
            $this->setScripts($column, $operator, $value, '||');
        }

       
        return $this;
    }

    public function orWhere(string $column, $operator = null, $value = null): self
    {

        // Here we will make some assumptions about the operator. If only 2 values are
        // passed to the method, we will assume that the operator is an equals sign
        // and keep going. Otherwise, we'll require the operator to be passed in.
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );
 
        if($operator === '=' && !is_array($value)){
            $this->setTerms($column, $value, 'should');
        } else {
            $this->setRanges($column, $operator, $value, 'should');
        }

       
        return $this;
    }

    public function whereBetween(string $column, array $value): self
    {

        if(count($value) != 2)
            throw new InvalidArgumentException('Illegal operator and value combination.');

        $this->where($column, $value);

        return $this;
    }

    public function orWhereBetween(string $column, array $value): self
    {

        if(count($value) != 2)
            throw new InvalidArgumentException('Illegal operator and value combination.');

        $this->orWhere($column, $value);

        return $this;
    }

    /**
     * Prepare the value and operator for a where clause.
     *
     * @param  string  $value
     * @param  string  $operator
     * @param  bool  $useDefault
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function prepareValueAndOperator($value, $operator, $useDefault = false)
    {
        if ($useDefault) {
            return [$operator, '='];
        } elseif ($this->invalidOperatorAndValue($operator, $value)) {
            throw new InvalidArgumentException('Illegal operator and value combination.');
        }

        return [$value, $operator];
    }

    /**
     * Determine if the given operator and value combination is legal.
     *
     * Prevents using Null values with invalid operators.
     *
     * @param  string  $operator
     * @param  mixed  $value
     * @return bool
     */
    public function invalidOperatorAndValue($operator, $value)
    {
        return is_null($value) && array_key_exists($operator, $this->operators) &&
             ! in_array($operator, ['=', '<>', '!=']);
    }


}