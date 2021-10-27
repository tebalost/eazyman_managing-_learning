<?php

namespace App\Http\Controllers;

use AfricasTalking\SDK\AfricasTalking;
use App\SmAssignClassTeacher;
use App\SmClassTeacher;
use App\SmDesignation;
use App\SmExam;
use App\SmClass;
use App\SmPassDefinition;
use App\SmResultsConfiguration;
use App\SmSmsGateway;
use App\SmStaff;
use App\SmSection;
use App\SmStreamResult;
use App\SmStudent;
use App\SmSubject;
use App\YearCheck;
use App\SmExamType;
use App\SmSeatPlan;
use App\SmClassRoom;
use App\SmClassTime;
use App\SmExamSetup;
use App\SmMarkStore;
use App\SmMarksGrade;
use App\ApiBaseMethod;
use App\SmResultStore;
use App\SmAcademicYear;
use App\SmExamSchedule;
use App\SmAssignSubject;
use App\SmMarksRegister;
use App\SmSeatPlanChild;
use App\SmExamAttendance;
use App\SmGeneralSettings;
use App\SmStudentPromotion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\SmTemporaryMeritlist;
use App\SmExamAttendanceChild;
use App\SmExamScheduleSubject;
use App\SmClassOptionalSubject;
use App\SmOptionalSubjectAssign;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmExaminationController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }


    public function examSchedule()
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
            return view('backEnd.examination.exam_schedule', compact('classes','exam_types'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function resultsArchiveView()
    {
        try {
            $academic_years = SmAcademicYear::where('school_id', Auth::user()->school_id)->get();
            $exam_types = SmExamType::where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.examination.resultsArchiveView', compact('classes', 'exam_types', 'academic_years'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function previousClassResults()
    {
        try {
            return view('backEnd.reports.previousClassResults');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function previousClassResultsView($admission_no, Request $request)
    {
        $request->validate([
            'admission_number' => 'required',
        ]);
        try {
            $admission_number = $admission_no;
            $promotes = SmStudentPromotion::where('admission_number', '=', $admission_no)
                ->join('sm_academic_years', 'sm_academic_years.id', '=', 'sm_student_promotions.previous_session_id')
                ->join('sm_classes', 'sm_classes.id', '=', 'sm_student_promotions.previous_class_id')
                ->join('sm_students', 'sm_students.id', '=', 'sm_student_promotions.student_id')
                ->join('sm_sections', 'sm_sections.id', '=', 'sm_student_promotions.previous_section_id')
                // ->select('admission_number', 'student_id', 'previous_class_id', 'class_name', 'previous_section_id', 'section_name', 'year', 'previous_session_id')
                ->get();
            if ($promotes->count() < 1) {
                Toastr::error('Ops! Admission number is not found in previous academic year', 'Failed');
                return redirect()->back()->withInput();
                // return redirect()->back()->withInput()->with('message-danger', 'Ops! Admission number is not found in previous academic year. Please try again');
            }
            $studentDetails = SmStudentPromotion::where('admission_number', '=', $admission_no)
                ->join('sm_academic_years', 'sm_academic_years.id', '=', 'sm_student_promotions.previous_session_id')
                ->join('sm_classes', 'sm_classes.id', '=', 'sm_student_promotions.previous_class_id')
                ->join('sm_students', 'sm_students.id', '=', 'sm_student_promotions.student_id')
                ->join('sm_sections', 'sm_sections.id', '=', 'sm_student_promotions.previous_section_id')
                // ->select('admission_number', 'student_id', 'previous_class_id', 'class_name', 'previous_section_id', 'section_name', 'year', 'previous_session_id')
                ->first();
            //  return $promotes;

            $generalSetting = SmGeneralSettings::find(1);

            if ($promotes->count() > 0) {
                $student_id = $studentDetails->student_id;

                $current_class = SmStudent::where('sm_students.id', $student_id)->join('sm_classes', 'sm_classes.id', '=', 'sm_students.class_id')->first();
                $current_section = SmStudent::where('sm_students.id', $student_id)->join('sm_sections', 'sm_sections.id', '=', 'sm_students.section_id')->first();
                $current_session = SmStudent::where('sm_students.id', $student_id)->join('sm_academic_years', 'sm_academic_years.id', '=', 'sm_students.session_id')->first();

                return view('backEnd.reports.previousClassResults', compact('promotes', 'studentDetails', 'generalSetting', 'current_class', 'current_section', 'current_session', 'admission_number'));
            } else {
                Toastr::error('Ops! Your result is not found! Please check mark register', 'Failed');
                return redirect('previous-class-results');
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function previousClassResultsViewPost(Request $request)
    {
        $request->validate([
            'admission_number' => 'required',
        ]);
        try {
            $admission_number = $request->admission_number;
            $promotes = SmStudentPromotion::where('admission_number', '=', $request->admission_number)
                ->join('sm_academic_years', 'sm_academic_years.id', '=', 'sm_student_promotions.previous_session_id')
                ->join('sm_classes', 'sm_classes.id', '=', 'sm_student_promotions.previous_class_id')
                ->join('sm_students', 'sm_students.id', '=', 'sm_student_promotions.student_id')
                ->join('sm_sections', 'sm_sections.id', '=', 'sm_student_promotions.previous_section_id')
                ->get();
            if ($promotes->count() < 1) {
                Toastr::error('Ops! Admission number is not found in previous academic year', 'Failed');
                return redirect()->back()->withInput();
            }
            $studentDetails = SmStudentPromotion::where('admission_number', '=', $request->admission_number)
                ->join('sm_academic_years', 'sm_academic_years.id', '=', 'sm_student_promotions.previous_session_id')
                ->join('sm_classes', 'sm_classes.id', '=', 'sm_student_promotions.previous_class_id')
                ->join('sm_students', 'sm_students.id', '=', 'sm_student_promotions.student_id')
                ->join('sm_sections', 'sm_sections.id', '=', 'sm_student_promotions.previous_section_id')
                ->first();

            $generalSetting = SmGeneralSettings::find(1);

            if ($promotes->count() > 0) {
                $student_id = $studentDetails->student_id;

                $current_class = SmStudent::where('sm_students.id', $student_id)->join('sm_classes', 'sm_classes.id', '=', 'sm_students.class_id')->first();
                $current_section = SmStudent::where('sm_students.id', $student_id)->join('sm_sections', 'sm_sections.id', '=', 'sm_students.section_id')->first();
                $current_session = SmStudent::where('sm_students.id', $student_id)->join('sm_academic_years', 'sm_academic_years.id', '=', 'sm_students.session_id')->first();

                return view('backEnd.reports.previousClassResults', compact('promotes', 'studentDetails', 'generalSetting', 'current_class', 'current_section', 'current_session', 'admission_number'));
            } else {
                Toastr::error('Ops! Your result is not found! Please check mark register', 'Failed');
                return redirect('previous-class-results');
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function previousClassResultsViewPrint(Request $request, $admission_number)
    {
        try {
            // return $request;
            $promotes = SmStudentPromotion::where('admission_number', '=', $admission_number)
                ->join('sm_academic_years', 'sm_academic_years.id', '=', 'sm_student_promotions.previous_session_id')
                ->join('sm_classes', 'sm_classes.id', '=', 'sm_student_promotions.previous_class_id')
                ->join('sm_students', 'sm_students.id', '=', 'sm_student_promotions.student_id')
                ->join('sm_sections', 'sm_sections.id', '=', 'sm_student_promotions.previous_section_id')
                // ->select('admission_number', 'student_id', 'previous_class_id', 'class_name', 'previous_section_id', 'section_name', 'year', 'previous_session_id')
                ->get();
            $studentDetails = SmStudentPromotion::where('admission_number', '=', $admission_number)
                ->join('sm_academic_years', 'sm_academic_years.id', '=', 'sm_student_promotions.previous_session_id')
                ->join('sm_classes', 'sm_classes.id', '=', 'sm_student_promotions.previous_class_id')
                ->join('sm_students', 'sm_students.id', '=', 'sm_student_promotions.student_id')
                ->join('sm_sections', 'sm_sections.id', '=', 'sm_student_promotions.previous_section_id')
                // ->select('admission_number', 'student_id', 'previous_class_id', 'class_name', 'previous_section_id', 'section_name', 'year', 'previous_session_id')
                ->first();
            $student_id = $studentDetails->student_id;

            $current_class = SmStudent::where('sm_students.id', $student_id)->join('sm_classes', 'sm_classes.id', '=', 'sm_students.class_id')->first();
            $current_section = SmStudent::where('sm_students.id', $student_id)->join('sm_sections', 'sm_sections.id', '=', 'sm_students.section_id')->first();
            $current_session = SmStudent::where('sm_students.id', $student_id)->join('sm_academic_years', 'sm_academic_years.id', '=', 'sm_students.session_id')->first();


            $generalSetting = SmGeneralSettings::find(1);

            if ($promotes->count() > 0) {

                return view('backEnd.reports.student_archive_print', compact('promotes', 'studentDetails', 'generalSetting', 'current_class', 'current_section', 'current_session'));
            } else {
                Toastr::error('Ops! Your result is not found! Please check mark register', 'Failed');
                return redirect('session-student');
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function resultsArchiveSearch(Request $request)
    {
        $request->validate([
            'exam' => 'required',
            'class' => 'required',
            'section' => 'required'
        ]);
    }

    public function passDefinition()
    {
        try {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $pass_definition = SmPassDefinition::where('school_id', Auth::user()->school_id)->where('id',0)->first();
            $pass_definitions = SmPassDefinition::where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.examination.pass_definition', compact('classes', 'subjects', 'pass_definition','pass_definitions'));
        } catch (\Exception $e) {
            dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function passDefinitionUpdate(Request $request, $id)
    {
        try {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $pass_definition = SmPassDefinition::where('school_id', Auth::user()->school_id)->where('id',$id)->first();
            $pass_definitions = SmPassDefinition::where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.examination.pass_definition', compact('classes', 'subjects', 'pass_definition','pass_definitions'));
        } catch (\Exception $e) {
            dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function passDefinitionStore(Request $request)
    {

        try {
            $session = SmGeneralSettings::where('school_id',Auth::user()->school_id)->first();
            $is_existing_data = SmPassDefinition::where('id', $request->id)->first();
                if (empty($is_existing_data)) {
                    $insert_pass_definition = new SmPassDefinition();
                } else {
                    $insert_pass_definition = SmPassDefinition::find($is_existing_data->id);
                }
            $insert_pass_definition->grade_table = $request->grade_table;
            $insert_pass_definition->pass_average = $request->pass_average;
            $insert_pass_definition->number_of_subjects = $request->number_of_subjects;
            $insert_pass_definition->course_work_percent = $request->course_work_percent;
            $insert_pass_definition->created_at = YearCheck::getYear() . '-' . date('m-d H:i:s');
            $insert_pass_definition->school_id = Auth::user()->school_id;
            $insert_pass_definition->exam_percent = $request->exam_percent;
            $insert_pass_definition->student_conduct = $request->student_conduct;
            $insert_pass_definition->coursework_type = $request->coursework_type;
            $insert_pass_definition->subject_average = $request->subject_average;
            $insert_pass_definition->subject_rank = $request->subject_rank;
            $insert_pass_definition->language_translation = $request->language_translation;
            $insert_pass_definition->compulsory_subjects = json_encode($request->compulsory_subjects);
            $insert_pass_definition->compulsory_subjects_pass_mark = $request->compulsory_subjects_pass_mark;
            $insert_pass_definition->other_core_subjects = json_encode($request->other_core_subjects);
            $insert_pass_definition->other_core_subjects_pass_mark = $request->other_core_subjects_pass_mark;
            $insert_pass_definition->streams = json_encode($request->streams);
            $insert_pass_definition->academic_id = $session->session_id;
            $insert_pass_definition->pass_mark = $request->pass_mark;
            $insert_pass_definition->save();


            if ($insert_pass_definition) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function passDefinitionDestroy(Request $request, $id)
    {

        try{
            $marks_grade = SmPassDefinition::destroy($id);

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($marks_grade) {
                    return ApiBaseMethod::sendResponse(null, 'Grade has been deleted successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($marks_grade) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('pass-definition');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examScheduleCreate()
    {
        try {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $sections = SmSection::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $exams = SmExam::where('school_id', Auth::user()->school_id)->get();
            $exam_types = SmExamType::where('school_id', Auth::user()->school_id)->get();

            return view('backEnd.examination.exam_schedule_create', compact('classes', 'exams', 'exam_types'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examScheduleSearch(Request $request)
    {
        $request->validate([
            'exam' => 'required',
            'class' => 'required',
            'section' => 'required'
        ]);

        try {
            $assign_subjects = SmAssignSubject::where('class_id', $request->class)->where('section_id', $request->section)->where('academic_id', getAcademicId())->groupBy('subject_id')->get();

            if ($assign_subjects->count() == 0) {
                Toastr::error('No Subject Assigned. Please assign subjects in this class', 'Failed');
                return redirect('exam-schedule-create');
            }


            $assign_subjects = SmAssignSubject::where('class_id', $request->class)->where('section_id', $request->section)->where('academic_id', getAcademicId())->groupBy('subject_id')->get();


            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $class_id = $request->class;
            $section_id = $request->section;
            $exam_id = $request->exam;

            $exam_types = SmExamType::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $exam_periods = SmClassTime::where('type', 'exam')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            return view('backEnd.examination.exam_schedule_create', compact('classes', 'exams', 'assign_subjects', 'class_id', 'section_id', 'exam_id', 'exam_types', 'exam_periods'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function examScheduleStore(Request $request)
    {

        $update_check = SmExamSchedule::where('exam_id', $request->exam_id)->where('class_id', $request->class_id)->where('section_id', $request->section_id)->first();

        DB::beginTransaction();

        try {
            if ($update_check == "") {
                $exam_schedule = new SmExamSchedule();
            } else {
                $exam_schedule = $update_check = SmExamSchedule::where('exam_id', $request->exam_id)->where('class_id', $request->class_id)->where('section_id', $request->section_id)->first();
            }


            $exam_schedule->class_id = $request->class_id;
            $exam_schedule->section_id = $request->section_id;
            $exam_schedule->exam_id = $request->exam_id;
            $exam_schedule->school_id = Auth::user()->school_id;
            $exam_schedule->academic_id = getAcademicId();
            $exam_schedule->save();
            $exam_schedule->toArray();

            $counter = 0;

            if ($update_check != "") {
                SmExamScheduleSubject::where('exam_schedule_id', $exam_schedule->id)->delete();
            }

            foreach ($request->subjects as $subject) {
                $counter++;
                $date = 'date_' . $counter;
                $start_time = 'start_time_' . $counter;
                $end_time = 'end_time_' . $counter;
                $room = 'room_' . $counter;
                $full_mark = 'full_mark_' . $counter;
                $pass_mark = 'pass_mark_' . $counter;

                $exam_schedule_subject = new SmExamScheduleSubject();
                $exam_schedule_subject->exam_schedule_id = $exam_schedule->id;
                $exam_schedule_subject->subject_id = $subject;
                $exam_schedule_subject->date = date('Y-m-d', strtotime($request->$date));
                $exam_schedule_subject->start_time = $request->$start_time;
                $exam_schedule_subject->end_time = $request->$end_time;
                $exam_schedule_subject->room = $request->$room;
                $exam_schedule_subject->full_mark = $request->$full_mark;
                $exam_schedule_subject->pass_mark = $request->$pass_mark;
                $exam_schedule_subject->school_id = Auth::user()->school_id;
                $exam_schedule_subject->academic_id = getAcademicId();
                $exam_schedule_subject->save();
            }


            DB::commit();
            Toastr::success('Operation successful', 'Success');
            return redirect('exam-schedule');
        } catch (\Exception $e) {
            DB::rollBack();
        }
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }


    public function viewExamSchedule($class_id, $section_id, $exam_id)
    {
        try {
            $class = SmClass::find($class_id);
            $section = SmSection::find($section_id);
            $assign_subjects = SmExamScheduleSubject::where('exam_schedule_id', $exam_id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.examination.view_exam_schedule_modal', compact('class', 'section', 'assign_subjects'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function viewExamStatus($exam_id)
    {
        try {
            $exam = SmExam::find($exam_id);
            $view_exams = SmExamSchedule::where('exam_id', $exam_id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.examination.view_exam_status', compact('exam', 'view_exams'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    // Mark Register View Page
    public function marksRegister()
    {
        try {

            $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $exam_types = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.examination.marks_register', compact('exams', 'classes', 'exam_types'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function marksRegisterCreate()
    {
        try {
            $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $exam_types = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            if (Auth::user()->role_id == 1) {
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            } else {
                $teacher_info = SmStaff::where('user_id', Auth::user()->id)->first();
                $classes = SmAssignSubject::where('teacher_id', $teacher_info->id)->join('sm_classes', 'sm_classes.id', 'sm_assign_subjects.class_id')
                    ->where('sm_assign_subjects.academic_id', getAcademicId())
                    ->where('sm_assign_subjects.active_status', 1)
                    ->where('sm_assign_subjects.school_id', Auth::user()->school_id)
                    ->select('sm_classes.id', 'class_name')
                    ->groupBy('sm_classes.id')
                    ->get();
            }
            $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.examination.marks_register_create', compact('exams', 'classes', 'subjects', 'exam_types'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    //show exam type method from sm_exams_types table
    public function exam_type()
    {
        try {
            $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $exams_types = SmExamType::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.examination.exam_type', compact('exams', 'classes', 'exams_types'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    //edit exam type method from sm_exams_types table
    public function exam_type_edit($id)
    {
        try {
            if (checkAdmin()) {
                $exam_type_edit = SmExamType::find($id);
            } else {
                $exam_type_edit = SmExamType::where('id', $id)->where('school_id', Auth::user()->school_id)->first();
            }
            $exams_types = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.examination.exam_type', compact('exam_type_edit', 'exams_types'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    //update exam type method from sm_exams_types table
    public function exam_type_update(Request $request)
    {
        $request->validate([
            'exam_type_title' => 'required|max:50',
            'active_status' => 'required'
        ]);
        // school wise uquine validation
        /*echo $request->exam_type_title."<br>";
        echo $request->quarter."<br>";
        echo $request->is_exam."<br>";
        exit;*/
        $is_duplicate = SmExamType::where('school_id', Auth::user()->school_id)->where('title', $request->exam_type_title)->where('id', '!=', $request->id)->where('quarter', $request->quarter)->where('is_examination', $request->is_exam)->first();
        if ($is_duplicate) {
            Toastr::error('Duplicate name found!', 'Failed');
            return redirect()->back()->withInput();
        }
        DB::beginTransaction();
        try {
            if (checkAdmin()) {
                $update_exame_type = SmExamType::find($request->id);
            } else {
                $update_exame_type = SmExamType::where('id', $request->id)->where('school_id', Auth::user()->school_id)->first();
            }
            $update_exame_type->title = $request->exam_type_title;
            $update_exame_type->quarter = $request->quarter;
            $update_exame_type->is_examination = $request->is_examination;
            $update_exame_type->active_status = $request->active_status;
            $update_exame_type->save();
            $update_exame_type->toArray();

            DB::commit();
            Toastr::success('Operation successful', 'Success');
            return redirect('exam-type');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    //store exam type method from sm_exams_types table
    public function exam_type_store(Request $request)
    {

        $request->validate([
            'exam_type_title' => 'required|max:50'
        ]);
        // school wise uquine validation
        $is_duplicate = SmExamType::where('school_id', Auth::user()->school_id)->where('title', $request->exam_type_title)->where('quarter', $request->quarter)->where('is_examination', $request->is_examination)->first();

        if ($is_duplicate) {
            Toastr::error('Duplicate name found!', 'Failed');
            return redirect()->back()->withInput();
        }
        try {
            $update_exame_type = new SmExamType();
            $update_exame_type->title = $request->exam_type_title;
            $update_exame_type->quarter = $request->quarter;
            $update_exame_type->is_examination = $request->is_examination;
            $update_exame_type->active_status = 1;    //1 for status active & 0 for inactive
            $update_exame_type->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
            $update_exame_type->school_id = Auth::user()->school_id;
            $update_exame_type->academic_id = getAcademicId();

            $result = $update_exame_type->save();

            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect('exam-type');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    //delete exam type method from sm_exams_types table
    public function exam_type_delete(Request $request, $id)
    {
        ;
        try {
            $id_key = 'exam_type_id';
            $term_key = 'exam_term_id';

            $type = \App\tableList::getTableList($id_key, $id);

            $term = \App\tableList::getTableList($term_key, $id);

            $tables = $type . '' . $term;
            // dd($tables);
            // $delete_query = SmExamType::destroy($id);
            // return $tables;
            // if ($tables==null || $tables=='') {
            //     dd('null');
            // }else{
            //     dd('bal');
            // }
            try {


                if ($tables == null || $tables == '') {
                    if (checkAdmin()) {

                        $delete_query = SmExamType::destroy($id);
                    } else {
                        $data = SmExamType::where('id', $id)->where('school_id', Auth::user()->school_id)->first();
                        $delete_query = $data->delete();

                    }
                    if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                        if ($delete_query) {
                            return ApiBaseMethod::sendResponse(null, 'Exam Type has been deleted successfully');
                        } else {
                            return ApiBaseMethod::sendError('Something went wrong, please try again.');
                        }
                    } else {
                        if ($delete_query) {
                            Toastr::success('Operation successful', 'Success');
                            return redirect()->back();
                        } else {
                            Toastr::error('Operation Failed', 'Failed');
                            return redirect()->back();
                        }
                    }
                } else {
                    // return $tables;
                    $msg = 'This data already used in   : ' . $tables . ' Please remove those data first';
                    Toastr::error($msg, 'Failed');
                    return redirect()->back();
                }
            } catch (\Illuminate\Database\QueryException $e) {
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            //dd($e->getMessage(), $e->errorInfo);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function marksRegisterSearch(Request $request)
    {

        $request->validate([
            'exam' => 'required',
            'class' => 'required',
            'section' => 'required',
            'subject' => 'required'
        ]);
        try {
            $exam_attendance = SmExamAttendance::where('class_id', $request->class)->where('section_id', $request->section)->where('exam_id', $request->exam)->where('subject_id', $request->subject)->first();

            if ($exam_attendance == "") {

                Toastr::error('Exam Attendance not taken yet, please check exam attendance', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-danger', 'Exam Attendance not taken yet, please check exam attendance');
            }
            $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $exam_types = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $exam_id = $request->exam;
            $class_id = $request->class;
            $section_id = $request->section;
            $subject_id = $request->subject;
            $subjectNames = SmSubject::where('id', $subject_id)->first();

            $optional_subjects = SmOptionalSubjectAssign::where('class_id', $request->class)->where('section_id', $request->section)->where('subject_id',$request->subject)->get();
            if($optional_subjects->count()==0){
            $students = SmStudent::where('active_status', 1)->where('class_id', $request->class)->where('section_id', $request->section)->where('academic_id', getAcademicId())->orderBy('last_name', 'asc')->get();
            }
            else{
                $optional_students=[];
                foreach ($optional_subjects as $optional_subject){
                    $optional_students[]=$optional_subject->student_id;
                }
                $students = SmStudent::where('active_status', 1)
                    ->where('class_id', $request->class)
                    ->where('section_id', $request->section)
                    ->whereIn('id', $optional_students)
                    ->where('academic_id', getAcademicId())->orderBy('last_name', 'asc')->get();

            }

            //$exam_schedule = SmExamSchedule::where('exam_id', $request->exam)->where('class_id', $request->class)->where('section_id', $request->section)->where('academic_id', getAcademicId())->first();

            if ($students->count() < 1) {
                Toastr::error('Student is not found in according this class and section!', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-danger', 'Student is not found in according this class and section! Please add student in this section of that class.');
            } else {
                $marks_entry_form = SmExamSetup::where(
                    [
                        ['exam_term_id', $exam_id],
                        ['class_id', $class_id],
                        ['section_id', $section_id],
                        ['subject_id', $subject_id]
                    ]
                )->where('academic_id', getAcademicId())->get();

                if ($marks_entry_form->count() > 0) {
                    $number_of_exam_parts = count($marks_entry_form);
                    // return $students;
                    return view('backEnd.examination.marks_register_create', compact('exams', 'classes', 'students', 'exam_id', 'class_id', 'section_id', 'subject_id', 'subjectNames', 'number_of_exam_parts', 'marks_entry_form', 'exam_types'));
                } else {
                    Toastr::error('No result found or exam setup is not done!', 'Failed');
                    return redirect()->back();
                    // return redirect()->back()->with('message-danger', 'No result found or exam setup is not done!');
                }
                return view('backEnd.examination.marks_register_create', compact('exams', 'classes', 'students', 'exam_id', 'class_id', 'section_id', 'marks_register_subjects', 'assign_subject_ids'));
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function marksRegisterStore(Request $request)
    {
        // dd(($request));
        DB::beginTransaction();
        try {

            $abc = [];

            $class_id = $request->class_id;
            $section_id = $request->section_id;
            $subject_id = $request->subject_id;
            $exam_id = $request->exam_id;

            $counter = 0;           // Initilize by 0

            foreach ($request->student_ids as $student_id) {
                $sid = $student_id;
                $admission_no = ($request->student_admissions[$sid] == null) ? '' : $request->student_admissions[$sid];
                $roll_no = ($request->student_rolls[$sid] == null) ? '' : $request->student_rolls[$sid];

                if (!empty($request->marks[$sid])) {
                    $exam_setup_count = 0;
                    $total_marks_persubject = 0;
                    $subject_total_mark = $request->part_marks;


                    foreach ($request->marks[$sid] as $part_mark) {
                        $mark_by_exam_part = ($part_mark == null) ? 0 : $part_mark;          // 0=If exam part is empty
                        $total_marks_persubject = round(($total_marks_persubject + $mark_by_exam_part) / $subject_total_mark * 100, 0);


                        // $is_absent = ($request->abs[$sid]==null) ? 0 : 1;
                        $exam_setup_id = $request->exam_Sids[$sid][$exam_setup_count];

                        $previous_record = SmMarkStore::where([
                            ['class_id', $class_id],
                            ['section_id', $section_id],
                            ['subject_id', $subject_id],
                            ['exam_term_id', $exam_id],
                            ['exam_setup_id', $exam_setup_id],
                            ['student_id', $sid]
                        ])->where('academic_id', getAcademicId())->first();
                        // Is previous record exist ?
                        $mark_grade = SmMarksGrade::where([['percent_from', '<=', $total_marks_persubject], ['percent_upto', '>=', $total_marks_persubject]])->where('school_id', Auth::user()->school_id)->first();
                        if ($previous_record == "" || $previous_record == null) {

                            $marks_register = new SmMarkStore();

                            $marks_register->exam_term_id = $exam_id;
                            $marks_register->class_id = $class_id;
                            $marks_register->section_id = $section_id;
                            $marks_register->subject_id = $subject_id;
                            $marks_register->student_id = $sid;
                            $marks_register->student_addmission_no = $admission_no;
                            $marks_register->student_roll_no = $roll_no;
                            $marks_register->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                            $marks_register->total_marks = $mark_by_exam_part;
                            $marks_register->exam_setup_id = $exam_setup_id;
                            if (isset($request->absent_students)) {
                                if (in_array($sid, $request->absent_students)) {
                                    $marks_register->is_absent = 1;
                                } else {
                                    $marks_register->is_absent = 0;
                                }
                            }else{
                                $marks_register->is_absent = 0;
                            }
                            if ($request->teacher_remarks[$sid][$subject_id] !== "") {
                                $marks_register->teacher_remarks = $request->teacher_remarks[$sid][$subject_id];
                            } else {
                                $marks_register->teacher_remarks = $mark_grade->description;
                            }


                            $marks_register->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                            $marks_register->school_id = Auth::user()->school_id;
                            $marks_register->academic_id = getAcademicId();

                            $marks_register->save();
                            $marks_register->toArray();
                        } else {                                                          //If already exists, it will updated
                            $pid = $previous_record->id;
                            $marks_register = SmMarkStore::find($pid);
                            $marks_register->total_marks= $total_marks_persubject;

                            if (isset($request->absent_students)) {
                                if (in_array($sid, $request->absent_students)) {
                                    $marks_register->is_absent = 1;
                                } else {
                                    $marks_register->is_absent = 0;
                                }
                            }else{
                                $marks_register->is_absent = 0;
                            }
                            if ($request->teacher_remarks[$sid][$subject_id] !== "") {
                                $marks_register->teacher_remarks = $request->teacher_remarks[$sid][$subject_id];
                            } else {
                                $marks_register->teacher_remarks = $mark_grade->description;
                            }
                            $marks_register->save();
                        }


                        $exam_setup_count++;
                    } // end part insertion


                    $abc[] = $total_marks_persubject;


                    $previous_result_record = SmResultStore::where([
                        ['class_id', $class_id],
                        ['section_id', $section_id],
                        ['subject_id', $subject_id],
                        ['exam_type_id', $exam_id],
                        ['student_id', $sid]
                    ])->first();


                    if ($previous_result_record == "" || $previous_result_record == null) {         //If not result exists, it will create
                        $result_record = new SmResultStore();
                        $result_record->class_id = $class_id;
                        $result_record->section_id = $section_id;
                        $result_record->subject_id = $subject_id;
                        $result_record->exam_type_id = $exam_id;
                        $result_record->student_id = $sid;

                        if (isset($request->absent_students)) {
                            if (in_array($sid, $request->absent_students)) {
                                $result_record->is_absent = 1;
                            } else {
                                $result_record->is_absent = 0;
                            }
                        }else{
                            $result_record->is_absent = 0;
                        }

                        $result_record->student_roll_no = $roll_no;
                        $result_record->student_addmission_no = $admission_no;
                        $result_record->total_marks = $total_marks_persubject;
                        $result_record->total_gpa_point = @$mark_grade->gpa;
                        $result_record->total_gpa_grade = @$mark_grade->grade_name;

                        if ($request->teacher_remarks[$sid][$subject_id] !== "") {
                            $result_record->teacher_remarks = $request->teacher_remarks[$sid][$subject_id];
                        } else {
                            $result_record->teacher_remarks = @$mark_grade->description;
                        }

                        $result_record->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                        $result_record->school_id = Auth::user()->school_id;
                        $result_record->academic_id = getAcademicId();
                        $result_record->save();
                        $result_record->toArray();
                    } else {                               //If already result exists, it will updated
                        $id = $previous_result_record->id;
                        $result_record = SmResultStore::find($id);
                        $result_record->total_marks = $total_marks_persubject;
                        $result_record->total_gpa_point = @$mark_grade->gpa;
                        $result_record->total_gpa_grade = @$mark_grade->grade_name;
                        $result_record->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                        if (isset($request->absent_students)) {
                            if (in_array($sid, $request->absent_students)) {
                                $result_record->is_absent = 1;
                            } else {
                                $result_record->is_absent = 0;
                            }
                        }else{
                            $result_record->is_absent = 0;
                        }
                        if ($request->teacher_remarks[$sid][$subject_id] !== "") {
                            $result_record->teacher_remarks = $request->teacher_remarks[$sid][$subject_id];
                        } else {
                            $result_record->teacher_remarks = @$mark_grade->description;
                        }


                        $result_record->save();
                        $result_record->toArray();
                    }
                }   // If student id is valid

            } //end student loop

            DB::commit();
            Toastr::success('Operation successful', 'Success');
            return redirect('marks-register');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            Toastr::error('Operation Failed', 'Failed');

            return redirect()->back();
        }
    }

    public function marksRegisterReportSearch(Request $request)
    {

        $request->validate([
            'exam' => 'required',
            'class' => 'required',
            'section' => 'required',
            'subject' => 'required'
        ]);
        try {
            $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $exam_types = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $exam_types = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            $exam_id = $request->exam;
            $class_id = $request->class;
            $section_id = $request->section;
            $subject_id = $request->subject;
            $subjectNames = SmSubject::where('id', $subject_id)->first();

            $exam_attendance = SmExamAttendance::where('exam_id', $exam_id)->where('class_id', $class_id)->where('section_id', $section_id)->where('subject_id', $subject_id)->first();
            if ($exam_attendance) {
                $exam_attendance_child = SmExamAttendanceChild::where('exam_attendance_id', $exam_attendance->id)->first();
            } else {
                Toastr::error('Exam attendance not done yet', 'Failed');
                return redirect()->back();
            }

            $optional_subjects = SmOptionalSubjectAssign::where('class_id', $request->class)->where('section_id', $request->section)->where('subject_id', $request->subject)->get();
            if($optional_subjects->count()==0) {
                $students = SmStudent::where('active_status', 1)->where('class_id', $request->class)->where('section_id', $request->section)->where('academic_id', getAcademicId())->get();
            }
            else{
                $optional_students=[];
                foreach ($optional_subjects as $optional_subject){
                    $optional_students[]=$optional_subject->student_id;
                }
                $students = SmStudent::where('active_status', 1)
                    ->where('class_id', $request->class)
                    ->where('section_id', $request->section)
                    ->whereIn('id', $optional_students)
                    ->where('academic_id', getAcademicId())->get();
            }
            $exam_schedule = SmExamSchedule::where('exam_id', $request->exam)->where('class_id', $request->class)->where('section_id', $request->section)->where('academic_id', getAcademicId())->first();
            if ($students->count() == 0) {
                Toastr::error('Sorry ! Student is not available Or exam schedule is not set yet.', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-danger', 'Sorry ! Student is not available Or exam schedule is not set yet.');
            } else {
                $marks_entry_form = SmExamSetup::where(
                    [
                        ['exam_term_id', $exam_id],
                        ['class_id', $class_id],
                        ['section_id', $section_id],
                        ['subject_id', $subject_id]
                    ]
                )->where('academic_id', getAcademicId())->get();

                if ($marks_entry_form->count() > 0) {
                    $number_of_exam_parts = count($marks_entry_form);
                    return view('backEnd.examination.marks_register_search', compact('exams', 'classes', 'students', 'exam_id', 'class_id', 'section_id', 'subject_id', 'subjectNames', 'number_of_exam_parts', 'marks_entry_form', 'exam_types'));
                } else {
                    Toastr::error('Sorry ! Exam setup is not set yet.', 'Failed');
                    return redirect()->back();
                    // return redirect()->back()->with('message-danger', 'Sorry ! Exam schedule is not set yet.');
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }


    }

    public function seatPlan()
    {
        try {
            $exam_types = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.examination.seat_plan', compact('exam_types', 'classes', 'subjects'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function seatPlanCreate()
    {
        try {
            $exam_types = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $class_rooms = SmClassRoom::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.examination.seat_plan_create', compact('exam_types', 'classes', 'subjects', 'class_rooms'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function seatPlanSearch(Request $request)
    {

        $request->validate([
            'exam' => 'required',
            'subject' => 'required',
            'class' => 'required',
            'section' => 'required'
        ]);
        try {
            $students = SmStudent::where('class_id', $request->class)->where('section_id', $request->section)->where('active_status', 1)->where('academic_id', getAcademicId())->get();

            if ($students->count() == 0) {
                Toastr::error('No result found', 'Failed');
                return redirect('seat-plan-create');
                // return redirect('seat-plan-create')->with('message-danger', 'No result found');
            }

            $seat_plan_assign = SmSeatPlan::where('exam_id', $request->exam)->where('subject_id', $request->subject)->where('class_id', $request->class)->where('section_id', $request->section)->where('date', date('Y-m-d', strtotime($request->date)))->first();


            $seat_plan_assign_childs = [];
            if ($seat_plan_assign != "") {
                $seat_plan_assign_childs = $seat_plan_assign->seatPlanChild;
            }

            $exam_types = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            $class_rooms = SmClassRoom::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $fill_uped = [];
            foreach ($class_rooms as $class_room) {
                $assigned_student = SmSeatPlanChild::where('room_id', $class_room->id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                if ($assigned_student->count() > 0) {
                    $assigned_student = $assigned_student->sum('assign_students');
                    if ($assigned_student >= $class_room->capacity) {
                        $fill_uped[] = $class_room->id;
                    }
                }
            }
            $class_id = $request->class;
            $section_id = $request->section;
            $exam_id = $request->exam;
            $subject_id = $request->subject;
            $date = $request->date;


            return view('backEnd.examination.seat_plan_create', compact('exam_types', 'classes', 'class_rooms', 'students', 'class_id', 'section_id', 'exam_id', 'subject_id', 'seat_plan_assign_childs', 'fill_uped', 'date'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function getExamRoomByAjax(Request $request)
    {
        try {
            $class_rooms = SmClassRoom::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $rest_class_rooms = [];
            foreach ($class_rooms as $class_room) {
                $assigned_student = SmSeatPlanChild::where('room_id', $class_room->id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                if ($assigned_student->count() > 0) {
                    $assigned_student = $assigned_student->sum('assign_students');
                    if ($assigned_student < $class_room->capacity) {
                        $rest_class_rooms[] = $class_room;
                    }
                } else {
                    $rest_class_rooms[] = $class_room;
                }
            }
            return response()->json([$rest_class_rooms]);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }

    public function getRoomCapacity(Request $request)
    {
        try {
            // $class_room = SmClassRoom::find($request->id);
            if (checkAdmin()) {
                $class_room = SmClassRoom::find($request->id);
            } else {
                $class_room = SmClassRoom::where('id', $request->id)->where('school_id', Auth::user()->school_id)->first();
            }
            $assigned = SmSeatPlanChild::where('room_id', $request->id)->where('date', date('Y-m-d', strtotime($request->date)))->first();
            $assigned_student = 0;
            if ($assigned != '') {
                $assigned_student = SmSeatPlanChild::where('room_id', $request->id)->where('date', date('Y-m-d', strtotime($request->date)))->where('start_time', '<=', date('H:i:s', strtotime($request->start_time)))->where('end_time', '>=', date('H:i:s', strtotime($request->end_time)))->sum('assign_students');
            }
            return response()->json([$class_room, $assigned_student]);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }

    public function seatPlanStore(Request $request)
    {

        $seat_plan_assign = SmSeatPlan::where('exam_id', $request->exam_id)->where('subject_id', $request->subject_id)->where('class_id', $request->class_id)->where('section_id', $request->section_id)->first();

        DB::beginTransaction();
        try {
            if ($seat_plan_assign == "") {
                $seat_plan = new SmSeatPlan();
            } else {
                $seat_plan = SmSeatPlan::where('exam_id', $request->exam_id)->where('subject_id', $request->subject_id)->where('class_id', $request->class_id)->where('section_id', $request->section_id)->where('date', date('Y-m-d', strtotime($request->exam_date)))->first();
            }
            $seat_plan->exam_id = $request->exam_id;
            $seat_plan->subject_id = $request->subject_id;
            $seat_plan->class_id = $request->class_id;
            $seat_plan->section_id = $request->section_id;
            $seat_plan->date = date('Y-m-d', strtotime($request->exam_date));
            $seat_plan->school_id = Auth::user()->school_id;
            $seat_plan->academic_id = getAcademicId();
            $seat_plan->save();
            $seat_plan->toArray();

            if ($seat_plan_assign != "") {
                SmSeatPlanChild::where('seat_plan_id', $seat_plan->id)->delete();
            }

            $i = 0;
            foreach ($request->room as $room) {
                $seat_plan_child = new SmSeatPlanChild();
                $seat_plan_child->seat_plan_id = $seat_plan->id;
                $seat_plan_child->room_id = $room;
                $seat_plan_child->assign_students = $request->assign_student[$i];
                $seat_plan_child->start_time = date('H:i:s', strtotime($request->start_time));
                $seat_plan_child->end_time = date('H:i:s', strtotime($request->end_time));
                $seat_plan_child->date = date('Y-m-d', strtotime($request->exam_date));
                $seat_plan_child->school_id = Auth::user()->school_id;
                $seat_plan_child->academic_id = getAcademicId();
                $seat_plan_child->save();
                $i++;
            }
            DB::commit();
            Toastr::success('Operation successful', 'Success');
            return redirect('seat-plan');
            // return redirect('seat-plan')->with('message-success', 'Seat Plan has been assigned successfully');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
            // return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
        }
    }

    public function seatPlanReportSearch(Request $request)
    {
        try {
            $seat_plans = SmSeatPlan::query();
            $seat_plans->where('active_status', 1);
            if ($request->exam != "") {
                $seat_plans->where('exam_id', $request->exam);
            }
            if ($request->subject != "") {
                $seat_plans->where('subject_id', $request->subject);
            }

            if ($request->class != "") {
                $seat_plans->where('class_id', $request->class);
            }

            if ($request->section != "") {
                $seat_plans->where('section_id', $request->section);
            }
            if ($request->date != "") {
                $seat_plans->where('date', date('Y-m-d', strtotime($request->date)));
            }
            $seat_plans = $seat_plans->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            if ($seat_plans->count() == 0) {
                Toastr::success('No Record Found', 'Success');
                return redirect('seat-plan');
            }


            $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            return view('backEnd.examination.seat_plan', compact('exams', 'classes', 'subjects', 'seat_plans'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examAttendance()
    {
        try {
            $exams = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            if (Auth::user()->role_id == 1) {
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            } else {
                $teacher_info = SmStaff::where('user_id', Auth::user()->id)->first();
                $classes = SmAssignSubject::where('teacher_id', $teacher_info->id)->join('sm_classes', 'sm_classes.id', 'sm_assign_subjects.class_id')
                    ->where('sm_assign_subjects.academic_id', getAcademicId())
                    ->where('sm_assign_subjects.active_status', 1)
                    ->where('sm_assign_subjects.school_id', Auth::user()->school_id)
                    ->select('sm_classes.id', 'class_name')
                    ->groupBy('sm_classes.id')
                    ->get();
            }
            $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.examination.exam_attendance', compact('exams', 'classes', 'subjects'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examAttendanceAeportSearch(Request $request)
    {
        $request->validate([
            'exam' => 'required',
            'subject' => 'required',
            'class' => 'required',
            'section' => 'required'
        ]);
        try {
            $exam_attendance = SmExamAttendance::where('class_id', $request->class)
                ->where('section_id', $request->section)->where('subject_id', $request->subject)
                ->where('exam_id', $request->exam)->first();

            if ($exam_attendance == "") {
                Toastr::success('No Record Found', 'Success');
                return redirect('exam-attendance');
            }

            $exam_attendance_childs = [];
            if ($exam_attendance != "") {
                $exam_attendance_childs = $exam_attendance->examAttendanceChild;
            }

            $exams = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            if (Auth::user()->role_id == 1) {
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            } else {
                $teacher_info = SmStaff::where('user_id', Auth::user()->id)->first();
                $classes = SmAssignSubject::where('teacher_id', $teacher_info->id)->join('sm_classes', 'sm_classes.id', 'sm_assign_subjects.class_id')
                    ->where('sm_assign_subjects.academic_id', getAcademicId())
                    ->where('sm_assign_subjects.active_status', 1)
                    ->where('sm_assign_subjects.school_id', Auth::user()->school_id)
                    ->select('sm_classes.id', 'class_name')
                    ->groupBy('sm_classes.id')
                    ->get();
            }
            $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.examination.exam_attendance', compact('exams', 'classes', 'subjects', 'exam_attendance_childs'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examAttendanceCreate()
    {
        try {
            $exams = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            if (Auth::user()->role_id == 1) {
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            } else {
                $teacher_info = SmStaff::where('user_id', Auth::user()->id)->first();
                $classes = SmAssignSubject::where('teacher_id', $teacher_info->id)->join('sm_classes', 'sm_classes.id', 'sm_assign_subjects.class_id')
                    ->where('sm_assign_subjects.academic_id', getAcademicId())
                    ->where('sm_assign_subjects.active_status', 1)
                    ->where('sm_assign_subjects.school_id', Auth::user()->school_id)
                    ->select('sm_classes.id', 'class_name')
                    ->groupBy('sm_classes.id')
                    ->get();
            }
            $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.examination.exam_attendance_create', compact('exams', 'classes', 'subjects'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examAttendanceSearch(Request $request)
    {
        $request->validate([
            'exam' => 'required',
            'subject' => 'required',
            'class' => 'required',
            'section' => 'required'
        ]);
        try {
            $exam_schedules = SmExamSchedule::where('class_id', $request->class)->where('exam_term_id', $request->exam)->where('subject_id', $request->subject)->count();
            if ($exam_schedules == 0) {
                Toastr::error('You have to create exam schedule first', 'Failed');
                return redirect('exam-attendance-create');
                // return redirect('exam-attendance-create')->with('message-danger', 'You have create exam schedule first');
            }

            $optional_subjects = SmOptionalSubjectAssign::where('class_id', $request->class)->where('section_id', $request->section)->where('subject_id', $request->subject)->get();
            if($optional_subjects->count()== 0) {
                $students = SmStudent::where('class_id', $request->class)->where('section_id', $request->section)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->where('active_status', 1)->orderBy('last_name', 'asc')->get();
            } else {
                $optional_students=[];
                foreach ($optional_subjects as $optional_subject){
                    $optional_students[]=$optional_subject->student_id;
                }
                $students = SmStudent::where('class_id', $request->class)->where('section_id', $request->section)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->whereIn('id',$optional_students)
                    ->where('active_status', 1)->orderBy('last_name', 'asc')->get();
            }
            if ($students->count() == 0) {
                Toastr::error('No Record Found', 'Failed');
                return redirect('exam-attendance-create');
                // return redirect('exam-attendance-create')->with('message-danger', 'No Record Found');
            }
            $exam_attendance = SmExamAttendance::where('class_id', $request->class)->where('section_id', $request->section)->where('subject_id', $request->subject)->where('exam_id', $request->exam)->first();
            // dd($exam_attendance);
            $exam_attendance_childs = [];
            if ($exam_attendance != "") {
                $exam_attendance_childs = $exam_attendance->examAttendanceChild;
            }

            $exams = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            if (Auth::user()->role_id == 1) {
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            } else {
                $teacher_info = SmStaff::where('user_id', Auth::user()->id)->first();
                $classes = SmAssignSubject::where('teacher_id', $teacher_info->id)->join('sm_classes', 'sm_classes.id', 'sm_assign_subjects.class_id')
                    ->where('sm_assign_subjects.academic_id', getAcademicId())
                    ->where('sm_assign_subjects.active_status', 1)
                    ->where('sm_assign_subjects.school_id', Auth::user()->school_id)
                    ->select('sm_classes.id', 'class_name')
                    ->groupBy('sm_classes.id')
                    ->get();
            }

            $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $exam_id = $request->exam;
            $subject_id = $request->subject;
            $class_id = $request->class;
            $section_id = $request->section;

            return view('backEnd.examination.exam_attendance_create', compact('exams', 'classes', 'subjects', 'students', 'exam_id', 'subject_id', 'class_id', 'section_id', 'exam_attendance_childs'));
        } catch (\Exception $e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examAttendanceStore(Request $request)
    {
        try {
            $alreday_assigned = SmExamAttendance::where('class_id', $request->class_id)->where('section_id', $request->section_id)->where('subject_id', $request->subject_id)->where('exam_id', $request->exam_id)->first();
            DB::beginTransaction();
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            try {
                if ($alreday_assigned == "") {
                    $exam_attendance = new SmExamAttendance();
                } else {
                    $exam_attendance = SmExamAttendance::where('class_id', $request->class_id)->where('section_id', $request->section_id)->where('subject_id', $request->subject_id)->where('exam_id', $request->exam_id)->first();
                }

                $exam_attendance->exam_id = $request->exam_id;
                $exam_attendance->subject_id = $request->subject_id;
                $exam_attendance->class_id = $request->class_id;
                $exam_attendance->section_id = $request->section_id;
                $exam_attendance->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                $exam_attendance->school_id = Auth::user()->school_id;
                $exam_attendance->academic_id = getAcademicId();
                $exam_attendance->save();
                $exam_attendance->toArray();

                if ($alreday_assigned != "") {
                    SmExamAttendanceChild::where('exam_attendance_id', $exam_attendance->id)->delete();
                }

                foreach ($request->id as $student) {

                    $exam_attendance_child = new SmExamAttendanceChild();
                    $exam_attendance_child->exam_attendance_id = $exam_attendance->id;
                    $exam_attendance_child->student_id = $student;
                    $exam_attendance_child->attendance_type = $request->attendance[$student];
                    $exam_attendance_child->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                    $exam_attendance_child->school_id = Auth::user()->school_id;
                    $exam_attendance_child->academic_id = getAcademicId();
                    $exam_attendance_child->save();
                }

                DB::commit();
                Toastr::success('Operation successful', 'Success');
                return redirect('exam-attendance-create');
            } catch (\Exception $e) {
                DB::rollback();
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentReportsComments(Request $request)
    {
        $class_id = $request->class_id;
        $section_id = $request->section_id;
        $exam_id = $request->exam_id;
        $comment_type = $request->comment_type;
        DB::beginTransaction();
        try {
            $is_existing_data = SmTemporaryMeritlist::where([['class_id', $class_id], ['section_id', $section_id], ['exam_id', $exam_id]])->
                where('academic_id',getAcademicId())->first();
            if (empty($is_existing_data)) {
                Toastr::error('Reports need to be generated before adding comments!', 'Failed');
                return redirect()->back();
            } else {
                   foreach ($request->comment as $student_id => $comment){
                       $report_comment = $comment;
                       $insert_comments = SmTemporaryMeritlist::where([['class_id', $class_id], ['section_id', $section_id], ['exam_id', $exam_id], ['student_id', $student_id]])
                           ->where('academic_id',getAcademicId())->first();
                       $insert_comments->school_id = Auth::user()->school_id;
                       $insert_comments->academic_id = getAcademicId();
                       if($comment_type=="class_teacher"){
                           $insert_comments->class_teacher_remark = $report_comment;
                       }else{
                           $insert_comments->principal_remark = $report_comment;
                       }
                       $insert_comments->save();
                }
            }
                DB::commit();
                Toastr::success('Operation successful', 'Success');
                return redirect('student-report-comments');
            } catch (\Exception $e) {
                DB::rollback();
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
    }

    public function sendMarksBySms()
    {
        $exams = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
        if (Auth::user()->role_id == 1) {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
        } else {
            $teacher_info = SmStaff::where('user_id', Auth::user()->id)->first();
            $classes = SmAssignSubject::where('teacher_id', $teacher_info->id)->join('sm_classes', 'sm_classes.id', 'sm_assign_subjects.class_id')
                ->where('sm_assign_subjects.academic_id', getAcademicId())
                ->where('sm_assign_subjects.active_status', 1)
                ->where('sm_assign_subjects.school_id', Auth::user()->school_id)
                ->select('sm_classes.id', 'class_name')
                ->get();
        }
        return view('backEnd.examination.send_marks_by_sms', compact('exams', 'classes'));
    }

    public function sendMarksBySmsStore(Request $request)
    {
        $request->validate([
            'exam' => 'required',
            'class' => 'required',
            'receiver' => 'required'
        ]);


        try {
            $receiver = $request->receiver;
            $exam_id = $request->exam;
            $exams = SmExamType::where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)->get();

            if ($receiver == "parents") {
                $students = SmStudent::where('sm_students.active_status', 1)
                    ->where('sm_students.academic_id', getAcademicId())
                    ->where('sm_students.school_id', Auth::user()->school_id)
                    ->where('sm_students.class_id', $request->class)
                    ->join('sm_parents', 'sm_parents.id', '=', 'sm_students.parent_id')
                    ->select('sm_students.id', 'sm_students.last_name', 'sm_students.first_name', 'guardians_name', 'guardians_mobile as mobile')
                    ->whereRaw('length(sm_parents.guardians_mobile)>8')
                    ->orderBy('last_name')
                    ->get();
            } else {
                $students = SmStudent::where('active_status', 1)
                    ->where('academic_id', getAcademicId())
                    ->where('class_id', $request->class)
                    ->where('school_id', Auth::user()->school_id)
                    ->whereRaw('length(mobile)>8')
                    ->orderBy('last_name')
                    ->get();
            }

            $class = SmClass::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('id', $request->class)
                ->where('school_id', Auth::user()->school_id)->first();
            $class_name = $class->class_name;

            $exam = SmExamType::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('id', $request->exam)
                ->where('school_id', Auth::user()->school_id)->first();

            $exam_name = $exam->title;

            return view('backEnd.examination.send_marks_by_sms', compact('exams', 'exam_id', 'classes', 'class', 'receiver', 'exam_name', 'class_name', 'students'));
        } catch (\Exception $e) {
            dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function blockResults()
    {
        $exams = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
        if (Auth::user()->role_id == 1 || Auth::user()->role_id == 5) {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $sections = SmSection::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
        } else {
            $teacher_info = SmStaff::where('user_id', Auth::user()->id)->first();
            $classes = SmAssignSubject::where('teacher_id', $teacher_info->id)->join('sm_classes', 'sm_classes.id', 'sm_assign_subjects.class_id')
                ->where('sm_assign_subjects.academic_id', getAcademicId())
                ->where('sm_assign_subjects.active_status', 1)
                ->where('sm_assign_subjects.school_id', Auth::user()->school_id)
                ->select('sm_classes.id', 'class_name')
                ->get();
        }
        return view('backEnd.examination.block_results', compact('exams', 'classes','sections'));
    }

    public function blockResultsStore(Request $request)
    {
        $request->validate([
            'exam' => 'required',
            'class' => 'required'
        ]);


        try {
            $exam_id = $request->exam;
            $exams = SmExamType::where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)->get();
            $sections = SmSection::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)->get();
            if($request->section!=="") {
                $students = SmStudent::where('active_status', 1)
                    ->where('academic_id', getAcademicId())
                    ->where('class_id', $request->class)
                    ->where('school_id', Auth::user()->school_id)
                    ->orderBy('last_name')
                    ->get();
            }
            else{
                $students = SmStudent::where('active_status', 1)
                    ->where('academic_id', getAcademicId())
                    ->where('class_id', $request->class)
                    ->where('section_id', $request->section)
                    ->where('school_id', Auth::user()->school_id)
                    ->orderBy('last_name')
                    ->get();
            }

            $receiver = "Block/Unblock Results";
            $class = SmClass::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('id', $request->class)
                ->where('school_id', Auth::user()->school_id)->first();
            $class_name = $class->class_name;

            $exam = SmExamType::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('id', $request->exam)
                ->where('school_id', Auth::user()->school_id)->first();

            $exam_name = $exam->title;

            return view('backEnd.examination.block_results', compact('exams', 'exam_id', 'classes', 'class', 'receiver', 'exam_name', 'class_name', 'students', 'sections'));
        } catch (\Exception $e) {
            dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function blockResultsProcess(Request $request)
    {


          try {
            foreach ($request->result as $key => $student) {
                $student_id = $key;

                $insert_status = SmTemporaryMeritlist::where([['class_id', $request->class_id], ['section_id', $request->section_id], ['exam_id', $request->exam_id], ['student_id', $student_id]])
                    ->where('academic_id', getAcademicId())->first();
                if (isset($insert_status)) {
                    $insert_status->school_id = Auth::user()->school_id;
                    $insert_status->academic_id = getAcademicId();
                    $insert_status->report_status = $student['status'];
                    if ($student['status'] == 0) {
                        $insert_status->status_reason = "Suspended by Admin due to Outstanding School Items";
                    }
                    $insert_status->save();
                }
            }

            Toastr::success('Operation successful', 'Success');
            return redirect('block-results');

        } catch (\Exception $e) {
            dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function sendSMSFromComunicate($to_mobile, $sms)
    {

        $activeSmsGateway = SmSmsGateway::where('active_status', '=', 1)->where('school_id', Auth::user()->school_id)->first();

        if (empty($activeSmsGateway)) {
            Toastr::error('Please active a SMS gateway', 'Failed');
            return redirect()->back();
        }
        if ($activeSmsGateway->gateway_name == 'Twilio') {
            // this is for school wise sms setting in saas.
            config(['TWILIO.SID' => $activeSmsGateway->twilio_account_sid]);
            config(['TWILIO.TOKEN' => $activeSmsGateway->twilio_authentication_token]);
            config(['TWILIO.FROM' => $activeSmsGateway->twilio_registered_no]);


            $account_id = $activeSmsGateway->twilio_account_sid; // Your Account SID from www.twilio.com/console
            $auth_token = $activeSmsGateway->twilio_authentication_token; // Your Auth Token from www.twilio.com/console
            $from_phone_number = $activeSmsGateway->twilio_registered_no;


            $client = new Twilio\Rest\Client($account_id, $auth_token);


            if (!empty($to_mobile)) {
                $result = $message = $client->messages->create($to_mobile, array('from' => $from_phone_number, 'body' => $sms));
            }
        } //end Twilio
        elseif ($activeSmsGateway->gateway_name == 'Clickatell') {


            // config(['clickatell.api_key' => $activeSmsGateway->clickatell_api_id]); //set a variale in config file(clickatell.php)

            $clickatell = new \Clickatell\Rest();

            $result = $clickatell->sendMessage(['to' => $to_mobile, 'content' => $sms]);
        } //end Clickatell
        elseif ($activeSmsGateway->gateway_name == 'Msg91') {
            $msg91_authentication_key_sid = $activeSmsGateway->msg91_authentication_key_sid;
            $msg91_sender_id = $activeSmsGateway->msg91_sender_id;
            $msg91_route = $activeSmsGateway->msg91_route;
            $msg91_country_code = $activeSmsGateway->msg91_country_code;

            $curl = curl_init();

            $url = "https://api.msg91.com/api/sendhttp.php?mobiles=" . $to_mobile . "&authkey=" . $msg91_authentication_key_sid . "&route=" . $msg91_route . "&sender=" . $msg91_sender_id . "&message=" . $sms . "&country=91";

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "GET", CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0,
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                $result = "cURL Error #:" . $err;
            } else {
                $result = $response;
            }
        } //end Msg91
        elseif ($activeSmsGateway->gateway_name == 'AfricaTalking') {


            $username = $activeSmsGateway->africatalking_username; // use 'sandbox' for development in the test environment
            $apiKey = $activeSmsGateway->africatalking_api_key; // use your sandbox app API key for development in the test environment
            $AT = new AfricasTalking($username, $apiKey);

            // Get one of the services
            $sms_Send = $AT->sms();


            // $to_mobile = implode(',', $to_mobile);

            // Use the service
            $result = $sms_Send->send([
                'to' => $to_mobile,
                'message' => $sms
            ]);


        }

        return $result;
    }

    public function sendMarksBySmsProcess(Request $request)
    {
        $school = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
        try {
            $count = 0;
            foreach ($request->result as $key => $student) {
                $student_id = $key;
                $phone = $student['phone'];
                $status = $student['status']; //TODO, make the content of the student to be blocked to be configurable


                if ($status == "Skip") {
                    $message = "Dear parent/guardian, the results of your student cannot be released due to outstanding School Items. Please Contact ".$school->phone." for more info. ". $school->school_name;
                    if (strlen($phone) >= 11) {
                        $this->sendSMSFromComunicate($phone, $message);
                    }
                    // dd($message);
                } else {
                    //Get Results MPHO MOSOTHO: MAT[90-A*], SES[30-F], AVERAGE: 46.6, POS: 2, RESULT: ADVANCED.
                    $studentInfo = SmStudent::where('active_status', 1)
                        ->where('academic_id', getAcademicId())
                        ->where('id', $student_id)
                        ->where('school_id', Auth::user()->school_id)
                        ->first();
                    $full_name = $studentInfo->full_name;

                    $exam_result = DB::table('sm_result_stores')
                        ->join('sm_exam_types', 'sm_exam_types.id', '=', 'sm_result_stores.exam_type_id')
                        ->join('sm_exams', 'sm_exams.id', '=', 'sm_exam_types.id')
                        ->join('sm_subjects', 'sm_subjects.id', '=', 'sm_result_stores.subject_id')
                        ->where('sm_exams.id', '=', $request->exam_id)
                        ->where('sm_result_stores.school_id', '=', $studentInfo->school_id)
                        ->where('sm_result_stores.class_id', '=', $studentInfo->class_id)
                        ->where('sm_result_stores.section_id', '=', $studentInfo->section_id)
                        ->where('sm_result_stores.student_id', '=', $studentInfo->id)
                        ->where('sm_result_stores.total_marks', '>', 0)
                        ->select('sm_subjects.id', 'sm_subjects.subject_name', 'sm_result_stores.total_marks as obtained_marks', 'sm_exams.exam_mark as total_marks', 'sm_result_stores.total_gpa_grade as grade', 'sm_result_stores.teacher_remarks as remark')
                        ->get();
                    $exam_results = [];
                    $exam_count = 0;
                    $exam_results_string = $full_name . " -> ";
                    $exam_count = 1;
                    foreach ($exam_result as $result) {
                        $mark_grade = SmMarksGrade::where([['percent_from', '<=', $result->obtained_marks], ['percent_upto', '>=', $result->obtained_marks]])->where('school_id', $studentInfo->school_id)->first();

                        $exam_results[$exam_count] = [
                            substr($result->subject_name, 0, 3) => $result->obtained_marks . "-" . $mark_grade->grade_name
                        ];
                        $exam_results_string .= substr($result->subject_name, 0, 3) . ":" . $result->obtained_marks . "-" . $mark_grade->grade_name . ", ";
                        $exam_count++;
                    }
                    $allresult_data = SmTemporaryMeritlist::where(['exam_id' => $request->exam_id, 'class_id' => $studentInfo->class_id, 'section_id' => $studentInfo->section_id])->where('academic_id', $studentInfo->academic_id)->where('school_id', $studentInfo->school_id)->where('student_id', $studentInfo->id)->first();

                    $report_status = $allresult_data->report_status;

                    $results = SmResultsConfiguration::where([['percent_from', '<=', floor($allresult_data->average_mark)], ['percent_upto', '>=', floor($allresult_data->average_mark)]])->where('academic_id', $studentInfo->academic_id)->where('school_id', Auth::user()->school_id)->first();
                    $exam_results_string .= " Average: " . $allresult_data->average_mark . ",";
                    $exam_results_string .= " Position: " . $allresult_data->merit_order . ".";
                    if($results->result_name!=="") {
                        $exam_results_string .= ", Result: " . $results->result_name . ".";
                    }

                    //NOW SEND SMS//
                    if($report_status == 1) {
                        if (strlen($phone) >= 11) {
                            $count++;
                            $this->sendSMSFromComunicate($phone, $exam_results_string . " School Re-Open on 02/08/2021. For more info please contact " . $school->phone . ". " . $school->school_name);
                        }
                    }else{
                        $message = "Dear parent/guardian, the results of your student cannot be released due to outstanding School Items. Please Contact ".$school->phone." for more info. ". $school->school_name;
                        $this->sendSMSFromComunicate($phone, $message);
                    }
                }
            }

            Toastr::success('Operation successful. Send Message to ' . $count . ' parents.', 'Success');
            return redirect('send-marks-by-sms');

        } catch (\Exception $e) {
            dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function meritListReport(Request $request)
    {
        try {
            $exams = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            if (Auth::user()->role_id == 1) {
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            } else {
                $teacher_info = SmStaff::where('user_id', Auth::user()->id)->first();
                $classes = SmAssignSubject::where('teacher_id', $teacher_info->id)->join('sm_classes', 'sm_classes.id', 'sm_assign_subjects.class_id')
                    ->where('sm_assign_subjects.academic_id', getAcademicId())
                    ->where('sm_assign_subjects.active_status', 1)
                    ->where('sm_assign_subjects.school_id', Auth::user()->school_id)
                    ->select('sm_classes.id', 'class_name')
                    ->get();
            }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['exams'] = $exams->toArray();
                $data['classes'] = $classes->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.reports.merit_list_report', compact('exams', 'classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    //created by Rashed
    public function reportsTabulationSheet()
    {
        try {
            $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.reports.report_tabulation_sheet', compact('exams', 'classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function reportsTabulationSheetSearch(Request $request)
    {
        try {
            $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.reports.report_tabulation_sheet', compact('exams', 'classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    //end tabulation sheet report

    public function make_merit_list($InputClassId, $InputSectionId, $InputExamId, Request $request)
    {

        $iid = time();
        $class = SmClass::find($InputClassId);
        $section = SmSection::find($InputSectionId);
        $exam = SmExamType::find($InputExamId);
        $is_data = DB::table('sm_mark_stores')->where([['class_id', $InputClassId], ['section_id', $InputSectionId], ['exam_term_id', $InputExamId]])->first();
        if (empty($is_data)) {
            Toastr::error('Your result is not found!', 'Failed');
            return redirect()->back();
            // return redirect()->back()->with('message-danger', 'Your result is not found!');
        }
        $exams = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
        $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
        $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
        $assign_subjects = SmAssignSubject::where('class_id', $class->id)->where('section_id', $section->id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
        $class_name = $class->class_name;
        $exam_name = $exam->title;
        $eligible_subjects = SmAssignSubject::where('class_id', $InputClassId)->where('section_id', $InputSectionId)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
        $eligible_students = SmStudent::where('class_id', $InputClassId)->where('section_id', $InputSectionId)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

        //all subject list in a specific class/section
        $subject_ids = [];
        $subject_strings = '';
        $marks_string = '';
        foreach ($eligible_students as $SingleStudent) {
            foreach ($eligible_subjects as $subject) {
                $subject_ids[] = $subject->subject_id;
                $subject_strings = (empty($subject_strings)) ? $subject->subject->subject_name : $subject_strings . ',' . $subject->subject->subject_name;

                $getMark = SmResultStore::where([
                    ['exam_type_id', $InputExamId],
                    ['class_id', $InputClassId],
                    ['section_id', $InputSectionId],
                    ['student_id', $SingleStudent->id],
                    ['subject_id', $subject->subject_id]
                ])->first();
                if ($getMark == "") {
                    Toastr::error('Please register marks for all students.!', 'Failed');
                    return redirect()->back();
                    // return redirect()->back()->with('message-danger', 'Please register marks for all students.!');
                }
                if ($marks_string == "") {
                    if ($getMark->total_marks == 0) {
                        $marks_string = '0';
                    } else {
                        $marks_string = $getMark->total_marks;
                    }
                } else {
                    $marks_string = $marks_string . ',' . $getMark->total_marks;
                }
            }

            //end subject list for specific section/class

            $results = SmResultStore::where([
                ['exam_type_id', $InputExamId],
                ['class_id', $InputClassId],
                ['section_id', $InputSectionId],
                ['student_id', $SingleStudent->id]
            ])->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $is_absent = SmResultStore::where([
                ['exam_type_id', $InputExamId],
                ['class_id', $InputClassId],
                ['section_id', $InputSectionId],
                ['is_absent', 1],
                ['student_id', $SingleStudent->id]
            ])->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            $total_gpa_point = SmResultStore::where([
                ['exam_type_id', $InputExamId],
                ['class_id', $InputClassId],
                ['section_id', $InputSectionId],
                ['student_id', $SingleStudent->id]
            ])->sum('total_gpa_point');

            $total_marks = SmResultStore::where([
                ['exam_type_id', $InputExamId],
                ['class_id', $InputClassId],
                ['section_id', $InputSectionId],
                ['student_id', $SingleStudent->id]
            ])->sum('total_marks');

            $sum_of_mark = ($total_marks == 0) ? 0 : $total_marks;
            $average_mark = ($total_marks == 0) ? 0 : floor($total_marks / $results->count()); //get average number
            $is_absent = (count($is_absent) > 0) ? 1 : 0;         //get is absent ? 1=Absent, 0=Present
            $total_GPA = ($total_gpa_point == 0) ? 0 : $total_gpa_point / $results->count();
            $exart_gp_point = number_format($total_GPA, 2, '.', '');            //get gpa results
            $full_name = $SingleStudent->full_name;                 //get name
            $admission_no = $SingleStudent->admission_no;           //get admission no
            $student_id = $SingleStudent->id;           //get admission no
            $is_existing_data = SmTemporaryMeritlist::where([['admission_no', $admission_no], ['class_id', $InputClassId], ['section_id', $InputSectionId], ['exam_id', $InputExamId]])->first();
            if (empty($is_existing_data)) {
                $insert_results = new SmTemporaryMeritlist();
            } else {
                $insert_results = SmTemporaryMeritlist::find($is_existing_data->id);
            }
            $insert_results->student_name = $full_name;
            $insert_results->admission_no = $admission_no;
            $insert_results->subjects_string = $subject_strings;
            $insert_results->marks_string = $marks_string;
            $insert_results->total_marks = $sum_of_mark;
            $insert_results->average_mark = $average_mark;
            $insert_results->gpa_point = $exart_gp_point;
            $insert_results->iid = $iid;
            $insert_results->student_id = $student_id;
            $markGrades = SmMarksGrade::where([['from', '<=', $exart_gp_point], ['up', '>=', $exart_gp_point]])->where('school_id', Auth::user()->school_id)->first();

            if ($is_absent == "") {
                $insert_results->result = $markGrades->grade_name;
            } else {
                $insert_results->result = 'F';
            }
            $insert_results->section_id = $InputSectionId;
            $insert_results->class_id = $InputClassId;
            $insert_results->exam_id = $InputExamId;
            $insert_results->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
            $insert_results->school_id = Auth::user()->school_id;
            $insert_results->academic_id = getAcademicId();
            $insert_results->save();

            $subject_strings = "";
            $marks_string = "";
            $total_marks = 0;
            $average = 0;
            $exart_gp_point = 0;
            $admission_no = 0;
            $full_name = "";
        } //end loop eligible_students

        $first_data = SmTemporaryMeritlist::where('iid', $iid)->first();
        $subjectlist = explode(',', $first_data->subjects_string);
        $allresult_data = SmTemporaryMeritlist::where('iid', $iid)->orderBy('gpa_point', 'desc')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
        $merit_serial = 1;
        foreach ($allresult_data as $row) {
            $D = SmTemporaryMeritlist::where('iid', $iid)->where('id', $row->id)->first();
            $D->merit_order = $merit_serial++;
            $D->save();
        }
        $allresult_data = SmTemporaryMeritlist::orderBy('merit_order', 'asc')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $data = [];
            $data['exams'] = $exams->toArray();
            $data['classes'] = $classes->toArray();
            $data['subjects'] = $subjects->toArray();
            $data['class'] = $class;
            $data['section'] = $section;
            $data['exam'] = $exam;
            $data['subjectlist'] = $subjectlist;
            $data['allresult_data'] = $allresult_data;
            $data['class_name'] = $class_name;
            $data['assign_subjects'] = $assign_subjects;
            $data['exam_name'] = $exam_name;
            return ApiBaseMethod::sendResponse($data, null);
        }
        $data['iid'] = $iid;
        $data['exams'] = $exams;
        $data['classes'] = $classes;
        $data['subjects'] = $subjects;
        $data['class'] = $class;
        $data['section'] = $section;
        $data['exam'] = $exam;
        $data['subjectlist'] = $subjectlist;
        $data['allresult_data'] = $allresult_data;
        $data['class_name'] = $class_name;
        $data['assign_subjects'] = $assign_subjects;
        $data['exam_name'] = $exam_name;
        $data['InputClassId'] = $InputClassId;
        $data['InputExamId'] = $InputExamId;
        $data['InputSectionId'] = $InputSectionId;
        return $data;
    }

    public function meritListReportSearch(Request $request)
    {
        try {
            $iid = time();
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            if ($request->method() == 'POST') {
                //ur code here

                // $emptyResult = SmTemporaryMeritlist::truncate();
                $input = $request->all();
                $validator = Validator::make($input, [
                    'exam' => 'required',
                    'class' => 'required',
                    'section' => 'required'
                ]);

                if ($validator->fails()) {
                    if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                        return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
                    }
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }

                $InputClassId = $request->class;
                $InputExamId = $request->exam;
                $InputSectionId = $request->section;

                $class = SmClass::find($InputClassId);
                $section = SmSection::find($InputSectionId);
                $exam = SmExamType::find($InputExamId);

                $optional_subject_setup = SmClassOptionalSubject::where('class_id', '=', $request->class)->first();

                $is_data = DB::table('sm_mark_stores')->where([['class_id', $InputClassId], ['section_id', $InputSectionId], ['exam_term_id', $InputExamId]])->first();
                //    dd( $is_data);
                if (empty($is_data)) {
                    Toastr::error('Your result is not found!', 'Failed');
                    return redirect()->back();
                    // return redirect()->back()->with('message-danger', 'Your result is not found!');
                }

                $exams = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();


                $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $assign_subjects = SmAssignSubject::where('class_id', $class->id)->where('section_id', $section->id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $class_name = $class->class_name;


                $exam_name = $exam->title;

                $eligible_subjects = SmAssignSubject::where('class_id', $InputClassId)->where('section_id', $InputSectionId)->where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $eligible_students = SmStudent::where('class_id', $InputClassId)->where('section_id', $InputSectionId)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->where('active_status', 1)->get();

                //all subject list in a specific class/section
                $subject_ids = [];
                $subject_strings = '';
                $subject_id_strings = '';
                $marks_string = '';
                foreach ($eligible_students as $SingleStudent) {
                    foreach ($eligible_subjects as $subject) {
                        $subject_ids[] = $subject->subject_id;
                        $subject_strings = (empty($subject_strings)) ? $subject->subject->subject_name : $subject_strings . ',' . $subject->subject->subject_name;
                        $subject_id_strings = (empty($subject_id_strings)) ? $subject->subject_id : $subject_id_strings . ',' . $subject->subject_id;
                        $getMark = SmResultStore::where([
                            ['exam_type_id', $InputExamId],
                            ['class_id', $InputClassId],
                            ['section_id', $InputSectionId],
                            ['student_id', $SingleStudent->id],
                            ['subject_id', $subject->subject_id]
                        ])->first();
                        if ($getMark == "") {
                            // dd($getMark);
                            Toastr::error('Please register marks for all students.!', 'Failed');
                            return redirect()->back();
                            // return redirect()->back()->with('message-danger', 'Please register marks for all students.!');
                        }

                        // if (empty($getMark->total_marks)) {
                        //     $FinalMarks = 0;
                        // } else {
                        //     $FinalMarks = $getMark->total_marks;
                        // }

                        if ($marks_string == "") {
                            if ($getMark->total_marks == 0) {
                                $marks_string = '0';
                            } else {
                                $marks_string = $getMark->total_marks;
                            }
                        } else {
                            $marks_string = $marks_string . ',' . $getMark->total_marks;
                        }
                    }

                    //end subject list for specific section/class

                    $results = SmResultStore::where([
                        ['exam_type_id', $InputExamId],
                        ['class_id', $InputClassId],
                        ['section_id', $InputSectionId],
                        ['student_id', $SingleStudent->id]
                    ])->where('academic_id', getAcademicId())->where('total_marks', '>', 0)->where('school_id', Auth::user()->school_id)->get();
                    $is_absent = SmResultStore::where([
                        ['exam_type_id', $InputExamId],
                        ['class_id', $InputClassId],
                        ['section_id', $InputSectionId],
                        ['is_absent', 1],
                        ['student_id', $SingleStudent->id]
                    ])->where('academic_id', getAcademicId())->where('total_marks', '>', 0)->where('school_id', Auth::user()->school_id)->get();

                    $total_gpa_point = SmResultStore::where([
                        ['exam_type_id', $InputExamId],
                        ['class_id', $InputClassId],
                        ['section_id', $InputSectionId],
                        ['student_id', $SingleStudent->id]
                    ])->sum('total_gpa_point');

                    $total_marks = SmResultStore::where([
                        ['exam_type_id', $InputExamId],
                        ['class_id', $InputClassId],
                        ['section_id', $InputSectionId],
                        ['student_id', $SingleStudent->id]
                    ])->sum('total_marks');

                    $dat = array();
                    $sum_of_mark = ($total_marks == 0) ? 0 : $total_marks;
                    $average_mark = ($total_marks == 0) ? 0 : floor($total_marks / $results->count()); //get average number
                    $is_absent = (count($is_absent) > 0) ? 1 : 0;         //get is absent ? 1=Absent, 0=Present
                    foreach ($results as $key => $gpa_result) {
                        $da = DB::table('sm_optional_subject_assigns')->where(['student_id' => $gpa_result->student_id, 'subject_id' => $gpa_result->subject_id])->count();
                        if ($da < 1) {
                            $grade_gpa = DB::table('sm_marks_grades')->where('percent_from', '<=', $gpa_result->total_marks)->where('percent_upto', '>=', $gpa_result->total_marks)->where('academic_id', getAcademicId())->first();
                            if ($grade_gpa->grade_name == 'F') {
                                array_push($dat, $grade_gpa->gpa);
                            }
                        }
                    }
                    if (!empty($dat)) {
                        $exart_gp_point = $dat['0'];
                    } else {
                        $total_GPA = ($total_gpa_point == 0) ? 0 : $total_gpa_point / $results->count();
                        $exart_gp_point = number_format($total_GPA, 2, '.', '');            //get gpa results
                    }
                    $full_name = $SingleStudent->full_name;                 //get name
                    $admission_no = $SingleStudent->admission_no;           //get admission no
                    $student_id = $SingleStudent->id;           //get admission no


                    $is_existing_data = SmTemporaryMeritlist::where([['admission_no', $admission_no], ['class_id', $InputClassId], ['section_id', $InputSectionId], ['exam_id', $InputExamId]])->first();
                    // return $is_existing_data;
                    if (empty($is_existing_data)) {
                        $insert_results = new SmTemporaryMeritlist();
                    } else {
                        $insert_results = SmTemporaryMeritlist::find($is_existing_data->id);
                    }
                    // $insert_results                     = new SmTemporaryMeritlist();
                    $insert_results->student_name = $full_name;
                    $insert_results->admission_no = $admission_no;
                    $insert_results->subjects_id_string = implode(',', array_unique($subject_ids));
                    $insert_results->subjects_string = $subject_strings;
                    $insert_results->marks_string = $marks_string;
                    $insert_results->total_marks = $sum_of_mark;
                    $insert_results->average_mark = $average_mark;
                    $insert_results->gpa_point = $exart_gp_point;
                    $insert_results->iid = $iid;
                    $insert_results->student_id = $SingleStudent->id;
                    $markGrades = SmMarksGrade::where([['from', '<=', $exart_gp_point], ['up', '>=', $exart_gp_point]])->where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->first();

                    if ($is_absent == "") {
                        $insert_results->result = @$markGrades->grade_name;
                    } else {
                        $insert_results->result = 'F';
                    }
                    $insert_results->section_id = $InputSectionId;
                    $insert_results->class_id = $InputClassId;
                    $insert_results->exam_id = $InputExamId;
                    $insert_results->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                    $insert_results->school_id = Auth::user()->school_id;
                    $insert_results->academic_id = getAcademicId();
                    $insert_results->save();


                    $subject_strings = "";
                    $marks_string = "";
                    $total_marks = 0;
                    $average = 0;
                    $exart_gp_point = 0;
                    $admission_no = 0;
                    $full_name = "";
                } //end loop eligible_students

                // return implode(',',array_unique($subject_ids));

                $first_data = SmTemporaryMeritlist::where('iid', $iid)->first();

                $subjectlist = explode(',', @$first_data->subjects_string);
                $allresult_data = SmTemporaryMeritlist::where('iid', $iid)->orderBy('gpa_point', 'desc')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $merit_serial = 1;
                foreach ($allresult_data as $row) {
                    $D = SmTemporaryMeritlist::where('iid', $iid)->where('id', $row->id)->first();
                    $D->merit_order = $merit_serial++;
                    $D->save();
                }


                $allresult_data = SmTemporaryMeritlist::orderBy('merit_order', 'asc')->where('exam_id', '=', $InputExamId)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    $data = [];
                    $data['exams'] = $exams->toArray();
                    $data['classes'] = $classes->toArray();
                    $data['subjects'] = $subjects->toArray();
                    $data['class'] = $class;
                    $data['section'] = $section;
                    $data['exam'] = $exam;
                    $data['subjectlist'] = $subjectlist;
                    $data['allresult_data'] = $allresult_data;
                    $data['class_name'] = $class_name;
                    $data['assign_subjects'] = $assign_subjects;
                    $data['exam_name'] = $exam_name;
                    return ApiBaseMethod::sendResponse($data, null);
                }

                if ($optional_subject_setup == '') {
                    return view('backEnd.reports.merit_list_report_normal', compact('iid', 'exams', 'classes', 'subjects', 'class', 'section', 'exam', 'subjectlist', 'allresult_data', 'class_name', 'assign_subjects', 'exam_name', 'InputClassId', 'InputExamId', 'InputSectionId', 'optional_subject_setup'));
                } else {
                    return view('backEnd.reports.merit_list_report', compact('iid', 'exams', 'classes', 'subjects', 'class', 'section', 'exam', 'subjectlist', 'allresult_data', 'class_name', 'assign_subjects', 'exam_name', 'InputClassId', 'InputExamId', 'InputSectionId', 'optional_subject_setup'));

                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function meritListPrint($exam_id, $class_id, $section_id)
    {
        set_time_limit(2700);
        try {
            // $iid = time();
            // DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // $emptyResult = SmTemporaryMeritlist::truncate();

            $InputClassId = $class_id;
            $InputExamId = $exam_id;
            $InputSectionId = $section_id;

            $class = SmClass::find($InputClassId);
            $section = SmSection::find($InputSectionId);
            $exam = SmExamType::find($InputExamId);

            // $is_data = DB::table('sm_mark_stores')->where([['class_id', $InputClassId], ['section_id', $InputSectionId], ['exam_term_id', $InputExamId]])->first();

            $exams = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $subjects = SmSubject::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $assign_subjects = SmAssignSubject::where('class_id', $class->id)->where('section_id', $section->id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $class_name = $class->class_name;
            $exam_name = $exam->title;

            $optional_subject_setup = SmClassOptionalSubject::where('class_id', '=', $class_id)->first();


            $allresult_dat = SmTemporaryMeritlist::orderBy('merit_order', 'asc')->where(['exam_id' => $exam_id, 'class_id' => $class_id, 'section_id' => $section_id])->where('academic_id', getAcademicId())->first();
            $allresult_data = SmTemporaryMeritlist::orderBy('merit_order', 'asc')->where(['exam_id' => $exam_id, 'class_id' => $class_id, 'section_id' => $section_id])->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            // $allresult_data = SmTemporaryMeritlist::orderBy('merit_order', 'asc')->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $subjectlist = explode(',', $allresult_dat->subjects_string);

            return view('backEnd.reports.merit_list_report_print', compact('exams', 'classes', 'subjects', 'class', 'section', 'exam', 'subjectlist', 'allresult_data', 'class_name', 'assign_subjects', 'exam_name', 'optional_subject_setup'));


            $pdf = PDF::loadView(
                'backEnd.reports.merit_list_report_print',
                [
                    'exams' => $exams,
                    'classes' => $classes,
                    'subjects' => $subjects,
                    'class' => $class,
                    'section' => $section,
                    'exam' => $exam,
                    'subjectlist' => $subjectlist,
                    'allresult_data' => $allresult_data,
                    'class_name' => $class_name,
                    'assign_subjects' => $assign_subjects,
                    'exam_name' => $exam_name,
                    'optional_subject_setup' => $optional_subject_setup,

                ]
            )->setPaper('A4', 'landscape');

            return $pdf->stream('student_merit_list.pdf');
        } catch (\Exception $e) {
            // dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function markSheetReport()
    {
        try {
            $exams = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.reports.mark_sheet_report', compact('exams', 'classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function markSheetReportSearch(Request $request)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $request->validate([
            'exam' => 'required',
            'class' => 'required',
            'section' => 'required'
        ]);
        try {
            $class = SmClass::find($request->class);
            $section = SmSection::find($request->section);
            $exam = SmExam::find($request->exam);

            $subjects = SmAssignSubject::where('class_id', $request->class)->where('section_id', $request->section)->where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $all_students = SmStudent::where('class_id', $request->class)->where('section_id', $request->section)->where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $students=$all_students;

            $marks_registers = SmMarksRegister::where('exam_id', $request->exam)->where('class_id', $request->class)->where('section_id', $request->section)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            $marks_register = SmMarksRegister::where('exam_id', $request->exam)->where('class_id', $request->class)->where('section_id', $request->section)->first();
            if ($marks_registers->count() == 0) {
                Toastr::error('Result not found', 'Failed');
                return redirect()->back();
                // return redirect('mark-sheet-report')->with('message-danger', 'Result not found');
            }
            // $marks_register_childs = $marks_register->marksRegisterChilds;
            $exams = SmExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $grades = SmMarksGrade::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            $exam_id = $request->exam;
            $class_id = $request->class;

            return view('backEnd.reports.mark_sheet_report', compact('exams', 'classes', 'marks_registers', 'marks_register','students', 'all_students', 'subjects', 'class', 'section', 'exam', 'grades', 'exam_id', 'class_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function markSheetReportStudent(Request $request)
    {
        try {
            $exams = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            $teacher = SmStaff::where('active_status', 1)->where('role_id', 4)->where('school_id', Auth::user()->school_id)->where('user_id', '=', Auth::user()->id)->first();
            $section = "";
            if (isset($teacher)) {
                $teacher_id = $teacher->id;
                $class_teacher = SmClassTeacher::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->where('teacher_id', $teacher_id)->first();
                if (isset($class_teacher)) {

                    $assign_class_teacher_id = $class_teacher->assign_class_teacher_id;
                    $assign_class_teacher = SmAssignClassTeacher::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->where('id', '=', $assign_class_teacher_id)->first();
                    $classes = SmClass::where('id', '=', $assign_class_teacher->class_id)->get();
                    foreach($classes as $class) {
                        $class_id = $class->id;
                    }
                    $section = SmSection::where('id', '=', $assign_class_teacher->section_id)->first();
                    $students = SmStudent::where('section_id',$section->id)
                        ->where('class_id', $class_id)
                        ->where('active_status', 1)
                        ->where('academic_id', getAcademicId())
                        ->where('school_id', Auth::user()->school_id)
                        ->get();
                }
            } else {
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $section = "";
                $students = SmStudent::where('active_status', 1)
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->get();
            }
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['exams'] = $exams->toArray();
                $data['classes'] = $classes->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.reports.mark_sheet_report_student', compact('exams', 'classes', 'section','students'));
        } catch (\Exception $e) {
            dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    //marks     SheetReport     Student     Search

    public function markSheetReportStudentSearch(Request $request)
    {
        $input = $request->all();

        $input['exam_id'] = $request->exam;
        $input['class_id'] = $request->class;
        $input['section_id'] = $request->section;
        $input['student_id'] = $request->student;

        if ($request->generate_results !== "" || $request->print_results !== "") {
            $validator = Validator::make($input, [
                'exam' => 'required',
                'class' => 'required',
                'section' => 'required'
            ]);
        } else {
            $validator = Validator::make($input, [
                'exam' => 'required',
                'class' => 'required',
                'section' => 'required',
                'student' => 'required'
            ]);
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->generate_results !== "") {
            try {
                $exam_term_id = $request->exam;
                $class_id = $request->class;
                $section_id = $request->section;
                $student_id = $request->student;

                $optional_subject_setup = SmClassOptionalSubject::where('class_id', '=', $request->class)->first();
                // return $optional_subject_setup;
                if ($request->student == "") {
                    $eligible_subjects = SmAssignSubject::where('class_id', $class_id)->where('section_id', $section_id)->where('academic_id', getAcademicId())->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
                    $eligible_students = SmStudent::where('class_id', $class_id)->where('section_id', $section_id)->where('academic_id', getAcademicId())->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();

                    //COURSE WORK CHECK
                    $pass_config = SmPassDefinition::where('school_id',Auth::user()->school_id)->where('academic_id',getAcademicId())->get();
                    $course_work_percent = $exam_percent = 0;
                    foreach ($pass_config as $pass_configuration){
                        $streams=json_decode($pass_configuration->streams);
                        if(in_array($class_id,$streams)){
                            $course_work_percent = $pass_configuration->course_work_percent;
                            $exam_percent = $pass_configuration->exam_percent;
                            $coursework_type = $pass_configuration->coursework_type;
                        }
                    }
                    $exam_course_work_exam_id = SmExamType::where('active_status', 1)
                        ->where('academic_id', getAcademicId())
                        ->where('id', $exam_term_id)
                        ->where('school_id', Auth::user()->school_id)->first();

                    $quarter = $exam_course_work_exam_id->quarter;
                    $exam_course_work = [];
                    if($coursework_type == "tests") {
                        $exam_course_work = SmExamType::where('active_status', 1)
                            ->where('academic_id', getAcademicId())
                            ->where('is_examination', '=', "0")
                            ->where('quarter', $quarter)
                            ->where('school_id', Auth::user()->school_id)->get();
                    }

                    //END COURSE WORK CHECK
                    foreach ($eligible_students as $SingleStudent) {

                        foreach ($eligible_subjects as $subject) {
                            $getMark = SmResultStore::where([
                                ['exam_type_id', $exam_term_id],
                                ['class_id', $class_id],
                                ['section_id', $section_id],
                                ['student_id', $SingleStudent->id],
                                ['subject_id', $subject->subject_id]
                            ])->first();

                            if(count($exam_course_work)>0) {
                                $total_Course_work = 0;
                                $total_tests = 0;
                                foreach ($exam_course_work as $tests) {
                                    $test_exam_id = $tests->id;
                                    $test_title = $tests->title;
                                    //Create a list of all the coursework marks

                                    $exam_mark_sheet = SmResultStore::where([['class_id', $class_id], ['exam_type_id', $test_exam_id], ['section_id', $section_id], ['student_id', $SingleStudent->id]])
                                        ->where('subject_id', $subject->subject_id)
                                        ->join('sm_subjects', 'sm_subjects.id', "=", 'sm_result_stores.subject_id')
                                        ->orderBy('sm_subjects.subject_code')
                                        ->where('sm_result_stores.school_id', Auth::user()->school_id)->first();

                                    if (isset($exam_mark_sheet)) {
                                        $total_Course_work += $exam_mark_sheet->total_marks;
                                        $total_tests++;
                                    }
                                }

                                if ($total_tests > 0) {
                                    $total_course_work_mark = round($total_Course_work / $total_tests, 0);
                                    //Get the exam_mark
                                    $exam_mark = round(($getMark->total_marks * $exam_percent) / 100, 0);
                                    $course_work = round(($total_course_work_mark * $course_work_percent) / 100, 0);
                                    $final_mark = $exam_mark + $course_work;

                                    //GET The Symbol of this Mark (total_gpa_grade)
                                    $grade_gpa = DB::table('sm_marks_grades')->where('percent_from', '<=', $final_mark)->where('percent_upto', '>=', $final_mark)->where('academic_id', getAcademicId())->get();
                                    $grade_symbol = "";
                                    foreach($grade_gpa as $grade) {
                                        $allstreams = json_decode($grade->streams);
                                        if (in_array($class_id, $allstreams)) {
                                            $grade_symbol = $grade->grade_name;
                                        }
                                    }

                                    $marks_update = SmResultStore::find($getMark->id);
                                    $marks_update->final_mark = $final_mark;
                                    $marks_update->exam_mark = $exam_mark;
                                    $marks_update->coursework_mark = $course_work;
                                    $marks_update->total_gpa_grade = $grade_symbol;
                                    $marks_update->save();
                                }else if(isset($getMark)){
                                    $grade_gpa = DB::table('sm_marks_grades')->where('percent_from', '<=', $getMark->total_marks)->where('percent_upto', '>=', $getMark->total_marks)->where('academic_id', getAcademicId())->get();
                                    $grade_symbol = "";
                                    foreach($grade_gpa as $grade) {
                                        $allstreams = json_decode($grade->streams);
                                        if (in_array($class_id, $allstreams)) {
                                            $grade_symbol = $grade->grade_name;
                                        }
                                    }
                                    $marks_update = SmResultStore::find($getMark->id);
                                    $marks_update->final_mark = $getMark->total_marks;
                                    $marks_update->total_gpa_grade = $grade_symbol;
                                    $marks_update->save();
                                }
                            }else if(isset($getMark)){
                                $grade_gpa = DB::table('sm_marks_grades')->where('percent_from', '<=', $getMark->total_marks)->where('percent_upto', '>=', $getMark->total_marks)->where('academic_id', getAcademicId())->get();
                                $grade_symbol = "";
                                foreach($grade_gpa as $grade) {
                                    $allstreams = json_decode($grade->streams);
                                    if (in_array($class_id, $allstreams)) {
                                        $grade_symbol = $grade->grade_name;
                                    }
                                }
                                $marks_update = SmResultStore::find($getMark->id);
                                $marks_update->total_gpa_grade = $grade_symbol;
                                $marks_update->save();
                            }

                            if ($getMark == "") {
                                $getMark = 0;
                                //return $getMark;
                                // return redirect()->back()->with('message-danger', 'Please register marks for all students.!');
                            }
                        }
                    }
                }
                else {

                    $eligible_subjects = SmAssignSubject::where('class_id', $class_id)->where('section_id', $section_id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

                    foreach ($eligible_subjects as $subject) {


                        $getMark = SmResultStore::where([
                            ['exam_type_id', $exam_term_id],
                            ['class_id', $class_id],
                            ['section_id', $section_id],
                            ['student_id', $request->student],
                            ['subject_id', $subject->subject_id]
                        ])->first();


                        if ($getMark == "") {
                            $getMark = "0";
                            //return $getMark;
                            // return redirect()->back()->with('message-danger', 'Please register marks for all students.!');
                        }
                    }
                }


                if ($request->student != '') {
                    $marks = SmMarkStore::where([
                        ['exam_term_id', $request->exam],
                        ['class_id', $request->class],
                        ['section_id', $request->section],
                        ['student_id', $request->student]
                    ])->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                    $students = SmStudent::where([
                        ['class_id', $request->class],
                        ['section_id', $request->section],
                        ['id', $request->student]
                    ])->where('academic_id', getAcademicId())->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();

                    $subjects = SmAssignSubject::where([
                        ['class_id', $request->class],
                        ['section_id', $request->section]
                    ])->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                    foreach ($subjects as $sub) {
                        $subject_list_name[] = $sub->subject->subject_name;
                    }
                    $grade_chart = SmMarksGrade::select('grade_name', 'gpa', 'percent_from as start', 'percent_upto as end', 'description','streams')->where('active_status', 1)->where('academic_id', getAcademicId())->get()->toArray();

                    $single_student = SmStudent::find($request->student);
                    $single_exam_term = SmExamType::find($request->exam);

                    $tabulation_details['student_name'] = $single_student->full_name;
                    $tabulation_details['student_roll'] = $single_student->roll_no;
                    $tabulation_details['student_admission_no'] = $single_student->admission_no;
                    $tabulation_details['student_class'] = $single_student->ClassName->class_name;
                    $tabulation_details['student_section'] = $single_student->section->section_name;
                    $tabulation_details['exam_term'] = $single_exam_term->title;
                    $tabulation_details['subject_list'] = $subject_list_name;
                    $tabulation_details['grade_chart'] = $grade_chart;
                } else {
                    $marks = SmMarkStore::where([
                        ['exam_term_id', $request->exam],
                        ['class_id', $request->class],
                        ['section_id', $request->section]
                    ])->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                    $students = SmStudent::where([
                        ['class_id', $request->class],
                        ['section_id', $request->section]
                    ])->where('academic_id', getAcademicId())->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
                }


                $exam_types = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $single_class = SmClass::find($request->class);
                $single_section = SmSection::find($request->section);
                $subjects = SmAssignSubject::where([
                    ['class_id', $request->class],
                    ['section_id', $request->section]
                ])->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();


                foreach ($subjects as $sub) {
                    $subject_list_name[] = $sub->subject->subject_name;
                }
                $grade_chart = SmMarksGrade::select('grade_name', 'gpa', 'percent_from as start', 'percent_upto as end', 'description','streams')->where('active_status', 1)->where('academic_id', getAcademicId())->get()->toArray();

                $single_exam_term = SmExamType::find($request->exam);

                $tabulation_details['student_class'] = $single_class->class_name;
                $tabulation_details['student_section'] = $single_section->section_name;
                $tabulation_details['exam_term'] = $single_exam_term->title;
                $tabulation_details['subject_list'] = $subject_list_name;
                $tabulation_details['grade_chart'] = $grade_chart;

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    $data = [];
                    $data['exam_types'] = $exam_types->toArray();
                    $data['classes'] = $classes->toArray();
                    $data['marks'] = $marks->toArray();
                    $data['subjects'] = $subjects->toArray();
                    $data['exam_term_id'] = $exam_term_id;
                    $data['class_id'] = $class_id;
                    $data['section_id'] = $section_id;
                    $data['students'] = $students->toArray();
                    return ApiBaseMethod::sendResponse($data, null);
                }
                $get_class = SmClass::where('active_status', 1)
                    ->where('id', $request->class)
                    ->first();
                $get_section = SmSection::where('active_status', 1)
                    ->where('id', $request->section)
                    ->first();
                $class_name = $get_class->class_name;
                $section_name = $get_section->section_name;

                $iid = time();
                $subject_ids = [];
                $subject_strings = '';
                $marks_string = '';

                foreach ($students as $SingleStudent) {
                    foreach ($eligible_subjects as $subject) {
                        $subject_ids[] = $subject->subject_id;

                        $getMark = SmResultStore::where([
                            ['exam_type_id', $request->exam],
                            ['class_id', $request->class],
                            ['section_id', $request->section],
                            ['student_id', $SingleStudent->id],
                            ['subject_id', $subject->subject_id]
                        ])->first();

                    }
                    $is_existing_data = SmTemporaryMeritlist::where([['admission_no', $SingleStudent->admission_no], ['class_id', $request->class], ['section_id', $request->section], ['exam_id', $request->exam]])->first();
                    if (empty($is_existing_data)) {
                        $insert_results = new SmTemporaryMeritlist();

                    } else {
                        $insert_results = SmTemporaryMeritlist::find($is_existing_data->id);

                    }

                    $full_name = $SingleStudent->full_name;                 //get name
                    $admission_no = $SingleStudent->admission_no;           //get admission no
                    $student_id = $SingleStudent->id;           //get admission no

                    $is_absent = SmResultStore::where([
                        ['exam_type_id', $request->exam],
                        ['class_id', $request->class],
                        ['section_id', $request->section],
                        ['is_absent', 1],
                        ['student_id', $SingleStudent->id]
                    ])->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

                    $total_marks = SmResultStore::where([
                        ['exam_type_id', $request->exam],
                        ['class_id', $request->class],
                        ['section_id', $request->section],
                        ['student_id', $SingleStudent->id]
                    ])->sum('total_marks');

                    $results = SmResultStore::where([
                        ['exam_type_id', $request->exam],
                        ['class_id', $request->class],
                        ['section_id', $request->section],
                        ['student_id', $SingleStudent->id]
                    ])->where('academic_id', getAcademicId())->where('total_marks', '>', 0)->where('school_id', Auth::user()->school_id)->get();

                    if ($total_marks > 0) {
                        $number_of_subjects = $results->count();
                        if ($number_of_subjects == 0) {
                            $number_of_subjects = 1;
                        }
                    } else {
                        $number_of_subjects = 1;
                    }

                    $sum_of_mark = ($total_marks == 0) ? 0 : $total_marks;
                    $average_mark = ($total_marks == 0) ? 0 : round($total_marks / $number_of_subjects, 1); //get average number
                    $is_absent = (count($is_absent) > 0) ? 1 : 0;         //get is absent ? 1=Absent, 0=Present

                    $studentResult = averageResult($average_mark);


                    $insert_results->student_name = $full_name;
                    $insert_results->admission_no = $admission_no;
                    $insert_results->subjects_string = "";
                    $insert_results->marks_string = "";
                    $insert_results->total_marks = $sum_of_mark;
                    $insert_results->average_mark = $average_mark;
                    $insert_results->gpa_point = "";
                    $insert_results->iid = $iid;
                    $insert_results->student_id = $student_id;

                    $insert_results->result = $studentResult;
                    $insert_results->section_id = $request->section;
                    $insert_results->class_id = $request->class;
                    $insert_results->exam_id = $request->exam;
                    $insert_results->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                    $insert_results->school_id = Auth::user()->school_id;
                    $insert_results->academic_id = getAcademicId();
                    $insert_results->save();

                    $subject_strings = "";
                    $marks_string = "";
                }

                /*SET NEW POSITION*/
                $first_data = SmTemporaryMeritlist::where('iid', $iid)->first();
                $subjectlist = explode(',', $first_data->subjects_string);
                $allresult_data = SmTemporaryMeritlist::where('iid', $iid)->orderBy('total_marks', 'desc')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $merit_serial = 1;
                foreach ($allresult_data as $row) {
                    $D = SmTemporaryMeritlist::where('iid', $iid)->where('id', $row->id)->first();
                    $D->merit_order = $merit_serial++;
                    $D->save();
                }

                /*Stream Positions*/
                $stream_students = SmTemporaryMeritlist::where([
                    ['class_id', $request->class]
                ])->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)
                    ->where('exam_id', $request->exam)
                    ->orderBy('average_mark', 'desc')->get();
                $pos = 1;

                foreach ($stream_students as $SingleStudent) {
                    $is_existing_data = SmStreamResult::where([['student_id', $SingleStudent->student_id], ['class_id', $request->class], ['exam_id', $request->exam]])->first();
                    $is_existing_result = SmTemporaryMeritlist::where([['student_id', $SingleStudent->student_id], ['class_id', $request->class], ['exam_id', $request->exam]])->first();

                    $student = SmStudent::where([
                        ['id', $SingleStudent->student_id]
                    ])->where('academic_id', getAcademicId())->where('active_status', 1)->where('school_id', Auth::user()->school_id)->first();

                    if (isset($student)) {
                        $update_stream_position = SmTemporaryMeritlist::find($is_existing_result->id);
                        if (empty($is_existing_data)) {
                            $insert_stream_results = new SmStreamResult();
                        } else {
                            $insert_stream_results = SmStreamResult::find($is_existing_data->id);
                        }
                        $position = $pos++;
                        $insert_stream_results->stream_position = $position;
                        $update_stream_position->stream_position = $position;
                        $update_stream_position->section_id = $student->section_id;
                        $update_stream_position->class_id = $student->class_id;
                        $insert_stream_results->section_id = $student->section_id;
                        $insert_stream_results->class_id = $student->class_id;
                        $insert_stream_results->exam_id = $request->exam;
                        $insert_stream_results->created_at = YearCheck::getYear() . '-' . date('m-d H:i:s');
                        $insert_stream_results->school_id = Auth::user()->school_id;
                        $insert_stream_results->academic_id = getAcademicId();
                        $insert_stream_results->average = $SingleStudent->average_mark;
                        $insert_stream_results->student_id = $SingleStudent->student_id;
                        $insert_stream_results->save();
                        $update_stream_position->save();
                    }
                }


                /*END Stream Positions*/
                $allresult_data = SmTemporaryMeritlist::orderBy('total_marks', 'desc')->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                if ($allresult_data) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                }
            }
            catch (\Exception $e) {
                dd($e);
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }
        else if ($request->print_results !== "") {
            try {

                $class_id = $input['class_id'];
                $section_id = $input['section_id'];
                $exam_id = $input['exam_id'];

                $exams = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $exam_types = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

                $students = SmStudent::where([
                    ['class_id', $class_id],
                    ['section_id', $section_id]
                ])->where('academic_id', getAcademicId())->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();

                $students_in_stream = SmStreamResult::where([
                    ['class_id', $class_id]
                ])->where('academic_id', getAcademicId())->where('exam_id', $exam_id)->where('school_id', Auth::user()->school_id)->groupBy('student_id')->get()->count();

                $student_results = [];
                $result_count = $is_result_available = 0;

                $exam_details = SmExamType::where('active_status', 1)->find($exam_id);
                $optional_subject = '';

                $results_config = SmResultsConfiguration::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

                $principal_designation = SmDesignation::where('title', "Principal")->where('school_id', Auth::user()->school_id)->first();
                $principal = SmStaff::where('designation_id', $principal_designation->id)->where('school_id', Auth::user()->school_id)->first();

                $assign_class_teacher = SmAssignClassTeacher::query();
                $assign_class_teacher->where('academic_id', getAcademicId())->where('active_status', 1);
                if ($section_id != "") {
                    $assign_class_teacher->where('section_id', $section_id);
                }
                $assign_class_teacher->where('class_id', $class_id);
                $assign_class_teacher = $assign_class_teacher->first();

                if (!isset($assign_class_teacher)) {
                    Toastr::error('Operation Failed! No class teacher allocated, please assign class teacher first.', 'Failed');
                    return redirect()->back();
                }

                if ($assign_class_teacher != "") {
                    $assign_class_teachers = $assign_class_teacher->classTeachers->first();
                } else {
                    $assign_class_teachers = '';
                }
                $section = SmSection::where('active_status', 1)->where('id', $section_id)->first();
                $class_name = SmClass::find($class_id);
                $exam_type_id = $exam_id;
                $subjects = SmAssignSubject::where([['class_id', $class_id], ['section_id', $section_id]])->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

                foreach ($students as $student_detail) {

                    $student_id = $student_detail->id;
                    $student_detail = $studentDetails = SmStudent::where([['class_id', $class_id], ['section_id', $section_id]])->where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->where('id', $student_id)->first();

                    $total_marks = SmResultStore::where([
                        ['exam_type_id', $exam_type_id],
                        ['class_id', $class_id],
                        ['section_id', $section_id],
                        ['student_id', $student_id]
                    ])->sum('total_marks');


                    $results = SmResultStore::where([
                        ['exam_type_id', $exam_type_id],
                        ['class_id', $class_id],
                        ['section_id', $section_id],
                        ['student_id', $student_id]
                    ])->where('sm_result_stores.academic_id', getAcademicId())
                        ->join('sm_subjects','sm_subjects.id','=','sm_result_stores.subject_id')
                        ->orderBy('sm_subjects.subject_code')
                        ->where('total_marks', '>', 0)
                        ->where('sm_result_stores.school_id', Auth::user()->school_id)->get();

                    //Get Corresponding Coursework

                    $pass_config = SmPassDefinition::where('school_id',Auth::user()->school_id)->where('academic_id',getAcademicId())->get();
                    $pass_mark = $number_of_subjects = $pass_average = 0;
                    foreach ($pass_config as $pass_configuration){
                        $streams=json_decode($pass_configuration->streams);
                        if(in_array($class_id,$streams)){
                            $pass_mark = $pass_configuration->pass_mark;
                        }
                    }
                    //Get number of passed subjects
                    $passed_subjects = "0";
                    $student_result = "";

                    $exam_course_work_exam_id = SmExamType::where('active_status', 1)
                        ->where('academic_id', getAcademicId())
                        ->where('id', $exam_type_id)
                        ->where('school_id', Auth::user()->school_id)->first();

                    $quarter = $exam_course_work_exam_id->quarter;
                    $coursework_type = "";
                    $count_marks = $course_work_counts = 0;
                    $exam_course_work = [];

                    $pass_config = SmPassDefinition::where('school_id',Auth::user()->school_id)->where('academic_id',getAcademicId())->get();
                    $course_work_percent = $exam_percent = 0;
                    foreach ($pass_config as $pass_configuration){
                        $streams=json_decode($pass_configuration->streams);
                        if(in_array($class_id,$streams)){
                            $course_work_percent = $pass_configuration->course_work_percent;
                            $exam_percent = $pass_configuration->exam_percent;
                            $coursework_type = $pass_configuration->coursework_type;
                        }
                    }
                    $course_work_mark = $course_work = [];
                    $count_marks = $course_work_counts = 0;
                    $overall_marks = $overall_average = 0;

                    $number_of_passed_subjects = 0;
                    if($coursework_type == "tests"){
                        $exam_course_work = SmExamType::where('active_status', 1)
                            ->where('academic_id', getAcademicId())
                            ->where('is_examination', '=', "0")
                            ->where('quarter', $quarter)
                            ->where('school_id', Auth::user()->school_id)->get();

                        //Foreach course work test, retrieve marks from marks store for that test

                        $count_marks = $course_work_counts = 0;
                        if(count($exam_course_work)==0){
                            foreach ($results as $marks) {

                                $course_work_mark[$count_marks] = [
                                    'coursework_marks' => [],
                                    'mark_sheet' => [
                                        "id" => $marks->id,
                                        "student_roll_no" => $marks->student_roll_no,
                                        "student_addmission_no" => $marks->student_addmission_no,
                                        "is_absent" => $marks->is_absent,
                                        "total_marks" => $marks->total_marks,
                                        "total_gpa_point" => $marks->total_gpa_point,
                                        "total_gpa_grade" => $marks->total_gpa_grade,
                                        "subject_name" => $marks->subject_name,
                                        "subject_code" => $marks->subject_code,
                                        "teacher_remarks" => $marks->teacher_remarks,
                                        "created_at" => $marks->created_at,
                                        "updated_at" => $marks->updated_at,
                                        "exam_type_id" => $marks->exam_type_id,
                                        "subject_id" => $marks->subject_id,
                                        "exam_setup_id" => $marks->exam_setup_id,
                                        "student_id" => $marks->student_id,
                                        "class_id" => $marks->class_id,
                                        "section_id" => $marks->section_id,
                                        "created_by" => $marks->created_by,
                                        "updated_by" => $marks->updated_by,
                                        "school_id" => $marks->school_id,
                                        "academic_id" => $marks->academic_id
                                    ]
                                ];
                                $course_work_counts++;
                                $count_marks++;
                            }
                        }
                        else {
                            $course_work_counts = 0;//Cater for more than 1 test in the future
                            $count_marks = 0;
                            foreach ($results as $marks) {
                                $total_Course_work=0;
                                $total_tests=0;
                                foreach ($exam_course_work as $tests) {
                                    $test_exam_id = $tests->id;
                                    $test_title = $tests->title;
                                    //Create a list of all the coursework marks

                                    $exam_mark_sheet = SmResultStore::where([['class_id', $class_id], ['exam_type_id', $test_exam_id], ['section_id', $section_id], ['student_id', $student_id]])
                                        ->where('subject_id',  $marks->subject_id)
                                        ->join('sm_subjects', 'sm_subjects.id', "=", 'sm_result_stores.subject_id')
                                        ->orderBy('sm_subjects.subject_code')
                                        ->where('sm_result_stores.school_id', Auth::user()->school_id)->first();

                                    if(isset($exam_mark_sheet)){
                                        $total_Course_work+=$exam_mark_sheet->total_marks;
                                        $total_tests++;
                                    }
                                }
                                if($total_tests>0){
                                    $total_course_work_mark = round($total_Course_work/ $total_tests,0);
                                }
                                //Get the exam_mark
                                $exam_mark = round(($marks->total_marks * $exam_percent) / 100, 0);


                                if(isset($exam_mark_sheet)) {
                                    $course_work = round(($total_course_work_mark * $course_work_percent) / 100, 0);
                                    $final_mark = $exam_mark+$course_work;
                                    $overall_marks+=$final_mark;

                                    if($final_mark>=$pass_mark) {

                                        $passed_subjects += 1;
                                    }

                                    $course_work = [
                                        'test_id' => $test_exam_id,
                                        'test_title' => $test_title,
                                        'subject_id' => $marks->subject_id,
                                        'subject_name' => $exam_mark_sheet->subject_name,
                                        'subject_code' => $exam_mark_sheet->subject_code,
                                        'course_work_percent' => $course_work_percent,
                                        'course_work_mark' => $course_work,
                                        'exam_type_id' => (int)$exam_type_id,
                                        'exam_percent' => $exam_percent,
                                        'exam_mark' => $exam_mark,
                                        'final_mark' => $final_mark
                                    ];
                                    $course_work_mark[$count_marks] = [
                                        'coursework_marks' => $course_work,
                                        'mark_sheet' => [
                                            "id" => $marks->id,
                                            "student_roll_no" => $marks->student_roll_no,
                                            "student_addmission_no" => $marks->student_addmission_no,
                                            "is_absent" => $marks->is_absent,
                                            "total_marks" => $marks->total_marks,
                                            "total_gpa_point" => $marks->total_gpa_point,
                                            "total_gpa_grade" => $marks->total_gpa_grade,
                                            "subject_name" => $marks->subject_name,
                                            "subject_code" => $marks->subject_code,
                                            "teacher_remarks" => $marks->teacher_remarks,
                                            "created_at" => $marks->created_at,
                                            "updated_at" => $marks->updated_at,
                                            "exam_type_id" => $marks->exam_type_id,
                                            "subject_id" => $marks->subject_id,
                                            "exam_setup_id" => $marks->exam_setup_id,
                                            "student_id" => $marks->student_id,
                                            "class_id" => $marks->class_id,
                                            "section_id" => $marks->section_id,
                                            "created_by" => $marks->created_by,
                                            "updated_by" => $marks->updated_by,
                                            "school_id" => $marks->school_id,
                                            "academic_id" => $marks->academic_id
                                        ]
                                    ];
                                    $course_work_counts++;
                                    $count_marks++;
                                }
                                else{
                                    $course_work = [
                                        'test_id' => $test_exam_id,
                                        'test_title' => $test_title,
                                        'subject_id' => $marks->subject_id,
                                        'subject_name' => $marks->subject_name,
                                        'course_work_percent' => $course_work_percent,
                                        'course_work_mark' => "-",
                                        'exam_type_id' => (int)$test_exam_id,
                                        'exam_percent' => $exam_percent,
                                        'exam_mark' => "-",
                                        'final_mark' => $marks->total_marks
                                    ];
                                    $course_work_mark[$count_marks] = [
                                        'coursework_marks' => $course_work,
                                        'mark_sheet' => [
                                            "id" => $marks->id,
                                            "student_roll_no" => $marks->student_roll_no,
                                            "student_addmission_no" => $marks->student_addmission_no,
                                            "is_absent" => $marks->is_absent,
                                            "total_marks" => $marks->total_marks,
                                            "total_gpa_point" => $marks->total_gpa_point,
                                            "total_gpa_grade" => $marks->total_gpa_grade,
                                            "subject_name" => $marks->subject_name,
                                            "subject_code" => $marks->subject_code,
                                            "teacher_remarks" => $marks->teacher_remarks,
                                            "created_at" => $marks->created_at,
                                            "updated_at" => $marks->updated_at,
                                            "exam_type_id" => $marks->exam_type_id,
                                            "subject_id" => $marks->subject_id,
                                            "exam_setup_id" => $marks->exam_setup_id,
                                            "student_id" => $marks->student_id,
                                            "class_id" => $marks->class_id,
                                            "section_id" => $marks->section_id,
                                            "created_by" => $marks->created_by,
                                            "updated_by" => $marks->updated_by,
                                            "school_id" => $marks->school_id,
                                            "academic_id" => $marks->academic_id
                                        ]
                                    ];
                                    $course_work_counts++;
                                    $count_marks++;
                                }
                            }
                        }
                        /* PASS DEFINITION */
                        $pass_config = SmPassDefinition::where('school_id',Auth::user()->school_id)->where('academic_id',getAcademicId())->get();
                        $pass_mark = $number_of_subjects = $pass_average = 0;
                        $compulsory_subjects = [];
                        foreach ($pass_config as $pass_configuration){
                            $streams=json_decode($pass_configuration->streams);
                            if(in_array($class_id,$streams)){
                                $pass_mark = $pass_configuration->pass_mark;
                                $grade_table = $pass_configuration->grade_table;
                                $student_position = $pass_configuration->student_position;
                                $number_of_subjects = $pass_configuration->number_of_subjects;
                                $pass_average = $pass_configuration->pass_average;
                                $course_work_percent = $pass_configuration->course_work_percent;
                                $exam_percent = $pass_configuration->exam_percent;
                                $compulsory_subjects = json_decode($pass_configuration->compulsory_subjects);
                            }
                        }

                        $must_pass_subjects = SmResultStore::where([
                            ['exam_type_id', $exam_type_id],
                            ['class_id', $class_id],
                            ['section_id', $section_id],
                            ['student_id', $student_id]
                        ])->whereIn('subject_id',$compulsory_subjects)->where('academic_id', getAcademicId())->get();

                        $sum_of_compulsory_subjects = $average_of_compulsory_subjects = 0;
                        //Compulsory subjects to pass are checked here.
                        $count_of_copulsory_subject = 0;
                        foreach($must_pass_subjects as $compul_subjects){
                            $total_Course_work=0;
                            $total_tests=0;
                            foreach ($exam_course_work as $tests) {
                                $test_exam_id = $tests->id;
                                $test_title = $tests->title;
                                //Create a list of all the coursework marks
                                $exam_mark_sheet = SmResultStore::where([['class_id', $class_id], ['exam_type_id', $test_exam_id], ['section_id', $section_id], ['student_id', $student_id]])
                                    ->where('subject_id',  $compul_subjects->subject_id)
                                    ->join('sm_subjects', 'sm_subjects.id', "=", 'sm_result_stores.subject_id')
                                    ->orderBy('sm_subjects.subject_code')
                                    ->where('sm_result_stores.school_id', Auth::user()->school_id)->first();

                                if(isset($exam_mark_sheet)){
                                    $total_Course_work+=$exam_mark_sheet->total_marks;
                                    $total_tests++;
                                }

                            }
                            $avarage_total_course_work=0;
                            if($total_tests>0){
                                $total_course_work_mark = round($total_Course_work/ $total_tests,0);
                                $avarage_total_course_work = round(($total_course_work_mark * $course_work_percent)/100, 0);


                            }

                            //Get the exam_mark
                            $total_exam_mark = round(($compul_subjects->total_marks * $exam_percent) / 100, 0);


                            $final_mark=$avarage_total_course_work+$total_exam_mark;
                            $count_of_copulsory_subject++;
                        }

                        $sum_of_compulsory_subjects += $final_mark;

                        $average_of_compulsory_subjects = $sum_of_compulsory_subjects / $count_of_copulsory_subject;

                        if($count_marks>0){
                        $overall_average=round($overall_marks/$count_marks,1);}
                        $student_result = averageResult($overall_average);

                        $studentResult = averageResult($overall_average);

                        $final_result = "";


                        foreach($studentResult as $performance){
                            $allstreams = json_decode($performance->streams);
                            if(in_array($class_id,$allstreams)){

                                if($passed_subjects>=$number_of_subjects && $overall_average>=$pass_average && $average_of_compulsory_subjects>=$pass_mark){
                                    $final_result = $performance->result_name;

                                }
                                else{
                                    $studentResult=averagePassResult($pass_mark);

                                    foreach($studentResult as $performance){
                                        $allstreams = json_decode($performance->streams);
                                        if(in_array($class_id,$allstreams)){
                                            $final_result=$performance->result_name;

                                        }
                                    }
                                }

                            }
                        }
                    }
                    else if($coursework_type == "exams" && $quarter == "FOUR"){
                        //For this onw, only make the coursework when it's 4th Quarter
                        $exam_course_work = SmExamType::where('active_status', 1)
                            ->where('academic_id', getAcademicId())
                            ->where('is_examination', '=', "1")
                            ->where('quarter', '<>', "FOUR")
                            ->where('quarter', $quarter)
                            ->where('school_id', Auth::user()->school_id)->get();

                        //Foreach course work test, retrieve marks from marks store for that test

                        $count_marks = $course_work_counts = 0;
                        $overall_marks = $overall_average = 0;
                        if(count($exam_course_work)==0){
                            foreach ($results as $marks) {
                                $course_work_mark[$count_marks] = [
                                    'coursework_marks' => [],
                                    'mark_sheet' => [
                                        "id" => $marks->id,
                                        "student_roll_no" => $marks->student_roll_no,
                                        "student_addmission_no" => $marks->student_addmission_no,
                                        "is_absent" => $marks->is_absent,
                                        "total_marks" => $marks->total_marks,
                                        "total_gpa_point" => $marks->total_gpa_point,
                                        "total_gpa_grade" => $marks->total_gpa_grade,
                                        "subject_name" => $marks->subject_name,
                                        "subject_code" => $marks->subject_code,
                                        "teacher_remarks" => $marks->teacher_remarks,
                                        "created_at" => $marks->created_at,
                                        "updated_at" => $marks->updated_at,
                                        "exam_type_id" => $marks->exam_type_id,
                                        "subject_id" => $marks->subject_id,
                                        "exam_setup_id" => $marks->exam_setup_id,
                                        "student_id" => $marks->student_id,
                                        "class_id" => $marks->class_id,
                                        "section_id" => $marks->section_id,
                                        "created_by" => $marks->created_by,
                                        "updated_by" => $marks->updated_by,
                                        "school_id" => $marks->school_id,
                                        "academic_id" => $marks->academic_id
                                    ]
                                ];
                                $course_work_counts++;
                                $count_marks++;
                            }
                        }
                        else {
                            $course_work_counts = 0;//Cater for more than 1 test in the future
                            $count_marks = 0;
                            foreach ($results as $marks) {
                                $total_Course_work=0;
                                $total_tests=0;
                                foreach ($exam_course_work as $tests) {
                                    $test_exam_id = $tests->id;
                                    $test_title = $tests->title;
                                    //Create a list of all the coursework marks

                                    $exam_mark_sheet = SmResultStore::where([['class_id', $request->class], ['exam_type_id', $test_exam_id], ['section_id', $request->section], ['student_id', $request->student]])
                                        ->where('subject_id',  $marks->subject_id)
                                        ->join('sm_subjects', 'sm_subjects.id', "=", 'sm_result_stores.subject_id')
                                        ->orderBy('sm_subjects.subject_code')
                                        ->where('sm_result_stores.school_id', Auth::user()->school_id)->first();

                                    if(isset($exam_mark_sheet)){
                                        $total_Course_work+=$exam_mark_sheet->total_marks;
                                        $total_tests++;
                                    }
                                }
                                $total_course_work_mark = round($total_Course_work/ $total_tests,0);
                                //Get the exam_mark
                                $exam_mark = round(($marks->total_marks * $exam_percent) / 100, 0);

                                    if(isset($exam_mark_sheet)) {
                                        $course_work = [
                                            'test_id' => $test_exam_id,
                                            'test_title' => $test_title,
                                            'subject_id' => $marks->subject_id,
                                            'subject_name' => $exam_mark_sheet->subject_name,
                                            'course_work_percent' => $course_work_percent,
                                            'course_work_mark' => round(($total_course_work_mark * $course_work_percent) / 100, 0),
                                            'exam_type_id' => (int)$exam_type_id,
                                            'exam_percent' => $exam_percent,
                                            'exam_mark' => $exam_mark,
                                            'final_mark' => round(($total_course_work_mark * $course_work_percent) / 100, 0) + $exam_mark
                                        ];

                                        $course_work_mark[$count_marks] = [
                                            'coursework_marks' => $course_work,
                                            'mark_sheet' => [
                                                "id" => $marks->id,
                                                "student_roll_no" => $marks->student_roll_no,
                                                "student_addmission_no" => $marks->student_addmission_no,
                                                "is_absent" => $marks->is_absent,
                                                "total_marks" => $marks->total_marks,
                                                "total_gpa_point" => $marks->total_gpa_point,
                                                "total_gpa_grade" => $marks->total_gpa_grade,
                                                "subject_name" => $marks->subject_name,
                                                "subject_code" => $marks->subject_code,
                                                "teacher_remarks" => $marks->teacher_remarks,
                                                "created_at" => $marks->created_at,
                                                "updated_at" => $marks->updated_at,
                                                "exam_type_id" => $marks->exam_type_id,
                                                "subject_id" => $marks->subject_id,
                                                "exam_setup_id" => $marks->exam_setup_id,
                                                "student_id" => $marks->student_id,
                                                "class_id" => $marks->class_id,
                                                "section_id" => $marks->section_id,
                                                "created_by" => $marks->created_by,
                                                "updated_by" => $marks->updated_by,
                                                "school_id" => $marks->school_id,
                                                "academic_id" => $marks->academic_id
                                            ]
                                        ];
                                        $course_work_counts++;
                                        $count_marks++;
                                    }
                                    else{
                                        $course_work = [
                                            'test_id' => $test_exam_id,
                                            'test_title' => $test_title,
                                            'subject_id' => $marks->subject_id,
                                            'subject_name' => $marks->subject_name,
                                            'course_work_percent' => $course_work_percent,
                                            'course_work_mark' => "-",
                                            'exam_type_id' => (int)$request->exam,
                                            'exam_percent' => $exam_percent,
                                            'exam_mark' => "-",
                                            'final_mark' => $marks->total_marks
                                        ];
                                        $course_work_mark[$count_marks] = [
                                            'coursework_marks' => $course_work,
                                            'mark_sheet' => [
                                                "id" => $marks->id,
                                                "student_roll_no" => $marks->student_roll_no,
                                                "student_addmission_no" => $marks->student_addmission_no,
                                                "is_absent" => $marks->is_absent,
                                                "total_marks" => $marks->total_marks,
                                                "total_gpa_point" => $marks->total_gpa_point,
                                                "total_gpa_grade" => $marks->total_gpa_grade,
                                                "subject_name" => $marks->subject_name,
                                                "subject_code" => $marks->subject_code,
                                                "teacher_remarks" => $marks->teacher_remarks,
                                                "created_at" => $marks->created_at,
                                                "updated_at" => $marks->updated_at,
                                                "exam_type_id" => $marks->exam_type_id,
                                                "subject_id" => $marks->subject_id,
                                                "exam_setup_id" => $marks->exam_setup_id,
                                                "student_id" => $marks->student_id,
                                                "class_id" => $marks->class_id,
                                                "section_id" => $marks->section_id,
                                                "created_by" => $marks->created_by,
                                                "updated_by" => $marks->updated_by,
                                                "school_id" => $marks->school_id,
                                                "academic_id" => $marks->academic_id
                                            ]
                                        ];
                                        $course_work_counts++;
                                        $count_marks++;
                                    }
                                }
                            }
                            $overall_average=round($overall_marks/$count_marks,1);
                        }
                    else{
                        foreach ($results as $marks) {
                            $course_work_mark[$count_marks] = [
                                'coursework_marks' => [],
                                'mark_sheet' => [
                                    "id" => $marks->id,
                                    "student_roll_no" => $marks->student_roll_no,
                                    "student_addmission_no" => $marks->student_addmission_no,
                                    "is_absent" => $marks->is_absent,
                                    "total_marks" => $marks->total_marks,
                                    "total_gpa_point" => $marks->total_gpa_point,
                                    "total_gpa_grade" => $marks->total_gpa_grade,
                                    "subject_name" => $marks->subject_name,
                                    "subject_code" => $marks->subject_code,
                                    "teacher_remarks" => $marks->teacher_remarks,
                                    "created_at" => $marks->created_at,
                                    "updated_at" => $marks->updated_at,
                                    "exam_type_id" => $marks->exam_type_id,
                                    "subject_id" => $marks->subject_id,
                                    "exam_setup_id" => $marks->exam_setup_id,
                                    "student_id" => $marks->student_id,
                                    "class_id" => $marks->class_id,
                                    "section_id" => $marks->section_id,
                                    "created_by" => $marks->created_by,
                                    "updated_by" => $marks->updated_by,
                                    "school_id" => $marks->school_id,
                                    "academic_id" => $marks->academic_id
                                ]
                            ];
                            $course_work_counts++;
                            $count_marks++;
                        }
                    }
                    /* COURSE WORK COMPUTATION */


                    $results_data = DB::table('sm_temporary_meritlists')
                        ->where('class_id', $class_id)
                        ->where('section_id', $section_id)
                        ->where('exam_id', $exam_type_id)
                        ->where('student_id', $student_id)
                        ->where('academic_id', getAcademicId())
                        ->where('school_id', Auth::user()->school_id)->first();

                    if (!isset($results_data)) {
                        Toastr::error('Operation Failed! No results found, please generate reports first.', 'Failed');
                        return redirect()->back();
                    }
                    $position = $results_data->merit_order;

                    $stream_data = DB::table('sm_stream_results')
                        ->where('class_id', $class_id)
                        ->where('section_id', $section_id)
                        ->where('exam_id', $exam_type_id)
                        ->where('student_id', $student_id)
                        ->where('academic_id', getAcademicId())
                        ->where('school_id', Auth::user()->school_id)->first();

                    if (!isset($stream_data)) {
                        Toastr::error('Operation Failed! No results found, please generate reports first.', 'Failed');
                        return redirect()->back();
                    }

                    $position_stream = $stream_data->stream_position;

                    $sum_of_mark = ($total_marks == 0) ? 0 : $total_marks;
                    $average_mark = ($total_marks == 0) ? 0 : round($total_marks / $results->count(), 1);
                    if($coursework_type == "exams" && $quarter != " FOUR") {


                        /* PASS DEFINITION */
                        $pass_config = SmPassDefinition::where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->get();
                        $pass_mark = $number_of_subjects = $pass_average = 0;
                        $compulsory_subjects = [];
                        foreach ($pass_config as $pass_configuration) {
                            $streams = json_decode($pass_configuration->streams);
                            if (in_array($class_id, $streams)) {
                                $pass_mark = $pass_configuration->pass_mark;
                                $number_of_subjects = $pass_configuration->number_of_subjects;
                                $grade_table = $pass_configuration->grade_table;
                                $student_position = $pass_configuration->student_position;
                                $pass_average = $pass_configuration->pass_average;
                                $compulsory_subjects = json_decode($pass_configuration->compulsory_subjects);
                            }
                        }
                        //Get number of passed subjects
                        $number_of_passed_subjects = "N/A";
                        if (isset($pass_config)) {
                            $number_of_passed_subjects = SmResultStore::where([
                                ['exam_type_id', $exam_type_id],
                                ['class_id', $class_id],
                                ['section_id', $section_id],
                                ['student_id', $student_id]
                            ])->where('total_marks', '>=', $pass_mark)->where('academic_id', getAcademicId())->count();

                            $studentResult = averageResult($average_mark);

                            $final_result = "";

                            //Must Pass Subjects
                            $must_pass_subjects = SmResultStore::where([
                                ['exam_type_id', $exam_type_id],
                                ['class_id', $class_id],
                                ['section_id', $section_id],
                                ['student_id', $student_id]
                            ])->whereIn('subject_id', $compulsory_subjects)->where('academic_id', getAcademicId())->get();

                            $sum_of_compulsory_subjects = $average_of_compulsory_subjects = 0;
                            //Compulsory subjects to pass are checked here.
                            foreach ($must_pass_subjects as $compul_subjects) {
                                $sum_of_compulsory_subjects += $compul_subjects->total_marks;
                                $average_of_compulsory_subjects = $sum_of_compulsory_subjects / count($compulsory_subjects);
                            }
                            $final_result = "";
                            foreach ($studentResult as $performance) {
                                $allstreams = json_decode($performance->streams);
                                if (in_array($class_id, $allstreams)) {
                                    if ($number_of_passed_subjects >= $number_of_subjects && $average_mark >= $pass_average && $average_of_compulsory_subjects >= $pass_mark) {
                                        $final_result = $performance->result_name;
                                    } else {
                                        $studentResult = averagePassResult($pass_mark);

                                        foreach ($studentResult as $performance) {
                                            $allstreams = json_decode($performance->streams);
                                            if (in_array($class_id, $allstreams)) {
                                                $final_result = $performance->result_name;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        //Get Results based on configuration above

                    }
                    /**PASS DEFINITION */

                    $get_optional_subject = SmOptionalSubjectAssign::where('student_id', '=', $student_detail->id)->where('session_id', '=', $student_detail->session_id)->first();
                    if ($get_optional_subject != '') {
                        $optional_subject = $get_optional_subject->subject_id;
                    }
                    $optional_subject_setup = SmClassOptionalSubject::where('class_id', '=', $class_id)->first();
                    $is_result_available = SmResultStore::where([['class_id', $class_id], ['exam_type_id', $exam_id], ['section_id', $section_id], ['student_id', $student_id]])->where('academic_id', getAcademicId())->get();

                    $student_results[$result_count] = [
                        'position' => $position,
                        'stream_position' => $position_stream,
                        'full_name' => $student_detail->full_name,
                        'academic_year' => $student_detail->academicYear->year,
                        'academic_id' => $student_detail->academicYear->id,
                        'admission_id_number' => $student_detail->admission_id_number,
                        'admission_no' => $student_detail->admission_no,
                        'studentDetails' => $studentDetails,
                        'student_id' => $student_id,
                        'optional_subject' => $optional_subject,
                        'optional_subject_setup' => $optional_subject_setup,
                        'sum_of_mark' => $sum_of_mark,
                        'average_mark' => $average_mark,
                        'class_id' => $class_id,
                        'section_id' => $section_id,
                        'exam_id' => $exam_id,
                        'students_in_stream' => $students_in_stream,
                        'pass_mark' => $pass_mark,
                        'number_of_passed_subjects' => $number_of_passed_subjects,
                        'pass_average' => $pass_average,
                        'compulsory_subjects' => $compulsory_subjects,
                        'final_result' => $final_result,
                        'passed_subjects'=>$passed_subjects,
                        'student_result'=>$student_result,
                        'course_work_mark' =>$course_work_mark,
                        'course_work_percent'=>$course_work_percent,
                        'exam_percent'=>$exam_percent,
                        'exam_course_work'=>$exam_course_work,
                        'overall_marks'=>$overall_marks,
                        'overall_average'=>$overall_average,
                    ];
                    $result_count++;

                }

                if ($is_result_available->count() > 0) {

                    $course_work_percent = $exam_percent = 0;
                    foreach ($pass_config as $pass_configuration){
                        $streams=json_decode($pass_configuration->streams);
                        if(in_array($class_id,$streams)){
                            $course_work_percent = $pass_configuration->course_work_percent;
                            $exam_percent = $pass_configuration->exam_percent;
                            $coursework_type = $pass_configuration->coursework_type;
                        }
                    }

                    return view('backEnd.reports.mark_sheet_report_normal_print_all', [
                            'student_results' => $student_results,
                            'subjects' => $subjects,
                            'classes' => $classes,
                            'students' => $students,
                            'results_config' => $results_config,
                            'class' => $class_id,
                            'principal' => $principal,
                            'class_name' => $class_name,
                            'section' => $section,
                            'assign_class_teachers' => $assign_class_teachers,
                            'exams' => $exams,
                            'section_id' => $section_id,
                            'exam_type_id' => $exam_type_id,
                            'is_result_available' => $is_result_available,
                            'class_id' => $class_id,
                            'exam_details' => $exam_details,
                            'pass_mark' => $pass_mark,
                            'pass_average' => $pass_average,
                            'exam_percent' => $exam_percent,
                            'course_work_percent' =>$course_work_percent,
                            'compulsory_subjects' => $compulsory_subjects,
                            'grade_table_view'=>$grade_table,
                            'student_position'=>$student_position
                        ]
                    );


                }
            } catch (\Exception $e) {
                dd($e);
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }
        else {
            try {
                $exams = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

                $student_detail = $studentDetails = SmStudent::find($request->student);

                $subjects = $studentDetails->className->subjects->where('section_id', $request->section)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id);

                $section_id = $request->section;
                $class_id = $request->class;
                $exam_type_id = $request->exam;
                $student_id = $request->student;
                $exam_details = $exams->where('active_status', 1)->find($exam_type_id);

                $optional_subject = '';

                $get_optional_subject = SmOptionalSubjectAssign::where('student_id', '=', $student_detail->id)->where('session_id', '=', $student_detail->session_id)->first();
                if ($get_optional_subject != '') {
                    $optional_subject = $get_optional_subject->subject_id;
                }
                $optional_subject_setup = SmClassOptionalSubject::where('class_id', '=', $request->class)->first();

                $mark_sheet = SmResultStore::where([['class_id', $request->class], ['exam_type_id', $request->exam], ['section_id', $request->section], ['student_id', $request->student]])
                    ->whereIn('subject_id', $subjects->pluck('subject_id')->toArray())
                    ->join('sm_subjects','sm_subjects.id','=','sm_result_stores.subject_id')
                    ->orderBy('sm_subjects.subject_code')
                    ->where('sm_subjects.school_id', Auth::user()->school_id)->get();

                //Get Corresponding Coursework

                $exam_course_work = SmExamType::where('active_status', 1)
                    ->where('academic_id', getAcademicId())
                    ->where('id', $request->exam)
                    ->where('school_id', Auth::user()->school_id)->first();

                $quarter = $exam_course_work->quarter;
                $coursework_type = "";
                $count_marks = $course_work_counts = 0;
                $exam_course_work = [];

                $pass_config = SmPassDefinition::where('school_id',Auth::user()->school_id)->where('academic_id',getAcademicId())->get();
                $course_work_percent = $exam_percent = 0;
                foreach ($pass_config as $pass_configuration){
                    $streams=json_decode($pass_configuration->streams);
                    if(in_array($request->class,$streams)){
                        $course_work_percent = $pass_configuration->course_work_percent;
                        $exam_percent = $pass_configuration->exam_percent;
                        $coursework_type = $pass_configuration->coursework_type;
                    }
                }
                $course_work_mark = $course_work = [];
                if($coursework_type == "tests"){
                    $exam_course_work = SmExamType::where('active_status', 1)
                        ->where('academic_id', getAcademicId())
                        ->where('is_examination', '=', "0")
                        ->where('quarter', $quarter)
                        ->where('school_id', Auth::user()->school_id)->get();

                    //Foreach course work test, retrieve marks from marks store for that test

                    $count_marks = $course_work_counts = 0;
                    if(count($exam_course_work)==0){
                        foreach ($mark_sheet as $marks) {
                            //Get the exam_mark
                            $exam_mark_sheet = SmResultStore::where([['class_id', $request->class], ['exam_type_id', $request->exam], ['section_id', $request->section], ['student_id', $request->student]])
                                ->where('subject_id', $marks->subject_id)
                                ->join('sm_subjects', 'sm_subjects.id', "=", 'sm_result_stores.subject_id')
                                ->where('sm_result_stores.school_id', Auth::user()->school_id)->first();

                            $course_work_mark[$count_marks] = [
                                'coursework_marks' => [],
                                'mark_sheet' => [
                                    "id" => $marks->id,
                                    "student_roll_no" => $marks->student_roll_no,
                                    "student_addmission_no" => $marks->student_addmission_no,
                                    "is_absent" => $marks->is_absent,
                                    "total_marks" => $marks->total_marks,
                                    "total_gpa_point" => $marks->total_gpa_point,
                                    "total_gpa_grade" => $marks->total_gpa_grade,
                                    "subject_name" => $marks->subject_name,
                                    "subject_code" => $marks->subject_code,
                                    "teacher_remarks" => $marks->teacher_remarks,
                                    "created_at" => $marks->created_at,
                                    "updated_at" => $marks->updated_at,
                                    "exam_type_id" => $marks->exam_type_id,
                                    "subject_id" => $marks->subject_id,
                                    "exam_setup_id" => $marks->exam_setup_id,
                                    "student_id" => $marks->student_id,
                                    "class_id" => $marks->class_id,
                                    "section_id" => $marks->section_id,
                                    "created_by" => $marks->created_by,
                                    "updated_by" => $marks->updated_by,
                                    "school_id" => $marks->school_id,
                                    "academic_id" => $marks->academic_id
                                ]
                            ];
                            $course_work_counts++;
                            $count_marks++;
                        }
                    }
                    else {

                        $course_work_counts = 0;//Cater for more than 1 test in the future
                        $count_marks = 0;
                            foreach ($mark_sheet as $marks) {
                                $total_Course_work=0;
                                $total_tests=0;
                                foreach ($exam_course_work as $tests) {
                                    $test_exam_id = $tests->id;
                                    $test_title = $tests->title;
                                    //Create a list of all the coursework marks

                                    $exam_mark_sheet = SmResultStore::where([['class_id', $request->class], ['exam_type_id', $test_exam_id], ['section_id', $request->section], ['student_id', $request->student]])
                                        ->where('subject_id',  $marks->subject_id)
                                        ->join('sm_subjects', 'sm_subjects.id', "=", 'sm_result_stores.subject_id')
                                        ->orderBy('sm_subjects.subject_code')
                                        ->where('sm_result_stores.school_id', Auth::user()->school_id)->first();

                                    if(isset($exam_mark_sheet)){
                                    $total_Course_work+=$exam_mark_sheet->total_marks;
                                     $total_tests++;
                                    }
                                }
                                if($total_tests>0){
                                    $total_course_work_mark = round($total_Course_work/ $total_tests,0);
                                }

                                //Get the exam_mark
                                $exam_mark = round(($marks->total_marks * $exam_percent) / 100, 0);

                                if(isset($exam_mark_sheet)) {
                                    $course_work = [
                                        'test_id' => $test_exam_id,
                                        'test_title' => $test_title,
                                        'subject_id' => $marks->subject_id,
                                        'subject_name' => $exam_mark_sheet->subject_name,
                                        'course_work_percent' => $course_work_percent,
                                        'course_work_mark' => round(($total_course_work_mark * $course_work_percent) / 100, 0),
                                        'exam_type_id' => (int)$request->exam,
                                        'exam_percent' => $exam_percent,
                                        'exam_mark' => $exam_mark,
                                        'final_mark' => round(($total_course_work_mark * $course_work_percent) / 100, 0) + $exam_mark
                                    ];
                                    $course_work_mark[$count_marks] = [
                                        'coursework_marks' => $course_work,
                                        'mark_sheet' => [
                                            "id" => $marks->id,
                                            "student_roll_no" => $marks->student_roll_no,
                                            "student_addmission_no" => $marks->student_addmission_no,
                                            "is_absent" => $marks->is_absent,
                                            "total_marks" => $marks->total_marks,
                                            "total_gpa_point" => $marks->total_gpa_point,
                                            "total_gpa_grade" => $marks->total_gpa_grade,
                                            "subject_name" => $marks->subject_name,
                                            "subject_code" => $marks->subject_code,
                                            "teacher_remarks" => $marks->teacher_remarks,
                                            "created_at" => $marks->created_at,
                                            "updated_at" => $marks->updated_at,
                                            "exam_type_id" => $marks->exam_type_id,
                                            "subject_id" => $marks->subject_id,
                                            "exam_setup_id" => $marks->exam_setup_id,
                                            "student_id" => $marks->student_id,
                                            "class_id" => $marks->class_id,
                                            "section_id" => $marks->section_id,
                                            "created_by" => $marks->created_by,
                                            "updated_by" => $marks->updated_by,
                                            "school_id" => $marks->school_id,
                                            "academic_id" => $marks->academic_id
                                        ]
                                    ];
                                    $course_work_counts++;
                                    $count_marks++;
                                }
                                else{
                                    $course_work = [
                                        'test_id' => $test_exam_id,
                                        'test_title' => $test_title,
                                        'subject_id' => $marks->subject_id,
                                        'subject_name' => $marks->subject_name,
                                        'course_work_percent' => $course_work_percent,
                                        'course_work_mark' => "-",
                                        'exam_type_id' => (int)$request->exam,
                                        'exam_percent' => $exam_percent,
                                        'exam_mark' => "-",
                                        'final_mark' => $marks->total_marks
                                    ];
                                    $course_work_mark[$count_marks] = [
                                        'coursework_marks' => $course_work,
                                        'mark_sheet' => [
                                            "id" => $marks->id,
                                            "student_roll_no" => $marks->student_roll_no,
                                            "student_addmission_no" => $marks->student_addmission_no,
                                            "is_absent" => $marks->is_absent,
                                            "total_marks" => $marks->total_marks,
                                            "total_gpa_point" => $marks->total_gpa_point,
                                            "total_gpa_grade" => $marks->total_gpa_grade,
                                            "subject_name" => $marks->subject_name,
                                            "subject_code" => $marks->subject_code,
                                            "teacher_remarks" => $marks->teacher_remarks,
                                            "created_at" => $marks->created_at,
                                            "updated_at" => $marks->updated_at,
                                            "exam_type_id" => $marks->exam_type_id,
                                            "subject_id" => $marks->subject_id,
                                            "exam_setup_id" => $marks->exam_setup_id,
                                            "student_id" => $marks->student_id,
                                            "class_id" => $marks->class_id,
                                            "section_id" => $marks->section_id,
                                            "created_by" => $marks->created_by,
                                            "updated_by" => $marks->updated_by,
                                            "school_id" => $marks->school_id,
                                            "academic_id" => $marks->academic_id
                                        ]
                                    ];
                                    $course_work_counts++;
                                    $count_marks++;
                                }
                            }
                        }
                    }
                else if($coursework_type == "exams" && $quarter == "FOUR"){
                    //For this onw, only make the coursework when it's 4th Quarter
                    $exam_course_work = SmExamType::where('active_status', 1)
                        ->where('academic_id', getAcademicId())
                        ->where('is_examination', '=', "1")
                        ->where('quarter', '<>', "FOUR")
                        ->where('quarter', $quarter)
                        ->where('school_id', Auth::user()->school_id)->get();

                    //Foreach course work test, retrieve marks from marks store for that test

                    $count_marks = $course_work_counts = 0;
                    if(count($exam_course_work)==0){
                        foreach ($mark_sheet as $marks) {
                            $course_work_mark[$count_marks] = [
                                'coursework_marks' => [],
                                'mark_sheet' => [
                                    "id" => $marks->id,
                                    "student_roll_no" => $marks->student_roll_no,
                                    "student_addmission_no" => $marks->student_addmission_no,
                                    "is_absent" => $marks->is_absent,
                                    "total_marks" => $marks->total_marks,
                                    "total_gpa_point" => $marks->total_gpa_point,
                                    "total_gpa_grade" => $marks->total_gpa_grade,
                                    "subject_name" => $marks->subject_name,
                                    "subject_code" => $marks->subject_code,
                                    "teacher_remarks" => $marks->teacher_remarks,
                                    "created_at" => $marks->created_at,
                                    "updated_at" => $marks->updated_at,
                                    "exam_type_id" => $marks->exam_type_id,
                                    "subject_id" => $marks->subject_id,
                                    "exam_setup_id" => $marks->exam_setup_id,
                                    "student_id" => $marks->student_id,
                                    "class_id" => $marks->class_id,
                                    "section_id" => $marks->section_id,
                                    "created_by" => $marks->created_by,
                                    "updated_by" => $marks->updated_by,
                                    "school_id" => $marks->school_id,
                                    "academic_id" => $marks->academic_id
                                ]
                            ];
                            $course_work_counts++;
                            $count_marks++;
                        }
                    }
                    else {
                        foreach ($exam_course_work as $tests) {
                            $test_exam_id = $tests->id;
                            $test_title = $tests->title;
                            //Create a list of all the coursework marks
                            $course_work_counts = 0;//Cater for more than 1 test in the future
                            $count_marks = 0;

                            foreach ($mark_sheet as $marks) {
                                //Get the exam_mark
                                $exam_mark_sheet = SmResultStore::where([['class_id', $request->class], ['exam_type_id', $request->exam], ['section_id', $request->section], ['student_id', $request->student]])
                                    ->where('subject_id', $marks->subject_id)
                                    ->join('sm_subjects', 'sm_subjects.id', "=", 'sm_result_stores.subject_id')
                                    ->orderBy('sm_subjects.subject_code')
                                    ->where('sm_result_stores.school_id', Auth::user()->school_id)->first();

                                $exam_mark = round(($exam_mark_sheet->total_marks * $exam_percent) / 100, 0);

                                $course_work = [
                                    'test_id' => $test_exam_id,
                                    'test_title' => $test_title,
                                    'subject_id' => $marks->subject_id,
                                    'subject_name' => $exam_mark_sheet->subject_name,
                                    'course_work_percent' => $course_work_percent,
                                    'course_work_mark' => round(($marks->total_marks * $course_work_percent) / 100, 0),
                                    'exam_type_id' => (int)$request->exam,
                                    'exam_percent' => $exam_percent,
                                    'exam_mark' => $exam_mark,
                                    'final_mark' => round(($marks->total_marks * $course_work_percent) / 100, 0) + $exam_mark
                                ];

                                $course_work_mark[$count_marks] = [
                                    'coursework_marks' => $course_work,
                                    'mark_sheet' => [
                                        "id" => $marks->id,
                                        "student_roll_no" => $marks->student_roll_no,
                                        "student_addmission_no" => $marks->student_addmission_no,
                                        "is_absent" => $marks->is_absent,
                                        "total_marks" => $marks->total_marks,
                                        "total_gpa_point" => $marks->total_gpa_point,
                                        "total_gpa_grade" => $marks->total_gpa_grade,
                                        "subject_name" => $marks->subject_name,
                                        "subject_code" => $marks->subject_code,
                                        "teacher_remarks" => $marks->teacher_remarks,
                                        "created_at" => $marks->created_at,
                                        "updated_at" => $marks->updated_at,
                                        "exam_type_id" => $marks->exam_type_id,
                                        "subject_id" => $marks->subject_id,
                                        "exam_setup_id" => $marks->exam_setup_id,
                                        "student_id" => $marks->student_id,
                                        "class_id" => $marks->class_id,
                                        "section_id" => $marks->section_id,
                                        "created_by" => $marks->created_by,
                                        "updated_by" => $marks->updated_by,
                                        "school_id" => $marks->school_id,
                                        "academic_id" => $marks->academic_id
                                    ]
                                ];
                                $course_work_counts++;
                                $count_marks++;
                            }
                        }
                    }
                }
                else{
                    foreach ($mark_sheet as $marks) {
                       $course_work_mark[$count_marks] = [
                            'coursework_marks' => [],
                            'mark_sheet' => [
                                "id" => $marks->id,
                                "student_roll_no" => $marks->student_roll_no,
                                "student_addmission_no" => $marks->student_addmission_no,
                                "is_absent" => $marks->is_absent,
                                "total_marks" => $marks->total_marks,
                                "total_gpa_point" => $marks->total_gpa_point,
                                "total_gpa_grade" => $marks->total_gpa_grade,
                                "subject_name" => $marks->subject_name,
                                "subject_code" => $marks->subject_code,
                                "teacher_remarks" => $marks->teacher_remarks,
                                "created_at" => $marks->created_at,
                                "updated_at" => $marks->updated_at,
                                "exam_type_id" => $marks->exam_type_id,
                                "subject_id" => $marks->subject_id,
                                "exam_setup_id" => $marks->exam_setup_id,
                                "student_id" => $marks->student_id,
                                "class_id" => $marks->class_id,
                                "section_id" => $marks->section_id,
                                "created_by" => $marks->created_by,
                                "updated_by" => $marks->updated_by,
                                "school_id" => $marks->school_id,
                                "academic_id" => $marks->academic_id
                            ]
                        ];
                        $course_work_counts++;
                        $count_marks++;
                    }
                }

                $grades = SmMarksGrade::where('active_status', 1)->get();
                $total_marks = SmResultStore::where([
                    ['exam_type_id', $request->exam],
                    ['class_id', $request->class],
                    ['section_id', $request->section],
                    ['student_id', $request->student]
                ])->where('academic_id', getAcademicId())->where('total_marks', '>', 0)->where('school_id', Auth::user()->school_id)->sum('total_marks');

                $results = SmResultStore::where([
                    ['exam_type_id', $request->exam],
                    ['class_id', $request->class],
                    ['section_id', $request->section],
                    ['student_id', $request->student]
                ])->where('sm_subjects.academic_id', getAcademicId())
                    ->join('sm_subjects','sm_subjects.id','=','sm_result_stores.subject_id')
                    ->orderBy('sm_subjects.subject_code')
                    ->where('total_marks', '>', 0)
                    ->where('sm_result_stores.school_id', Auth::user()->school_id)->get();

                $students_in_stream = SmStreamResult::where([
                    ['class_id', $request->class]
                ])->where('academic_id', getAcademicId())->where('exam_id', $request->exam)->where('school_id', Auth::user()->school_id)->get()->count();

            
                $sum_of_mark = ($total_marks == 0) ? 0 : $total_marks;
                $average_mark = ($total_marks == 0) ? 0 : round($total_marks / $results->count(), 1); //get average number

                if (count($mark_sheet) == 0) {

                    Toastr::error('Ops! Your result is not found! Please check mark register', 'Failed');
                    return redirect('mark-sheet-report-student');
                }
                $is_result_available = SmResultStore::where([['class_id', $request->class], ['exam_type_id', $request->exam], ['section_id', $request->section], ['student_id', $request->student]])->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->where('total_marks', '>', 0)->where('school_id', Auth::user()->school_id)->get();
                if (count($mark_sheet) > 0) {


                    if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                        $data = [];
                        $data['exam_types'] = $exams->toArray();
                        $data['classes'] = $classes->toArray();
                        $data['studentDetails'] = $studentDetails;
                        $data['exams'] = $exams->toArray();
                        $data['subjects'] = $subjects->toArray();
                        $data['section'] = $student_detail->section;
                        $data['class_id'] = $class_id;
                        $data['student_detail'] = $student_detail;
                        $data['is_result_available'] = $is_result_available;
                        $data['exam_type_id'] = $exam_type_id;
                        $data['section_id'] = $section_id;
                        $data['student_id'] = $student_id;
                        $data['exam_details'] = $exam_details;
                        $data['class_name'] = $student_detail->className;
                        $data['students_in_stream'] = $students_in_stream;
                        return ApiBaseMethod::sendResponse($data, null);
                    }
                    $student = $student_id;

                    return view('backEnd.reports.mark_sheet_report_normal', compact('optional_subject_setup', 'course_work_mark', 'course_work_percent', 'exam_percent','classes', 'studentDetails', 'exams', 'classes', 'subjects', 'class_id', 'student_detail', 'mark_sheet', 'exam_type_id', 'section_id', 'student_id', 'exam_details', 'input', 'optional_subject', 'grades', 'sum_of_mark', 'average_mark','exam_course_work'));


                } else {

                    if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                        return ApiBaseMethod::sendError('Ops! Your result is not found! Please check mark register');
                    }
                    Toastr::error('Ops! Your result is not found! Please check mark register', 'Failed');
                    return redirect('mark-sheet-report-student');
                }


                $marks_register = SmMarksRegister::where('exam_id', $request->exam)->where('student_id', $request->student)->first();


                $subjects = SmAssignSubject::where('class_id', $request->class)->where('section_id', $request->section)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $exams = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $grades = SmMarksGrade::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $class = SmClass::find($request->class);
                $section = SmSection::find($request->section);
                $exam_detail = SmExam::find($request->exam);
                $exam_id = $request->exam;
                $class_id = $request->class;

                return view('backEnd.reports.mark_sheet_report_student', compact('optional_subject', 'classes', 'studentDetails', 'exams', 'classes', 'marks_register', 'subjects', 'class', 'section', 'exam_detail', 'grades', 'exam_id', 'class_id', 'student_detail', 'input'));
            } catch (\Exception $e) {
                dd($e);
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }
    }

    public function markSheetReportStudentCommentSearch(Request $request)
    {
        $input = $request->all();

        $input['exam_id'] = $request->exam;
        $input['class_id'] = $request->class;
        $input['section_id'] = $request->section;

            $validator = Validator::make($input, [
                'exam' => 'required',
                'class' => 'required',
                'section' => 'required'
            ]);

            if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $students = SmStudent::where([
            ['class_id', $request->class],
            ['section_id', $request->section]
        ])->where('academic_id', getAcademicId())->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();


            try {
                $exams = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
                $exam_types = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

                $class = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->where('id',$request->class)->first();
                $section = SmSection::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->where('id',$request->section)->first();

                $section_id = $request->section;
                $class_id = $request->class;
                $exam_type_id = $request->exam;
                $exam_details = $exams->where('active_status', 1)->find($exam_type_id);

                $mark_sheet = SmResultStore::where([['class_id', $request->class], ['exam_type_id', $request->exam], ['section_id', $request->section]])->where('school_id', Auth::user()->school_id)->get();

                if (count($mark_sheet) == 0) {
                    Toastr::error('Ops! Your result is not found! Please check mark register', 'Failed');
                    return redirect('mark-sheet-report-student');
                }

                if (count($mark_sheet) > 0) {
                    return view('backEnd.reports.mark_sheet_report_student_comment', compact('class', 'section', 'students', 'exam_types', 'exams', 'class_id', 'mark_sheet', 'exam_type_id', 'section_id',  'exam_details'));
                }
            }catch (\Exception $e) {
                dd($e);
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
    }

    public function markSheetReportStudentPrint($exam_id, $class_id, $section_id, $student_id)
    {
        try {
            $exams = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $exam_types = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            $subjects = SmAssignSubject::where([['class_id', $class_id], ['section_id', $section_id]])->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $student_detail = $studentDetails = SmStudent::find($student_id);
            $section = SmSection::where('active_status', 1)->where('id', $section_id)->first();

            $class_name = SmClass::find($class_id);
            $exam_type_id = $exam_id;
            $exam_details = SmExamType::where('active_status', 1)->find($exam_type_id);
            $optional_subject = '';

            $results_config = SmResultsConfiguration::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            $principal_designation = SmDesignation::where('title', "Principal")->where('school_id', Auth::user()->school_id)->first();
            $principal = SmStaff::where('designation_id', $principal_designation->id)->where('school_id', Auth::user()->school_id)->first();

            $assign_class_teacher = SmAssignClassTeacher::query();
            $assign_class_teacher->where('academic_id', getAcademicId())->where('active_status', 1);
            if ($section_id != "") {
                $assign_class_teacher->where('section_id', $section_id);
            }
            $assign_class_teacher->where('class_id', $class_id);
            $assign_class_teacher = $assign_class_teacher->first();

            if (!isset($assign_class_teacher)) {
                Toastr::error('Operation Failed! No class teacher allocated, please assign class teacher first.', 'Failed');
                return redirect()->back();
            }

            if ($assign_class_teacher != "") {
                $assign_class_teachers = $assign_class_teacher->classTeachers->first();
            } else {
                $assign_class_teachers = '';
            }

            $students = SmStudent::where([
                ['class_id', $class_id],
                ['section_id', $section_id]
            ])->where('academic_id', getAcademicId())->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();

            $students_in_stream = SmStreamResult::where([
                ['class_id', $class_id]
            ])->where('academic_id', getAcademicId())->where('exam_id', $exam_id)->where('school_id', Auth::user()->school_id)->get()->count();


            $total_marks = SmResultStore::where([
                ['exam_type_id', $exam_type_id],
                ['class_id', $class_id],
                ['section_id', $section_id],
                ['student_id', $student_id]
            ])->where('academic_id', getAcademicId())->sum('total_marks');

            $results = SmResultStore::where([
                ['exam_type_id', $exam_type_id],
                ['class_id', $class_id],
                ['section_id', $section_id],
                ['student_id', $student_id]
            ])->where('sm_result_stores.academic_id', getAcademicId())
                ->join('sm_subjects','sm_subjects.id','=','sm_result_stores.subject_id')
                ->orderBy('sm_subjects.subject_code')
                ->where('total_marks', '>', 0)
                ->where('sm_result_stores.school_id', Auth::user()->school_id)->get();


            //Get Corresponding Coursework
            $overall_marks = $overall_average = 0;
            $pass_config = SmPassDefinition::where('school_id',Auth::user()->school_id)->where('academic_id',getAcademicId())->get();
            $pass_mark = $number_of_subjects = $pass_average = 0;
            foreach ($pass_config as $pass_configuration){
                $streams=json_decode($pass_configuration->streams);
                if(in_array($class_id,$streams)){
                    $pass_mark = $pass_configuration->pass_mark;
                }
            }
            //Get number of passed subjects
            $passed_subjects = "0";
            $student_result = "";

            $exam_course_work_exam_id = SmExamType::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('id', $exam_type_id)
                ->where('school_id', Auth::user()->school_id)->first();

            $quarter = $exam_course_work_exam_id->quarter;
            $coursework_type = "";
            $count_marks = $course_work_counts = 0;
            $exam_course_work = [];

            $pass_config = SmPassDefinition::where('school_id',Auth::user()->school_id)->where('academic_id',getAcademicId())->get();
            $course_work_percent = $exam_percent = 0;
            foreach ($pass_config as $pass_configuration){
                $streams=json_decode($pass_configuration->streams);
                if(in_array($class_id,$streams)){
                    $course_work_percent = $pass_configuration->course_work_percent;
                    $exam_percent = $pass_configuration->exam_percent;
                    $coursework_type = $pass_configuration->coursework_type;
                }
            }
            $course_work_mark = $course_work = [];
            $number_of_passed_subjects = 0;
            if($coursework_type == "tests"){
                $exam_course_work = SmExamType::where('active_status', 1)
                    ->where('academic_id', getAcademicId())
                    ->where('is_examination', '=', "0")
                    ->where('quarter', $quarter)
                    ->where('school_id', Auth::user()->school_id)->get();

                //Foreach course work test, retrieve marks from marks store for that test

                $count_marks = $course_work_counts = 0;
                if(count($exam_course_work)==0){
                    foreach ($results as $marks) {

                        $course_work_mark[$count_marks] = [
                            'coursework_marks' => [],
                            'mark_sheet' => [
                                "id" => $marks->id,
                                "student_roll_no" => $marks->student_roll_no,
                                "student_addmission_no" => $marks->student_addmission_no,
                                "is_absent" => $marks->is_absent,
                                "total_marks" => $marks->total_marks,
                                "total_gpa_point" => $marks->total_gpa_point,
                                "total_gpa_grade" => $marks->total_gpa_grade,
                                "subject_name" => $marks->subject_name,
                                "subject_code" => $marks->subject_code,
                                "teacher_remarks" => $marks->teacher_remarks,
                                "created_at" => $marks->created_at,
                                "updated_at" => $marks->updated_at,
                                "exam_type_id" => $marks->exam_type_id,
                                "subject_id" => $marks->subject_id,
                                "exam_setup_id" => $marks->exam_setup_id,
                                "student_id" => $marks->student_id,
                                "class_id" => $marks->class_id,
                                "section_id" => $marks->section_id,
                                "created_by" => $marks->created_by,
                                "updated_by" => $marks->updated_by,
                                "school_id" => $marks->school_id,
                                "academic_id" => $marks->academic_id
                            ]
                        ];
                        $course_work_counts++;
                        $count_marks++;
                    }
                }
                else {
                    $course_work_counts = 0;//Cater for more than 1 test in the future
                    $count_marks = 0;
                    foreach ($results as $marks) {
                        $total_Course_work=0;
                        $total_tests=0;
                        foreach ($exam_course_work as $tests) {
                            $test_exam_id = $tests->id;
                            $test_title = $tests->title;
                            //Create a list of all the coursework marks

                            $exam_mark_sheet = SmResultStore::where([['class_id', $class_id], ['exam_type_id', $test_exam_id], ['section_id', $section_id], ['student_id', $student_id]])
                                ->where('subject_id',  $marks->subject_id)
                                ->join('sm_subjects', 'sm_subjects.id', "=", 'sm_result_stores.subject_id')
                                ->orderBy('sm_subjects.subject_code')
                                ->where('sm_result_stores.school_id', Auth::user()->school_id)->first();

                            if(isset($exam_mark_sheet)){
                                $total_Course_work+=$exam_mark_sheet->total_marks;
                                $total_tests++;
                            }
                        }
                        if($total_tests>0){
                            $total_course_work_mark = round($total_Course_work/ $total_tests,0);
                        }
                        //Get the exam_mark
                        $exam_mark = round(($marks->total_marks * $exam_percent) / 100, 0);


                        if(isset($exam_mark_sheet)) {
                                $course_work = round(($total_course_work_mark * $course_work_percent) / 100, 0);
                                $final_mark = $exam_mark+$course_work;
                                $overall_marks+=$final_mark;

                                if($final_mark>=$pass_mark) {

                                    $passed_subjects += 1;
                                }

                                $course_work = [
                                    'test_id' => $test_exam_id,
                                    'test_title' => $test_title,
                                    'subject_id' => $marks->subject_id,
                                    'subject_name' => $exam_mark_sheet->subject_name,
                                    'subject_code' => $exam_mark_sheet->subject_code,
                                    'course_work_percent' => $course_work_percent,
                                    'course_work_mark' => $course_work,
                                    'exam_type_id' => (int)$exam_type_id,
                                    'exam_percent' => $exam_percent,
                                    'exam_mark' => $exam_mark,
                                    'final_mark' => $final_mark
                                ];
                                $course_work_mark[$count_marks] = [
                                    'coursework_marks' => $course_work,
                                    'mark_sheet' => [
                                        "id" => $marks->id,
                                        "student_roll_no" => $marks->student_roll_no,
                                        "student_addmission_no" => $marks->student_addmission_no,
                                        "is_absent" => $marks->is_absent,
                                        "total_marks" => $marks->total_marks,
                                        "total_gpa_point" => $marks->total_gpa_point,
                                        "total_gpa_grade" => $marks->total_gpa_grade,
                                        "subject_name" => $marks->subject_name,
                                        "subject_code" => $marks->subject_code,
                                        "teacher_remarks" => $marks->teacher_remarks,
                                        "created_at" => $marks->created_at,
                                        "updated_at" => $marks->updated_at,
                                        "exam_type_id" => $marks->exam_type_id,
                                        "subject_id" => $marks->subject_id,
                                        "exam_setup_id" => $marks->exam_setup_id,
                                        "student_id" => $marks->student_id,
                                        "class_id" => $marks->class_id,
                                        "section_id" => $marks->section_id,
                                        "created_by" => $marks->created_by,
                                        "updated_by" => $marks->updated_by,
                                        "school_id" => $marks->school_id,
                                        "academic_id" => $marks->academic_id
                                    ]
                                ];
                                $course_work_counts++;
                                $count_marks++;
                            }
                            else{
                                $course_work = [
                                    'test_id' => $test_exam_id,
                                    'test_title' => $test_title,
                                    'subject_id' => $marks->subject_id,
                                    'subject_name' => $marks->subject_name,
                                    'course_work_percent' => $course_work_percent,
                                    'course_work_mark' => "-",
                                    'exam_type_id' => (int)$test_exam_id,
                                    'exam_percent' => $exam_percent,
                                    'exam_mark' => "-",
                                    'final_mark' => $marks->total_marks
                                ];
                                $course_work_mark[$count_marks] = [
                                    'coursework_marks' => $course_work,
                                    'mark_sheet' => [
                                        "id" => $marks->id,
                                        "student_roll_no" => $marks->student_roll_no,
                                        "student_addmission_no" => $marks->student_addmission_no,
                                        "is_absent" => $marks->is_absent,
                                        "total_marks" => $marks->total_marks,
                                        "total_gpa_point" => $marks->total_gpa_point,
                                        "total_gpa_grade" => $marks->total_gpa_grade,
                                        "subject_name" => $marks->subject_name,
                                        "subject_code" => $marks->subject_code,
                                        "teacher_remarks" => $marks->teacher_remarks,
                                        "created_at" => $marks->created_at,
                                        "updated_at" => $marks->updated_at,
                                        "exam_type_id" => $marks->exam_type_id,
                                        "subject_id" => $marks->subject_id,
                                        "exam_setup_id" => $marks->exam_setup_id,
                                        "student_id" => $marks->student_id,
                                        "class_id" => $marks->class_id,
                                        "section_id" => $marks->section_id,
                                        "created_by" => $marks->created_by,
                                        "updated_by" => $marks->updated_by,
                                        "school_id" => $marks->school_id,
                                        "academic_id" => $marks->academic_id
                                    ]
                                ];
                                $course_work_counts++;
                                $count_marks++;
                            }
                        }
                    }
                /* PASS DEFINITION */
                $pass_config = SmPassDefinition::where('school_id',Auth::user()->school_id)->where('academic_id',getAcademicId())->get();
                $pass_mark = $number_of_subjects = $pass_average = 0;
                $compulsory_subjects = [];
                foreach ($pass_config as $pass_configuration){
                    $streams=json_decode($pass_configuration->streams);
                    if(in_array($class_id,$streams)){
                        $pass_mark = $pass_configuration->pass_mark;
                        $grade_table = $pass_configuration->grade_table;
                        $student_position = $pass_configuration->student_position;
                        $number_of_subjects = $pass_configuration->number_of_subjects;
                        $pass_average = $pass_configuration->pass_average;
                        $course_work_percent = $pass_configuration->course_work_percent;
                        $exam_percent = $pass_configuration->exam_percent;
                        $compulsory_subjects = json_decode($pass_configuration->compulsory_subjects);
                    }
                }

                $must_pass_subjects = SmResultStore::where([
                    ['exam_type_id', $exam_type_id],
                    ['class_id', $class_id],
                    ['section_id', $section_id],
                    ['student_id', $student_id]
                ])->whereIn('subject_id',$compulsory_subjects)->where('academic_id', getAcademicId())->get();

                $sum_of_compulsory_subjects = $average_of_compulsory_subjects = 0;
                //Compulsory subjects to pass are checked here.
                $count_of_copulsory_subject = 0;
                foreach($must_pass_subjects as $compul_subjects){
                    $total_Course_work=0;
                    $total_tests=0;
                    foreach ($exam_course_work as $tests) {
                        $test_exam_id = $tests->id;
                        $test_title = $tests->title;
                        //Create a list of all the coursework marks
                        $exam_mark_sheet = SmResultStore::where([['class_id', $class_id], ['exam_type_id', $test_exam_id], ['section_id', $section_id], ['student_id', $student_id]])
                            ->where('subject_id',  $compul_subjects->subject_id)
                            ->join('sm_subjects', 'sm_subjects.id', "=", 'sm_result_stores.subject_id')
                            ->orderBy('sm_subjects.subject_code')
                            ->where('sm_result_stores.school_id', Auth::user()->school_id)->first();

                        if(isset($exam_mark_sheet)){
                            $total_Course_work+=$exam_mark_sheet->total_marks;
                            $total_tests++;
                        }

                    }
                    $avarage_total_course_work=0;
                    if($total_tests>0){
                        $total_course_work_mark = round($total_Course_work/ $total_tests,0);
                        $avarage_total_course_work = round(($total_course_work_mark * $course_work_percent)/100, 0);


                    }

                    //Get the exam_mark
                    $total_exam_mark = round(($compul_subjects->total_marks * $exam_percent) / 100, 0);


                    $final_mark=$avarage_total_course_work+$total_exam_mark;
                    $count_of_copulsory_subject++;
                }

                $sum_of_compulsory_subjects += $final_mark;

                $average_of_compulsory_subjects = $sum_of_compulsory_subjects/$count_of_copulsory_subject;
                if($count_marks>0){
                $overall_average=round($overall_marks/$count_marks,1);}
                $student_result = averageResult($overall_average);

                $studentResult = averageResult($overall_average);

                $final_result = "";


                foreach($studentResult as $performance){
                    $allstreams = json_decode($performance->streams);
                    if(in_array($class_id,$allstreams)){

                        if($passed_subjects>=$number_of_subjects && $overall_average>=$pass_average && $average_of_compulsory_subjects>=$pass_mark){
                            $final_result = $performance->result_name;

                        }
                        else{
                            $studentResult=averagePassResult($pass_mark);

                            foreach($studentResult as $performance){
                                $allstreams = json_decode($performance->streams);
                                if(in_array($class_id,$allstreams)){
                                    $final_result=$performance->result_name;

                                }
                            }
                        }

                    }
                }
                }

            else if($coursework_type == "exams" && $quarter == "FOUR"){
                //For this onw, only make the coursework when it's 4th Quarter
                $exam_course_work = SmExamType::where('active_status', 1)
                    ->where('academic_id', getAcademicId())
                    ->where('is_examination', '=', "1")
                    ->where('quarter', '<>', "FOUR")
                    ->where('quarter', $quarter)
                    ->where('school_id', Auth::user()->school_id)->get();

                //Foreach course work test, retrieve marks from marks store for that test

                $count_marks = $course_work_counts = 0;
                $overall_marks = $overall_average = 0;
                if(count($exam_course_work)==0){
                    foreach ($results as $marks) {
                        $course_work_mark[$count_marks] = [
                            'coursework_marks' => [],
                            'mark_sheet' => [
                                "id" => $marks->id,
                                "student_roll_no" => $marks->student_roll_no,
                                "student_addmission_no" => $marks->student_addmission_no,
                                "is_absent" => $marks->is_absent,
                                "total_marks" => $marks->total_marks,
                                "total_gpa_point" => $marks->total_gpa_point,
                                "total_gpa_grade" => $marks->total_gpa_grade,
                                "subject_name" => $marks->subject_name,
                                "subject_code" => $marks->subject_code,
                                "teacher_remarks" => $marks->teacher_remarks,
                                "created_at" => $marks->created_at,
                                "updated_at" => $marks->updated_at,
                                "exam_type_id" => $marks->exam_type_id,
                                "subject_id" => $marks->subject_id,
                                "exam_setup_id" => $marks->exam_setup_id,
                                "student_id" => $marks->student_id,
                                "class_id" => $marks->class_id,
                                "section_id" => $marks->section_id,
                                "created_by" => $marks->created_by,
                                "updated_by" => $marks->updated_by,
                                "school_id" => $marks->school_id,
                                "academic_id" => $marks->academic_id
                            ]
                        ];
                        $course_work_counts++;
                        $count_marks++;
                    }

                }
                else {
                    $course_work_counts = 0;//Cater for more than 1 test in the future
                    $count_marks = 0;
                    foreach ($results as $marks) {
                        $total_Course_work=0;
                        $total_tests=0;
                        foreach ($exam_course_work as $tests) {
                            $test_exam_id = $tests->id;
                            $test_title = $tests->title;
                            //Create a list of all the coursework marks

                            $exam_mark_sheet = SmResultStore::where([['class_id', $class_id], ['exam_type_id', $test_exam_id], ['section_id', $section_id], ['student_id', $student_id]])
                                ->where('subject_id',  $marks->subject_id)
                                ->join('sm_subjects', 'sm_subjects.id', "=", 'sm_result_stores.subject_id')
                                ->orderBy('sm_subjects.subject_code')
                                ->where('sm_result_stores.school_id', Auth::user()->school_id)->first();

                            if(isset($exam_mark_sheet)){
                                $total_Course_work+=$exam_mark_sheet->total_marks;
                                $total_tests++;
                            }
                        }
                        $total_course_work_mark = round($total_Course_work/ $total_tests,0);
                        //Get the exam_mark
                        $exam_mark = round(($marks->total_marks * $exam_percent) / 100, 0);

                            if(isset($exam_mark_sheet)) {
                                $course_work = [
                                    'test_id' => $test_exam_id,
                                    'test_title' => $test_title,
                                    'subject_id' => $marks->subject_id,
                                    'subject_name' => $exam_mark_sheet->subject_name,
                                    'course_work_percent' => $course_work_percent,
                                    'course_work_mark' => round(($total_course_work_mark * $course_work_percent) / 100, 0),
                                    'exam_type_id' => (int)$exam_type_id,
                                    'exam_percent' => $exam_percent,
                                    'exam_mark' => $exam_mark,
                                    'final_mark' => round(($total_course_work_mark * $course_work_percent) / 100, 0) + $exam_mark
                                ];

                                $course_work_mark[$count_marks] = [
                                    'coursework_marks' => $course_work,
                                    'mark_sheet' => [
                                        "id" => $marks->id,
                                        "student_roll_no" => $marks->student_roll_no,
                                        "student_addmission_no" => $marks->student_addmission_no,
                                        "is_absent" => $marks->is_absent,
                                        "total_marks" => $marks->total_marks,
                                        "total_gpa_point" => $marks->total_gpa_point,
                                        "total_gpa_grade" => $marks->total_gpa_grade,
                                        "subject_name" => $marks->subject_name,
                                        "subject_code" => $marks->subject_code,
                                        "teacher_remarks" => $marks->teacher_remarks,
                                        "created_at" => $marks->created_at,
                                        "updated_at" => $marks->updated_at,
                                        "exam_type_id" => $marks->exam_type_id,
                                        "subject_id" => $marks->subject_id,
                                        "exam_setup_id" => $marks->exam_setup_id,
                                        "student_id" => $marks->student_id,
                                        "class_id" => $marks->class_id,
                                        "section_id" => $marks->section_id,
                                        "created_by" => $marks->created_by,
                                        "updated_by" => $marks->updated_by,
                                        "school_id" => $marks->school_id,
                                        "academic_id" => $marks->academic_id
                                    ]
                                ];
                                $course_work_counts++;
                                $count_marks++;

                            }
                            else{
                                $course_work = [
                                    'test_id' => $test_exam_id,
                                    'test_title' => $test_title,
                                    'subject_id' => $marks->subject_id,
                                    'subject_name' => $marks->subject_name,
                                    'course_work_percent' => $course_work_percent,
                                    'course_work_mark' => "-",
                                    'exam_type_id' => (int)$test_exam_id,
                                    'exam_percent' => $exam_percent,
                                    'exam_mark' => "-",
                                    'final_mark' => $marks->total_marks
                                ];
                                $course_work_mark[$count_marks] = [
                                    'coursework_marks' => $course_work,
                                    'mark_sheet' => [
                                        "id" => $marks->id,
                                        "student_roll_no" => $marks->student_roll_no,
                                        "student_addmission_no" => $marks->student_addmission_no,
                                        "is_absent" => $marks->is_absent,
                                        "total_marks" => $marks->total_marks,
                                        "total_gpa_point" => $marks->total_gpa_point,
                                        "total_gpa_grade" => $marks->total_gpa_grade,
                                        "subject_name" => $marks->subject_name,
                                        "subject_code" => $marks->subject_code,
                                        "teacher_remarks" => $marks->teacher_remarks,
                                        "created_at" => $marks->created_at,
                                        "updated_at" => $marks->updated_at,
                                        "exam_type_id" => $marks->exam_type_id,
                                        "subject_id" => $marks->subject_id,
                                        "exam_setup_id" => $marks->exam_setup_id,
                                        "student_id" => $marks->student_id,
                                        "class_id" => $marks->class_id,
                                        "section_id" => $marks->section_id,
                                        "created_by" => $marks->created_by,
                                        "updated_by" => $marks->updated_by,
                                        "school_id" => $marks->school_id,
                                        "academic_id" => $marks->academic_id
                                    ]
                                ];
                                $course_work_counts++;
                                $count_marks++;
                            }
                        }
                    }
                    $overall_average=round($overall_marks/$count_marks,1);

                }
            else{
                foreach ($results as $marks) {
                    $course_work_mark[$count_marks] = [
                        'coursework_marks' => [],
                        'mark_sheet' => [
                            "id" => $marks->id,
                            "student_roll_no" => $marks->student_roll_no,
                            "student_addmission_no" => $marks->student_addmission_no,
                            "is_absent" => $marks->is_absent,
                            "total_marks" => $marks->total_marks,
                            "total_gpa_point" => $marks->total_gpa_point,
                            "total_gpa_grade" => $marks->total_gpa_grade,
                            "subject_name" => $marks->subject_name,
                            "subject_code" => $marks->subject_code,
                            "teacher_remarks" => $marks->teacher_remarks,
                            "created_at" => $marks->created_at,
                            "updated_at" => $marks->updated_at,
                            "exam_type_id" => $marks->exam_type_id,
                            "subject_id" => $marks->subject_id,
                            "exam_setup_id" => $marks->exam_setup_id,
                            "student_id" => $marks->student_id,
                            "class_id" => $marks->class_id,
                            "section_id" => $marks->section_id,
                            "created_by" => $marks->created_by,
                            "updated_by" => $marks->updated_by,
                            "school_id" => $marks->school_id,
                            "academic_id" => $marks->academic_id
                        ]
                    ];
                    $course_work_counts++;
                    $count_marks++;
                }
            }
            /* COURSE WORK COMPUTATION */

            $results_data = DB::table('sm_temporary_meritlists')
                ->where('class_id', $class_id)
                ->where('section_id', $section_id)
                ->where('exam_id', $exam_type_id)
                ->where('student_id', $student_id)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)->first();

            if (!isset($results_data)) {
                Toastr::error('Operation Failed! No results found, please generate reports first.', 'Failed');
                return redirect()->back();
            }
            $position = $results_data->merit_order;

            $stream_data = DB::table('sm_stream_results')
                ->where('class_id', $class_id)
                ->where('section_id', $section_id)
                ->where('exam_id', $exam_type_id)
                ->where('student_id', $student_id)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)->first();

            if (!isset($stream_data)) {
                Toastr::error('Operation Failed! No results found, please generate reports first.', 'Failed');
                return redirect()->back();
            }

            $position_stream = $stream_data->stream_position;

            $sum_of_mark = ($total_marks == 0) ? 0 : $total_marks;
            $average_mark = ($total_marks == 0) ? 0 : round($total_marks / $results->count(), 1);

            if($coursework_type == "exams" && $quarter != " FOUR") {


                /* PASS DEFINITION */
                $pass_config = SmPassDefinition::where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->get();
                $pass_mark = $number_of_subjects = $pass_average = 0;
                $compulsory_subjects = [];
                foreach ($pass_config as $pass_configuration) {
                    $streams = json_decode($pass_configuration->streams);
                    if (in_array($class_id, $streams)) {
                        $pass_mark = $pass_configuration->pass_mark;
                        $number_of_subjects = $pass_configuration->number_of_subjects;
                        $grade_table = $pass_configuration->grade_table;
                        $student_position = $pass_configuration->student_position;
                        $pass_average = $pass_configuration->pass_average;
                        $compulsory_subjects = json_decode($pass_configuration->compulsory_subjects);
                    }
                }
                //Get number of passed subjects
                $number_of_passed_subjects = "N/A";
                if (isset($pass_config)) {
                    $number_of_passed_subjects = SmResultStore::where([
                        ['exam_type_id', $exam_type_id],
                        ['class_id', $class_id],
                        ['section_id', $section_id],
                        ['student_id', $student_id]
                    ])->where('total_marks', '>=', $pass_mark)->where('academic_id', getAcademicId())->count();

                    $studentResult = averageResult($average_mark);

                    $final_result = "";

                    //Must Pass Subjects
                    $must_pass_subjects = SmResultStore::where([
                        ['exam_type_id', $exam_type_id],
                        ['class_id', $class_id],
                        ['section_id', $section_id],
                        ['student_id', $student_id]
                    ])->whereIn('subject_id', $compulsory_subjects)->where('academic_id', getAcademicId())->get();

                    $sum_of_compulsory_subjects = $average_of_compulsory_subjects = 0;
                    //Compulsory subjects to pass are checked here.
                    foreach ($must_pass_subjects as $compul_subjects) {
                        $sum_of_compulsory_subjects += $compul_subjects->total_marks;
                        $average_of_compulsory_subjects = $sum_of_compulsory_subjects / count($compulsory_subjects);
                    }
                    $final_result = "";
                    foreach ($studentResult as $performance) {
                        $allstreams = json_decode($performance->streams);
                        if (in_array($class_id, $allstreams)) {
                            if ($number_of_passed_subjects >= $number_of_subjects && $average_mark >= $pass_average && $average_of_compulsory_subjects >= $pass_mark) {
                                $final_result = $performance->result_name;
                            } else {
                                $studentResult = averagePassResult($pass_mark);

                                foreach ($studentResult as $performance) {
                                    $allstreams = json_decode($performance->streams);
                                    if (in_array($class_id, $allstreams)) {
                                        $final_result = $performance->result_name;
                                    }
                                }
                            }
                        }
                    }
                }
                //Get Results based on configuration above

            }
                /**PASS DEFINITION */

            $get_optional_subject = SmOptionalSubjectAssign::where('student_id', '=', $student_detail->id)->where('session_id', '=', $student_detail->session_id)->first();
            if ($get_optional_subject != '') {
                $optional_subject = $get_optional_subject->subject_id;
            }
            $optional_subject_setup = SmClassOptionalSubject::where('class_id', '=', $class_id)->first();
            $is_result_available = SmResultStore::where([['class_id', $class_id], ['exam_type_id', $exam_id], ['section_id', $section_id], ['student_id', $student_id]])->where('academic_id', getAcademicId())->get();

            if ($is_result_available->count() > 0) {


                return view('backEnd.reports.mark_sheet_report_normal_print', [
                        'exam_types' => $exam_types,
                        'classes' => $classes,
                        'subjects' => $subjects,
                        'position' => $position,
                        'students' => $students,
                        'passed_subjects'=>$passed_subjects,
                        'student_result'=>$student_result,
                        'course_work_mark' =>$course_work_mark,
                        'course_work_percent'=>$course_work_percent,
                        'exam_percent'=>$exam_percent,
                        'exam_course_work'=>$exam_course_work,
                        'results_config' => $results_config,
                        'class' => $class_id,
                        'overall_marks'=>$overall_marks,
                        'overall_average'=>$overall_average,
                        'principal' => $principal,
                        'class_name' => $class_name,
                        'section' => $section,
                        'assign_class_teachers' => $assign_class_teachers,
                        'exams' => $exams,
                        'section_id' => $section_id,
                        'exam_type_id' => $exam_type_id,
                        'is_result_available' => $is_result_available,
                        'student_detail' => $student_detail,
                        'class_id' => $class_id,
                        'studentDetails' => $studentDetails,
                        'student_id' => $student_id,
                        'exam_details' => $exam_details,
                        'optional_subject' => $optional_subject,
                        'optional_subject_setup' => $optional_subject_setup,
                        'sum_of_mark' => $sum_of_mark,
                        'average_mark' => $average_mark,
                        'students_in_stream' => $students_in_stream,
                        'position_stream' => $position_stream,
                        'pass_mark' => $pass_mark,
                        'number_of_passed_subjects' => $number_of_passed_subjects,
                        'pass_average' => $pass_average,
                        'compulsory_subjects' => $compulsory_subjects,
                        'final_result' => $final_result,
                        'grade_table_view'=>$grade_table,
                        'student_position'=>$student_position
                    ]
                );
            }

        } catch (\Exception $e) {
            dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

}
