<?php

use App\User;
use App\Mail\UserCreated;
use App\Events\ActionNotification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

Broadcast::routes();

Route::group(['middleware' => ['check_language']], function () {
    // frontend
    Route::get('/','Tenant\FrontendController@index');
    Route::post('/mail','Tenant\FrontendController@mail');

    Auth::routes();

    Route::get('/language/{locale}', function ($locale) {
        if (! in_array($locale, ['en', 'th'])) {
            abort(400);
        }
    
        session(['my_locale' => $locale]);
        
        return redirect()->back();
    });
});

Route::get('/api/email-exists','Auth\RegisterController@hasEmail');
Route::get('/api/citizen-number-exists','Auth\RegisterController@hasCitizenNumber');
Route::get('/api/symbol-number-exists','Auth\RegisterController@hasSymbolNumber');
Route::get('/api/get-section/{id}','Tenant\ClassroomController@getSection');
Route::get('/api/get-sections-and-courses/{classroom}','Tenant\ClassroomController@getSectionsAndCourses');
Route::get('/api/get-tecehers/{course}','Tenant\CourseController@getTeachers');
Route::get('/api/question/order-exists','Tenant\QuestionController@hasOrder');
Route::get('/api/question/mark-valid','Tenant\QuestionController@isMarkValid');
Route::get('/api/question/get-mark/{set}','Tenant\QuestionController@getMark');
Route::get('/api/options/{option}', 'Tenant\QuestionController@destroyOption');
Route::get('/api/sets/{set}', 'Tenant\Teacher\SetController@destroy');
Route::get('/api/get-questions/{id}', 'Tenant\Teacher\SetController@getQuestions');
Route::get('/api/students-list', 'Tenant\StudentController@studentListForIndex');

Route::get('/api/notifications', 'Tenant\NotificationController@notificationListForIndex');
Route::get('/api/sets/has-questions/{set}', 'Tenant\Teacher\SetController@hasQuestions');
Route::get('/api/getStudentsFromSection/{id}', 'Tenant\StudentController@getStudentsFromSection');

// test api
Route::get('/api/test-questions/order-exists','Tenant\Teacher\TestQuestionController@hasOrder');
Route::get('/api/test-questions/mark-valid','Tenant\Teacher\TestQuestionController@isMarkValid');
Route::get('/api/test-questions/get-mark/{set}','Tenant\Teacher\TestQuestionController@getMark');
Route::get('/api/tests/options/{option}', 'Tenant\Teacher\TestQuestionController@destroyOption');
Route::get('/api/tests/sets/{set}', 'Tenant\Teacher\TestSetController@destroy');
Route::get('/api/tests/get-questions/{id}', 'Tenant\Teacher\TestSetController@getQuestions');
Route::get('/api/test-sets/{id}', 'Tenant\Teacher\TestSetController@destroy');

Route::get('/api/test-sets/has-questions/{testset}', 'Tenant\Teacher\TestSetController@hasQuestions');

Route::post('/api/notification-read','Tenant\NotificationController@readNotification');

