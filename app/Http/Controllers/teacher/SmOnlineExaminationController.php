<?php


namespace App\Http\Controllers\teacher;


use App\SmAssignSubject;
use App\SmClass;
use App\SmGeneralSettings;
use App\SmOnlineExam;
use App\SmSection;
use App\SmStaff;
use App\SmSubject;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class SmOnlineExaminationController
{
    public function teacherOnlineExams()
    {
        $time_zone_setup=SmGeneralSettings::join('sm_time_zones','sm_time_zones.id','=','sm_general_settings.time_zone_id')
            ->where('school_id',Auth::user()->school_id)->first();
        date_default_timezone_set($time_zone_setup->time_zone);
        try{
            $online_exams = SmOnlineExam::where('status', '!=', 2)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            if (Auth::user()->role_id==1) {
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            } else {
                $teacher_info=SmStaff::where('user_id',Auth::user()->id)->first();
                $classes= SmAssignSubject::where('teacher_id',$teacher_info->id)->join('sm_classes','sm_classes.id','sm_assign_subjects.class_id')
                    ->where('sm_assign_subjects.academic_id', getAcademicId())
                    ->where('sm_assign_subjects.active_status', 1)
                    ->where('sm_assign_subjects.school_id',Auth::user()->school_id)
                    ->select('sm_classes.id','class_name')
                    ->get();
            }
            $sections = SmSection::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $present_date_time = date("Y-m-d H:i:s");
            $present_time = date("H:i:s");

            return view('backEnd.teacherPanel.online_exam', compact('online_exams', 'classes', 'sections', 'subjects', 'present_date_time','present_time'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}