<!DOCTYPE html>
<html lang="en">
<head>
  <title>@lang('lang.section') @lang('lang.list')</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"> 
  <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/print/bootstrap.min.css"/>
  <script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/print/jquery.min.js"></script>
  <script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/print/bootstrap.min.js"></script>
</head>
<style>
    * {
        margin: 0;
        padding: 0;
    }

    body {
        font-size: 12px;
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
        font-size: 10px;
        text-transform: capitalize;
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
        padding: 0;
        text-align: center;
    }

    th {
        border: 1px solid #000000;
        text-transform: capitalize;
        text-align: center;
        padding: 0;
        white-space: nowrap;
    }

    thead {
        font-weight: bold;
        text-align: center;
        color: #000;
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
        border: 1px solid #000000 !important;
        padding: 0;
        background: #351681;
        font-weight: 600;
        color: #FFFFFF;
        font-size: 12px;
    }

    table.custom_table thead th {
        padding-right: 0;
        padding-left: 0;
        text-transform: uppercase;
    }

    table.custom_table thead tr > th {
        border: 1px solid #000000;
        padding: 0;
        text-transform: uppercase;
        background: #351681;
        color: #ffffff;
    }

    table#grade_table tr > td {
        border: 1px solid #000000;
        padding: 0;
        font-size: 10px;
        font-weight: 600;
    }

    /* tr:last-child td {
        border: 0 !important;
    }
    tr:nth-last-child(2) td {
        border: 0 !important;
    }
    tr:nth-last-child(3) td {
        border: 0 !important;
    } */

    table.custom_table thead tr th .fees_title {
        font-size: 14px;
        font-weight: 600;
        border-top: 1px solid #000000;
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

    /* tbody td p{
      text-align: right;
    } */
    tbody td {
        padding: 0;
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
        border: 1px solid #000
    }

    .numbered_table_row h3 {
        font-size: 24px;
        text-transform: uppercase;
        margin-top: 15px;
        font-weight: 500;
        display: inline-block;
        border-bottom: 2px solid #000;
    }

    .numbered_table_row td {
        border: 1px solid #000000;
        padding: 0;
        font-weight: 600;
        color: #000;
    }

    table.grade_table th td {
        border: 1px solid #000000 !important;
        padding: 0;
        width: 90%;
        margin: auto;
        font-weight: 600;
        color: #000;
    }

    td.border-top.border_left_hide {
        border-left: 0;
        text-align: left;
        font-weight: 600;
    }

    .devide_td {
        padding: 0;
    }

    .devide_td p {
        border-bottom: 1px solid #000;
    }

    .ssc_text {
        font-size: 20px;
        font-weight: 500;
        color: #000;
        margin-bottom: 20px;
    }

</style>
<body>
 

@php 
    $generalSetting= App\SmGeneralSettings::find(1); 
    if(!empty($generalSetting)){
        $school_name =$generalSetting->school_name;
        $site_title =$generalSetting->site_title;
        $school_code =$generalSetting->school_code;
        $address =$generalSetting->address;
        $phone =$generalSetting->phone; 
    } 
    $exam=App\SmExamType::find(@$exam_id);
    $class=App\SmClass::find(@$class_id);
    $section=App\SmSection::find(@$section_id);
@endphp
<div class="container-fluid"> 
                    
                    <table  cellspacing="0" width="100%">
                        <tr>

                            <td style=" border: #FFFFFF">
                                <h1 style="font-size:22px !important" class="text-white"> <strong>{{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}}</strong> </h1>
                                <p style="font-size:18px !important" class="text-white mb-0"> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </p>
                          </td>
                            <td style="text-aligh:center; border: #FFFFFF">
                                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">@lang('lang.section'): <strong>{{ @$class->class_name}}({{ @$section->section_name}})</strong> </p>
                                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">@lang('lang.academic_year'): <strong>{{ @$academic_year->title}} ({{ @$academic_year->year}})</strong> </p>
                                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">@lang('lang.total') @lang('lang.students'): <strong>{{ @$total_students}}</strong> </p>
                                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">@lang('lang.section') @lang('lang.teacher'): <strong>{{ @$class_teacher}}</strong> </p>
                          </td>
                        </tr>
                    </table>

                    <hr>



    <table width="100%">
        <thead>
        <tr>
            <th rowspan="2">@lang('lang.no')</th>
            <th rowspan="2">@lang('lang.student') @lang('lang.name')</th>
            @foreach($subjects as $subject)
            @php
            $subject_ID = $subject->subject_id;
            $subject_Name = (strlen($subject->subject->subject_name) > 12) ? substr($subject->subject->subject_name, 0, 12) . '...' : $subject->subject->subject_name;
            $mark_parts = App\SmAssignSubject::getNumberOfSubjects($class_id, $section_id);



            $teacher_id = App\SmAssignSubject::where('class_id',$class_id)
            ->where('section_id', $section_id)
            ->where('subject_id',$subject_ID)
            ->where('academic_id',$academic_year->id)
            ->where('school_id',generalSetting()->school_id)
            ->first();
            if(isset($teacher_id)){
                $subject_teacher = App\SmStaff::where('id',$teacher_id->teacher_id)->where('school_id',generalSetting()->school_id)->where('active_status',1)->first();
                $teacher=substr($subject_teacher->first_name,0,1)." ".$subject_teacher->last_name;
            }else{
                $teacher="N/A";
            }
            @endphp
            <th colspan="{{count($mark_parts)}}" class="subject-list"><p style="transform: rotateZ(180deg); transform-origin: 50% 50%; text-align: center; writing-mode: vertical-rl;"> {{$subject_Name}} <br> <i style="font-weight: 400">{{$teacher}}</i></p></th>
            @endforeach
            <th rowspan="2"><p style="transform: rotateZ(180deg); transform-origin: 50% 50%; text-align: center; writing-mode: vertical-rl;">@lang('lang.total') @lang('lang.subjects')</p></th>

        </tr>
        <tr>

            @foreach($subjects as $subject)
            @php
            $subject_ID = $subject->subject_id;
            $subject_Name = (strlen($subject->subject->subject_name) > 12) ? substr($subject->subject->subject_name, 0, 12) . '...' : $subject->subject->subject_name;
            $subject_Code = $subject->subject->subject_code;
            $mark_parts = App\SmAssignSubject::getNumberOfSubjects($class_id, $section_id);
            @endphp
            <th colspan="{{count($mark_parts)}}" class="subject-list" style="text-align: center"><p style="text-align: center"> {{$subject_Code}}</p></th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @php $count=1; @endphp
        @foreach($students as $student)
        @php $subjectsCount=0; $average=0; $this_student_failed=0; $tota_grade_point= 0; $tota_grade_point_main=
        0; $marks_by_students = 0; $result = ""; @endphp
        @php
        $optional_subject=App\SmOptionalSubjectAssign::where('student_id','=',$student->id)->where('session_id','=',$student->session_id)->first();
        $num_sub = 0;
        @endphp
        <tr>
            <td style="padding-bottom: 0; padding-top: 0; border-bottom:1px solid black">{{$count++}}</td>
            <td style="padding-left:2px; padding-bottom: 0; padding-top: 0; border-bottom:1px solid black"><strong><p style="text-align: left"> {{$student->last_name}} {{$student->first_name}}</p></strong></td>
            @foreach($subjects as $subject)
            @php

            $subject_ID = $subject->subject_id;
            $subject_Name = $subject->subject->subject_name;
            $mark_parts = App\SmAssignSubject::getNumberOfSubjects($class_id, $section_id);

            $optional_subject_marks=DB::table('sm_optional_subject_assigns')
            ->join('sm_mark_stores','sm_mark_stores.subject_id','=','sm_optional_subject_assigns.subject_id')
            ->where('sm_optional_subject_assigns.student_id','=',$student->id)
            ->first();

            $optional_subjects_student = DB::table('sm_optional_subject_assigns')
            ->where('sm_optional_subject_assigns.school_id',Auth::user()->school_id)
            ->where('sm_optional_subject_assigns.class_id', '=', $class_id)
            ->where('sm_optional_subject_assigns.section_id', '=', $section_id)
            ->where('sm_optional_subject_assigns.subject_id', '=', $subject_ID)
            ->where('sm_optional_subject_assigns.student_id', '=', $student->id)
            ->where('sm_optional_subject_assigns.academic_id', getAcademicId())
            ->groupBy('subject_id')->first();

            $doing = "✘";
            if(isset($optional_subjects_student)){
                $doing = "✔";
                $num_sub+=1;
            }

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
            if(!in_array($subject_ID, $optional)){
                $doing = "✔";
                $num_sub+=1;
            }

            @endphp

            {{--TODO: CHECK FROM HERE IF THE STUDENT IS DOING THIS SUBJECT OR NOT--}}


            <td style="padding-bottom: 0; padding-top: 0; border-bottom:1px solid black" colspan="{{count($mark_parts)}}" class="subject-list">
                {{$doing}}
            </td>
            @endforeach

            <td class="subject-list">
                <strong>{{$num_sub}}</strong>
            </td>

        </tr>

        @endforeach
        </tbody>
    </table>

</div>
 

</body>
</html>

<script>
    window.print();
</script>
