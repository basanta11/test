<?php

namespace App\Http\Controllers\Tenant;

use File;
use App\User;
use App\Topic;
use App\Course;
use ZipArchive;
use App\Schedule;
use App\CourseDetail;
use App\Helpers\CsvHelper\DataHelper;
use App\TopicAttachment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use hisorange\BrowserDetect\Parser as Browser;

class StudentAssignedCourseController extends Controller
{
    public function index(DataHelper $dh)
    {
        $dh->tinel_su();

        $sn = 0;
        $sectionId = auth()->user()->student_detail->section_id;

        $courses = CourseDetail::where('section_id', $sectionId)
            ->whereHas('course', function($query) {
                $query->where('status', 1);
            })
            ->with([
                'user:id,name',
                'course.classroom'
            ])
            ->get()
            ->map(function($data, $sn) {
                $classroom=$data->course->classroom ? $data->course->classroom->title : 'N/A';
                
                return [
                    'sn' => $sn += 1,
                    'id' => $data->course->id,
                    'teacher_id' => $data->user_id,
                    'title' => $data->course->title,
                    'credit_hours' => $data->course->credit_hours,
                    'learn_what' => $data->course->learn_what,
                    'classroom' => $classroom,
                    'teacher' => $data->user->name,
                    'created_at' => $data->created_at,
                ];
            });

        $class = auth()->user()->student_detail;
        // dd($class);  

        return view('admin.assigned-courses-students.index', compact('courses', 'class'));
    }

    public function show($id)
    {
        $course = Course::where('id', $id)
            ->with([
                'lessons' => function($q) {
                    $q->where('status', 1);
                },
                'lessons.topics' => function($qu) {
                    $qu->where('status', 1);
                },
                'lessons.topics.topic_attachments'
            ])
            ->first();

        if ( !in_array(auth()->user()->student_detail->section_id, $course->course_details->pluck('section_id')->toArray()) ) {
            abort(401);
        }

        if ($course->status == 0) 
            return abort(404);

        $flag = $topic = 0;
        
        foreach ($course->lessons as $l) {
            if (!$l->topics->isEmpty())
                $flag = 1;
        }

        $user = User::whereId(auth()->user()->id)->with([
            'student_reading' => function($q) use($course) {
                $q->where('course_id', $course->id);
            },
            'student_reading.topic.topic_attachments'
        ])
        ->first();
    
        
        if ($flag == 0)
            return back()->with('error', 'No topics added yet in this course.');

        if (!empty($user->student_reading)) {
            $topic = $user->student_reading;

            $videoData = $this->formatVideoData($topic);
        }
        else {
            foreach($course->lessons as $lesson) {
                if ( !$lesson->topics->isEmpty() ) {
                    $topic = $lesson->topics->first();
                }
            }

            $videoData = $this->formatVideoData($topic);
        }
        // dd($topic);

        return view('admin.assigned-courses-students.show', compact('course', 'topic', 'videoData'));
    }

    private function formatVideoData($topic)
    {
        if (!empty($topic->video)) {
            $data['video'] = Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/videos/'. $topic->video);
            $data['videoType'] = 'video';
        }
        else if (!empty($topic->video_url)) {
            $url = $topic->video_url;
            $data['video'] = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","//www.youtube.com/embed/$1", $url);
            $data['videoType'] = 'url';
        }
        else {
            $data['video'] = null;
            $data['videoType'] = null;
        }

        return $data;
    }

    public function download(TopicAttachment $attachment)
    {
        return Storage::disk(config('app.storage_driver'))->download('/attachments/'.$attachment->attachment);
    }
    
    public function downloadAll(Topic $topic)
    {
        $zip = new ZipArchive;
   
        $fileName = auth()->user()->id.'-'.now()->timestamp.'-attachments.zip';
        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE)
        {
            $files = $topic->topic_attachments;
            
            foreach ($files as $file) {
                $zip->addFile(public_path('storage/' . config('app.filesystem_suffix') . tenant()->id . '/attachments/' . $file->attachment), $file->attachment);
            }
            
            $zip->close();
        }
    
        return response()->download(public_path($fileName))->deleteFileAfterSend();
    }

    public function getTopicDetails(Topic $topic)
    {
        $videoData = $this->formatVideoData($topic);

        return response()->json([
            'result' => view('admin.assigned-courses-students.render-topic', compact('topic', 'videoData'))->render()
        ]);
    }

    public function currentClass()
    {
        $schedule = Schedule::where('section_id', auth()->user()->student_detail->section_id)
            ->where('day', date('l'))
            ->where('start_time', '<=', date('H:i:s'))
            ->where('end_time', '>=', date('H:i:s'))
            ->first();

        if ($schedule)
            return redirect('/student/assigned-courses/' . $schedule->course_id);
        else
            return back()->with('error', 'No classes available currently.');
    }
}
