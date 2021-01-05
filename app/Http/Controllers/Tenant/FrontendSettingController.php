<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Frontend;
use Illuminate\Support\Facades\Storage;
use App\Helpers\FileHelper;
use Str;

class FrontendSettingController extends Controller
{
    //
    public function index(FileHelper $file)
    {
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
                : [],
            
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
        return view('admin.settings.frontend', compact('data'));
    }
    public function changeColor(Request $request)
    {
        // dd($request->all());
        $f=Frontend::first();
        if($f){
            if($request->type=="primary")
                $f->update(['primary_color'=>$request->color]);
            elseif($request->type=="secondary")
                $f->update(['secondary_color'=>$request->color]);
            else
                $f->update(['card_color'=>$request->color]);
        }else{
            if($request->type=="primary")
                Frontend::create(['user_id'=>auth()->user()->id,'primary_color'=>$request->color]);
            elseif($request->type=="secondary")
                Frontend::create(['user_id'=>auth()->user()->id,'secondary_color'=>$request->color]);
            else
                Frontend::create(['user_id'=>auth()->user()->id,'card_color'=>$request->color]);
        }
        return response()->json(['success'=>true]);
    }
    
    public function addBanner(Request $request, FileHelper $file)
    {
        $f=Frontend::first();
        if($f){
            if($request->image){
               $image=$file->storeFile($request->image,'frontend');
            }
            $old=json_decode($f->banners,true);
            if($old){
                $f->update(['banners'=>json_encode(array_merge($old,[
                    strval(Str::uuid())=>[
                        'title'=>$request->title,
                        'description'=>$request->description,
                        'image'=>$image,
                    ]
                ]))]);
            }else{
                $f->update(['banners'=>json_encode([
                    strval(Str::uuid())=>[
                        'title'=>$request->title,
                        'description'=>$request->description,
                        'image'=>$image,
                    ]
                ])]);
            }
        }else{
            if($request->image){
                $image=$file->storeFile($request->image,'frontend');
            }
            Frontend::create(array_merge(['user_id'=>auth()->user()->id],['banners'=>json_encode([
                strval(Str::uuid())=>[
                    'title'=>$request->title,
                    'description'=>$request->description,
                    'image'=>$image,
                ]
            ])]));
        }
        
        return back()->with('success','Banner created')->with('res','banner');
    }
    public function changeBanner(Request $request,$id, FileHelper $file)
    {
        $f=Frontend::first();
        if(!$f)
            abort(404);
        $banners=json_decode($f->banners,true);
        $image=$banners[$id]['image'];
        if($request->image){
            $image=$file->updateFile($request->image,'frontend',$image);
        }
        unset($banners[$id]);
        $f->update(['banners'=>json_encode(array_merge($banners,[
            strval(Str::uuid())=>[
                'title'=>$request->title,
                'description'=>$request->description,
                'image'=>$image,
            ]
        ]))]);
        
        return back()->with('success','Banner updated')->with('res','banner');;
    }
    public function changeAbout(Request $request)
    {
        $f=Frontend::first();
        if($f){
            $f->update(['about_us'=>json_encode([
                'title'=>$request->title,
                'description'=>$request->description
            ])]);
        }else{
            Frontend::create(['about_us'=>json_encode([
                'title'=>$request->title,
                'description'=>$request->description
            ])]);
        }

        return back()->with('success','About us Updated')->with('res','about-us');

    }
    public function changeMission(Request $request,FileHelper $file)
    {
        $f=Frontend::first();
        $arr=$request->only('mission');
        if($f){
            if($request->image){
                $arr=array_merge($arr,['mission_image'=>$file->updateFile($request->image,'frontend',$f->mission_image)]);
            }
            $f->update($arr);
        }else{
            if($request->image){
                $arr=array_merge($arr,['mission_image'=>$file->storeFile($request->image,'frontend')]);
            }
            Frontend::create(array_merge(['user_id'=>auth()->user()->id],$arr));
        }

        return back()->with('success','Mission Updated')->with('res','about-us');

    }
    public function changeVision(Request $request, FileHelper $file)
    {
        $f=Frontend::first();
        $arr=$request->only('vision');
        if($f){
            if($request->image){
                $arr=array_merge($arr,['vision_image'=>$file->updateFile($request->image,'frontend',$f->vision_image)]);
            }
            $f->update($arr);
        }else{
            if($request->image){
                $arr=array_merge($arr,['vision_image'=>$file->storeFile($request->image,'frontend')]);
            }
            Frontend::create(array_merge(['user_id'=>auth()->user()->id],$arr));
        }

        return back()->with('success','Vision Updated')->with('res','about-us');
    }
    public function changeGoal(Request $request, FileHelper $file)
    {
        $f=Frontend::first();
        $arr=$request->only('goal');
        if($f){
            if($request->image){
                $arr=array_merge($arr,['goal_image'=>$file->updateFile($request->image,'frontend',$f->goal_image)]);
            }
            $f->update($arr);
        }else{
            if($request->image){
                $arr=array_merge($arr,['goal_image'=>$file->storeFile($request->image,'frontend')]);
            }
            Frontend::create(array_merge(['user_id'=>auth()->user()->id],$arr));
        }

        return back()->with('success','Goal Updated')->with('res','about-us');
    }

    public function deleteBanner($id, FileHelper $file)
    {
        $f=Frontend::first();
        if(!$f)
            abort(404);

        $banners=json_decode($f->banners,true);
        $image=$banners[$id]['image'];
        if($image){
            $image=$file->deleteFile('frontend',$image);
        }
        unset($banners[$id]);
        // dd($banners);
        $f->update(['banners'=>json_encode($banners)]);
        return back()->with('success','Banner deleted')->with('res','banner');

    }

    public function changeSocialLinks(Request $request)
    {
        $f=Frontend::first();
        if($f){
            $f->update(['social_links'=>json_encode([
                'facebook'=>$request->facebook,
                'twitter'=>$request->twitter,
                'line'=>$request->line,
                'youtube'=>$request->youtube,
                'skype'=>$request->skype,
                'instagram'=>$request->instagram
            ])]);
        }else{
            Frontend::create(['social_links'=>json_encode([
                'facebook'=>$request->facebook,
                'twitter'=>$request->twitter,
                'line'=>$request->line,
                'youtube'=>$request->youtube,
                'skype'=>$request->skype,
                'instagram'=>$request->instagram
            ])]);
        }


        return back()->with('success','Social links updated')->with('res','social');


    }
    public function changeContacts(Request $request)
    {
        $f=Frontend::first();
        if($f){
            $f->update(['contacts'=>json_encode([
                'phone'=>$request->phone,
                'email'=>$request->email,
                'address'=>$request->address,
                'video'=>$request->video,
                'website'=>$request->website,
            ])]);
        }else{
            Frontend::create(['contacts'=>json_encode([
                'phone'=>$request->phone,
                'email'=>$request->email,
                'address'=>$request->address,
                'video'=>$request->video,
                'website'=>$request->website,
            ])]);
        }


        return back()->with('success','Social links updated')->with('res','contact');


    }
    public function changeMap(Request $request)
    {
        $f=Frontend::first();
        if($f){
            $f->update(['map'=>$request->map]);
        }else{
            Frontend::create(['map'=>$request->map]);
        }

        return back()->with('success','Social links updated')->with('res','map');


    }
}
