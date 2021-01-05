<?php


namespace App\Helpers\Datatable;

abstract class Query
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function prepareQuery($query, $fields)
    {
        if ( !empty($this->request->input('query')) ) {
            $param = $this->request->input('query');
            // dump(empty($param['generalSearch']));
            if (!empty($param['generalSearch'])) {
                $query->where(function($query) use($fields, $param) {
                  
                    foreach ($fields as $field) {
                        
                        $query->orWhere($field, 'LIKE', '%'.$param['generalSearch'].'%');
                        
                    }
                });
            }

			$query = $this->selectFilterQueries($query, $param);
        }
        if ($this->request->input('sort') != '') {
            $sort = $this->request->input('sort');

            $query->orderBy($sort['field'], $sort['sort']);
        }
        else {
	        $query->orderBy('created_at');
        }

        return $query;
    }

    abstract function selectFilterQueries($query, $param);
}