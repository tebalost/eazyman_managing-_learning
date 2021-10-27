<?php

namespace App\Http\Controllers;

use App\ApiBaseMethod;
use App\SmAssignSubject;
use App\SmClass;
use App\SmClassOptionalSubject;
use App\SmExamType;
use App\SmMarksGrade;
use App\SmMarkStore;
use App\SmPassDefinition;
use App\SmResultStore;
use App\SmSection;
use App\SmStreamResult;
use App\SmStudent;
use App\SmTemporaryMeritlist;
use App\YearCheck;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PassListController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function search(Request $request)
    {
        try {
            $exam_term_id = $request->exam;
            $class_id = $request->class;
            $section_id = $request->section;

            $exams = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            $is_existing_data = SmTemporaryMeritlist::where([['class_id', $class_id], ['section_id', $section_id], ['exam_id', $exam_term_id]])->first();
            if (empty($is_existing_data)) {
                Toastr::warning('Please Generate Results Before Viewing the Pass List', 'Warning');
                return redirect()->back();
            }

            $eligible_subjects = SmAssignSubject::where('class_id', $class_id)->where('section_id', $section_id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            $students = SmStudent::where([
                ['class_id', $request->class],
                ['section_id', $request->section]
            ])->where('academic_id', getAcademicId())->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();

            //PASS DEFINITION//
            $exam_course_work_exam_id = SmExamType::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('id', $request->exam)
                ->where('school_id', Auth::user()->school_id)->first();

            $quarter = $exam_course_work_exam_id->quarter;

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
            //PASS DEFINITION

            foreach ($students as $SingleStudent) {
                foreach ($eligible_subjects as $subject) {

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

                }
                $is_existing_data = SmTemporaryMeritlist::where([['admission_no', $SingleStudent->id], ['class_id', $request->class], ['section_id', $request->section], ['exam_id', $request->exam]])->first();
                if (empty($is_existing_data)) {
                    Toastr::warning('Please Generate Results Before Viewing the Pass List', 'Warning');
                    return redirect()->back();

                } else {
                    $insert_results = SmTemporaryMeritlist::find($is_existing_data->id);
                    $insert_results->result_name = "";
                    $insert_results->academic_id = getAcademicId();
                    $insert_results->save();
                }

            }
            return view('backEnd.pass_list.index', compact('exams','classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function index()
    {
        try {
            $exams = SmExamType::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            return view('backEnd.pass_list.index', compact('exams','classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
