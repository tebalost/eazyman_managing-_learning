<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('/')}}/public/backEnd/css/report/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="style.css"> -->
    <title>Mark Sheet Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-size: 14px;
            font-family: 'Leelawadee', sans-serif;
        }

        .student_marks_table {
            width: 100%;
            margin: 30px auto 0 auto;
        }

        .text_center {
            text-align: center;
        }

        p {
            margin: 0;
            font-size: 14px;
        }

        ul {
            margin: 0;
            padding: 0;
        }

        li {
            list-style: none;
        }

        td {
            border: 1px solid #000000;
            padding: .3rem;
            text-align: center;
        }

        th {
            border: 1px solid #000000;
            text-transform: capitalize;
            text-align: center;
            padding: 1rem;
            white-space: nowrap;
        }

        thead {
            font-weight: bold;
            text-align: center;
            color: #222;
            font-size: 14px
        }

        .custom_table {
            width: 90%;
        }

        .grade_table {
            width: 50%;
            padding: 0;
        }

        table#grade_table th{
            border: 1px solid #726E6D !important;
            padding: .1rem;
            background: #dddddd;
            font-weight: 600;
            color: #000000;
            font-size: 12px;
        }

        table.custom_table thead th {
            padding-right: 0;
            padding-left: 0;
            text-transform: uppercase;
        }

        table.custom_table thead tr > th {
            border: 1px solid #000000;
            padding: 0.3em;
            text-transform: uppercase;
            background: #dddddd;
            color: #000000;
        }

        table#grade_table tr > td {
            border: 1px solid #000000;
            padding: 0;
            font-size: 10px;
            font-weight: 500;
        }


        table.custom_table thead tr th .fees_title {
            font-size: 14px;
            font-weight: 600;
            border-top: 1px solid #726E6D;
            padding-top: 5px;
            margin-top: 5px;
            text-transform: uppercase;

        }

        .border-top {
            border-top: 0 !important;
        }

        .custom_table th ul li {
        }

        .custom_table th ul li p {
            margin-bottom: 5px;
            font-weight: 500;
            font-size: 14px;
            text-transform: uppercase;
        }

        tbody td {
            padding: 0.5rem;
            font-size: 14px;
        }

        table {
            border-spacing: 0px;
            width: 90%;
            margin: auto;
            font-size: 12px;
        }

        .fees_pay {
            text-align: center;
        }

        .border-0 {
            border: 0 !important;
        }

        .copy_collect {
            text-align: center;
            font-weight: 500;
            color: #000;
        }

        .copyies_text {
            display: flex;
            justify-content: space-between;
            margin: 30px 0;
        }

        .copyies_text li {
            text-transform: capitalize;
            color: #000;
            font-weight: 500;
            border-top: 1px dashed #ddd;
        }

        .text_left {
            text-align: left;
        }

        .italic_text {
        }

        .student_info {

        }

        .student_info li {
            display: flex;
        }

        .info_details {
            display: flex;
            flex-wrap: wrap;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .info_details li > p {
            flex-basis: 20%;
        }

        .info_details li {
            display: flex;
            flex-basis: 50%;
        }

        .school_name {
            text-align: center;
        }

        .numbered_table_row {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            align-items: center;
        }

        .numbered_table_row thead {
            border: 1px solid #222
        }

        .numbered_table_row h3 {
            font-size: 24px;
            text-transform: uppercase;
            margin-top: 15px;
            font-weight: 500;
            display: inline-block;
            border-bottom: 2px solid #222;
        }

        .numbered_table_row td {
            border: 1px solid #726E6D;
            padding: .4rem;
            font-weight: 400;
            color: #222;
        }

        @media print {
            footer {page-break-after: always;}
        }

        table.grade_table th td {
            border: 1px solid #726E6D !important;
            padding: 0;
            width: 90%;
            margin: auto;
            font-weight: 600;
            color: #222;
        }

        td.border-top.border_left_hide {
            border-left: 0;
            text-align: left;
            font-weight: 600;
        }

        .motto {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            color: #000;
            text-align: center;
            }

        .devide_td {
            padding: 0;
        }

        .devide_td p {
            border-bottom: 1px solid #222;
        }

        .ssc_text {
            font-size: 20px;
            font-weight: 500;
            color: #222;
            margin-bottom: 20px;
        }
    </style>
    @php
    $generalSetting= App\SmGeneralSettings::find(1);
    if(!empty($generalSetting)){
    $school_name =$generalSetting->school_name;
    $site_title =$generalSetting->site_title;
    $school_code =$generalSetting->school_code;

    $address =$generalSetting->address;
    $phone =$generalSetting->phone;
    $email =$generalSetting->email;
    }

    @endphp
</head>
<script>
    var is_chrome = function () {
        return Boolean(window.chrome);
    }
    if (is_chrome) {
        window.print();
        //    setTimeout(function(){window.close();}, 10000); 
        //give them 10 seconds to print, then close
    } else {
        window.print();
        //    window.close();
    }
</script>
<body onLoad="loadHandler();">

<div class="student_marks_table">
    <table class="custom_table">
        <thead>
        <tr>
            <td width="20%" border="0" style="border: #ffffff ">
                <div class="student-meta-img img-100">
                    <img style="max-width: 200px; max-height: 230px; height: auto; border-radius: 6px;"
                         src=" {{asset('/')}}{{generalSetting()->logo }}" alt="">
                </div>
            </td>
            <td border="0" style="border: #ffffff">
                <div class="school_name">
                    <h1 style="font-size: xxx-large">
                        {{isset(generalSetting()->school_name)?generalSetting()->school_name:'EazyMan School Management
                        ERP'}} </h1>
                    <p style="font-size: medium">{{isset(generalSetting()->address)?generalSetting()->address:'EazyMan
                        School Address'}}
                    <p style="font-size: medium; text-transform: lowercase;  "><strong>{{generalSetting()->phone}} |
                            {{generalSetting()->email}}</strong></p>
                    <hr style="color: #000000">
                    <p class="text_center" style="font-size: large"><strong> PERFORMANCE REPORT FOR </strong>
                        {{$exam_details->title}} ({{@$student_detail->academicYear->year}})</p>
                </div>
            </td>
        </tr>
        </thead>
    </table>
    @if($overall_marks!==0)
    @php
        $average_mark = $overall_average;
        $sum_of_mark = $overall_marks;
    @endphp
    @endif
    <table class="custom_table" border="0">
        <tr>
            <!-- first header  -->
            <th style="border: #ffffff" colspan="1" class="text_left">
                <ul class="info_details" style="margin-top: 0; margin-bottom: 0;">
                    <li><p>Name of Student &nbsp; : &nbsp; </p> &nbsp; <p><strong>
                                {{$student_detail->full_name}}</strong></p></li>
                    <li><p>Class: </p>
                        <p><strong> {{$class_name->class_name}}({{$section->section_name}})</strong></p></li>
                    <li><p>Student No: &nbsp; &nbsp; </p> &nbsp; <p>
                            <strong>{{$student_detail->admission_id_number}}</strong>
                        </p></li>
                    <li><p>Admission No: &nbsp; &nbsp; </p> &nbsp; <p><strong>
                                {{$student_detail->admission_no}}</strong></p>
                    </li>
                    <li><p>Total Marks: &nbsp; &nbsp; </p> &nbsp; <p><strong>
                                {{$sum_of_mark}}</strong></p>
                    </li>
                    <li><p>Term Average: &nbsp; &nbsp; </p> &nbsp; <p><strong>
                                {{$average_mark}}</strong></p>
                    </li>
                    @if($student_position == 1)
                    <li><p>Term Position: &nbsp; &nbsp; </p> &nbsp; <p><strong>
                        {{$position}} </strong> out of <strong>{{$students->count()}} Students</strong></p>
                    </li>
                    @endif
                    <li><p>Days Absent: &nbsp; &nbsp; </p> &nbsp; <p><strong>
                                </strong></p>
                    </li>
                    @if($student_position == 1)
                    <li><p>Position in Stream: &nbsp; &nbsp; </p> &nbsp; <p><strong>
                                {{$position_stream}}</strong> out of <strong>{{$students_in_stream}} Students</strong> in <strong>{{$class_name->class_name}}</strong></p>
                    </li>
                    @endif
                    <li><p>Subjects Passed: &nbsp; &nbsp; </p> &nbsp;
                        @if($passed_subjects == 0)
                        <p><strong>{{$number_of_passed_subjects}}</strong></p>
                        @else
                        <p><strong>{{$passed_subjects}}</strong></p>
                        @endif
                    </li>
                </ul>
            </th>
            <th style="border: #ffffff">
                @php
                $marks_grade = DB::table('sm_marks_grades')->where('school_id', Auth::user()->school_id)->get();
                $classes = DB::table('sm_classes')->where('school_id', Auth::user()->school_id)->get();
                $primary=[];
                $count=0;
                foreach($classes as $class){
                $class_detail=$class->class_name;
                if($class_detail=="GRADE 1" || $class_detail=="GRADE 2" || $class_detail=="GRADE 3" || $class_detail=="GRADE 4"
                || $class_detail=="GRADE 5" || $class_detail=="GRADE 6" || $class_detail=="GRADE 7"){
                $primary[$count]=$class_detail;
                $count++;
                }
                }

                @endphp

                @if(@$marks_grade && !in_array($class_name->class_name,$primary) && $grade_table_view == 1)
                <table id="grade_table">
                    <thead>
                    <tr>
                        <th>Range</th>
                        <th>Grade</th>
                        <th>Evaluation</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($marks_grade as $grade_d)
                    @php
                    $streams=json_decode($grade_d->streams);
                    @endphp
                    @if(isset($streams))
                    @if(in_array($class_id,$streams))
                    <tr style="margin-top: 0">
                        <td>{{$grade_d->percent_from}} - {{$grade_d->percent_upto}}</td>
                        <td>{{$grade_d->grade_name}}</td>
                        <td class="text-left">{{$grade_d->description}}</td>
                    </tr>
                    @endif
                    @endif
                    @endforeach

                    </tbody>
                </table>
                @endif
            </th>
        </tr>

    </table>

    <br>

    <table class="custom_table">
        <thead>
        <tr>
            <!-- first header  -->
            <th rowspan="2">Code</th>
            <th colspan="2" rowspan="2">Name of Subject</th>
            @if(count($exam_course_work)>0)
            <th colspan="4">Student Performance (%)</th>
            @else
            <th rowspan="2">Top in Class (%)</th>
            <th colspan="3">Student Performance (%)</th>
            @endif
            <th rowspan="2">Teacher's Remarks</th>
            <th rowspan="2">Teacher</th>

        </tr>
        <tr>
            @if(count($exam_course_work)>0)
            <th style="width: 5%;">Course<br> work ({{@$course_work_percent}}%)</th>
            <th>Exam<br> Mark ({{@$exam_percent}}%)</th>
            <th>Final<br> Mark</th>
            <th>Grade</th>
            @else
            <th>Mark</th>
            <th>Grade</th>
            <th>Pass/Fail</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @php
        $main_subject_total_gpa=0;

        $Optional_subject_count=$subjects->count();
        @endphp
        @php $sum_gpa= 0; $resultCount=1; $subject_count=1; $tota_grade_point=0; $this_student_failed=0; $count=1;

        $class_optional_subjects = DB::table('sm_optional_subject_assigns')
        ->where('sm_optional_subject_assigns.school_id',Auth::user()->school_id)
        ->where('sm_optional_subject_assigns.class_id', '=', $class_id)
        ->where('sm_optional_subject_assigns.section_id', '=', $section_id)
        ->where('sm_optional_subject_assigns.academic_id', getAcademicId())
        ->groupBy('subject_id')->get();

        $optional_subjects = [];
        foreach($class_optional_subjects as $options){
        $optional_subjects[]=$options->subject_id;
        }
        $optional = $optional_subjects;

        @endphp

        @foreach($course_work_mark as $marks => $data)
        @php

        $subject_ID=$data['mark_sheet']['subject_id'];
        $student_id = $data['mark_sheet']['student_id'];

        @endphp

        @php
        $optional_subjects_student = DB::table('sm_optional_subject_assigns')
        ->where('sm_optional_subject_assigns.school_id',Auth::user()->school_id)
        ->where('sm_optional_subject_assigns.class_id', '=', $class_id)
        ->where('sm_optional_subject_assigns.section_id', '=', $section_id)
        ->where('sm_optional_subject_assigns.subject_id', '=', $subject_ID)
        ->where('sm_optional_subject_assigns.student_id', '=', $student_id)
        ->where('sm_optional_subject_assigns.academic_id', getAcademicId())
        ->groupBy('subject_id')->first();
        @endphp

        @if($data['mark_sheet']['total_marks']>=0 && $data['mark_sheet']['is_absent']==0 && !in_array($subject_ID, $optional) || isset($optional_subjects_student))
        {{--Get subject teacher name--}}

        @php

        $academic_id = $student_detail['academic_id'];

        $teacher_assign=DB::table('sm_assign_subjects') -> where('school_id',
        Auth::user()->school_id)->where('subject_id', $subject_ID)->where('class_id',
        $class_id)->where('section_id', $section_id)->where('academic_id', $academic_id)->first();

        $teacher_info=DB::table('sm_staffs') -> where('school_id',Auth::user()->school_id)->where('id', $teacher_assign->teacher_id)->first();

        $highest=DB::table('sm_mark_stores')
        -> where('school_id', Auth::user()->school_id)
        -> where('subject_id', $subject_ID)
        -> where('class_id', $class_id)
        -> where('section_id', $section_id)
        -> where('academic_id', $academic_id)
        -> where('exam_term_id',$exam_type_id)->orderBy('total_marks', 'desc')->first();


        @endphp
        <tr>
            <td class="border-top"
                style="border-bottom: 1px solid black;"><strong>{{@$data['mark_sheet']['subject_code']}}</strong>
            </td>
            @if(count($exam_course_work)==0)
            <td colspan="2" class="border-top"
                style="text-align: left;padding-left: 15px;border-bottom: 1px solid black;">
                <p><strong>{{@$data['mark_sheet']['subject_name']}}</strong></p>
            </td>
            @else
            <td colspan="2" class="border-top"
                style="text-align: left;padding-left: 15px;border-bottom: 1px solid black;">
                <p><strong>{{@$data['coursework_marks']['subject_name']}}</strong></p>
            </td>
            @endif

            @if(count($exam_course_work)>0)
            <td class="border-top"
                style="border-bottom: 1px solid black; width: 5%;"><strong>{{$data['coursework_marks']['course_work_mark']}}</strong></td>
            <td class="border-top"
                style="border-bottom: 1px solid black;"><strong>{{$data['coursework_marks']['exam_mark']}}</strong></td>
            @if($data['coursework_marks']['final_mark']>=50)
            <td  class="border-top"
                 style="border-bottom: 1px solid black;"><strong>{{$data['coursework_marks']['final_mark']}}</strong></td>
            @else
            <td  class="border-top"
                 style="border-bottom: 1px solid black;"><p style="font-weight: 600; color: #FF0000">{{$data['coursework_marks']['final_mark']}}</p></td>
            @endif
            @php
            $result = markGpa($data['coursework_marks']['final_mark']);
            $studentResult = markGpaResults($data['coursework_marks']['final_mark']);
            @endphp
            <td class="border-top"
                style="border-bottom: 1px solid black;">
                <p>

                    @foreach($studentResult as $performance)
                    @php $allstreams = json_decode($performance->streams); @endphp
                    @if(in_array($class_id,$allstreams))
                    @php
                    $grade_name = $performance->grade_name;
                    $grade_comment = $performance->description;
                    @endphp
                    @endif
                    @endforeach

                    <strong>{{@$grade_name}}</strong>
                </p>
            </td>
            @else
            <td class="border-top"
                style="border-bottom: 1px solid black;">
                @if($highest->total_marks>=50)
                <p style="font-weight: 600">
                <p><strong>{{@$highest->total_marks}}</strong></p>
                @else
                <p style="font-weight: 600; color: #FF0000">
                    <strong>{{@$highest->total_marks}}</strong></p>
                @endif
            </td>
            <td class="border-top"
                style="border-bottom: 1px solid black; background: #dddddd">
                @if($data['mark_sheet']['total_marks']>=50)
                <p style="font-weight: 600">
                    {{@$data['mark_sheet']['total_marks']}}
                </p>
                @else
                <p style="color: #FF0000; font-weight: 600">
                    {{@$data['mark_sheet']['total_marks']}}
                </p>
                @endif
                {{-- {{@$subject_result[2][0]}} --}}
            </td>
            <td class="border-top"
                style="border-bottom: 1px solid black;">
                <p>
                    @php
                    $result = markGpa($data['mark_sheet']['total_marks']);
                    $studentMark = averageResult($student_detail['average_mark']);
                    $studentResult = markGpaResults($data['mark_sheet']['total_marks']);
                    @endphp

                    @if(in_array($class_name->class_name,$primary))

                    @foreach($studentMark as $performance)
                    @php $allstreams = json_decode($performance->streams); @endphp
                    @if(in_array($class_id,$allstreams))
                    @php
                    $grade_result = $performance->result_name;
                    @endphp
                    @endif
                    @endforeach

                    <strong> {{$grade_result}} </strong>
                    @else

                    @foreach($studentResult as $performance)
                    @php $allstreams = json_decode($performance->streams); @endphp
                    @if(in_array($class_id,$allstreams))
                    @php
                    $grade_name = $performance->grade_name;
                    $grade_comment = $performance->description;
                    @endphp
                    @endif
                    @endforeach
                   <strong>{{@$grade_name}}</strong>
                    @endif
                </p>
                {{-- {{@$subject_result[2][0]}} --}}
            </td>
            <td>
                @if($data['mark_sheet']['total_marks']>=50)
                Pass
                @else
                Fail
                @endif
            </td>
            @endif


            @php
            $main_subject_total_gpa += $result->gpa;
            @endphp

            <td class="border-top"
                style="border-bottom: 1px solid black;">
                <p style="font-style: italic">

                    @if(isset($streams))
                    @php $streams=json_decode($result->streams); @endphp
                    @else
                    @php $streams = []; @endphp
                    @endif

                    @if(isset($data->teacher_remarks))
                    {{@$data->teacher_remarks}}
                    @else
                    {{@$grade_comment}}
                    @endif
                </p>
            </td>

            <td class="border-top"
                style="border-bottom: 1px solid black;">
                <p>
                    {{substr(@$teacher_info->first_name,0,1)}} {{@$teacher_info->last_name}}
                </p>
            </td>
            @php
            $count++
            @endphp

        </tr>

        @elseif($data['mark_sheet']['total_marks']>=0 && $data['mark_sheet']['is_absent']==1 && !in_array($subject_ID, $optional) || isset($optional_subjects_student))
        @php
        $academic_id = $student_detail['academic_id'];

        $teacher_assign=DB::table('sm_assign_subjects') -> where('school_id',
        Auth::user()->school_id)->where('subject_id', $subject_ID)->where('class_id',
        $class_id)->where('section_id', $section_id)->where('academic_id', $academic_id)->first();

        $teacher_info=DB::table('sm_staffs') -> where('school_id',Auth::user()->school_id)->where('id', $teacher_assign->teacher_id)->first();

        $highest=DB::table('sm_mark_stores')
        -> where('school_id', Auth::user()->school_id)
        -> where('subject_id', $subject_ID)
        -> where('class_id', $class_id)
        -> where('section_id', $section_id)
        -> where('academic_id', $academic_id)
        -> where('exam_term_id',$exam_type_id)->orderBy('total_marks', 'desc')->first();



        @endphp
        <tr>

            @if(count($exam_course_work)==0)
            <td class="border-top"
                style="border-bottom: 1px solid black;"><strong>{{@$data['mark_sheet']['subject_code']}}</strong>
            </td>
            <td colspan="2" class="border-top"
                style="text-align: left;padding-left: 15px;border-bottom: 1px solid black;">
                <p><strong>{{@$data['mark_sheet']['subject_name']}}</strong></p>
            </td>
            @else
            <td class="border-top"
                style="border-bottom: 1px solid black;"><strong>{{@$data['coursework_marks']['subject_code']}}</strong>
            </td>
            <td colspan="2" class="border-top"
                style="text-align: left;padding-left: 15px;border-bottom: 1px solid black;">
                <p><strong>{{@$data['coursework_marks']['subject_name']}}</strong></p>
            </td>
            @endif
            @if(count($exam_course_work)>0)
            <td class="border-top"
                style="border-bottom: 1px solid black; background: #dddddd">
                ✘
            </td>
            @else
            <td class="border-top"
                style="border-bottom: 1px solid black;">
                @if($highest->total_marks>=50)
                <p style="font-weight: 600">
                <p><strong>{{@$highest->total_marks}}</strong></p>
                @else
                <p style="font-weight: 600; color: #FF0000">
                    <strong>{{@$highest->total_marks}}</strong></p>
                @endif
            </td>
            @endif
            <td class="border-top"
                style="border-bottom: 1px solid black; background: #dddddd">
                ✘
            </td>
            <td class="border-top"
                style="border-bottom: 1px solid black;">
                ✘
            </td>
            <td>
                ✘
            </td>
            <td class="border-top"
                style="border-bottom: 1px solid black;">
                ✘
            </td>

            <td class="border-top"
                style="border-bottom: 1px solid black;">
                <p>
                    {{substr(@$teacher_info->first_name,0,1)}} {{@$teacher_info->last_name}}
                </p>
            </td>
            @php
            $count++
            @endphp

        </tr>
        @endif
        @endforeach
        <tr style="border: #0b0b0b 2px solid">
            <td colspan="4" style="text-align: left; border: #0b0b0b 2px solid"><strong>Term Average</strong></td>

            <td colspan="3" style="border: #0b0b0b 2px solid">

                @if($average_mark>=50)
                <p style="font-weight: 600">
                    <strong>{{$average_mark}}</strong>
                </p>
                @else
                <p style="color: #FF0000; font-weight: 600">
                    <strong>{{$average_mark}}</strong>
                </p>
                @endif
            </td>

            <td colspan="2" style="border: #0b0b0b 2px solid"></td>
        </tr>
        @php
        $report_status = DB::table('sm_temporary_meritlists')->where([['class_id', $class_id], ['section_id', $section_id], ['exam_id', $exam_type_id], ['student_id', $student_detail->id]])
        ->where('academic_id',getAcademicId())->first();
        @endphp
        @php
        $studentResult = averageResult($average_mark);

        $class_teacher_remark = $principal_remark ="";
        @endphp
        @foreach($studentResult as $performance)
        @php $allstreams = json_decode($performance->streams); @endphp
        @if(in_array($class_id,$allstreams))
        @php
        
        @endphp

        @if(isset($report_status))

            @if($report_status->class_teacher_remark=="")
                @php $class_teacher_remark = $performance->class_teacher_remark; @endphp
            @else
                @php $class_teacher_remark = $report_status->class_teacher_remark; @endphp
            @endif

            @if($report_status->principal_remark=="")
                @php $principal_remark = $performance->principal_remark; @endphp
            @else
                @php
                $principal_remark = $report_status->principal_remark;
                @endphp
            @endif
        @endif
        @endif
        @endforeach

        @if($final_result!=="")
        <tr style="border: #0b0b0b 2px solid">
            <td colspan="4" style="text-align: left;border: #0b0b0b 2px solid"><strong>Result</strong></td>
            <td colspan="3" style="text-align: center;border: #0b0b0b 2px solid"><strong>{{$final_result}}</strong></td>
            <td colspan="2"></td>
        </tr>
        @endif
        </tbody>
    </table>
    @if($final_result!=="")

    @foreach($results_config as $result_settings)
    @php $stream = json_decode($result_settings->streams);  @endphp
    @if(in_array($class_id,$stream))
    @php
    $result_setting_view = $result_settings->description;
    @endphp
    @endif
    @endforeach

    @if($result_setting_view!=="")
    <table class="custom_table" border="0">

        <tr>
            <td colspan="3" class="text_center" border="0" style="border: #ffffff">
                <p class="text_center" style="font-size: large"><strong>@lang('lang.pass') @lang('lang.ranges')
                        @lang('lang.and') @lang('lang.performance') @lang('lang.level')</strong></p>
            </td>

        </tr>
        <tr>
            <!-- first header  -->
            <th colspan="1" class="text_left">
                Performance
            </th>
            <th>
                Score Range
            </th>
            <th>
                Skills and Competences displayed
            </th>
        </tr>
        @foreach($results_config as $result_settings)
        @php $stream = json_decode($result_settings->streams);  @endphp
        @if(in_array($class_id,$stream))
        <tr>
            <td @if($result_settings->result_name==$final_result) style="background: #ddd" @endif>{{$result_settings->result_name}}</td>
            <td @if($average_mark>=$result_settings->percent_from && $average_mark<=$result_settings->percent_upto) style="background: #ddd" @endif>{{$result_settings->percent_from}}-{{$result_settings->percent_upto}}%</td>
            <td style="text-align: left">{{$result_settings->description}}</td>
        </tr>
        @endif
        @endforeach

    </table>
    @endif
    @endif
    <script>
        function myFunction(value, subject) {
            if (value != "") {
                var res = Number(value / subject).toFixed(2);
            } else {
                var res = 0;
            }
            document.getElementById("main_subject_total_gpa").innerHTML = res;
        }


        myFunction({
        {
            $main_subject_total_gpa
        }
        },
        {
            {
                $Optional_subject_count
            }
        }
        )
        ;


    </script>


    <table class="custom_table border-0">
        <tbody>
        <tr>
            <td class="border-0"><p style="text-align:left; float:left;"><strong>Class Teacher Remarks:</strong></p></td>
            <td class="border-0" style="width: 60%">
                <p style="font-size:14px !important; border-bottom:1px solid gray; font-style: italic; text-align: left">
                    {{$class_teacher_remark}}</p>

            </td>
            <td class="border-0">
                <p style="text-align:center;"><strong><em>{{ $assign_class_teachers->teacher->full_name }}</em></strong></p>
                <p style="text-align:center; border-top:1px solid #0b0b0b; display:inline-block; margin-top:10px;">
                    <strong>(Class Teacher)</strong></p>
            </td>
        </tr>
        <tr>
            <td class="border-0"><p style="text-align:left; float:left;"><strong>Principal Remarks:</strong></p></td>
            <td class="border-0">
                <p style="font-size:14px !important; border-bottom:1px solid gray; text-align: left; font-style: italic">
                {{$principal_remark}}
                </p>
            </td>
            <td class="border-0">
                <p style="text-align:left;"><br></p>
                <p style="text-align:center; border-top:1px solid #0b0b0b; display:inline-block; margin-top:10px;"><strong><em>{{$principal->full_name}}</em></strong><br>
                    <strong>(Principal)</strong></p>
            </td>
        </tr>
        </tbody>
    </table>
    <table class="custom_table border-0">
        <tbody>
        <tr>
            <td class="border-0"><p class="result-date"
                                    style="text-align:left; float:left; display:inline-block; margin-top:10px; padding-left: 0;">
                    @lang('lang.school_reopen_date') : <b> {{date_format(date_create(generalSetting()->re_open_date),"l, jS F Y")}}</b></b>
                </p></td>

            <td class="border-0"><p class="result-date"
                                    style="text-align:right; float:right; display:inline-block; margin-top:10px; padding-left: 0;">
                    @php
                    $date = date('Y-m-d H:i:s');

                    @endphp
                    @lang('lang.date_of_publication_of_result') : <b> {{date_format(date_create($date)," j F Y, g:i A")}}</b></b>
                </p></td>
        </tr>


        </tbody>
    </table>
</div>
<div class="motto">
  <p>{{generalSetting()->registration_no}} - {{generalSetting()->motto}}</p>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="{{ asset('/') }}/public/backEnd/js/fees_invoice/jquery-3.2.1.slim.min.js"></script>
<script src="{{ asset('/') }}/public/backEnd/js/fees_invoice/popper.min.js"></script>
<script src="{{ asset('/') }}/public/backEnd/js/fees_invoice/bootstrap.min.js"></script>


{{--
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
--}}
</body>
</html>
