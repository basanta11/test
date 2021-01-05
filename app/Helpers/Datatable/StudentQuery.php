<?php


namespace App\Helpers\Datatable;

use App\Classroom;
use Illuminate\Database\Eloquent\Builder;

class StudentQuery extends Query
{
	public function __construct($request)
	{
		parent::__construct($request);
	}

	public function selectFilterQueries($query, $param)
	{
        if (!empty($param['Status'])) {
            $query->whereStatus((int)($param['Status'])-10);
        }
        // dd($query->get());

		if (!empty($param['Classroom'])) {
			$classroom_id = $param['Classroom'];
			if ($param['Classroom'] == 'all') {
				$classrooms = Classroom::get()->pluck('id');

				$query->whereHas('student_detail.classroom', function(Builder $subquery) use($classrooms) {
					$subquery->whereIn('id', $classrooms);
				});
			}
			else {
				$query->whereHas('student_detail.classroom', function(Builder $subquery) use($classroom_id) {
					$subquery->where('id', $classroom_id);
				});

				if (!empty($param['Section'])) {
					if ($param['Section'] == 'all') {
						$query;
					}
					else {
						$section_id=$param['Section'];
						$query->whereHas('student_detail.section', function(Builder $subquery) use($section_id) {
							$subquery->where('id', $section_id);
						});
						// $param['Section']);
					}
				}
			}

        }
        return $query;
	}
}