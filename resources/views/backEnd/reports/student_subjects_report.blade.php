@extends('backEnd.master')
@section('mainContent')

<link rel="stylesheet" href="{{ asset('/') }}public/backEnd/css/custom_result/style.css">

<style>
    tr {
        border: 1px solid #351681;

    }

    table.meritList {
        width: 100%;
        border: 1px solid #351681;
    }

    table.meritList th {
        padding: 2px;
        text-transform: capitalize !important;
        font-size: 11px !important;
        text-align: center !important;
        border: 1px solid #351681;
        text-align: center;

    }

    table.meritList td {
        padding: 2px;
        font-size: 11px !important;
        border: 1px solid #351681;
        text-align: center !important;
    }

    .single-report-admit table tr td {
        padding: 5px 5px !important;

        border: 1px solid #351681;
    }

    .single-report-admit table tr th {
        padding: 5px 5px !important;
        vertical-align: middle;
        border: 1px solid #351681;
    }

    .main-wrapper {
        display: block !important;
    }

    #main-content {
        width: auto !important;
    }

    hr {
        margin: 0px;
    }

    .gradeChart tbody td {
        padding: 0;
        border: 1px solid #351681;
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


    #grade_table th {
        border: 1px solid black;
        text-align: center;
        background: #351681;
        color: white;
    }

    #grade_table td {
        color: black;
        text-align: center;
        border: 1px solid black;
    }
</style>
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.student_subjects_reports') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.reports')</a>
                <a href="#">@lang('lang.student_subjects_reports')</a>
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
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student-subjects-list',
                'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                <div class="row">
                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">

                    <div class="col-lg-6 mt-30-md">
                        <select class="w-100 bb niceSelect form-control {{ $errors->has('class') ? ' is-invalid' : '' }}"
                                id="select_class" name="class">
                            <option data-display="@lang('lang.select_class') *" value="">@lang('lang.select_class') *
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
                    <div class="col-lg-6 mt-30-md" id="select_section_div">
                        <select class="w-100 bb niceSelect form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section"
                                id="select_section" name="section">
                            <option data-display="@lang('lang.select_section') *" value="">@lang('lang.select_section')
                                *
                            </option>
                        </select>
                        @if ($errors->has('section'))
                        <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('section') }}</strong>
                                </span>
                        @endif
                    </div>

                    <div class="col-lg-12 mt-20 text-right">
                        <button type="submit" class="primary-btn small fix-gr-bg">
                            <span class="ti-search pr-2"></span>
                            @lang('lang.search')
                        </button>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</section>

@if (isset($students))


