<?php

namespace App\Http\Controllers\Tenant;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Course;
use App\CourseDetail;
use App\Frontend;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Storage;
use App\Mail\FrontendMessage;
use Illuminate\Support\Facades\Mail;

class FrontendController extends Controller
{
    //
    public function index(FileHelper $file)
    {
        $teachers=User::with(['created_user'])->whereStatus(1)->whereRoleId(3)->get()->map(function($data,$sn) use($file){
            
            return [
                'sn'=>$sn+=1,
                'id'=>$data->id,
                'name'=>$data->name,
                'email'=>$data->email,
                'image'=>($data->image && $file->fileExists('users',$data->image)) ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.$data->image) : global_asset('assets/media/users/default.jpg'),
            ];
        });
        $courses=Course::whereStatus(1)->get()->map(function($data,$sn) use($file){
            if ( auth()->user() ) {
                if ( auth()->user()->hasRole('Principal') || auth()->user()->hasRole('Administrator') ) {
                    $link = "/courses/" . $data['id'];
                }
                else if ( auth()->user()->hasRole('Teacher') ) {
                    $coursedetails = CourseDetail::where('user_id', auth()->user()->id)->pluck('course_id')->toArray();
                    if ( in_array($data['id'], $coursedetails) ) {
                        $link = "/assigned-courses/" . $data['id'];
                    }
                    else {
                        $link = "/assigned-courses";
                    }
                }
                else if ( auth()->user()->hasRole('Student') ) {
                    $sectionId = auth()->user()->student_detail->section_id;
                    $coursedetails = CourseDetail::where('section_id', $sectionId)->pluck('course_id')->toArray();
                    
                    if ( in_array($data['id'], $coursedetails) ) {
                        $link = "/student/assigned-courses/" . $data['id'];
                    }
                    else {
                        $link = "/student/assigned-courses";
                    }
                }
                else {
                    $link = "/login";
                }
            }
            else {
                $link = "/login";
            }
            return [
                'id'=>$data->id,
                'title'=>$data->title,
                'learn_what'=>$data->learn_what,
                'credit_hours'=>$data->credit_hours,
                'image'=>($data->image && $file->fileExists('courses',$data->image)) ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/courses/'.$data->image) : global_asset('assets/media/default-image.jpg'),
                'link' => $link
            ];
        });
        $frontend=Frontend::first();
        $data=[
            'map'=> $frontend ? $frontend->map ? $frontend->map : '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4559.462571540951!2d100.61817515190376!3d13.83261819468169!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30e29d7d44e2c4b9:0x8ba7486c550fd55d!2s79, 95 Soi Prasert Manukich 29 Yaek 2, Khwaeng Lat Phrao, Khet Lat Phrao, Krung Thep Maha Nakhon 10230, Thailand!5e0!3m2!1sen!2snp!4v1596022169682!5m2!1sen!2snp" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>' : '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4559.462571540951!2d100.61817515190376!3d13.83261819468169!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30e29d7d44e2c4b9:0x8ba7486c550fd55d!2s79, 95 Soi Prasert Manukich 29 Yaek 2, Khwaeng Lat Phrao, Khet Lat Phrao, Krung Thep Maha Nakhon 10230, Thailand!5e0!3m2!1sen!2snp!4v1596022169682!5m2!1sen!2snp" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>',
                'color'=>[
                    'primary'=>$frontend ? $frontend->primary_color ? $frontend->primary_color : '#202c45' : '#202c45',
                    'secondary'=>$frontend ? $frontend->secondary_color ? $frontend->secondary_color : '#f36324' : '#f36324',
                    'card'=>$frontend ? $frontend->card_color ? $frontend->card_color : '#f1f1f1' : '#f1f1f1',
                ],
                'social-links'=>[
                    'facebook'=>$frontend ? isset(json_decode($frontend->social_links,true)['facebook']) 
                    ? json_decode($frontend->social_links,true)['facebook']
                    : ''
                    : '',
                    'line'=>$frontend ? isset(json_decode($frontend->social_links,true)['line']) 
                    ? json_decode($frontend->social_links,true)['line']
                    : ''
                    : '',
                    'twitter'=>$frontend ? isset(json_decode($frontend->social_links,true)['twitter']) 
                    ? json_decode($frontend->social_links,true)['twitter']
                    : ''
                    : '',
                    'youtube'=>$frontend ? isset(json_decode($frontend->social_links,true)['youtube']) 
                    ? json_decode($frontend->social_links,true)['youtube']
                    : ''
                    : '',
                    'skype'=>$frontend ? isset(json_decode($frontend->social_links,true)['skype']) 
                    ? json_decode($frontend->social_links,true)['skype']
                    : ''
                    : '',
                    'instagram'=>$frontend ? isset(json_decode($frontend->social_links,true)['instagram']) 
                    ? json_decode($frontend->social_links,true)['instagram']
                    : ''
                    : '',
                ],
                'about-us'=>[
                    'title'=>$frontend ? isset(json_decode($frontend->about_us,true)['title']) 
                    ? json_decode($frontend->about_us,true)['title']
                    : ''
                    : '',
                    'description'=>$frontend ? isset(json_decode($frontend->about_us,true)['description']) 
                    ? json_decode($frontend->about_us,true)['description']
                    : ''
                    : '',
                ],
                'mission'=>[
                    'title'=>$frontend ? $frontend->mission : '',
                    'image'=>$frontend 
                    ? ($frontend->mission_image && $file->fileExists('frontend',$frontend->mission_image))
                    ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/frontend/'.$frontend->mission_image) 
                    : global_asset('assets/media/default-image.jpg')
                    : global_asset('assets/media/default-image.jpg')
                ],
                'vision'=>[
                    'title'=>$frontend ? $frontend->vision : '',
                    'image'=>$frontend 
                    ? ($frontend->vision_image && $file->fileExists('frontend',$frontend->vision_image))
                    ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/frontend/'.$frontend->vision_image) 
                    : global_asset('assets/media/default-image.jpg')
                    : global_asset('assets/media/default-image.jpg')
                ],
                'goal'=>[
                    'title'=>$frontend ? $frontend->goal : '',
                    'image'=>$frontend 
                    ? ($frontend->goal_image && $file->fileExists('frontend',$frontend->goal_image))
                    ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/frontend/'.$frontend->goal_image) 
                    : global_asset('assets/media/default-image.jpg')
                    : global_asset('assets/media/default-image.jpg')
                ],
                'banners'=>$frontend ?
                $frontend->banners== null 
                ? [] 
                
                : array_map(function($data) use($file){
                    return [
                        'title'=>$data['title'],
                        'description'=>$data['description'],
                        'image'=>($data['image'] && $file->fileExists('frontend',$data['image']))
                        ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/frontend/'.$data['image']) 
                        : global_asset('assets/media/default-image.jpg')
                        
                    ];
                }, json_decode($frontend->banners,true))
                : [[
                    'title'=>'No banners',
                    'description'=>'Add Banner',
                    'image'=>global_asset('assets/media/default-image.jpg'),
                    ]],
                    'contacts'=>[
                        'phone'=>$frontend ? isset(json_decode($frontend->contacts,true)['phone']) 
                        ? json_decode($frontend->contacts,true)['phone']
                        : ''
                        : '',
                        'email'=>$frontend ? isset(json_decode($frontend->contacts,true)['email']) 
                        ? json_decode($frontend->contacts,true)['email']
                        : ''
                        : '',
                        'website'=>$frontend ? isset(json_decode($frontend->contacts,true)['website']) 
                        ? json_decode($frontend->contacts,true)['website']
                        : ''
                        : '',
                        'address'=>$frontend ? isset(json_decode($frontend->contacts,true)['address']) 
                        ? json_decode($frontend->contacts,true)['address']
                        : ''
                        : '',
                        'video'=>$frontend ? isset(json_decode($frontend->contacts,true)['video']) 
                        ? json_decode($frontend->contacts,true)['video']
                        : ''
                        : '',
                        ]
                    ];
                    // dd($data);
                    
                    return view('frontend.index',compact('teachers','data','courses'));
                }
                
                public function mail(Request $request)
                {
                    if(session()->get('mailed_time')){
                        if(session()->get('mailed_time')<now()){
                            return response()->json(['status'=>false,'message'=>'Please wait an hour after sending one email.']);
                        }
                    }
                    
                    $data=[
                        'name'=>$request->name,
                        'email'=>$request->email,
                        'message'=>$request->message,
                        'phone'=>$request->phone,
                        'subject'=>$request->subject
                    ];
                    Mail::to(config('app.mail_username'))->queue(new FrontendMessage($data));
                    
                    session()->put('mailed_time',now());
                    return response()->json(['status'=>true,'message'=>'Mesage has been successfully sent.']);
                }
            }
            