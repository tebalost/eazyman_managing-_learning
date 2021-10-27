@extends('backEnd.master')
@section('mainContent')
<style type="text/css">
    .table tbody td {
        padding: 5px;
        text-align: center;
        vertical-align: middle;
    }

    .table head th {
        padding: 5px;
        text-align: center;
        vertical-align: middle;
    }

    .table head tr th {
        padding: 5px;
        text-align: center;
        vertical-align: middle;
    }

    tr, th, td {
        border: 1px solid #a2a6c5;
        text-align: center !important;
    }

    th, td {
        white-space: nowrap;
        text-align: center !important;
    }

    th.subject-list {
        white-space: inherit;
    }


    #main-content {
        width: auto !important;
    }

    .main-wrapper {
        display: inherit;
    }

    .table thead th {
        padding: 5px;
        vertical-align: middle;
    }

    .student_name, .subject-list {
        line-height: 12px;
    }

    .student_name b {
        min-width: 20%;
    }


    .gradeChart tbody td {
        padding: 0px;
        padding-left: 5px;
    }

    .gradeChart thead th {
        background: #f2f2f2;
    }

    hr {
        margin: 0px;
    }
</style>
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.exam_results_analysis') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.reports')</a>
                <a href="{{route('tabulation_sheet_report')}}">@lang('lang.exam_results_analysis')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-8 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('lang.select_criteria') </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            @if(session()->has('message-success') != "")
            @if(session()->has('message-success'))
            <div class="alert alert-success">
                {{ session()->get('message-success') }}
            </div>
            @endif
            @endif
            @if(session()->has('message-danger') != "")
            @if(session()->has('message-danger'))
            <div class="alert alert-danger">
                {{ session()->get('message-danger') }}
            </div>
            @endif
            @endif
            <div class="white-box">
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'exam_results_analysis',
                'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                <div class="row">
                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                    <div class="col-lg-4 mt-30-md md_mb_20">
                        <select class="w-100 bb niceSelect form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}"
                                name="exam">
                            <option data-display="@lang('lang.select_exam')*" value="">@lang('lang.select_exam')*
                            </option>
                            @foreach($exam_types as $exam)
                            <option value="{{$exam->id}}" {{isset($exam_id)? ($exam_id== $exam->id?
                                'selected':''):''}}>{{$exam->title}}
                            </option>
                            @endforeach
                        </select>
                        @if ($errors->has('exam'))
                        <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('exam') }}</strong>
                                </span>
                        @endif
                    </div>
                    <div class="col-lg-4 mt-30-md md_mb_20">
                        <select class="w-100 bb niceSelect form-control {{ $errors->has('class') ? ' is-invalid' : '' }}"
                                 id="select_class"
                                 name="class">
                            <option data-display="@lang('lang.select_class') *" value="">@lang('lang.select_class')
                                *
                            </option>
                            @foreach($classes as $class)
                            <option value="{{$class->id}}" {{isset($class_id)? ($class_id== $class->id?
                                'selected':''):''}}>{{$class->class_name}}
                            </option>
                            @endforeach

                        </select>
                        @if ($errors->has('class'))
                        <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('class') }}</strong>
                                </span>
                        @endif
                    </div>
                    <div class="col-lg-4 mt-30-md md_mb_20" id="select_section_div">

                        <select class="w-100 bb niceSelect form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section"
                                id="select_section" name="section">
                            <option data-display="@lang('lang.select_section')" value="">@lang('lang.select_section')</option>
                        </select>

                        @if ($errors->has('section'))
                        <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('section') }}</strong>
                                </span>
                        @endif
                    </div>



                    <div class="col-lg-12 mt-20 text-right">
                        <button type="submit" class="primary-btn small fix-gr-bg">
                            <span class="ti-search"></span>
                            @lang('lang.search')
                        </button>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</section>

@if(isset($marks))

@php
$generalSetting= App\SmGeneralSettings::find(1);
if(!empty($generalSetting)){
$school_name =$generalSetting->school_name;
$site_title =$generalSetting->site_title;
$school_code =$generalSetting->school_code;
$address =$generalSetting->address;
$phone =$generalSetting->phone;
}


@endphp