<section class="student-details">
    <div class="container-fluid p-0">
        <div class="row mt-40">
            <div class="col-lg-4 no-gutters">
                <div class="main-title">
                    <h3 class="mb-30 mt-0">@lang('lang.custom') @lang('lang.merit_list_report')</h3>
                </div>
            </div>
            <div class="col-lg-8 pull-right">
                <a href="{{route('student-subjects-report-print', [$InputClassId, $InputSectionId])}}"
                   class="primary-btn small fix-gr-bg pull-right" target="_blank"><i class="ti-printer"> </i> Print</a>

                <a href="{{route('student-subjects-report-print-validation', [$InputClassId, $InputSectionId])}}"
                   class="primary-btn small fix-gr-bg pull-right" target="_blank"><i class="ti-printer"> </i>Subjects Validation</a>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-12">
                <div class="white-box">


                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="single-report-admit">
                                <div class="card">
                                    <div class="card-header">

                                        <div class="d-flex">
                                            {{--
                                            <div class="offset-2">

                                            </div>
                                            --}}
                                            <div class="col-lg-2">
                                                <img class="logo-img" src="{{ @generalSetting()->logo }}" alt="">
                                            </div>
                                            <div class="col-lg-6 ml-30">
                                                <h3 class="text-white">
                                                    {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix
                                                    School Management ERP'}} </h3>
                                                <p class="text-white mb-0">
                                                    {{isset(generalSetting()->address)?generalSetting()->address:'Infix
                                                    School Address'}} </p>
                                                <p class="text-white mb-0"> @lang('lang.email')
                                                    {{isset($email)?$email:'admin@demo.com'}} , @lang('lang.phone')
                                                    {{isset(generalSetting()->phone)?generalSetting()->phone:'admin@demo.com'}} </p>
                                            </div>
                                            <div class="offset-2">

                                            </div>
                                        </div>
                                    </div>


                                    <div class="card-body">
                                        <div class="col-md-12">
                                            <div class="row">
                                                {{-- start col-lg-8 --}}
                                                <div class="col-lg-8">
                                                    <div class="row">

                                                        <div class="col-md-6">
                                                            <h3>@lang('lang.student_subjects_reports')</h3>
                                                            <p class="mb-0">
                                                                @lang('lang.academic_year') : <span
                                                                        class="primary-color fw-500">{{@generalSetting()->academic_Year->year ?? ''}}</span>
                                                            </p>

                                                            <p class="mb-0">
                                                                @lang('lang.class') : <span
                                                                        class="primary-color fw-500">{{@$class_name}}</span>
                                                            </p>
                                                            <p class="mb-0">
                                                                @lang('lang.section') : <span
                                                                        class="primary-color fw-500">{{@$section->section_name}}</span>
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h3>@lang('lang.subjects')</h3>
                                                            @foreach($subjects as $subject)
                                                            <p class="mb-0">
                                                                <span class="primary-color fw-500">{{@$subject->subject->subject_name}}</span>
                                                            </p>
                                                            @endforeach
                                                        </div>

                                                    </div>

                                                </div>
                                                {{-- end col-lg-8 --}}

                                                <style>
                                                    #grade_table td {
                                                        color: #4E5B9C;
                                                        text-align: center;
                                                        border: 1px solid #351681;
                                                    }
                                                </style>


                                            </div>
                                        </div>
                                        <h3 class="primary-color fw-500 text-center">Student Subjects List</h3>
                                        <hr>


                                        <div class="table-responsive">
                                            <table class="mt-30 mb-20 table table-striped table-bordered ">
                                                <thead>
                                                <tr>
                                                    <th rowspan="2">@lang('lang.sl')</th>
                                                    <th rowspan="2">@lang('lang.student') @lang('lang.name')</th>
                                                    @foreach($subjects as $subject)
                                                    @php
                                                    $subject_ID = $subject->subject_id;
                                                    $subject_Name = $subject->subject->subject_name;
                                                    $mark_parts = App\SmAssignSubject::getNumberOfSubjects($class_id, $section_id);
                                                    @endphp
                                                    <th colspan="{{count($mark_parts)}}" class="subject-list"> {{$subject_Name}}</th>
                                                    @endforeach


                                                </tr>
                                                <tr>

                                                    @foreach($subjects as $subject)
                                                    @php
                                                    $subject_ID = $subject->subject_id;
                                                    $subject_Name = $subject->subject->subject_name;
                                                    $subject_Code = $subject->subject->subject_code;
                                                    $mark_parts = App\SmAssignSubject::getNumberOfSubjects($class_id, $section_id);
                                                    @endphp
                                                    <th colspan="{{count($mark_parts)}}" class="subject-list">{{$subject_Code}}</th>
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
                                                @endphp
                                                <tr>
                                                    <td>{{$count++}}</td>
                                                    <td> {{$student->last_name}} {{$student->first_name}}</td>
                                                    @foreach($subjects as $subject)
                                                    @php

                                                    $subject_ID = $subject->subject_id;
                                                    $subject_Name = $subject->subject->subject_name;
                                                    $mark_parts = App\SmAssignSubject::getNumberOfSubjects($class_id, $section_id);

                                                    $optional_subject_marks=DB::table('sm_optional_subject_assigns')
                                                    ->join('sm_mark_stores','sm_mark_stores.subject_id','=','sm_optional_subject_assigns.subject_id')
                                                    ->where('sm_optional_subject_assigns.student_id','=',$student->id)
                                                    ->first();

                                                    @endphp

                                                    {{--TODO: CHECK FROM HERE IF THE STUDENT IS DOING THIS SUBJECT OR NOT--}}

                                                    <td colspan="{{count($mark_parts)}}" class="subject-list"></td>
                                                    @endforeach



                                                </tr>

                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
@endif

@endsection
