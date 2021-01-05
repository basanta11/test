<?php

namespace App\Http\Controllers\Tenant;

use Auth;
use App\User;

use App\Feedback;
use App\Http\Requests;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeedbackController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {   
        if (auth()->user()->hasRole('Principal')) {
            $feedbacks = Feedback::where('tenant_id', tenant()->id)
                ->get()
                ->map(function($f) {
                    return (object) [
                        'id' => $f->id,
                        'title' => $f->title,
                        'description' => $f->description,
                        'created_at' => $f->created_at,
                        'image' => $f->image,
                        'user' => User::where('id', $f->user_id)->select('id', 'name', 'role_id')->with('role')->first()->toArray(),
                    ];
                });
        }
        else {
            $feedbacks = Feedback::where('user_id', auth()->user()->id)->where('tenant_id', tenant()->id)->get();
        }

        return view('admin.feedbacks.index', compact('feedbacks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(){
        return view('admin.feedbacks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, FileHelper $file)
    {
        if ($request->photo) {
            $image = $file->storeFile($request->photo, 'feedbacks');
        }
        else {
            $image = null;
        }

        $request->merge(['user_id' => auth()->user()->id, 'image' => $image, 'tenant_id' => tenant()->id, 'domain' => tenant()->domains[0]]);
        
        Feedback::create($request->all());

        return redirect('feedbacks')->with('success', 'Feedback added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id){
        $feedback = Feedback::findOrFail($id);

        return view('admin.feedbacks.show', compact('feedback'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id){
        $feedback = Feedback::findOrFail($id);

        return view('admin.feedbacks.edit', compact('feedback'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Feedback $feedback, FileHelper $file)
    {
        if ($request->photo) {
            $image = $file->updateFile($request->photo, 'feedbacks', $feedback->image);
        }
        else {
            $image = $feedback->image;
        }

        $request->merge(['image' => $image]);

        $feedback->update($request->all());

        return redirect('feedbacks')->with('success', 'Feedback updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Feedback $feedback, FileHelper $file)
    {
        if ( !empty($feedback->image) ) {
            $file->deleteFile('feedbacks', $feedback->image);
        }

        $feedback->delete();

        return redirect('feedbacks')->with('success', 'Feedback deleted!');
    }
}