Route::group(['middleware' => ['auth', 'check_language']], function () {
    Route::view('/question', 'admin.question');


    Route::get('/app','Tenant\HomeController@home');
    Route::get('/change-password','Tenant\HomeController@changePassword');
    Route::patch('/change-password','Tenant\HomeController@updatePassword');
    Route::post('/is-password-matched','Tenant\HomeController@isPasswordMatched');

    Route::group(['middleware' => ['has_permission:CRUD administrators']], function () {
        Route::resource('/administrators', 'Tenant\AdministratorController')->middleware('has_role:Principal');
        Route::patch('/administrators/change-status-bulk/{status}','Tenant\AdministratorController@statusControlBulk');
        Route::patch('/administrators/change-status/{id}/{status}','Tenant\AdministratorController@statusControl');
    });
    Route::group(['middleware' => ['has_role:Principal']],function(){
        // admin backend setting
        Route::get('/settings/backend','Tenant\BackendSettingController@index');
        Route::post('/settings/backend/change-color','Tenant\BackendSettingController@changeColor');
        
        // admin frontend setting
        Route::get('/settings/frontend','Tenant\FrontendSettingController@index');
        Route::post('/settings/frontend/change-color','Tenant\FrontendSettingController@changeColor');
        Route::patch('/settings/frontend/about-us','Tenant\FrontendSettingController@changeAbout');

        Route::patch('/settings/frontend/socials','Tenant\FrontendSettingController@changeSocialLinks');
        Route::patch('/settings/frontend/contacts','Tenant\FrontendSettingController@changeContacts');
        
        Route::patch('/settings/frontend/maps','Tenant\FrontendSettingController@changeMap');
        
        Route::patch('/settings/frontend/change-mission','Tenant\FrontendSettingController@changeMission');
        Route::patch('/settings/frontend/change-vision','Tenant\FrontendSettingController@changeVision');
        Route::patch('/settings/frontend/change-goal','Tenant\FrontendSettingController@changeGoal');

        Route::patch('/settings/frontend/change-banner/{id}','Tenant\FrontendSettingController@changebanner');
        Route::patch('/settings/frontend/add-banner','Tenant\FrontendSettingController@addBanner');
        Route::delete('/settings/frontend/delete-banner/{id}','Tenant\FrontendSettingController@deleteBanner');

    });
    Route::group(['middleware' => ['has_permission:CRUD teachers']], function () {
        Route::get('/teachers/csv', 'Tenant\TeacherController@createBulk');
        Route::post('/teachers/csv', 'Tenant\TeacherController@storeBulk');
        Route::post('/teachers/csv-validation', 'Tenant\TeacherController@csvValidation');
        Route::resource('/teachers', 'Tenant\TeacherController')->except('show');
        Route::patch('/teachers/change-status-bulk/{status}','Tenant\TeacherController@statusControlBulk');
        Route::patch('/teachers/change-status/{id}/{status}','Tenant\TeacherController@statusControl');
    });
    Route::get('/teachers/{teacher}', 'Tenant\TeacherController@show');
    
    Route::group(['middleware' => ['has_permission:CRUD students']], function () {
        Route::get('/students/csv', 'Tenant\StudentController@createBulk');
        Route::post('/students/csv', 'Tenant\StudentController@storeBulk');
        Route::post('/students/csv-validation', 'Tenant\StudentController@csvValidation');
        Route::resource('/students', 'Tenant\StudentController');
        Route::patch('/students/change-status-bulk/{status}','Tenant\StudentController@statusControlBulk');
        Route::patch('/students/change-status/{id}/{status}','Tenant\StudentController@statusControl');

        Route::patch('/students/change-behavior/{id}','Tenant\StudentController@behaviorControl');
    });
    
    Route::resource('/messages', 'Tenant\MessageController')->middleware('has_permission:Test');
    
    Route::resource('/guardians', 'Tenant\GuardianController');

    Route::group(['middleware' => ['has_permission:CRUD courses']], function () {
        Route::resource('/courses', 'Tenant\CourseController');
        Route::get('/courses/assign-teacher/{id}','Tenant\CourseController@createAssignedTeacher');
        Route::patch('/courses/assign-teacher/{id}','Tenant\CourseController@updateAssignedTeacher');
        Route::patch('/courses/change-status/{id}/{status}','Tenant\CourseController@statusControl');
        Route::patch('/courses/change-status-bulk/{status}','Tenant\CourseController@statusControlBulk');
    });
    
    Route::group(['middleware' => ['has_permission:CRUD lessons']], function () {
        Route::get('/lessons/create/{id}','Tenant\LessonController@create');
        Route::get('/lessons/{id}/edit','Tenant\LessonController@edit');
        Route::post('/lessons','Tenant\LessonController@store');
        Route::patch('/lessons/{id}','Tenant\LessonController@update');
        Route::get('/lessons/{id}','Tenant\LessonController@show');
        Route::patch('/lessons/change-status/{id}/{status}','Tenant\LessonController@statusControl');
        Route::patch('/lessons/change-status-bulk/{status}','Tenant\LessonController@statusControlBulk');
    });

    // topics
    Route::group(['middleware' => ['has_permission:CRUD topics']], function () {
        Route::get('/topics/create/{id}','Tenant\TopicController@create');
        Route::get('/topics/{id}/edit','Tenant\TopicController@edit');
        Route::post('/topics','Tenant\TopicController@store');
        Route::patch('/topics/{id}','Tenant\TopicController@update');
        Route::get('/topics/{id}','Tenant\TopicController@show');
        Route::patch('/topics/change-status/{id}/{status}','Tenant\TopicController@statusControl');
        Route::patch('/topics/change-status-bulk/{status}','Tenant\TopicController@statusControlBulk');
        Route::patch('/topics/change-status-attach/{id}/{status}','Tenant\TopicController@statusControlAttach');
        Route::patch('/topics/change-status-bulk-attach/{status}','Tenant\TopicController@statusControlBulkAttach');

        Route::get('/topics/attachments/create/{id}','Tenant\TopicController@createAttach');
        Route::get('/topics/attachments/{id}/edit','Tenant\TopicController@editAttach');
        Route::post('/topics/attachments','Tenant\TopicController@storeAttach');
        Route::patch('/topics/attachments/{id}','Tenant\TopicController@updateAttach');

        
        Route::get('/topics/resources/create/{id}/{type}','Tenant\TopicResourceController@create');
        Route::get('/topics/resources/{id}/{type}/edit','Tenant\TopicResourceController@edit');
        
        Route::patch('/topics/resources/{id}/update','Tenant\TopicResourceController@update');
        Route::patch('/topics/resources/{id}','Tenant\TopicResourceController@store');
        Route::delete('/topics/resources/{id}','Tenant\TopicResourceController@destroy');

        Route::post('/topics/storeFile', 'Tenant\TopicController@storeFile');
        Route::get('/topics/removeFile/{folder}/{filename}', 'Tenant\TopicController@removeFile');
    });

    Route::group(['middleware' => ['has_permission:CRUD assigned courses']], function () {
        Route::get('/assigned-courses', 'Tenant\AssignedCourseController@index');
        Route::get('/assigned-courses/{course}/edit', 'Tenant\AssignedCourseController@edit');
        Route::patch('/assigned-courses/{course}', 'Tenant\AssignedCourseController@update');
        Route::get('/assigned-courses/{course}', 'Tenant\AssignedCourseController@show');
    });

    Route::group(['middleware' => ['has_permission:CRUD classrooms']], function () {
        Route::resource('/classrooms', 'Tenant\ClassroomController');
        Route::patch('/classrooms/change-status/{id}/{status}','Tenant\ClassroomController@statusControl');
        Route::patch('/classrooms/change-status-bulk/{status}','Tenant\ClassroomController@statusControlBulk');
        Route::get('/assign-class-teacher/{classroom}', 'Tenant\ClassroomController@assignClassTeacherPage');
        Route::post('/assign-class-teacher/{classroom}', 'Tenant\ClassroomController@assignClassTeacher');
    });

    Route::group(['middleware' => ['has_permission:CRUD sections']], function () {
        Route::resource('/sections', 'Tenant\SectionController')->only([
            'index', 'edit', 'update'
        ]);
        Route::patch('/sections/change-status/{id}/{status}','Tenant\SectionController@statusControl');
        Route::patch('/sections/change-status-bulk/{status}','Tenant\SectionController@statusControlBulk');
    });

    Route::group(['middleware' => ['tenant_plan:regular']], function () {
        
        // Schedules
        Route::group(['middleware' => ['has_permission:CRUD schedules']], function () {
            Route::resource('/schedules', 'Tenant\ScheduleController');
            Route::delete('/schedules/section/{id}','Tenant\ScheduleController@destroySection');
            Route::get('/get-sections/{classroom}','Tenant\ScheduleController@getSections');
            Route::get('/get-courses/{section}','Tenant\ScheduleController@getCourses');
            Route::get('/schedules/{section}/create','Tenant\ScheduleController@createSection');
            Route::post('/schedules/{section}/store','Tenant\ScheduleController@storeSection');
            Route::get('/validate-time/{start}/{end}/{day}/{section}','Tenant\ScheduleController@validateTime');    
        });

        // Exams
        Route::group(['middleware' => ['has_permission:CRUD exams']], function () {
            Route::resource('/exams', 'Tenant\ExamController');
            Route::patch('/exams/change-status/{id}/{status}','Tenant\ExamController@statusControl');
            Route::patch('/exams/{id}/change-result/{change}','Tenant\ExamController@changeResult');
        });

        // Sets
        Route::resource('/sets', 'Tenant\Teacher\SetController');

        // schedule for teacher and student (middleware pending)
        Route::get('/my-schedule','Tenant\AssignedScheduleController@index');

        Route::group(['middleware' => ['has_permission:CRUD exams teachers']], function () {
            // teacher exams

            Route::resource('/exam-teachers', 'Tenant\Teacher\ExamController');

            // Tests
            Route::get('/tests/{lesson}','Tenant\Teacher\TestController@index');
            Route::get('/tests/{lesson}/view','Tenant\Teacher\TestController@show');
            Route::get('/tests/create/{lesson}','Tenant\Teacher\TestController@create');
            Route::patch('/tests/change-status/{id}/{status}','Tenant\Teacher\TestController@statusControl');
            Route::patch('/tests/{id}/change-result/{change}','Tenant\Teacher\TestController@changeResult');
            Route::resource('tests', 'Tenant\Teacher\TestController')->except(['index','create','show']);

            // Test sets 
            Route::get('/api/get-test-questions/{id}', 'Tenant\Teacher\TestSetController@getQuestions');
            Route::get('/test-sets/{testset}','Tenant\Teacher\TestSetController@index');
            Route::delete('/test-sets/{testset}/delete','Tenant\Teacher\TestSetController@deleteSet');
            Route::resource('test-sets', 'Tenant\Teacher\TestSetController')->except(['index','create','show']);

            // Test Questions
            Route::get('/test-questions/{question}/edit', 'Tenant\Teacher\TestQuestionController@edit');
            Route::patch('/test-questions/{question}', 'Tenant\Teacher\TestQuestionController@update');
            Route::patch('/test-questions-multi/{question}', 'Tenant\Teacher\TestQuestionController@updateMultiChoice');
            Route::patch('/test-questions/{question}/pdf','Tenant\Teacher\TestQuestionController@updatePdf');
            
            Route::delete('/test-questions/{question}', 'Tenant\Teacher\TestQuestionController@destroy');
            Route::post('/test-questions/store-single-choice', 'Tenant\Teacher\TestQuestionController@storeSingleChoice');
            Route::post('/test-questions/store-multi-choice', 'Tenant\Teacher\TestQuestionController@storeMultiChoice');
            Route::post('/test-questions/store-upload-pdf', 'Tenant\Teacher\TestQuestionController@storeUploadPdf');
            Route::post('/test-questions/store-paragraph', 'Tenant\Teacher\TestQuestionController@storeParagraph');

            Route::post('/test-questions/store-text', 'Tenant\Teacher\TestQuestionController@storeText');

            Route::post('/test-questions/store-image-upload', 'Tenant\Teacher\TestQuestionController@storeImageUpload');

            Route::patch('/test-questions/update-image-upload/{question}', 'Tenant\Teacher\TestQuestionController@updateImageUpload');

            Route::patch('/test-questions/update-paragraph/{question}', 'Tenant\Teacher\TestQuestionController@updateParagraph');
            
            Route::patch('/test-questions/update-text/{question}', 'Tenant\Teacher\TestQuestionController@updateText');

            Route::post('/test-questions/upload-dropzone', 'Tenant\Teacher\TestQuestionController@uploadDropzone');
            Route::post('/test-questions/storeFile', 'Tenant\Teacher\TestQuestionController@storeFile');

            Route::get('/test-questions/removeFile/{folder}/{filename}', 'Tenant\Teacher\TestQuestionController@removeFile');

            Route::get('/test-questions/removeFileAndAttachment/{folder}/{filename}/{id}', 'Tenant\Teacher\TestQuestionController@removeFileAndAttachment');


            // TestSubmissions
            Route::get('/tests/submissions/{test}', 'Tenant\Teacher\TestSubmissionController@index');
            Route::get('/tests/submissions/{testsetuser}/show', 'Tenant\Teacher\TestSubmissionController@show');
            Route::get('/tests/submissions/{testsetuser}/edit', 'Tenant\Teacher\TestSubmissionController@edit');
            Route::post('/tests/marks-auto-save', 'Tenant\Teacher\TestSubmissionController@autoSave');
            Route::post('/tests/submission-finish/{testsetuser}', 'Tenant\Teacher\TestSubmissionController@finish');
            Route::get('/tests/download-answer/{testanswer}', 'Tenant\Teacher\TestSubmissionController@downloadAnswer');
            Route::get('/test-submissions/download/{testquestion}', 'Tenant\Teacher\TestSubmissionController@downloadQuestion');


            // Questions
            Route::get('/questions/{question}/edit', 'Tenant\QuestionController@edit');
            Route::patch('/questions/{question}', 'Tenant\QuestionController@update');
            Route::patch('/questions-multi/{question}', 'Tenant\QuestionController@updateMultiChoice');
            Route::patch('/questions/{question}/pdf','Tenant\QuestionController@updatePdf');
            
            Route::delete('/questions/{question}', 'Tenant\QuestionController@destroy');
            Route::post('/store-single-choice', 'Tenant\QuestionController@storeSingleChoice');
            Route::post('/store-multi-choice', 'Tenant\QuestionController@storeMultiChoice');
            Route::post('/store-upload-pdf', 'Tenant\QuestionController@storeUploadPdf');
            Route::post('/store-paragraph', 'Tenant\QuestionController@storeParagraph');

            Route::post('/store-text', 'Tenant\QuestionController@storeText');

            Route::post('/store-image-upload', 'Tenant\QuestionController@storeImageUpload');

            Route::patch('/update-image-upload/{question}', 'Tenant\QuestionController@updateImageUpload');

            Route::patch('/update-paragraph/{question}', 'Tenant\QuestionController@updateParagraph');
            
            Route::patch('/update-text/{question}', 'Tenant\QuestionController@updateText');

            Route::post('/upload-dropzone', 'Tenant\QuestionController@uploadDropzone');
            Route::post('/questions/storeFile', 'Tenant\QuestionController@storeFile');

            Route::get('/questions/removeFile/{folder}/{filename}', 'Tenant\QuestionController@removeFile');

            Route::get('/questions/removeFileAndAttachment/{folder}/{filename}/{id}', 'Tenant\QuestionController@removeFileAndAttachment');

            // Submissions
            Route::get('/submissions/{exam}', 'Tenant\Teacher\SubmissionController@index');
            Route::get('/submissions/{setuser}/show', 'Tenant\Teacher\SubmissionController@show');
            Route::get('/submissions/{setuser}/edit', 'Tenant\Teacher\SubmissionController@edit');
            Route::post('/marks-auto-save', 'Tenant\Teacher\SubmissionController@autoSave');
            Route::post('/submission-finish/{setuser}', 'Tenant\Teacher\SubmissionController@finish');
            Route::get('/download-answer/{answer}', 'Tenant\Teacher\SubmissionController@downloadAnswer');
            Route::get('/submissions/download/{question}', 'Tenant\Teacher\SubmissionController@downloadQuestion');
        });

        Route::group(['middleware' => ['has_permission:Student exams']], function () {
            Route::get('/exam-students', 'Tenant\Student\ExamController@index');
            Route::get('/exam-students/{exam}', 'Tenant\Student\ExamController@show');
            Route::get('/exam-students/{exam}/result', 'Tenant\Student\ExamController@showResult');
            Route::get('/exam-start/{exam}', 'Tenant\Student\ExamController@start');
            Route::get('/exam-start/download/{question}/{user}', 'Tenant\Student\ExamController@downloadQuestion');
            Route::post('/exam-finish', 'Tenant\Student\ExamController@finish');
            Route::post('/auto-save', 'Tenant\Student\ExamController@autoSave');

            Route::resource('results', 'Tenant\Student\ResultController');

            Route::get('/tests-results/{id}/index','Tenant\Student\TestResultController@index');
            Route::get('/tests-results/{id}','Tenant\Student\TestResultController@show');


            // test
            Route::get('/tests-students/{id}', 'Tenant\Student\TestController@index');
            Route::get('/tests-students/{test}/view', 'Tenant\Student\TestController@show');
            Route::get('/tests-students/{test}/result', 'Tenant\Student\TestController@showResult');
            Route::get('/test-start/{test}', 'Tenant\Student\TestController@start');
            Route::get('/test-start/download/{testquestion}/{user}', 'Tenant\Student\TestController@downloadQuestion');
            Route::post('/test-finish', 'Tenant\Student\TestController@finish');
            Route::post('/tests/auto-save', 'Tenant\Student\TestController@autoSave');

        });
    });

    Route::group(['middleware' => ['has_permission:Student assigned courses']], function () {
        Route::get('/student/assigned-courses', 'Tenant\StudentAssignedCourseController@index');
        Route::get('/student/assigned-courses/{course}', 'Tenant\StudentAssignedCourseController@show');
        Route::get('/student/download/{attachment}', 'Tenant\StudentAssignedCourseController@download');
        Route::get('/student/download-all/{topic}', 'Tenant\StudentAssignedCourseController@downloadAll');
        Route::get('/student/load-topic/{topic}', 'Tenant\StudentAssignedCourseController@getTopicDetails');

        Route::get('current-class', 'Tenant\StudentAssignedCourseController@currentClass');
    });
    
    Route::post('/storeFile', 'Tenant\HomeController@storeFile');

    // Feedbacks
    Route::resource('feedbacks', 'Tenant\FeedbackController');

    Route::group(['middleware' => ['tenant_plan:large']], function () {
        // Events
        Route::group(['middleware' => ['has_permission:CRUD events']], function () {
            Route::get('/events', 'Tenant\EventController@index');
            Route::post('/events', 'Tenant\EventController@store');
            Route::delete('/events/{event}', 'Tenant\EventController@destroy');
        });
        Route::get('/check-event/{date}', 'Tenant\EventController@checkEvent');
        Route::get('/calendar', 'Tenant\EventController@calendar');

        // Leave Appications
        Route::group(['middleware' => ['has_permission:Leave applications']], function () {
            Route::resource('applications', 'Tenant\LeaveController');
            Route::patch('/application-status/{application}', 'Tenant\LeaveController@statusChange');
        });

        Route::group(['middleware' => ['has_permission:Student applications']], function () {
            Route::resource('leave-applications', 'Tenant\Student\LeaveController');
        });

        // Behaviours
        Route::group(['middleware' => ['has_permission:CRUD behaviours']], function () {
            Route::post('/behaviours/update-marks','Tenant\BehaviourController@updateMark');
            Route::resource('/behaviours', 'Tenant\BehaviourController');
        });

        Route::group(['middleware' => ['has_permission:View behaviours']], function () {
            Route::get('/guardian/behaviours', 'Tenant\BehaviourController@guardianBehaviours');
        });

        Route::group(['middleware' => ['has_permission:CRUD behaviour types']], function () {
            Route::get('/behaviour-types/assign','Tenant\BehaviourTypeController@assign');
            Route::post('/behaviour-types/section/update','Tenant\BehaviourTypeController@sectionUpdate');
            Route::get('/behaviour-types/assign/{id}/edit','Tenant\BehaviourTypeController@assignEdit');
            Route::resource('behaviour-types', 'Tenant\BehaviourTypeController');
        });

        Route::get('/class-teacher/behaviour-types/assign','Tenant\ClassTeacherController@assignBehaviour');
        Route::post('/class-teacher/behaviour-types/section/update','Tenant\ClassTeacherController@sectionUpdate');
        Route::get('/class-teacher/behaviour-types/assign/{id}/edit','Tenant\ClassTeacherController@assignEdit');

        // Chat
        Route::get('/api/getEverything', 'Chat\MessageController@getEverything');
        Route::post('/api/messages', 'Chat\MessageController@store');
        Route::get('/chat', 'Chat\ChatController@index');
        Route::get('/api/load-messages/{groupId}', 'Chat\MessageController@loadMessages');
        Route::get('/api/groups/create/{id}', 'Chat\ChatController@create');

        // Homeworks START
            // teachers
            Route::resource('homeworks', 'Tenant\Teacher\HomeworkController');
            Route::get('/homeworks/{course}/get-sections','Tenant\Teacher\HomeworkController@getSections');
            Route::get('/homeworks/removeFile/{folder}/{filename}', 'Tenant\Teacher\HomeworkController@removeFile');
            Route::get('/homeworks/removeFileAndAttachment/{folder}/{filename}/{id}', 'Tenant\Teacher\HomeworkController@removeFileAndAttachment');
            Route::post('/homeworks/upload-dropzone', 'Tenant\Teacher\HomeworkController@uploadDropzone');
            // submission
            Route::get('homework-submissions/{id}', 'Tenant\Teacher\HomeworkSubmissionController@index');
            Route::get('/homework-submissions/download/{attachment}', 'Tenant\Teacher\HomeworkSubmissionController@download');
            Route::get('/homework-submissions/download-all/{homeworkUser}', 'Tenant\Teacher\HomeworkSubmissionController@downloadAll');

            Route::get('homework-submissions/{id}/show', 'Tenant\Teacher\HomeworkSubmissionController@show');
            Route::resource('homework-submissions', 'Tenant\Teacher\HomeworkSubmissionController')->except(['index','show']);


            // student
            Route::get('/my-homeworks/{id}/create', 'Tenant\Student\HomeworkController@create');
            Route::resource('my-homeworks', 'Tenant\Student\HomeworkController')->except(['create']);
            Route::get('/my-homeworks/download/{attachment}', 'Tenant\Student\HomeworkController@download');
            Route::get('/my-homeworks/download-all/{homework}', 'Tenant\Student\HomeworkController@downloadAll');
            Route::get('/my-homeworks/removeFile/{folder}/{filename}', 'Tenant\Student\HomeworkController@removeFile');
            Route::get('/my-homeworks/removeFileAndAttachment/{folder}/{filename}/{id}', 'Tenant\Student\HomeworkController@removeFileAndAttachment');
            Route::post('/my-homeworks/upload-dropzone', 'Tenant\Student\HomeworkController@uploadDropzone');
        // Homeworks END

        // Meetings START
            // teacher
            Route::resource('meetings', 'Tenant\Teacher\MeetingController');
            Route::get('/meetings/{course}/get-sections','Tenant\Teacher\MeetingController@getSections');
            Route::post('/meetings/upload','Tenant\Teacher\MeetingController@upload');
            Route::get('/meetings/{meeting}/saved-videos','Tenant\Teacher\MeetingController@showVideo');
            Route::delete('/meetings/{meetingVideo}/video','Tenant\Teacher\MeetingController@deleteVideo');

            // student
            Route::get('/my-meetings','Tenant\Student\MeetingController@index');
            Route::get('/my-meetings/{meeting}','Tenant\Student\MeetingController@show');
        // Meetings END

        // Notificaions
        Route::get('/notifications','Tenant\NotificationController@index');
    });
});
