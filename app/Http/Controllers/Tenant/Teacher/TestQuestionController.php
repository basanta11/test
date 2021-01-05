<?php

namespace App\Http\Controllers\Tenant\Teacher;

use App\Http\Controllers\Controller;
use App\Attachment;
use App\Test;
use Exception;
use App\TestQuestion;
use App\TestQuestionOption;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\TestSet;
use Illuminate\Support\Facades\Storage;
use Str;
use Illuminate\Support\Facades\Http;

class TestQuestionController extends Controller
{
    //
    public function storeSingleChoice(Request $request)
    {
        $options = [];

        DB::beginTransaction();
        try {
            $question = TestQuestion::create([
                'title' => $request->question,
                'test_set_id' => $request->set_id,
                'type' => $request->type,
                'marks' => $request->marks,
                'order' => $request->order,
                'status' => 1,
            ]);
            
            if ($request->option_type == 'text') {
                if ($request->options) {
                    foreach ($request->options as $key => $value) {
                        if ( isset($value['correct_answer_text']) ) {
                            $options['is_correct'] = 1;
                        }
                        else {
                            $options['is_correct'] = 0;
                        }

                        $options['test_question_id'] = $question->id;
                        $options['type'] = 0;
                        $options['title'] = $value['option'];
                        
                        TestQuestionOption::create($options);
                    }
                }
            }
            else {
                if ($request->options) {
                    foreach ($request->options as $value) {
                        if ( isset($value['correct_answer_image']) ) {
                            $options['is_correct'] = 1;
                        }
                        else {
                            $options['is_correct'] = 0;
                        }

                        $options['test_question_id'] = $question->id;
                        $options['type'] = 1;
                        $options['title'] = $value['option_file_name'];

                        TestQuestionOption::create($options);
                    }
                }
            }
            
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
            
            return response()->json(['result' => 'failure', 'message' => $e->getMessage()]);
        }

        return response()->json(['result' => 'success']);
    }

    public function storeFile(Request $request)
    {
        $getFile = $_FILES['selectedFile'];
     
        if ( isset($getFile) && !empty($getFile) ) {
            $folder = $request->type;

            $info = pathinfo($getFile['name']);
            $fileName = $folder . '-'. Str::uuid() . '.' . $info['extension'];

            Storage::disk(config('app.storage_driver'))->putFileAs(
                $folder, $getFile['tmp_name'], $fileName
            );
        }
        else {
            return response()->json(['result' => 'failure']);
        }

        return response()->json(['result' => 'success', 'filename' => $fileName]);
    }

    public function removeFile($folder, $filename, FileHelper $file)
    {
        return $file->deleteFile($folder, $filename);
    }

    public function removeFileAndAttachment($folder, $filename, $id, FileHelper $file)
    {
        $attachment=Attachment::whereId($id)->first();
        if($attachment)
            $attachment->delete();
        return $file->deleteFile($folder, $filename);
    }

    public function edit(TestQuestion $question)
    {;
        $testset=$question->test_set;
        if($testset->test->created_by != auth()->user()->id){
            abort(401);
        }
        $test=Test::whereId($testset->test_id)->with(['lesson','lesson.course'])->first();
        $lesson=$test['lesson'];
        // dd($test);
        switch($question->type){
            case 0:
                return view('teachers.test-questions.edit-pdf', compact('testset','test','lesson','question'));
                break;

            case 1:
                $options = $question->test_question_options;
                if ($options->first()->type == 0) {
                    return view('teachers.test-questions.edit-text', compact('testset','test','lesson','question', 'options'));
                }
                else {
                    return view('teachers.test-questions.edit-image', compact('testset','test','lesson','question', 'options'));
                }
                break;  
            
            case 2:
                $options = $question->test_question_options;
                if ($options->first()->type == 0) {
                    return view('teachers.test-questions.edit-text-multi', compact('testset','test','lesson','question', 'options'));
                }
                else {
                    return view('teachers.test-questions.edit-image-multi', compact('testset','test','lesson','question', 'options'));
                }
                break;  
            case 3:
                stream_context_set_default( [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ]);
                $attachments=$question->attachments()->get()->map(function($data){
                    $url=Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/attachments/'. $data->body);
                    $url_fix=Http::get($url);
                    return [
                        'id'=>$data->id,
                        'serverName'=>$data->body,
                        'location'=>$url,
                        'size'=>isset($url_fix->headers()["Content-Length"][0]) ? $url_fix->headers()["Content-Length"][0] : null,
                    ];
                });
                return view('teachers.test-questions.edit-upload-image', compact('testset','test','lesson','question','attachments'));
                break;
            case 4:
                return view('teachers.test-questions.edit-upload-paragraph', compact('testset','test','lesson','question'));
                break;
            case 5:
                return view('teachers.test-questions.edit-upload-text', compact('testset','test','lesson','question'));
                break;

            default:
                abort(403);
                break;
        }
    }

