<?php

use App\SmAssignSubject;
use App\SmClass;
use App\SmExam;
use App\SmSection;
use App\SmStaff;
use App\SmSubject;
use App\SmExamType;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::group(['middleware' => ['XSS']], function () {
    // student panel
    Route::group(['middleware' => ['TeacherMiddleware']], function () {
        // Teacher custom dashboard by serumula business solutions
        Route::get('teacher-dashboard',  'teacher\SmTeacherPanelController@teacherProfile')->name('teacher-dashboard')->middleware('userRolePermission:1');
        Route::get('teacher-subjects', 'teacher\SmTeacherPanelController@teacherSubject');
        Route::post('find-students', 'teacher\SmTeacherPanelController@studentDetailsSearch');
        Route::get('teacher-online-exams', 'teacher\SmOnlineExaminationController@teacherOnlineExams')->name('teacher-online-exams')->middleware('userRolePermission:1');
        Route::get('exams-schedule','teacher\SmTeacherPanelController@teacherExamSchedule')->name('exams-schedule');
        Route::post('teacher-exam-schedule', 'teacher\SmTeacherPanelController@teacherExamScheduleSearch')->name('teacher-exam-schedule')->middleware('userRolePermission:1');
        Route::post('record-marks', 'teacher\SmTeacherPanelController@examSchedule');
    });
});