<section class="student-details mt-20">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 no-gutters">
                <div class="main-title">
                    <h3 class="mb-30 mt-30"> @lang('lang.exam_results_analysis')</h3>
                </div>
            </div>
            <div class="col-lg-8 pull-right mt-20">

                <div class="print_button pull-right">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'results-analysis/print',
                    'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student', 'target' =>
                    '_blank']) }}
                    <input type="hidden" name="exam" value="{{$exam_term_id}}">
                    <input type="hidden" name="class" value="{{$class_id}}">
                    <input type="hidden" name="section" value="{{$section_id}}">
                    <button type="submit" class="primary-btn small fix-gr-bg"><i class="ti-printer"> </i> Print
                    </button>
                    {{ Form::close() }}
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="single-report-admit">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-lg-4">
                                <img class="logo-img" src="{{ generalSetting()->logo }}" alt="">
                            </div>
                            <div class=" col-lg-8 text-left text-lg-right mt-30-md">
                                <h3 class="text-white">
                                    {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School
                                    Management ERP'}} </h3>
                                <p class="text-white mb-0">
                                    {{isset(generalSetting()->address)?generalSetting()->address:'Infix School
                                    Adress'}} </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <div class="row">
                        <div class="col-lg-7">
                            <h3 class="exam_title text-center text-capitalize">
                                {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School
                                Management ERP'}} </h3>
                            <h4 class="exam_title text-center text-capitalize">
                                {{isset(generalSetting()->address)?generalSetting()->address:'Infix School
                                Adress'}} </h4>
                            <h4 class="exam_title text-center text-uppercase"> tabulation sheet
                                of {{$tabulation_details['exam_term']}} in {{date('Y')}}</h4>
                            <hr>

                            <div class="row">
                                <div class=" col-lg-6">
                                    @if(@$tabulation_details['student_name'])
                                    @if(@$tabulation_details['student_name'])
                                    <p class="student_name">
                                        <b>@lang('lang.student') @lang('lang.name') </b>
                                        {{$tabulation_details['student_name']}}
                                    </p>
                                    @endif
                                    @if(@$tabulation_details['student_roll'])
                                    <p class="student_name">
                                        <b>@lang('lang.student') @lang('lang.roll') </b>
                                        {{$tabulation_details['student_roll']}}
                                    </p>
                                    @endif
                                    @if(@$tabulation_details['student_admission_no'])
                                    <p class="student_name">
                                        <b>@lang('lang.student') @lang('lang.admission') </b>
                                        {{$tabulation_details['student_admission_no']}}
                                    </p>
                                    @endif
                                    @else
                                    @foreach($tabulation_details['subject_list'] as $d)
                                    <p class="subject-list">{{$d}}</p>
                                    @endforeach

                                    @endif
                                </div>
                                <div class=" col-lg-6">

                                    @if(@$tabulation_details['student_class'])
                                    <p class="student_name">
                                        <b>@lang('lang.class') </b> {{$tabulation_details['student_class']}}
                                    </p>
                                    @endif
                                    @if(@$tabulation_details['student_section'])
                                    <p class="student_name">
                                        <b>@lang('lang.section') </b> {{$tabulation_details['student_section']}}
                                    </p>
                                    @endif
                                    @if(@$tabulation_details['student_admission_no'])
                                    <p class="student_name">
                                        <b> @lang('lang.exam') </b> {{$tabulation_details['exam_term']}}
                                    </p>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-5">
                            @if(@$tabulation_details)
                            <table class="table gradeChart">
                                <thead>
                                <th>SL</th>
                                <th>Staring</th>
                                <th>Ending</th>
                                <th>GPA</th>
                                <th>Grade</th>
                                <th>Evalution</th>
                                </thead>
                                <tbody>
                                @php $gdare_count =1; @endphp
                                @foreach($tabulation_details['grade_chart'] as $d)
                                @php $allstreams = json_decode($d['streams']); @endphp
                                @if(in_array($class_id,$allstreams))
                                <tr>
                                    <td>{{$gdare_count++}}</td>
                                    <td>{{$d['start']}}</td>
                                    <td>{{$d['end']}}</td>
                                    <td>{{$d['gpa']}}</td>
                                    <td>{{$d['grade_name']}}</td>
                                    <td class="text-left">{{$d['description']}}</td>
                                </tr>
                                @endif
                                @endforeach
                                </tbody>
                            </table>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="mt-30 mb-20 table table-striped table-bordered ">
                            <thead>
                            <tr>
                                <th rowspan="2">@lang('lang.sl')</th>
                                <th rowspan="2">@lang('lang.subject') @lang('lang.name')</th>
                                <th rowspan="2">@lang('lang.subject') @lang('lang.code')</th>
                                @foreach($tabulation_details['grade_chart'] as $grade_details)
                                @php
                                $mark_parts = getGrades();
                                $allstreams = json_decode($grade_details['streams']);
                                @endphp
                                @if(in_array($class_id,$allstreams))
                                <th colspan="{{count($mark_parts)}}" class="subject-list"> {{$grade_details['grade_name']}}</th>
                                @endif
                                @endforeach
                                <th rowspan="2">@lang('lang.number_of_students')</th>
                                <th rowspan="2">@lang('lang.pass')</th>
                                <th rowspan="2">@lang('lang.fail')</th>
                            </tr>
                            <tr>

                                @foreach($tabulation_details['grade_chart'] as $grade_details)
                                @php
                                $mark_parts = getGrades();
                                $allstreams = json_decode($grade_details['streams'])
                                @endphp
                                @if(in_array($class_id,$allstreams))
                                <th colspan="{{count($mark_parts)}}">{{$grade_details['start']}} - {{$grade_details['end']}}</th>
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
                                <td> {{$count++}} </td>
                                <td> {{$subject->subject->subject_name}} </td>
                                <td> {{$subject->subject->subject_code}} </td>
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
                                <td colspan="{{count($mark_parts)}}" class="total">{{$grades_count}}</td>
                                @endif
                                @endforeach
                                <td>{{$grades_count_total_each}}</td>
                                <td>@if($grades_count_total_each>0) {{$pass_count}} ({{round($pass_count/$grades_count_total_each*100,1)}}%) @else 0 @endif</td>
                                <td>@if($grades_count_total_each>0) {{$grades_count_total_each-$pass_count}} ({{round(($grades_count_total_each-$pass_count)/$grades_count_total_each*100,1)}}%) @else 0 @endif</td>
                            </tr>

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif


@endsection
