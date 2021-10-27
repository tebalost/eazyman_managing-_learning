@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.block_results') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.examinations')</a>
                <a href="#">@lang('lang.block_results')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('lang.block_results') @lang('lang.search')</h3>
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
                     @if(userPermission(227))

                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'block_results_store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                        @endif

                    <div class="row">
                            <div class="col-lg-3 mt-30-md">
                                <select class="w-100 bb niceSelect form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}" name="exam">
                                    <option data-display="@lang('lang.select_exam') *" value="">@lang('lang.select_exam')*</option>
                                    @foreach($exams as $exam)
                                        <option value="{{$exams!=''?$exam->id:''}}">{{$exams!=''?$exam->title:''}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('exam'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('exam') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-3 mt-30-md">
                                <select class="w-100 bb niceSelect form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                    <option data-display="@lang('lang.select_class') *" value="">@lang('lang.select_class') *</option>
                                    @foreach($classes as $class)
                                    <option value="{{$class->id}}"  {{( old('class') == $class->id ? "selected":"")}}>{{$class->class_name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('class'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('class') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-3 mt-30-md">
                                <select class="w-100 bb niceSelect form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section"
                                        id="select_section" name="section">
                                    <option data-display="@lang('lang.select_section')"
                                            value="">@lang('lang.select_section')
                                    </option>
                                    @foreach($sections as $section)
                                    <option value="{{$section->id}}"  {{( old('class') == $section->id ? "selected":"")}}>{{$section->section_name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('section'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('section') }}</strong>
                                </span>
                                @endif
                            </div>
                 				@php 
                                  $tooltip = "";
                                  if(userPermission(229)){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp

                                <div class="col-lg-3 mt-30-md text-center">
                               <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{$tooltip}}">
                                    <span class="ti-check"></span>
                                    @lang('lang.view') @lang('lang.students')
                                </button>
                                </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        @if(isset($exam_name))
        <div class="row mt-40">
            <div class="col-lg-12 ">
        <div class=" white-box mb-40">
            <div class="row">
                <div class="col-lg-12">
                    <div class="main-title">
                        <h3 class="mb-30 text-center">@lang('lang.block') @lang('lang.exam') @lang('lang.results') </h3>
                    </div>

                </div>
                <div class="col-lg-3">
                    <b> @lang('lang.class'): </b> {{$class_name}}
                </div>
                <div class="col-lg-3">
                    <b> @lang('lang.exam'): </b> {{$exam_name}}
                </div>
                <div class="col-lg-3">
                    <b> @lang('lang.reason'): </b> {{$receiver}}
                </div>
            </div>
        </div>
            </div>
        </div>
        @endif
        @if(isset($students))
        <div class="row">
            <div class="col-lg-12">
                <table class="display school-table school-table-style" cellspacing="0" width="100%">
                    <thead>
                    @if(session()->has('message-danger') != "")
                    <tr>
                        <td colspan="9">
                            @if(session()->has('message-danger'))
                            <div class="alert alert-danger">
                                {{ session()->get('message-danger') }}
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th width="5%">@lang('lang.sl')</th>
                        <th>@lang('lang.student') @lang('lang.name')</th>
                        <th>@lang('lang.reason')</th>
                        <th>@lang('lang.send')</th>
                    </tr>
                    </thead>

                    <tbody>
                    @php $count=1; @endphp
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'block_results_process', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}

                    @foreach($students as $student)
                    @php
                    $status = DB::table('sm_temporary_meritlists')->where([['class_id', $student->class_id], ['section_id', $student->section_id], ['exam_id', $exam_id], ['student_id', $student->id]])
                    ->where('academic_id', getAcademicId())->first();
                    @endphp
                    <tr>
                        <td>{{$count++}} </td>
                        <td>{{$student->last_name.' '.$student->first_name}}</td>
                        <td>
                            <div class="input-effect sm2_mb_20 md_mb_20">
                                <input class="primary-input form-control{{ $errors->has('reason') ? ' is-invalid' : '' }}" type="text" name="result[{{$student->id}}][reason]" value="{{old('reason')}}">
                                <span class="focus-border"></span>
                                @if ($errors->has('reason'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('reason') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="d-flex radio-btn-flex">
                                <div class="mr-20">
                                    <input type="radio" name="result[{{$student->id}}][status]" id="resultSend{{$student->id}}" value="1" class="common-radio resultSend"
                                           @if(!isset($status) || (isset($status) && $status->report_status==1))  checked @endif>
                                    <label for="resultSend{{$student->id}}">@lang('lang.active')</label>
                                </div>
                                <div class="mr-20">
                                    <input type="hidden" name="exam_id" id="" value="{{$exam_id}}">
                                    <input type="hidden" name="class_id" id="" value="{{$student->class_id}}">
                                    <input type="hidden" name="section_id" id="" value="{{$student->section_id}}">
                                    <input type="radio" name="result[{{$student->id}}][status]" id="resultSkip{{$student->id}}" value="0"  @if((isset($status) && $status->report_status==0))  checked @endif class="common-radio">
                                    <label for="resultSkip{{$student->id}}">@lang('lang.block')</label>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <div class="row  text-center">
            <div class="col-lg-12">
                <div class="col-lg-12 mt-30-md text-center">
                    <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{$tooltip}}">
                        <span class="ti-check"></span>
                        @lang('lang.update_results')
                    </button>
                </div>
            </div>
        </div>
        {{ Form::close() }}
        @endif
    </div>
</section>

@endsection
