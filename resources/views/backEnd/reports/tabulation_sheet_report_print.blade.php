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
    table.tabluationsheet {
        width: 100%;
    }

    .tabluationsheet th, .tabluationsheet td {
        border: 1px solid #ddd;
        font-size: 11px;
        padding: 5px;
    }


    .tabluationsheet td {
        text-align: center;
        padding: 3px;
    }

    body {
        padding: 0;
        margin-top: 35px;
    }

    html {
        padding: 0px;
        margin: 0px;


    }

    .container-fluid {
        padding-bottom: 50px;
    }

    h1, h2, h3, h4 {

        font-weight: 500;
        margin-bottom: 15px;
    }

    .gradeChart tbody td {
        padding: 0;
        border-collapse: 1px solid #ddd;
    }

    table.gradeChart {
        padding: 0px;
        margin: 0px;
        width: 60%;
        text-align: right;
    }

    table.gradeChart thead th {
        border: 1px solid #000000;
        border-collapse: collapse;
        text-align: center !important;
        padding: 0px;
        margin: 0px;
        font-size: 9px;
    }

    table.gradeChart tbody td {
        border: 1px solid #000000;
        border-collapse: collapse;
        text-align: center !important;
        padding: 0px;
        margin: 0px;
        font-size: 9px;
    }

    hr {
        margin: 0px;
    }

    .tabulation th {
        vertical-align: middle;
        text-align: center;
        font-size: 10px;
    }

    .tabulation td {
        font-size: 10px;
        padding: 2px !important;
        text-align: center;
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
            <td style="text-align: center">
                <h3 style="font-size:22px !important" class="text-white"> {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3>
                <p style="font-size:18px !important" class="text-white mb-0"> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </p>

            </td>
            <td style="text-aligh:center">
                <p style="font-size:14px !important; border-bottom:1px solid gray;" align="left" class="text-white">@lang('lang.exam'):  <strong>{{$tabulation_details['exam_term']}}</strong> </p>
                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">@lang('lang.class'): <strong>{{ $tabulation_details['student_class']}}</strong> </p>
                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">@lang('lang.section'): <strong>{{ $tabulation_details['student_section']}}</strong> </p>
                <p style="font-size:14px !important; border-bottom:1px solid gray" align="left" class="text-white">@lang('lang.academic_year'): <strong>{{ @$academic_year->title}} ({{ @$academic_year->year}})</strong> </p>

            </td>
        </tr>
    </table>

    <table class="mt-30 mb-20 table table-striped table-bordered tabulation" style="width: 100%; table-layout: fixed;">
        <thead>
        <tr>
            <th rowspan="2">@lang('lang.position')</th>
            <th rowspan="2" style="text-align: left">@lang('lang.student') @lang('lang.name')</th>
            @foreach($subjects as $subject)

            @php
            $subject_ID = $subject->subject_id;
            $subject_Name = (strlen($subject->subject->subject_name) > 12) ? substr($subject->subject->subject_name, 0, 12) . '...' : $subject->subject->subject_name;
            $subject_Code = $subject->subject->subject_code;
            $mark_parts = App\SmAssignSubject::getNumberOfPart($subject_ID, $class_id, $section_id, $exam_term_id);
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
            $mark_parts = App\SmAssignSubject::getNumberOfPart($subject_ID, $class_id, $section_id, $exam_term_id);
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
        $class_pos = App\SmTemporaryMeritlist::where('student_id','=',$student->id)->where('exam_id','=',$exam_term_id)->first();
        $position_in_class = $class_pos->merit_order;
        $average = $class_pos->average_mark;
        $total_mark = $class_pos->total_marks;
        @endphp
        <tr>
            <td><strong>{{$position_in_class}}</strong></td>
            <td width='10%' style="text-align: left"><strong>{{$student->last_name}} {{$student->first_name}}</strong></td>

            @foreach($subjects as $subject)
            @php
            $subject_ID = $subject->subject_id;
            $subject_Name = $subject->subject->subject_name;
            $subject_Code = $subject->subject->subject_code;
            $mark_parts = App\SmAssignSubject::getMarksOfPart($student->id, $subject_ID, $class_id, $section_id,
            $exam_term_id);

            $optional_subject_marks=DB::table('sm_optional_subject_assigns')
            ->join('sm_mark_stores','sm_mark_stores.subject_id','=','sm_optional_subject_assigns.subject_id')
            ->where('sm_optional_subject_assigns.student_id','=',$student->id)
            ->first();
            @endphp

            <td class="total">
                @php
                $tola_mark_by_subject = App\SmAssignSubject::getSumMark($student->id, $subject_ID, $class_id,
                $section_id, $exam_term_id);
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
            <td>{{$total_mark}}
                @php $marks_by_students = 0; @endphp
            </td>

            <td>
                <strong>{{ $average }}</strong>
            </td>
            <td>
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

    <table style="width:100%">
        <tr>
            <td>
                <p style="padding-top:10px; text-align:right; float:right; border-top:1px solid #ddd; display:inline-block; margin-top:50px;">
                    ( Principal )</p>
            </td>
        </tr>

    </table>


</div>
</body>
</html>