    public function update(TestQuestion $question, Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->edit_type == "text") {
                if ($request->oldoption) {
                    foreach ($request->oldoption as $id => $o) {
                        TestQuestionOption::where('id', $id)->update(['title' => $o['option'], 'is_correct' => isset($o['is_correct']) ? 1 : 0]);
                    }
                }
    
                if ($request->options) {
                    foreach ($request->options as $v) {
                        if (!empty($v['option'])) {
                            if ( isset($v['correct_answer_text']) ) {
                                $correct = 1;
                            }
                            else {
                                $correct = 0;
                            }
    
                            TestQuestionOption::create([
                                'title' => $v['option'], 
                                'test_question_id' => $question->id, 
                                'is_correct' => $correct
                            ]);
                        }
                    }
                }
            }
            else {
                if ($request->oldoption) {
                    foreach ($request->oldoption as $id => $o) {
                        TestQuestionOption::where('id', $id)->update(['title' => $o['title'], 'is_correct' => isset($o['is_correct']) ? 1 : 0]);
                    }
                }

                if ($request->options) {
                    foreach ($request->options as $value) {
                        if (!empty($value['option'])) {
                            if ( isset($value['correct_answer_image']) ) {
                                $options['is_correct'] = 1;
                            }
                            else {
                                $options['is_correct'] = 0;
                            }

                            $options['test_question_id'] = $question->id;
                            $options['type'] = 1;
                            $options['title'] = $value['option_file_name'];

                            TestQuestionOption::create($options);
                        }
                    }
                }
            }
            
            $question->update(array_merge([ 'title'=>$request->question ],$request->except(['question'])));

            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
            
