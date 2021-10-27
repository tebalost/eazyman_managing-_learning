<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Results Sheet </title>
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
        font-size: 11px;
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
        padding-top: 0;
        margin-top: 5px;
        text-transform: uppercase;

    }

    .border-top {
        border-top: 0 !important;
    }

    .custom_table th ul li {
    }

    .custom_table th ul li p {
        margin-bottom: 0;
        font-weight: 500;
        font-size: 10px;
        text-transform: uppercase;
    }

    /* tbody td p{
      text-align: right;
    } */
    tbody td {
        padding: 0;
        font-size: 12px;
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
        border-top: 1px dashed #000;
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
        margin-bottom: 1px;
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
        margin-bottom: 1px;
    }
</style>
<body>


@php
$generalSetting= App\SmGeneralSettings::find(1);
if(!empty($generalSetting)){
$school_name =$generalSetting->school_name;
$school_id = $generalSetting->school_id;
$site_title =$generalSetting->site_title;
$school_code =$generalSetting->school_code;
$address =$generalSetting->address;
$phone =$generalSetting->phone;
}

$optional_subject_setup = "";
@endphp

<div class="container-fluid">


    <table  cellspacing="0" width="100%">
        <tr>
            <td  style=" border: #FFFFFF">
                <h3 style="font-size:22px !important" class="text-white"> {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3>
                <p style="font-size:18px !important" class="text-white mb-0"> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </p>

            </td  style=" border: #FFFFFF">
            <td  style=" border: #FFFFFF">
                <p style="font-size:14px !important; border-bottom:1px solid gray;" align="left" class="text-white">@lang('lang.exam'):  <strong>{{$tabulation_details['exam_term']}}</strong> </p>
                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">@lang('lang.class'): <strong>{{ $tabulation_details['student_class']}}</strong> </p>
                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">@lang('lang.academic_year'): <strong>{{ @$academic_year->title}} ({{ @$academic_year->year}})</strong> </p>

            </td>
        </tr>
    </table>

    <table style="width: 100%;">
        <thead>
        <tr>
            <th rowspan="2">@lang('lang.position')</th>
            <th rowspan="2" style="text-align: left">@lang('lang.student') @lang('lang.name')</th>
            @foreach($subjects as $subject)
            @php
            $subject_ID = $subject->subject_id;
            $subject_Name = $subject->subject->subject_name;
            $subject_Code = $subject->subject->subject_code;
            $mark_parts = App\SmAssignSubject::getNumberOfPartStream($subject_ID, $class_id, $exam_term_id);
            @endphp
            <th colspan="{{count($mark_parts)}}" class="subject-list"> {{$subject_Name}}</th>
            @endforeach
            <th rowspan="2">@lang('lang.total_mark')</th>
            <th rowspan="2">@lang('lang.average')</th>
            @if ($optional_subject_setup!='')
            <th rowspan="2">@lang('lang.results')</th>
            @else
            <th rowspan="2">@lang('lang.results')</th>
            @endif


        </tr>
        <tr>

            @foreach($subjects as $subject)
            @php
            $subject_ID = $subject->subject_id;
            $subject_Name = $subject->subject->subject_name;
            $subject_Code = $subject->subject->subject_code;
            $mark_parts = App\SmAssignSubject::getNumberOfPartStream($subject_ID, $class_id, $exam_term_id);
            @endphp

            <th>{{$subject_Code}}</th>
            @endforeach


        </tr>
        </thead>
        <tbody>
        @php $count=1; @endphp
        @foreach($results_data as $result)
        @php
        $student=App\SmStudent::where('id','=',$result->student_id)->where('active_status','=',1)->first();

        @endphp
        @if(isset($student))
        @php $subjectsCount=0; $average=0; $this_student_failed=0; $tota_grade_point= 0; $tota_grade_point_main= 0; $marks_by_students = 0; @endphp
        @php
        $optional_subject=App\SmOptionalSubjectAssign::where('student_id','=',$student->id)->where('session_id','=',$student->session_id)->first();
        @endphp
        <tr style="padding: 0">
            <td style="padding-bottom: 0; padding-top: 0; border-bottom:1px solid black"><strong>{{$count++}}</strong></td>
            <td style="padding-bottom: 0; padding-top: 0; border-bottom:1px solid black"><strong><p style="text-align: left">{{$student->last_name}} {{$student->first_name}}</p></strong></td>

            @foreach($subjects as $subject)
            @php
            $subject_ID = $subject->subject_id;
            $subject_Name = $subject->subject->subject_name;
            $subject_Code = $subject->subject->subject_code;
            $mark_parts = App\SmAssignSubject::getMarksOfPartStream($student->id, $subject_ID, $class_id,
            $exam_term_id);

            $optional_subject_marks=DB::table('sm_optional_subject_assigns')
            ->join('sm_mark_stores','sm_mark_stores.subject_id','=','sm_optional_subject_assigns.subject_id')
            ->where('sm_optional_subject_assigns.student_id','=',$student->id)
            ->first();
            $stream_pos = App\SmStreamResult::where('student_id','=',$student->id)->where('exam_id','=',$exam_term_id)->first();
            $stream_position = $stream_pos->stream_position;
            $average = $stream_pos->average;
            @endphp

            <td style="padding-bottom: 0; padding-top: 0; border-bottom:1px solid black">
                @php
                $tola_mark_by_subject = App\SmAssignSubject::getSumMarkStream($student->id, $subject_ID, $class_id, $exam_term_id);
                $marks_by_students = $marks_by_students + $tola_mark_by_subject;
                @endphp
                @if ($tola_mark_by_subject>0)
                @php
                $subjectsCount++;

                @endphp
                {{$tola_mark_by_subject }}
                @endif
            </td>


            @endforeach
            <td style="padding-bottom: 0; padding-top: 0; border-bottom:1px solid black">{{$marks_by_students}}
                @php $marks_by_students = 0; @endphp
            </td>

            <td style="padding-bottom: 0; padding-top: 0; border-bottom:1px solid black">
                <strong>{{ $average }}</strong>
            </td>
            <td style="padding-bottom: 0; padding-top: 0; border-bottom:1px solid black">
                @php
                $studentResult = averageResult($average);
                $final_result = $class_teacher_remark = $principal_remark ="";
                @endphp
                @foreach($studentResult as $performance)
                @php $allstreams = json_decode($performance->streams); @endphp
                @if(in_array($class_id,$allstreams))
                @php
                $final_result = $performance->result_name;
                @endphp

                @endif
                @endforeach
                {{$final_result}}
            </td>

        </tr>
        @endif
        @endforeach
        </tbody>
    </table>

    <table  cellspacing="0" width="100%">
        <tr>
            <td style=" border: #FFFFFF">
                <p style="padding-top:10px; text-align:right; float:right; border-top:1px solid #ddd; display:inline-block; margin-top:50px;">
                    ( Principal )</p>
            </td>
        </tr>

    </table>


</div>
</body>
</html>
