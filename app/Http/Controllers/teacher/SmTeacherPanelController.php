<?php

namespace App\Http\Controllers\teacher;
use App\SmOptionalSubjectAssign;
use File;
use App\User;
use App\SmBook;
use App\SmExam;
use ZipArchive;
use App\SmClass;
use App\SmEvent;
use App\SmRoute;
use App\SmStaff;
use App\SmHoliday;
use App\SmSection;
use App\SmStudent;
use App\SmVehicle;
use App\SmWeekend;
use App\SmExamType;
use App\SmHomework;
use App\SmRoomList;
use App\SmRoomType;
use App\SmBaseSetup;
use App\SmBookIssue;
use App\SmClassTime;
use App\SmLeaveType;
use App\SmFeesAssign;
use App\SmMarksGrade;
use App\SmOnlineExam;
use App\ApiBaseMethod;
use App\SmLeaveDefine;
use App\SmNoticeBoard;
use App\SmAcademicYear;
use App\SmExamSchedule;
use App\SmLeaveRequest;
use App\SmNotification;
use App\SmStudentGroup;
use App\SmAssignSubject;
use App\SmAssignVehicle;
use App\SmDormitoryList;
use App\SmLibraryMember;
use App\SmGeneralSettings;
use App\SmStudentCategory;
use App\SmStudentDocument;
use App\SmStudentTimeline;
use App\SmStudentAttendance;
use Illuminate\Http\Request;
use App\SmFeesAssignDiscount;
use App\SmExamScheduleSubject;
use App\SmTeacherUploadContent;
use App\SmStudentTakeOnlineExam;
use App\SmUploadHomeworkContent;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Modules\RolePermission\Entities\InfixRole;

