<?php

use App\Http\Controllers\Dashboard\MemberController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Dashboard', 'as' => 'dashboard.', 'prefix' => 'dashboard', 'middleware' => ['auth', '2fa', 'accessDashboard']], function () {
    Route::controller(MemberController::class)->group(function () {
        Route::get('members', 'index')->name('members.index');
        Route::post('members/enable', 'enable')->name('members.enable');
        Route::post('members/create', 'create')->name(name: 'members.create');
        Route::get('members/edit/{courseId}', 'edit')->name('members.edit');
        Route::get('members/content/{courseId}', 'content')->name('members.content');
        Route::get('members/module/{courseId}/add', 'addModule')->name('members.addModule');
        Route::post('members/module/create', 'createModule')->name('members.createModule');
        Route::get('courses/{courseId}/modules/{moduleId}/add-lesson', 'addLesson')->name('members.addLesson');
        Route::post('courses/add-lesson', 'createLesson')->name('members.createLesson');
        Route::get('courses/{courseId}/modules/{moduleId}/add-quiz', 'addQuiz')->name('members.addQuiz');
        Route::post('courses/add-quiz', 'createQuiz')->name('members.createQuiz');
        Route::get('members/students/{courseId}', 'students')->name('members.students');
        Route::get('members/classes/{courseId}', 'classes')->name('members.classes');
        Route::put('members/update/{courseId}', 'updateCourse')->name('members.update');
        Route::get('courses/{courseId}/modules/{moduleId}/edit', 'editModule')->name('members.editModule');
        Route::post('members/module/update/{moduleId}', 'updateModule')->name('members.updateModule');
        Route::get('courses/{courseId}/lesson/{lessonId}/edit', 'editLesson')->name('members.editLessonVideo');
        Route::post('members/lesson/update/{lessonId}', 'updateLesson')->name('members.updateLesson');
        Route::delete('members/lesson/complement/{complementId}', 'deleteLessonComplement')->name('members.deleteLessonComplement');
        Route::get('courses/{courseId}/lessonQuiz/{lessonId}/edit', 'editLessonQuiz')->name('members.editLessonQuiz');
        Route::post('members/lessonQuiz/update/{lessonId}', 'updateQuiz')->name('members.updateQuiz');
        Route::delete('members/lessonQuiz/delete/{quizId}', 'deleteQuiz')->name('members.deleteQuiz');
        Route::get('redirect/suitmembers', 'redirectMembers')->name('members.redirectMembers');
        Route::get('redirect/suitmembers/course/{courseId}', 'redirectMembersCourse')->name('members.redirectMembersCourse');
        Route::get('courses/{courseId}/settings', 'settingsCourse')->name('members.settingsCourse');
        Route::post('courses/{courseId}/add-domain', 'addDomainSuitMembers')->name('members.addDomain');
        Route::delete('courses/{courseId}/delete-domain', 'deleteDomainSuitMembers')->name('members.deleteDomain');
        Route::post('courses/{courseId}/add-student', 'addStudent')->name('members.addStudent');
        Route::post('courses/{courseId}/add-many-student', 'addManyStudents')->name('members.addManyStudents');
        Route::post('courses/{courseId}/add-comment-config', 'addConfigComment')->name('members.addConfigComment');
        Route::put('courses/{courseId}/changeStatus', 'changeMemberStatus')->name('members.changeMemberStatus');
        Route::put('courses/{courseId}/moderator', 'changeMemberModerator')->name('members.moderator');
        Route::post('courses/{courseId}/customization', 'customization')->name('members.customization');
        Route::delete('/modules/{moduleId}/delete', 'deleteModule')->name('members.deleteModule');
        Route::put('modules/{moduleId}/reactivate', 'reactivateModule')->name('members.reactivateModule');
        Route::delete('lessons/{lessonId}/delete', 'deleteLesson')->name('members.deleteLesson');
        Route::put('lessons/{lessonId}/reactivate', 'reactivateLesson')->name('members.reactivateLesson');
        Route::post('courses/{courseId}/lessons', 'createClass')->name('members.createClass');
        Route::put('courses/{courseId}/class/{classId}', 'updateClass')->name('members.updateClass');
        Route::get('members/access', 'checkCanAccessMembers')->name('members.checkAccess');
        Route::post('/members/content/{courseId}/add-track', 'createTrack')->name('members.createTrack');
        Route::put('/members/content/{courseId}/edit-track', 'editTrack')->name('members.editTrack');
        Route::get('/members/content/{trackId}/add-tracks-content', 'addTrackContent')->name('members.addTrackContent');
        Route::get('/members/content/{trackId}/add-course-track', 'addCourseTrack')->name('members.addCourseTrack');
        Route::post('/members/content/{trackId}/create-course-track', 'createCourseTrack')->name('members.createCourseTrack');
        Route::get('/members/edit/{courseId}/track', 'editCourseTrack')->name('members.editCourseTrack');
        Route::put('/members/update/{courseId}/track', 'updateCourseTrack')->name('members.updateCourseTrack');
        Route::get('/members/content/{courseId}/add-module-track', 'addModuleTrack')->name('members.addModuleTrack');
        Route::delete('/courses/{courseId}', 'deleteCourseTrack')->name('members.deleteCourseTrack');
        Route::put('/courses/{courseId}/activate', 'activateCourseTrack')->name('members.activateCourseTrack');
        Route::get('/members/classes/{courseId}/create', 'addClass')->name('members.addClass');
        Route::get('courses/{courseId}/class/{classId}', 'editClass')->name('members.editClass');
        Route::put('courses/class/{classId}/status', 'toggleStatusClass')->name('members.toggleClass');
        Route::get('productsRelatedAvailable/{courseId}', 'getProductsAvailableCreateRelation')->name('members.getProductsAvailableCreateRelation');
        Route::get('productsOffersCreateRelation/{productRef}', 'getOffersCreateRelation')->name('members.getProductsOffer');
        Route::post('courses/{courseId}/releation', 'createCourseRelation')->name('members.createCourseRelation');
        Route::put('releation/{courseRelationId}', 'updateCourseRelation')->name('members.updateCourseRelation');
        Route::delete('releation/{courseRelationId}', 'deleteCourseRelation')->name('members.deleteCourseRelation');
    });

    Route::controller(\App\Http\Controllers\Dashboard\CloudflareStreamController::class)->group(function () {
        Route::post('cloudflare-stream/upload-url', 'getUploadTusUrl')->name('cloudflare.getUploadTusUrl');
        Route::get('cloudflare-stream/video/{videoId}/details', 'getVideoDetails')->name('cloudflare.getVideoDetails');
        Route::delete('cloudflare-stream/video/{videoId}', 'deleteVideo')->name('cloudflare.deleteVideo');
    });
});
