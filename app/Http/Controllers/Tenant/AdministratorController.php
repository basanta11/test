<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Mail\UserCreated;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Str;

class AdministratorController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FileHelper $file)
    {
        
        $sn=0;
        $administrators=User::with(['created_user'])->whereRoleId(2)->get()->map(function($data,$sn) use($file){
            $status=10;
            if($data->status==1)
            {
                $status=11;
            }elseif($data->status==2){
                $status=12;
            }
            $g='Male';
            if($data->gender==1){
                $g='Female';
            }
            if($data->gender==2){
                $g='Other';
            }
            return [
                'sn'=>$sn+=1,
                'id'=>$data->id,
                'name'=>$data->name,
                'email'=>$data->email,
                'address'=>$data->address,
                'phone'=>$data->phone,
                'citizen_number'=>$data->citizen_number,
                'symbol_number'=>$data->symbol_number,
                'gender'=>$g,
                'status'=>$status,
                'created_by'=>isset($data['created_user']['name'] ) ? $data['created_user']['name'] : "",
                'image'=>($data->image && $file->fileExists('users',$data->image)) ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.$data->image) : global_asset('assets/media/users/default.jpg'),
                'created_at'=>$data->created_at,
            ];
        });
        return view('admin.administrators.index',compact('administrators'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.administrators.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,FileHelper $file)
    {
        //
        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'gender' => ['required', 'integer'],
            // 'phone' => ['required', 'integer'],
            // 'citizen_number' => ['required','unique:users'],
            'symbol_number' => ['required', 'numeric','unique:users'],
            'address' => ['required', 'string', 'max:255'],
        ]);
        
        $password = Str::random(8);
        $request->merge([
            'role_id' => 2,
            'password' => bcrypt($password),
            'status' => 0
        ]);
        
        if ( $request->image ) {
            $image=$file->storeFile($request->image,'users');
        }
        else {
            $image = null;
        }
        
        $user = User::create(array_merge(['image'=>$image,'created_by'=>auth()->user()->id],$request->except(['image'])));
        
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = $password;

        Mail::to($user)->queue(new UserCreated($data));
        
        return redirect('/administrators')->with('success', 'Data saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,FileHelper $file)
    {
        //
        $administrator=User::findOrFail($id);
        if($administrator->role_id!=2)
            abort(403);
        $profile_image=($administrator->image && $file->fileExists('users',$administrator->image)) ?  Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.$administrator->image) : global_asset('assets/media/users/default.jpg');
                
        return view('admin.administrators.show',compact('administrator','profile_image'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $administrator=User::findOrFail($id);
        if($administrator->role_id!=2)
            abort(403);
        
        return view('admin.administrators.edit',compact('administrator'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id,FileHelper $file)
    {
        //
        $administrator=User::findOrFail($id);
        if($administrator->role_id!=2)
            abort(403);
        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email, '.$administrator->id],
            // 'citizen_number' => ['required','unique:users,citizen_number, '.$administrator->id],
            'symbol_number' => ['required', 'numeric','unique:users,symbol_number, '.$administrator->id],
            'gender' => ['required', 'integer'],
            // 'phone' => ['required', 'integer'],
            'address' => ['required', 'string', 'max:255'],
        ]);
        
        $image=$administrator->image;
        if(isset($request->image)){
            $image=$file->updateFile($request->image,'users',$administrator->image);
        };

        $administrator->update(array_merge(['image'=>$image],$request->except(['csrf_token','_method','image'])));
        
        return redirect('/administrators')->with('success', 'Data updated.');
        
    }

    // $status -> send 1 to active, 2 to deactivate
    public function statusControl($id,$status)
    {
        if(!in_array($status,[1,2])){
            return back()->with('error','Action cannot be recognized. Please try again.');
        }
        $message=$status==1 ? 'Administrator activated.': 'Administrator deactivated' ;
        $administrator=User::findOrFail($id);

        $administrator->update(['status'=>$status]);
        return back()->with('success',$message);

    }

    // $status -> send 1 to active, 2 to deactivate
    public function statusControlBulk(Request $request,$status)
    {
    if(!in_array($status,[1,2]) || !json_decode($request->user_list)){
        return back()->with('error','Action cannot be recognized. Please try again.');
    }
    $message=$status==1 ? 'Administrator(s) activated.': 'Administrator(s) deactivated' ;
    User::whereIn('id',json_decode($request->user_list))->update(['status'=>$status]);
        
    return back()->with('success',$message);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