            return back()->with('error', $e->getMessage());
        }

        return redirect('/test-sets/'.$question->test_set_id)->with('success', 'Data updated.');
    }

    public function updateMultiChoice(TestQuestion $question, Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->edit_type == "text") {
                if ($request->oldoption) {
                    foreach ($request->oldoption as $id => $o) {
                        TestQuestionOption::where('id', $id)->update(['title' => $o['option']]);
                    }
                }
    
                if ($request->options) {
                    foreach ($request->options as $v) {
                        if (!empty($v['option'])) {
    
                            TestQuestionOption::create([
                                'title' => $v['option'], 
                                'test_question_id' => $question->id
                            ]);
                        }
                    }
                }
            }
            else {
                if ($request->oldoption) {
                    foreach ($request->oldoption as $id => $o) {
                        TestQuestionOption::where('id', $id)->update(['title' => $o['title']]);
                    }
                }

                if ($request->options) {
                    foreach ($request->options as $value) {
                        if (!empty($value['option'])) {
                            $options['test_question_id'] = $question->id;
                            $options['type'] = 1;
                            $options['title'] = $value['option_file_name'];

                            TestQuestionOption::create($options);
                        }
                    }
                }
            }
            
            $question->update(array_merge([ 'title'=>$request->question ],$request->except(['question'])));

            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
            
            return back()->with('error', $e->getMessage());
        }

        return redirect('/test-sets/'.$question->test_set_id)->with('success', 'Data updated.');
    }

    public function destroy(TestQuestion $question, FileHelper $file)
    {
        if($question->test_set->test->test_start < now()->addHours(3)){
            return back()->with('error','You cannot delete this question.');
        }
        $question->delete();
        return back()->with('success', 'Data deleted.');

    }

    public function uploadDropzone(Request $request,FileHelper $file)
    {
        $getFile=$request->file;
        if ( isset($getFile) && !empty($getFile) ) {
            // $folder = $request->type;
            $folder = $request->type;
            $fileName =$folder.'-'. Str::uuid(). '.' . $getFile->getClientOriginalExtension();
            Storage::disk(config('app.storage_driver'))->putFileAs(
                $folder, $getFile, $fileName
            );
        }
        else {
            return response()->json(['result' => 'failure']);
        }

        return response()->json(['sucess' => true, 'filename' => $fileName]);
    }

    public function storeImageUpload(Request $request)
    {
        $set=TestSet::findOrFail($request->set_id)->test()->first();
        
        DB::beginTransaction();
        try {
            $question=TestQuestion::create(array(
                'title' => $request->question,
                'test_set_id' => $request->set_id,
                'type' => 3,
                'marks' => $request->marks,
                'order' => $request->order,
                'status' => 1,
                'note'=>$request->note,

            ));
            foreach(explode(',',$request->attachments) as $attach){
                // array_push($arr,new Attachment(['body'=>$attach]));
                $question->attachments()->save(new Attachment(['body'=>$attach]));
            }

            DB::commit();
        }catch (Exception $e) {
            DB::rollback();
            
            // foreach(explode(',',$request->attachments) as $attach){
            //     $file->deleteFile('attachments',$attach);   
            // }
            return response()->json(['result' => 'failure']);
        }

        return response()->json(['result'=>'success']);

    }
    public function updateImageUpload(Request $request, $id)
    {
        $question=TestQuestion::findOrFail($id);
        
        $prevAttachments=$question->attachments()->pluck('body')->toArray();
        
        DB::beginTransaction();
        try{
            $question->update([
                'title'=>$request->question,
                'note'=>$request->note,
                'marks' => $request->marks,
                'order' => $request->order,
            ]);
            foreach(explode(',',$request->attachments) as $attach){
                if(!in_array($attach, $prevAttachments)){
                    $question->attachments()->save(new Attachment(['body'=>$attach]));
                }
            }

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            return back()->with('error','Server error. Please try again.');
        }
        return redirect('/test-sets/'.$question->test_set_id)->with('success','Data updated.');
    }
    public function storeUploadPdf(Request $request, FileHelper $file)
    {

        $set=TestSet::findOrFail($request->set_id)->test()->first();

        $fileName=null;
        DB::beginTransaction();
        try {
            $question=TestQuestion::create(array(
                'type'=>0,
                'test_set_id'=>$request->set_id,
                'status'=>1,
                
                // from here
                'title'=>$set->title, 
                'order'=>1,
                'marks'=>$set->full_marks,
                // to here

            ));
            $attachment=new Attachment(['body'=>$request->option_file_name]);
            $question->attachments()->save($attachment);

            DB::commit();
        }catch (Exception $e) {
            DB::rollback();
            $file->deleteFile('attachments',$fileName);   
            return response()->json(['result' => 'failure']);
        }

        return response()->json(['result'=>'success']);
    }

    public function updatePdf(Request $request, $id,FileHelper $file)
    {
        $question=TestQuestion::findOrFail($id);
        if(!$request->option_file_name){
            return redirect('/test-sets/'.$question->test_set_id)->with('success','No changes have been made.');
         
        }else{
            if($question->attachments()->first()){
                $file->deleteFile('attachments',$question->attachments()->first()->body);
                $question->attachments()->first()->update(['body'=>$request->option_file_name]);
            }
            return redirect('/test-sets/'.$question->test_set_id)->with('success','Data updated.');
        }
        
    }

    public function destroyOption(TestQuestionOption $option, FileHelper $file)
    {
        if ($option->type == 1) {
            $file->deleteFile('test_question_options', $option->title);
        }

        $option->delete();

        return response()->json(['result' => 'success']);
    }

    public function storeMultiChoice(Request $request)
    {
        $options = [];

        DB::beginTransaction();
        try {
            $question = TestQuestion::create([
                'title' => $request->question,
                'test_set_id' => $request->set_id,
                'type' => $request->type,
                'marks' => $request->marks,
                'order' => $request->order,
                'status' => 1,
            ]);
            
            if ($request->option_type == 'text') {
                if ($request->options) {
                    foreach ($request->options as $key => $value) {
                        $options['test_question_id'] = $question->id;
                        $options['type'] = 0;
                        $options['title'] = $value['option'];
                        
                        TestQuestionOption::create($options);
                    }
                }
            }
            else {
                if ($request->options) {
                    foreach ($request->options as $value) {
                        $options['test_question_id'] = $question->id;
                        $options['type'] = 1;
                        $options['title'] = $value['option_file_name'];

                        TestQuestionOption::create($options);
                    }
                }
            }
            
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
            
            return response()->json(['result' => 'failure', 'message' => $e->getMessage()]);
        }

        return response()->json(['result' => 'success']);
    }

    // paragraph
    public function storeParagraph(Request $request)
    {
        $question=TestQuestion::create(array(
            'title' => $request->question,
            'test_set_id' => $request->set_id,
            'type' => 4,
            'marks' => $request->marks,
            'order' => $request->order,
            'status' => 1,
            'note'=>$request->note,

        ));

        return response()->json(['result'=>'success']);

    }
    public function updateParagraph(Request $request, TestQuestion $question)
    {
        $question->update(array(
            'title' => $request->question,
            'marks' => $request->marks,
            'order' => $request->order,
            'status' => 1,
            'note'=>$request->note,

        ));
        return redirect('/test-sets/'.$question->test_set_id)->with('success','Data updated');

    }
    // text
    public function storeText(Request $request)
    {
        $question=TestQuestion::create(array(
            'title' => $request->question,
            'test_set_id' => $request->set_id,
            'type' => 5,
            'marks' => $request->marks,
            'order' => $request->order,
            'status' => 1,
        ));

        return response()->json(['result'=>'success']);

    }
    public function updateText(Request $request, TestQuestion $question)
    {
        $question->update(array(
            'title' => $request->question,
            'marks' => $request->marks,
            'order' => $request->order,
            'status' => 1,

        ));
        return redirect('/test-sets/'.$question->test_set_id)->with('success','Data updated');

    }

    // api
    public function hasOrder(Request $request)
    {
        $set=TestSet::whereId($request->set_id)->first();
        // dd($set->first());
        if($request->question_id){

            return response()->json(['status'=>in_array($request->order, $set->testQuestions()->where('id','<>',$request->question_id)->pluck('order')->toArray())]);
        }else{
            return response()->json(['status'=>in_array($request->order, $set->testQuestions()->pluck('order')->toArray())]);

        }
    }

    public function getMark(Request $request,$set)
    {
        $set=TestSet::whereId($set)->first();
        if($set){
            if($request->question_id){
                $totalMark=Test::whereId($set->test_id)->first()->full_marks;
                $allocatedMark=array_sum($set->testQuestions()->where('id','<>',$request->question_id)->pluck('marks')->toArray());
                return response()->json(['success'=>true, 'full'=>$totalMark,'remains'=>$totalMark-$allocatedMark]);
            }else{
                $totalMark=Test::whereId($set->test_id)->first()->full_marks;
                $allocatedMark=array_sum($set->testQuestions()->pluck('marks')->toArray());
                return response()->json(['success'=>true, 'full'=>$totalMark,'remains'=>$totalMark-$allocatedMark]);

            }
        }
        abort(403);
    }

    public function isMarkValid(Request $request)
    {
        $chk=$this->getMark($request,$request->set_id)->getData();
        
        if($chk){
            return response()->json(['status'=>$request->marks>$chk->remains]);
        }
        abort(403);
    }
}