class SmTeacherPanelController extends Controller
{

    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function teacherProfile(){
       $attendances= array(56,89,78);
       $fees_assignments= array(56,89,78);
       $fees_discounts= array(56,89,78);
       $students = array(56,89,78);
       $timelines = array(56,89,78);
       $siblings = array(56,89,78);
       $grades = array(56,89,78);
       $events =  array(56,89,78);
      
       $documents = array(56,89,78);


        try {
            
            $user = Auth::user();
            $teacher_detail= SmStaff::where('user_id', $user->id)
                ->first();
            $totalSubjects = SmAssignSubject::where('teacher_id',  $teacher_detail->id)
                ->where('academic_id', getAcademicId())
                ->where('school_id',Auth::user()->school_id)
                ->get();
            $totalNotices = SmNoticeBoard::where('active_status', '=', 1)
                ->where('is_published', '=', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id',Auth::user()->school_id)
                ->get();
            $holidays = SmHoliday::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id',Auth::user()->school_id)
                ->get();
            $homeworkLists = SmHomework::where('created_by',  $teacher_detail->id)
                ->where('academic_id', getAcademicId())
                ->where('school_id',Auth::user()->school_id)
                ->get()
                ->count();




            $teacherClassSections = SmAssignSubject::where('teacher_id', $teacher_detail->id)->get();
            $online_exams = 0;
            $exams = 0;
            foreach ($teacherClassSections as $teacherClassSection){
                $online_exams += SmOnlineExam::where('active_status', 1)->where('status', 1)->where('class_id', $teacherClassSection->class_id)->where('section_id', $teacherClassSection->section_id)->where('subject_id', $teacherClassSection->subject_id)
                    // ->where('date', 'like', date('Y-m-d'))->where('start_time', '<', $now)->where('end_time', '>', $now)
                    ->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get()->count();

                $exams += SmExamSchedule::where('class_id', $teacherClassSection->class_id)->where('section_id', $teacherClassSection->section_id)->where('subject_id', $teacherClassSection->subject_id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get()->count();
            }


//                $aList = SmAssignSubject::where('teacher_id', $user->id)->get();
//                //TODO remove as its for testing only
//                foreach($aList as $smAssignSubject){
//                    $sub= $smAssignSubject->subject()->get();
//                    foreach($sub as $singleSubject){
//                        echo $singleSubject->subject_name;
//                        echo "\n";
//                    }
//                }
//                exit();

            return view('backEnd.teacherPanel.teacherDashboard', compact('totalSubjects', 'totalNotices', 'online_exams', 'students', 'homeworkLists', 'attendances', 'teacher_detail', 'fees_assignments', 'fees_discounts', 'exams', 'documents', 'timelines', 'siblings', 'grades', 'events', 'holidays'));

        } catch (\Exception $e) {
             Toastr::error('Operation Failed'.$e->getMessage(), 'Failed');
            //return redirect()->back();
        }
    }
    public function teacherSubject()
    {
        try {
            $user = Auth::user();
            $teacher_detail= SmStaff::where('user_id', $user->id)->first();
            session(['teacher_id'=>$teacher_detail->id]);
            $teacherAssignedSubjects = SmAssignSubject::where('teacher_id', $teacher_detail->id)->get();

            $teacherStudentsSubjects=[];
            $loop =0;
            foreach ($teacherAssignedSubjects as $teacherAssignedSubject){

                $teacherStudentsSubjects[$loop]['subject'] = $teacherAssignedSubject->subject()->first();
                $teacherStudentsSubjects[$loop]['class']   = $teacherAssignedSubject->className()->first();
                $teacherStudentsSubjects[$loop]['section'] = $teacherAssignedSubject->section()->first();

                $optional_subjects = SmOptionalSubjectAssign::where('class_id', $teacherAssignedSubject->className()->first()->id)->where('section_id', $teacherAssignedSubject->section()->first()->id)->where('subject_id',$teacherAssignedSubject->subject()->first()->id)->where('academic_id', getAcademicId())->get();

                if($optional_subjects->count()==0){
                    $studentCount = SmStudent::where('class_id', $teacherAssignedSubject->className()->first()->id)
                        ->where('section_id', $teacherAssignedSubject->section()->first()->id)
                        ->where('active_status',1)
                        ->where('school_id',Auth::user()->school_id)
                        ->get()
                        ->count();
                }
                else{
                    $optional_students=[];
                    foreach ($optional_subjects as $optional_subject){
                        $optional_students[]=$optional_subject->student_id;

                    }

                    $studentCount = SmStudent::where('class_id', $teacherAssignedSubject->className()->first()->id)
                        ->where('section_id', $teacherAssignedSubject->section()->first()->id)
                        ->wherein('id',$optional_students)
                        ->where('active_status',1)
                        ->where('school_id',Auth::user()->school_id)
                        ->get()->count();




                }
                $teacherStudentsSubjects[$loop]['count']= $studentCount;
                $teacherStudentsSubjects[$loop]['can-record-marks']= true; // TODO Complete it

                $loop++;
            }
             return view('backEnd.teacherPanel.teacher_subject', compact('teacherStudentsSubjects'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed'.$e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }
    public function studentDetailsSearch(Request $request, $subject, $section, $class){
        $optional_subjects = SmOptionalSubjectAssign::where('class_id', $class)->where('section_id', $section)->where('subject_id',$subject)->where('academic_id', getAcademicId())->get();
        if($optional_subjects->count()==0){
            $students = SmStudent::where('class_id', $class)->where('active_status',1)->where('section_id', $section)->where('academic_id', getAcademicId())->get();

        }
        else{
            $optional_students=[];
            foreach ($optional_subjects as $optional_subject){
                $optional_students[]=$optional_subject->student_id;

            }
            $students = SmStudent::where('class_id', $class)->where('active_status',1)->where('section_id', $section)->WhereIn('id',$optional_students)->where('academic_id', getAcademicId())->get();
        }

        $sessions = SmAcademicYear::where('school_id', Auth::user()->school_id)->get();
        return view('backEnd.teacherPanel.class_students', compact('students', 'sessions'));
    }

    public function teacherExamSchedule()
    {
        try {
            $exam_types = SmExamType::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
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
            return view('backEnd.teacherPanel.exam_schedule', compact('classes', 'exam_types'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function teacherExamScheduleSearch(Request $request)
    {
        $$request->validate([
            'exam' => 'required',
            'class' => 'required',
            'section' => 'required',
        ]);
        // $InputExamId = $request->exam;
        // $InputClassId = $request->class;
        // $InputSectionId = $request->section;

        try {
            $assign_subjects = SmAssignSubject::where('class_id', $request->class)->where('section_id', $request->section)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            if ($assign_subjects->count() == 0) {
                Toastr::error('No Subject Assigned. Please assign subjects in this class.', 'Failed');
                return redirect()->back();
                // return redirect('exam-schedule-create')->with('message-danger', 'No Subject Assigned. Please assign subjects in this class.');
            }

            $assign_subjects = SmAssignSubject::where('class_id', $request->class)->where('section_id', $request->section)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $class_id = $request->class;
            $section_id = $request->section;
            $exam_id = $request->exam;

            $exam_types = SmExamType::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $exam_periods = SmClassTime::where('type', 'exam')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $exam_schedules = SmExamSchedule::where('exam_term_id', $exam_id)->where('class_id', $request->class)->where('section_id', $request->section)->where('school_id',Auth::user()->school_id)->get();

            $exam_dates = [];
            foreach ($exam_schedules as $exam_schedule) {
                $exam_dates[] = $exam_schedule->date;
            }

            $exam_dates = array_unique($exam_dates);

            return view('backEnd.teacherPanel.current_exam_schedule', compact('classes', 'exams', 'assign_subjects', 'class_id', 'section_id', 'exam_id', 'exam_types', 'exam_periods', 'exam_dates'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }




    public function studentMyAttendanceSearchAPI(Request $request, $id = null)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'month' => "required",
            'year' => "required",
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $student_detail = SmStudent::where('user_id', $id)->first();

            $year = $request->year;
            $month = $request->month;
            if ($month < 10) {
                $month = '0' . $month;
            }
            $current_day = date('d');

            $days = cal_days_in_month(CAL_GREGORIAN, $month, $request->year);
            $days2 = '';
            if ($month != 1) {
                $days2 = cal_days_in_month(CAL_GREGORIAN, $month - 1, $request->year);
            } else {
                $days2 = cal_days_in_month(CAL_GREGORIAN, $month, $request->year);
            }
            // return  $days2;
            $previous_month = $month - 1;
            $previous_date = $year . '-' . $previous_month . '-' . $days2;
            $previousMonthDetails['date'] = $previous_date;
            $previousMonthDetails['day'] = $days2;
            $previousMonthDetails['week_name'] = date('D', strtotime($previous_date));
            $attendances = SmStudentAttendance::where('student_id', $student_detail->id)
                ->where('attendance_date', 'like', '%' . $request->year . '-' . $month . '%')
                ->select('attendance_type', 'attendance_date')
                ->where('school_id',Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data['attendances'] = $attendances;
                $data['previousMonthDetails'] = $previousMonthDetails;
                $data['days'] = $days;
                $data['year'] = $year;
                $data['month'] = $month;
                $data['current_day'] = $current_day;
                $data['status'] = 'Present: P, Late: L, Absent: A, Holiday: H, Half Day: F';
                return ApiBaseMethod::sendResponse($data, null);
            }
            //Test
            return view('backEnd.studentPanel.student_attendance', compact('attendances', 'days', 'year', 'month', 'current_day'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentMyAttendanceSearch(Request $request, $id = null)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'month' => "required",
            'year' => "required",
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $login_id = $id;
            } else {
                $login_id = Auth::user()->id;
            }
            $student_detail = SmStudent::where('user_id', $login_id)->first();

            $year = $request->year;
            $month = $request->month;
            $current_day = date('d');

            $days = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);

            $attendances = SmStudentAttendance::where('student_id', $student_detail->id)->where('academic_id', getAcademicId())->where('attendance_date', 'like', $request->year . '-' . $request->month . '%')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $academic_years = SmAcademicYear::where('active_status', '=', 1)->where('school_id',Auth::user()->school_id)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data['attendances'] = $attendances;
                $data['days'] = $days;
                $data['year'] = $year;
                $data['month'] = $month;
                $data['current_day'] = $current_day;
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.studentPanel.student_attendance', compact('attendances', 'days', 'year', 'month', 'current_day', 'student_detail', 'academic_years'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentMyAttendancePrint($month, $year)
    {
        try {
            $login_id = Auth::user()->id;
            $student_detail = SmStudent::where('user_id', $login_id)->first();
            $current_day = date('d');
            $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $attendances = SmStudentAttendance::where('student_id', $student_detail->id)->where('academic_id', getAcademicId())->where('attendance_date', 'like', $year . '-' . $month . '%')->where('school_id',Auth::user()->school_id)->get();
            $customPaper = array(0, 0, 700.00, 1000.80);
            $pdf = PDF::loadView(
                'backEnd.studentPanel.my_attendance_print',
                [
                    'attendances' => $attendances,
                    'days' => $days,
                    'year' => $year,
                    'month' => $month,
                    'current_day' => $current_day,
                    'student_detail' => $student_detail
                ]
            )->setPaper('A4', 'landscape');
            return $pdf->stream('my_attendance.pdf');
            //return view('backEnd.studentPanel.student_attendance', compact('attendances', 'days', 'year', 'month', 'current_day', 'student_detail'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentDashboard(Request $request, $id = null)
    {
        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $user_id = $id;
            } else {
                $user = Auth::user();

                if ($user) {
                    $user_id = $user->id;
                } else {
                    $user_id = $request->user_id;
                }
            }

            $student_detail = SmStudent::where('user_id', $user_id)->first();
           
            $driver = SmVehicle::where('sm_vehicles.id', '=', $student_detail->vechile_id)
                ->join('sm_staffs', 'sm_staffs.id', '=', 'sm_vehicles.driver_id')
                ->first();
            $siblings = SmStudent::where('parent_id', $student_detail->parent_id)->where('school_id',Auth::user()->school_id)->get();
            $fees_assigneds = SmFeesAssign::where('student_id', $student_detail->id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $fees_discounts = SmFeesAssignDiscount::where('student_id', $student_detail->id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $documents = SmStudentDocument::where('student_staff_id', $student_detail->id)->where('type', 'stu')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $timelines = SmStudentTimeline::where('staff_student_id', $student_detail->id)->where('type', 'stu')->where('visible_to_student', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $exams = SmExamSchedule::where('class_id', $student_detail->class_id)->where('section_id', $student_detail->section_id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $grades = SmMarksGrade::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $exam_terms = SmExamType::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            // return $exam_terms;


            $result_views = SmStudentTakeOnlineExam::
            where('active_status', 1)->where('status', 2)
            ->where('academic_id', getAcademicId())
            ->where('student_id', @Auth::user()->student->id)
            ->where('school_id', Auth::user()->school_id)
            ->get();
            $academic_year = SmAcademicYear::find($student_detail->session_id);
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['student_detail'] = $student_detail->toArray();
                $data['fees_assigneds'] = $fees_assigneds->toArray();
                $data['fees_discounts'] = $fees_discounts->toArray();
                $data['exams'] = $exams->toArray();
                $data['documents'] = $documents->toArray();
                $data['timelines'] = $timelines->toArray();
                $data['siblings'] = $siblings->toArray();
                $data['grades'] = $grades->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.studentPanel.my_profile', compact('driver', 'academic_year', 'student_detail', 'fees_assigneds', 'fees_discounts', 'exams', 'documents', 'timelines', 'siblings', 'grades', 'exam_terms','result_views'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    
    
    }

    public function studentUpdate(Request $request)
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $student_detail = SmStudent::find($request->id); 
        $request->validate(
            [ 
                'first_name' => 'required|max:100',
                'date_of_birth' => 'required',
                'document_file_1' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
                'document_file_2' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
                'document_file_3' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
                'document_file_4' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
            ]
        );

 

        // always happen start

        $document_file_1 = "";
        if ($request->file('document_file_1') != "") {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('document_file_1');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            if ($student_detail->document_file_1 != "") {
                if (file_exists($student_detail->document_file_1)) {
                    unlink($student_detail->document_file_1);
                }
            }
            $file = $request->file('document_file_1');
            $document_file_1 = 'doc1-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/student/document/', $document_file_1);
            $document_file_1 =  'public/uploads/student/document/' . $document_file_1;
        }

        $document_file_2 = "";
        if ($request->file('document_file_2') != "") {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('document_file_2');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            if ($student_detail->document_file_2 != "") {
                if (file_exists($student_detail->document_file_2)) {
                    unlink($student_detail->document_file_2);
                }
            }
            $file = $request->file('document_file_2');
            $document_file_2 = 'doc2-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/student/document/', $document_file_2);
            $document_file_2 =  'public/uploads/student/document/' . $document_file_2;
        }

        $document_file_3 = "";
        if ($request->file('document_file_3') != "") {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('sdocument_file_3');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            if ($student_detail->document_file_3 != "") {
                if (file_exists($student_detail->document_file_3)) {
                    unlink($student_detail->document_file_3);
                }
            }
            $file = $request->file('document_file_3');
            $document_file_3 = 'doc3-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/student/document/', $document_file_3);
            $document_file_3 =  'public/uploads/student/document/' . $document_file_3;
        }

        $document_file_4 = "";
        if ($request->file('document_file_4') != "") {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('document_file_4');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            if ($student_detail->document_file_4 != "") {
                if (file_exists($student_detail->document_file_4)) {
                    unlink($student_detail->document_file_4);
                }
            }
            $file = $request->file('document_file_4');
            $document_file_4 = 'doc4-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/student/document/', $document_file_4);
            $document_file_4 =  'public/uploads/student/document/' . $document_file_4;
        }

 


        $shcool_details = SmGeneralSettings::find(1);
        $school_name = explode(' ', $shcool_details->school_name);
        $short_form = '';

        foreach ($school_name as $value) {
            $ch = str_split($value);
            $short_form = $short_form . '' . $ch[0];
        }



        DB::beginTransaction(); 
        try {    
            $student = SmStudent::find($request->id);   
            $student->first_name = $request->first_name;
            $student->last_name = $request->last_name;
            $student->full_name = $request->first_name . ' ' . $request->last_name;
            $student->gender_id = $request->gender;
            $student->date_of_birth = date('Y-m-d', strtotime($request->date_of_birth)); 
            $student->age = $request->age; 
            $student->caste = $request->caste;
            $student->email = $request->email_address;
            $student->mobile = $request->phone_number;
            $student->admission_date = date('Y-m-d', strtotime($request->admission_date));

            // dd(Session::get('student_photo'));
            if (Session::get('student_photo') != "") {
                $student->student_photo = Session::get('student_photo');
            }

            if (@$request->blood_group != "") {
                $student->bloodgroup_id = $request->blood_group;
            }
            if (@$request->religion != "") {
                $student->religion_id = $request->religion;
            }

            $student->height = $request->height;
            $student->weight = $request->weight;
            $student->current_address = $request->current_address;
            $student->permanent_address = $request->permanent_address;
            $student->student_category_id = $request->student_category_id;
            $student->student_group_id = $request->student_group_id;
            

            $student->national_id_no = $request->national_id_number;
            $student->local_id_no = $request->local_id_number;
            $student->bank_account_no = $request->bank_account_number;
            $student->bank_name = $request->bank_name;
            $student->previous_school_details = $request->previous_school_details;
            $student->aditional_notes = $request->additional_notes;
            $student->ifsc_code = $request->ifsc_code;
            $student->document_title_1 = $request->document_title_1;

            if ($document_file_1 != "") {
                $student->document_file_1 =  $document_file_1;
            }

            $student->document_title_2 = $request->document_title_2;
            if ($document_file_2 != "") {
                $student->document_file_2 =  $document_file_2;
            }

            $student->document_title_3 = $request->document_title_3;
            if ($document_file_3 != "") {
                $student->document_file_3 = $document_file_3;
            }

            $student->document_title_4 = $request->document_title_4;

            if ($document_file_4 != "") {
                $student->document_file_4 = $document_file_4;
            }


            

            $student->save();
            DB::commit();


            // session null
            $update_stud = SmStudent::where('user_id', $student->user_id)->first('student_photo');
            // dd($update_stud);
            Session::put('profile', $update_stud->student_photo);
            Session::put('fathers_photo', '');
            Session::put('mothers_photo', '');
            Session::put('guardians_photo', '');



            Toastr::success('Operation successful', 'Success');
            return redirect('student-profile');
        } catch (\Exception $e) {
            return $e->getMessage();
            DB::rollback();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentProfileUpdate(Request $request, $id = null)
    {
        try {
            $student = SmStudent::find($id);
            


            $classes = SmClass::where('active_status', '=', '1')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            $religions = SmBaseSetup::where('active_status', '=', '1')->where('base_group_id', '=', '2')->get();
            $blood_groups = SmBaseSetup::where('active_status', '=', '1')->where('base_group_id', '=', '3')->get();
            $genders = SmBaseSetup::where('active_status', '=', '1')->where('base_group_id', '=', '1')->get();
            $route_lists = SmRoute::where('active_status', '=', '1')->where('school_id', Auth::user()->school_id)->get();
            $vehicles = SmVehicle::where('active_status', '=', '1')->where('school_id', Auth::user()->school_id)->get();
            $dormitory_lists = SmDormitoryList::where('active_status', '=', '1')->where('school_id', Auth::user()->school_id)->get();
            $driver_lists = SmStaff::where([['active_status', '=', '1'], ['role_id', 9]])->where('school_id', Auth::user()->school_id)->get();
            $categories = SmStudentCategory::where('school_id', Auth::user()->school_id)->get();
            $groups = SmStudentGroup::where('school_id', Auth::user()->school_id)->get();
            $sessions = SmAcademicYear::where('active_status', '=', '1')->where('school_id', Auth::user()->school_id)->get();
            $siblings = SmStudent::where('parent_id', $student->parent_id)->where('school_id', Auth::user()->school_id)->get();
           
            return view('backEnd.studentPanel.my_profile_update', compact('student', 'classes', 'religions', 'blood_groups', 'genders', 'route_lists', 'vehicles', 'dormitory_lists', 'categories','groups', 'sessions', 'siblings', 'driver_lists'));
        } catch (\Exception $e) {
            dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }
    public function studentProfile(Request $request, $id = null)
    {

        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $user_id = $id;
            } else {
                $user = Auth::user();

                if ($user) {
                    $user_id = $user->id;
                } else {
                    $user_id = $request->user_id;
                }
            }

            $student_detail = SmStudent::where('user_id', $user_id)->first();
            $driver = SmVehicle::where('sm_vehicles.id', '=', $student_detail->vechile_id)
                ->join('sm_staffs', 'sm_staffs.id', '=', 'sm_vehicles.driver_id')
                ->first();
            $siblings = SmStudent::where('parent_id', $student_detail->parent_id)->where('school_id',Auth::user()->school_id)->get();
            $fees_assigneds = SmFeesAssign::where('student_id', $student_detail->id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $fees_discounts = SmFeesAssignDiscount::where('student_id', $student_detail->id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $documents = SmStudentDocument::where('student_staff_id', $student_detail->id)->where('type', 'stu')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $timelines = SmStudentTimeline::where('staff_student_id', $student_detail->id)->where('type', 'stu')->where('visible_to_student', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $exams = SmExamSchedule::where('class_id', $student_detail->class_id)->where('section_id', $student_detail->section_id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $grades = SmMarksGrade::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $totalSubjects = SmAssignSubject::where('class_id', '=', $student_detail->class_id)->where('section_id', '=', $student_detail->section_id)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $totalNotices = SmNoticeBoard::where('active_status', '=', 1)->where('is_published', '=', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $time_zone_setup=SmGeneralSettings::join('sm_time_zones','sm_time_zones.id','=','sm_general_settings.time_zone_id')
            ->where('school_id',Auth::user()->school_id)->first();
            date_default_timezone_set($time_zone_setup->time_zone);


            $now = date('H:i:s');

          
            $online_exams = SmOnlineExam::where('active_status', 1)->where('status', 1
            )->where('class_id', $student_detail->class_id)->where('section_id', $student_detail->section_id)
            // ->where('date', 'like', date('Y-m-d'))->where('start_time', '<', $now)->where('end_time', '>', $now)
            ->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

           
            $teachers = SmAssignSubject::select('teacher_id')->where('class_id', $student_detail->class_id)
                ->where('section_id', $student_detail->section_id)->distinct('teacher_id')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $issueBooks = SmBookIssue::where('member_id', $student_detail->user_id)->where('issue_status', 'I')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $homeworkLists = SmHomework::where('class_id', $student_detail->class_id)
                ->where('section_id', $student_detail->section_id)
                ->where('evaluation_date', '=', null)
                ->where('submission_date', '>', $now)
                ->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $month = date('m');
            $year = date('Y');
            // return $year;

            $attendances = SmStudentAttendance::where('student_id', $student_detail->id)
                ->where('attendance_date', 'like', $year . '-' . $month . '%')
                ->where('attendance_type', '=', 'P')->where('school_id',Auth::user()->school_id)->get();
            // return $attendances;


            $holidays = SmHoliday::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();



            $events = SmEvent::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->where(function ($q) {
                    $q->where('for_whom', 'All')->orWhere('for_whom', 'Student');
                })
                ->get();


            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['student_detail'] = $student_detail->toArray();
                $data['fees_assigneds'] = $fees_assigneds->toArray();
                $data['fees_discounts'] = $fees_discounts->toArray();
                $data['exams'] = $exams->toArray();
                $data['documents'] = $documents->toArray();
                $data['timelines'] = $timelines->toArray();
                $data['siblings'] = $siblings->toArray();
                $data['grades'] = $grades->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.studentPanel.studentProfile', compact('totalSubjects', 'totalNotices', 'online_exams', 'teachers', 'issueBooks', 'homeworkLists', 'attendances', 'driver', 'student_detail', 'fees_assigneds', 'fees_discounts', 'exams', 'documents', 'timelines', 'siblings', 'grades', 'events', 'holidays'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentsDocumentApi(Request $request, $id)
    {
        try {
            $student_detail = SmStudent::where('user_id', $id)->first();
            $documents = SmStudentDocument::where('student_staff_id', $student_detail->id)->where('type', 'stu')
                ->select('title', 'file')
                ->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['student_detail'] = $student_detail->toArray();
                $data['documents'] = $documents->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function classRoutine(Request $request, $id = null)
    {
        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $user_id = $id;
            } else {
                $user = Auth::user();

                if ($user) {
                    $user_id = $user->id;
                } else {
                    $user_id = $request->user_id;
                }
            }

            $student_detail = SmStudent::where('user_id', $user_id)->first();
            $class_id = $student_detail->class_id;
            $section_id = $student_detail->section_id;

            $sm_weekends = SmWeekend::orderBy('order', 'ASC')->where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            $class_times = SmClassTime::where('type', 'class')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['student_detail'] = $student_detail->toArray();
                // $data['class_id'] = $class_id;
                // $data['section_id'] = $section_id;
                // $data['sm_weekends'] = $sm_weekends->toArray();
                // $data['class_times'] = $class_times->toArray();

                $weekenD = SmWeekend::where('academic_id', getAcademicId())->get();
                foreach ($weekenD as $row) {
                    $data[$row->name] = DB::table('sm_class_routine_updates')
                        ->select('sm_class_times.period', 'sm_class_times.start_time', 'sm_class_times.end_time', 'sm_subjects.subject_name', 'sm_class_rooms.room_no')
                        ->join('sm_classes', 'sm_classes.id', '=', 'sm_class_routine_updates.class_id')
                        ->join('sm_sections', 'sm_sections.id', '=', 'sm_class_routine_updates.section_id')
                        ->join('sm_class_times', 'sm_class_times.id', '=', 'sm_class_routine_updates.class_period_id')
                        ->join('sm_subjects', 'sm_subjects.id', '=', 'sm_class_routine_updates.subject_id')
                        ->join('sm_class_rooms', 'sm_class_rooms.id', '=', 'sm_class_routine_updates.room_id')

                        ->where([
                            ['sm_class_routine_updates.class_id', $class_id], ['sm_class_routine_updates.section_id', $section_id], ['sm_class_routine_updates.day', $row->id],
                        ])->where('sm_class_routine_updates.academic_id', getAcademicId())->where('sm_classesschool_id',Auth::user()->school_id)->get();
                }

                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.studentPanel.class_routine', compact('class_times', 'class_id', 'section_id', 'sm_weekends'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentResult()
    {
        try {
            $student_detail = Auth::user()->student;
            $exams = SmExamSchedule::where('class_id', $student_detail->class_id)->where('section_id', $student_detail->section_id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $grades = SmMarksGrade::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            //dd($exams);
            $exam_terms = SmExamType::where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->get();

            return view('backEnd.studentPanel.student_result', compact('student_detail', 'exams', 'grades','exam_terms'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    function examSchedule(Request $request){
        $request->validate([
            'exam' => 'required',
        ]);

        try {
            $exam_types = SmExamType::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
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
            return view('backEnd.teacherPanel.exam_schedule', compact('classes', 'exam_types'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }
}