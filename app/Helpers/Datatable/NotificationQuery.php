<?php


namespace App\Helpers\Datatable;

use App\Classroom;
use Illuminate\Database\Eloquent\Builder;

class NotificationQuery extends Query
{
	public function __construct($request)
	{
		parent::__construct($request);
	}

	public function selectFilterQueries($query, $param)
	{
        return $query;
    }
}
