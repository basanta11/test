<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\Datatable\NotificationQuery;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notification;
use Illuminate\Pagination\Paginator;

class NotificationController extends Controller
{
    //
    public function readNotification(Request $request)
    {

        $arr=array_map(function($val){
            return $val['id'];
        }, $request->unreads);
        Notification::whereIn('id',$arr)->update([
            'read_at'=>now()
        ]);
        return response()->json(['success'=>true]);
    }
    public function index()
    {
        // $notifications=Notificatuib\\
        return view('admin.notifications.index');
    }

    public function notificationListForIndex(NotificationQuery $query, Request $request)
    {
        $data = $mainData = [];
        $currentPage = $request->all();
        $columnsToSearch = [];

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage['pagination']['page'];
        });
		$customQuery = Notification::query();

        $filteredQuery = $query->prepareQuery($customQuery, $columnsToSearch);
        $mainData= $filteredQuery
        ->where('notifiable_type','App\User')
        ->where('notifiable_id' ,auth()->user()->id)
        ->paginate(10);


        $data['data'] = $mainData->map(function($data,$sn) {
            
            return [
                'sn'=>$sn+=1,
                'id'=>$data->id,
                'link'=>json_decode($data->data)->model_link,
                'notification'=>json_decode($data->data)->notification,
                'created_at'=>date('jS M, Y g:i a', strtotime($data->created_at))
            ];
        });

        $pagination = $mainData->toArray();
        $data = array_merge($data, [
            "meta" => [
                "page" => $pagination['current_page'],
                "pages" => $pagination['last_page'],
                "perpage" => $pagination['per_page'],
                "total" => $pagination['total']
            ]
        ]);
        
        return $data;

    }
}
