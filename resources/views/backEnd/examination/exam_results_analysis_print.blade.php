<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} - @lang('lang.results') @lang('lang.analysis') {{$class_name}} {{$section_name}}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/print/bootstrap.min.css"/>
    <script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/print/jquery.min.js"></script>
    <script type="text/javascript" src="{{asset('public/backEnd/')}}/vendors/js/print/bootstrap.min.js"></script>



        <meta http-equiv="Content-Type" content="charset=utf-8" />
        <style>
            @font-face {
                font-family: 'Leelawadee', sans-serif;
            font-weight: 400;
                                                                                             font-style: normal; // use the matching font-style here
            }
            body {
                font-family: 'Leelawadee', sans-serif
            }
        </style>

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
        font-size: 14px;
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
        font-size: 14px;
    }

    table {
        border-spacing: 0px;
        width: 90%;
        margin: auto;
        font-size: 14px;
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
                <p>Results Analysis</p>
            </td>
            <td style="text-aligh:center; border: #FFFFFF">
                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">@lang('lang.term'): <strong>{{ @$tabulation_details['exam_term']}}</strong> </p>
                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">@lang('lang.section'): <strong>{{ @$class->class_name}}({{ @$section->section_name}})</strong> </p>
                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">@lang('lang.academic_year'): <strong>{{ @$academic_year->title}} ({{ @$academic_year->year}})</strong> </p>
                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">@lang('lang.total') @lang('lang.students'): <strong>{{ @$total_students}}</strong> </p>
            </td>
        </tr>
    </table>

    <hr>

    <div class="table-responsive">
        <table class="mt-30 mb-20 table table-striped table-bordered ">
            <thead>
            <tr>
                <th style="border:1px solid black" rowspan="2">@lang('lang.sl')</th>
                <th style="text-align: left; border:1px solid black" rowspan="2">@lang('lang.subject') @lang('lang.name')</th>
                <th style="border:1px solid black" rowspan="2">@lang('lang.subject') @lang('lang.code')</th>
                @foreach($tabulation_details['grade_chart'] as $grade_details)
                @php
                $mark_parts = getGrades();
                $allstreams = json_decode($grade_details['streams'])
                @endphp
                @if(in_array($class_id,$allstreams))
                <th style="border:1px solid black" colspan="{{count($mark_parts)}}" class="subject-list"> {{$grade_details['grade_name']}}</th>
                @endif
                @endforeach
                <th style="border:1px solid black" rowspan="2">@lang('lang.total')</th>
                <th style="border:1px solid black;" rowspan="2">@lang('lang.pass') @lang('lang.rate')</th>
                <th style="border:1px solid black;" rowspan="2">@lang('lang.failure') @lang('lang.rate')</th>
            </tr>
            <tr>

                @foreach($tabulation_details['grade_chart'] as $grade_details)
                @php
                $mark_parts = getGrades();
                $allstreams = json_decode($grade_details['streams'])
                @endphp
                @if(in_array($class_id,$allstreams))
                <th  style="border:1px solid black" colspan="{{count($mark_parts)}}">{{$grade_details['start']}} - {{$grade_details['end']}}</th>
                @endif
                @endforeach
            </tr>

            </thead>
            <tbody>
            @php $count=1; @endphp
            @foreach($subjects as $subject)
            @php $grades_count_total = 0;
            @endphp
            <tr>
                <td style="padding-bottom: 0; padding-top: 0; border:1px solid black"> {{$count++}} </td>
                <td style="text-align: left; padding-bottom: 0; padding-top: 0; border:1px solid black"> {{$subject->subject->subject_name}} </td>
                <td style="padding-bottom: 0; padding-top: 0; border:1px solid black; text-align: center"> {{$subject->subject->subject_code}} </td>
                @php $grades_count_total_each=0; @endphp
                @foreach($tabulation_details['grade_chart'] as $grade_details)
                @php
                $allstreams = json_decode($grade_details['streams'])
                @endphp
                @if(in_array($class_id,$allstreams))
                @php
                $subject_ID = $subject->subject_id;
                $subject_Name = $subject->subject->subject_name;
                $grade_name = $grade_details['grade_name'];
                $grades_count = getGradesOfPart($subject_ID, $class_id, $section_id, $exam_term_id,$grade_name);
                $pass_count = getPassRate($subject_ID, $class_id, $section_id, $exam_term_id,$pass_mark);
                $mark_parts = getGrades();
                $grades_count_total_each+=$grades_count;
                @endphp
                <td style="padding-bottom: 0; padding-top: 0; border:1px solid black" colspan="{{count($mark_parts)}}" class="total">{{$grades_count}}</td>
                @endif
                @endforeach
                <td style="padding-bottom: 0; padding-top: 0; border:1px solid black">{{$grades_count_total_each}}</td>
                <td style="padding-bottom: 0; padding-top: 0; border:1px solid black">@if($grades_count_total_each>0) {{$pass_count}} ({{round($pass_count/$grades_count_total_each*100,1)}}%) @else 0 @endif</td>
                <td style="padding-bottom: 0; padding-top: 0; border:1px solid black">@if($grades_count_total_each>0) {{$grades_count_total_each-$pass_count}} ({{round(($grades_count_total_each-$pass_count)/$grades_count_total_each*100,1)}}%) @else 0 @endif</td>
            </tr>

            @endforeach
            </tbody>
        </table>
    </div>

</div>


</body>
</html>

<script>
    window.print();
</script>
