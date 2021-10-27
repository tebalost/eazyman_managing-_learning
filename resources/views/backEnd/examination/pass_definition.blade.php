@extends('backEnd.master')
@section('mainContent')

<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.manage') @lang('lang.pass_definition')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.admin_section')</a>
                <a href="#">@lang('lang.student_id_card')</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($pass_definition))
        @if(userPermission(46))
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('student-id-card')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('lang.add')
                </a>
            </div>
        </div>
        @endif
        @endif
        <div class="row">

            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">@if(isset($pass_definition))
                                @lang('lang.edit')
                                @else
                                @lang('lang.add')
                                @endif
                                @lang('lang.pass_definition')
                            </h3>
                        </div>
                        @if(isset($pass_definition))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' =>
                        array('pass-definition-store',@$id->id), 'method' => 'POST', 'enctype' =>
                        'multipart/form-data']) }}
                        @else
                        @if(userPermission(46))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'pass-definition-store',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        @if(session()->has('message-success'))
                                        <div class="alert alert-success">
                                            {{ session()->get('message-success') }}
                                        </div>
                                        @elseif(session()->has('message-danger'))
                                        <div class="alert alert-danger">
                                            {{ session()->get('message-danger') }}
                                        </div>
                                        @endif
                                        <div class="input-effect">
                                            <input class="primary-input form-control{{ $errors->has('pass_average') ? ' is-invalid' : '' }}"
                                                   type="number" step="0.01" min="0" max="100" name="pass_average"
                                                   autocomplete="off"
                                                   value="{{isset($pass_definition)? $pass_definition->pass_average: old('pass_average')}}">
                                            <label>@lang('lang.pass') @lang('lang.average') <span>*</span></label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('pass_average'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('pass_average') }}</strong>
                                            </span>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12">

                                        <div class="input-effect">
                                            <input class="primary-input form-control{{ $errors->has('pass_mark') ? ' is-invalid' : '' }}"
                                                   type="number" min="0" name="pass_mark" autocomplete="off"
                                                   value="{{isset($pass_definition)? $pass_definition->pass_mark: old('pass_mark')}}">
                                            <input type="hidden" name="id"
                                                   value="{{isset($pass_definition)? $pass_definition->id: ''}}">
                                            <label>@lang('lang.pass_mark')<span> *</span></label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('pass_mark'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('pass_mark') }}</strong>
                                            </span>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12">

                                        <div class="input-effect">
                                            <input class="primary-input form-control{{ $errors->has('number_of_subjects') ? ' is-invalid' : '' }}"
                                                   type="number" min="0" name="number_of_subjects" autocomplete="off"
                                                   value="{{isset($pass_definition)? $pass_definition->number_of_subjects: old('number_of_subjects')}}">
                                            <input type="hidden" name="id"
                                                   value="{{isset($pass_definition)? $pass_definition->id: ''}}">
                                            <label>@lang('lang.number_of_subjects')<span> *</span></label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('number_of_subjects'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('number_of_subjects') }}</strong>
                                            </span>
                                            @endif
                                        </div>

                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-12">

                                        <div class="input-effect">
                                            <input class="primary-input form-control{{ $errors->has('course_work_percent') ? ' is-invalid' : '' }}"
                                                   type="number" min="0" max="100" name="course_work_percent"
                                                   autocomplete="off"
                                                   value="{{isset($pass_definition)? $pass_definition->course_work_percent: old('course_work_percent')}}">
                                            <input type="hidden" name="id"
                                                   value="{{isset($pass_definition)? $pass_definition->id: ''}}">
                                            <label>@lang('lang.course_work_percent')</label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('course_work_percent'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('course_work_percent') }}</strong>
                                            </span>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12">

                                        <div class="input-effect">
                                            <input class="primary-input form-control{{ $errors->has('exam_percent') ? ' is-invalid' : '' }}"
                                                   type="number" min="0" max="100" name="exam_percent"
                                                   autocomplete="off"
                                                   value="{{isset($pass_definition)? $pass_definition->exam_percent: old('exam_percent')}}">
                                            <input type="hidden" name="id"
                                                   value="{{isset($pass_definition)? $pass_definition->id: ''}}">
                                            <label>@lang('lang.exam_percent')</label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('exam_percent'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('exam_percent') }}</strong>
                                            </span>
                                            @endif
                                        </div>

                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-12 d-flex">
                                        <p class="text-uppercase fw-500 mb-10"> @lang('lang.student_conduct')</p>
                                        <div class="d-flex radio-btn-flex ml-40">
                                            <div class="mr-30">
                                                <input type="radio" name="student_conduct" id="student_conduct_yes"
                                                       value="1" class="common-radio relationButton"
                                                       {{isset($pass_definition)? ($pass_definition->student_conduct ==
                                                1? 'checked': ''):'checked'}}>
                                                <label for="student_conduct_yes">@lang('lang.yes')</label>
                                            </div>
                                            <div class="mr-30">
                                                <input type="radio" name="student_conduct" id="student_conduct_no"
                                                       value="0" class="common-radio relationButton"
                                                       {{isset($pass_definition)? ($pass_definition->student_conduct ==
                                                0? 'checked': ''):''}}>
                                                <label for="student_conduct_no">@lang('lang.none')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12 d-flex">
                                        <p class="text-uppercase fw-500 mb-10">@lang('lang.grade')
                                            @lang('lang.table') </p>
                                        <div class="d-flex radio-btn-flex ml-40">
                                            <div class="mr-30">
                                                <input type="radio" name="grade_table" id="grade_table_yes" value="1"
                                                       class="common-radio relationButton" {{isset($pass_definition)?
                                                       ($pass_definition->grade_table == 1? 'checked': ''):'checked'}}>
                                                <label for="grade_table_yes">@lang('lang.yes')</label>
                                            </div>
                                            <div class="mr-30">
                                                <input type="radio" name="grade_table" id="grade_table_no" value="0"
                                                       class="common-radio relationButton" {{isset($pass_definition)?
                                                       ($pass_definition->grade_table == 0? 'checked': ''):''}}>
                                                <label for="grade_table_no">@lang('lang.none')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row mt-25">
                                    <div class="col-lg-12 d-flex">
                                        <p class="text-uppercase fw-500 mb-10">@lang('lang.coursework_type') </p>
                                        <div class="d-flex radio-btn-flex ml-40">
                                            <div class="mr-30">
                                                <input type="radio" name="coursework_type" id="coursework_type_yes"
                                                       value="exams" class="common-radio relationButton"
                                                       {{isset($pass_definition)? ($pass_definition->coursework_type ==
                                                'exams'? 'checked': ''):'checked'}}>
                                                <label for="coursework_type_yes">@lang('lang.exams')</label>
                                            </div>
                                            <div class="mr-30">
                                                <input type="radio" name="coursework_type" id="coursework_type_no"
                                                       value="tests" class="common-radio relationButton"
                                                       {{isset($pass_definition)? ($pass_definition->coursework_type ==
                                                'tests'? 'checked': ''):''}}>
                                                <label for="coursework_type_no">@lang('lang.tests')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12 d-flex">
                                        <p class="text-uppercase fw-500 mb-10">@lang('lang.subject')
                                            @lang('lang.average')</p>
                                        <div class="d-flex radio-btn-flex ml-40">
                                            <div class="mr-30">
                                                <input type="radio" name="subject_average" id="subject_average_yes"
                                                       value="1" class="common-radio relationButton"
                                                       {{isset($pass_definition)? ($pass_definition->subject_average ==
                                                1? 'checked': ''):'checked'}}>
                                                <label for="subject_average_yes">@lang('lang.yes')</label>
                                            </div>
                                            <div class="mr-30">
                                                <input type="radio" name="subject_average" id="subject_average_no"
                                                       value="0" class="common-radio relationButton"
                                                       {{isset($pass_definition)? ($pass_definition->subject_average ==
                                                0? 'checked': ''):''}}>
                                                <label for="subject_average_no">@lang('lang.none')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12 d-flex">
                                        <p class="text-uppercase fw-500 mb-10">@lang('lang.subject')
                                            @lang('lang.rank')</p>
                                        <div class="d-flex radio-btn-flex ml-40">
                                            <div class="mr-30">
                                                <input type="radio" name="subject_rank" id="subject_rank_yes" value="1"
                                                       class="common-radio relationButton" {{isset($pass_definition)?
                                                       ($pass_definition->subject_rank == 1? 'checked': ''):'checked'}}>
                                                <label for="subject_rank_yes">@lang('lang.yes')</label>
                                            </div>
                                            <div class="mr-30">
                                                <input type="radio" name="subject_rank" id="subject_rank_no" value="0"
                                                       class="common-radio relationButton" {{isset($pass_definition)?
                                                       ($pass_definition->subject_rank == 0? 'checked': ''):''}}>
                                                <label for="subject_rank_no">@lang('lang.none')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12 d-flex">
                                        <p class="text-uppercase fw-500 mb-10">@lang('lang.language')
                                            @lang('lang.translation')</p>
                                        <div class="d-flex radio-btn-flex ml-40">
                                            <div class="mr-30">
                                                <input type="radio" name="language_translation"
                                                       id="language_translation_yes" value="1"
                                                       class="common-radio relationButton" {{isset($pass_definition)?
                                                       ($pass_definition->language_translation == 1? 'checked':
                                                ''):'checked'}}>
                                                <label for="language_translation_yes">@lang('lang.yes')</label>
                                            </div>
                                            <div class="mr-30">
                                                <input type="radio" name="language_translation"
                                                       id="language_translation_no" value="0"
                                                       class="common-radio relationButton" {{isset($pass_definition)?
                                                       ($pass_definition->language_translation == 0? 'checked':
                                                ''):''}}>
                                                <label for="language_translation_no">@lang('lang.none')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12 d-flex">
                                        <div class="input-effect">
                                            <label>@lang('lang.compulsory_subjects')<span> *</span></label>
                                            <span class="focus-border"></span>
                                        <select multiple id="selectSectionss" name="compulsory_subjects[]" style="width: 100%">

                                            @if(isset($pass_definition))
                                            @php $compulsory_subjects = json_decode($pass_definition->compulsory_subjects);@endphp
                                            @endif

                                            @foreach($subjects as $subject)
                                                <option value="{{$subject->id}}" {{isset($pass_definition)?
                                                        (in_array($subject->id,$compulsory_subjects)? 'selected': ''):''}}>{{$subject->subject_name}}</option>
                                            @endforeach
                                        </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12 d-flex">
                                        <div class="input-effect">
                                            <input class="primary-input form-control{{ $errors->has('compulsory_subjects_pass_mark') ? ' is-invalid' : '' }}"
                                                   type="number" step="0.01" min="0" max="100"
                                                   name="compulsory_subjects_pass_mark" autocomplete="off"
                                                   value="{{isset($pass_definition)? $pass_definition->compulsory_subjects_pass_mark: old('compulsory_subjects_pass_mark')}}">
                                            <label>@lang('lang.compulsory_subjects_pass_mark') <span>*</span></label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('compulsory_subjects_pass_mark'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('compulsory_subjects_pass_mark') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12 d-flex">
                                        <div class="input-effect">
                                            <label>@lang('lang.other_core_subjects')<span> *</span></label>
                                            <span class="focus-border"></span>
                                            <select multiple id="selectStaffss" name="other_core_subjects[]" style="width: 100%">
                                                @if(isset($pass_definition))
                                                @php $other_core_subjects = json_decode($pass_definition->other_core_subjects); @endphp
                                                @endif
                                                @foreach($subjects as $subject)
                                                @if(isset($other_core_subjects))
                                                <option value="{{$subject->id}}" {{isset($pass_definition)?
                                                        (in_array($subject->id,$other_core_subjects)? 'selected': ''):''}}>{{$subject->subject_name}}
                                                </option>
                                                @endif
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12 d-flex">
                                        <div class="input-effect">
                                            <input class="primary-input form-control{{ $errors->has('other_core_subjects_pass_mark') ? ' is-invalid' : '' }}"
                                                   type="number" step="0.01" min="0" max="100"
                                                   name="other_core_subjects_pass_mark" autocomplete="off"
                                                   value="{{isset($pass_definition)? $pass_definition->other_core_subjects_pass_mark: old('other_core_subjects_pass_mark')}}">
                                            <label>@lang('lang.other_core_subjects_pass_mark') <span>*</span></label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('other_core_subjects_pass_mark'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('other_core_subjects_pass_mark') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <label>@lang('lang.select') @lang('lang.stream') *</label>
                                        @php
                                        $streams = [];
                                        @endphp
                                        @if(isset($pass_definition))
                                        @php $streams = json_decode($pass_definition->streams); @endphp
                                        @endif

                                @foreach($classes as $value)

                                    <input type="checkbox" id="class_{{@$value->id}}" class="common-checkbox stream-checkbox" name="streams[]" value="{{@$value->id}}" {{ in_array($value->id, $streams) ? 'checked' : ''}}>
                                    <label for="class_{{@$value->id}}">{{@$value->class_name}}</label>

                                @endforeach
                                <div class="input-effect">
                                    <input type="checkbox" id="all_streams" class="common-checkbox" name="all_streams[]" value="0" {{ (is_array(old('stream_ids')) and in_array($value->id, old('stream_ids'))) ? ' checked' : '' }}>
                                    <label for="all_streams">@lang('lang.select') @lang('lang.all')</label>
                                </div>
                                    </div>
                                </div>
                                @php
                                $tooltip = "";
                                if(userPermission(46)){
                                $tooltip = "";
                                }else{
                                $tooltip = "You have no permission to add";
                                }
                                @endphp

                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg" data-toggle="tooltip"
                                                title="{{$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($pass_definition))
                                            @lang('lang.update')
                                            @else
                                            @lang('lang.save')
                                            @endif
                                            @lang('lang.pass_definition')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0"> @lang('lang.pass_definition') @lang('lang.list') </h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">

                        <table id="table_id" class="display school-table" cellspacing="0" width="100%">

                            <thead>
                            @if(session()->has('message-success-delete') != "" ||
                            session()->get('message-danger-delete') != "")
                            <tr>
                                <td colspan="6">
                                    @if(session()->has('message-success-delete'))
                                    <div class="alert alert-success">
                                        {{ session()->get('message-success-delete') }}
                                    </div>
                                    @elseif(session()->has('message-danger-delete'))
                                    <div class="alert alert-danger">
                                        {{ session()->get('message-danger-delete') }}
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <th>@lang('lang.pass') @lang('lang.average')</th>
                                <th>@lang('lang.classes')</th>
                                <td>@lang('lang.compulsory')</td>
                                <th>@lang('lang.actions')</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if(isset($pass_definitions))
                            @foreach($pass_definitions as $definition)
                            @php
                            $compulsory_subjects = json_decode($definition->compulsory_subjects);
                            $streams = json_decode($definition->streams);
                            @endphp
                            <tr>
                                <td>{{$definition->pass_average}}</td>
                                <td>
                                    @foreach($classes as $class)
                                    @if(in_array($class->id, $streams))
                                    {{$class->class_name}}<br>
                                    @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($subjects as $subject)
                                    @if(in_array($subject->id, $compulsory_subjects))
                                    {{$subject->subject_name}}<br>
                                    @endif
                                    @endforeach
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                            @lang('lang.select')
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">

                                            @if(userPermission(47))
                                            <a class="dropdown-item"
                                               href="{{route('pass-definition-update',$definition->id)}}">@lang('lang.edit')</a>
                                            @endif
                                            @if(userPermission(48))
                                            <a class="dropdown-item" data-toggle="modal"
                                               data-target="#deleteIDCardModal{{$definition->id}}" href="#">@lang('lang.delete')</a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <div class="modal fade admin-query" id="deleteIDCardModal{{$definition->id}}">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">@lang('lang.delete') @lang('lang.pass_definition')</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="text-center">
                                                <h4>@lang('lang.are_you_sure_to_delete')</h4>
                                            </div>

                                            <div class="mt-40 d-flex justify-content-between">
                                                <button type="button" class="primary-btn tr-bg" data-dismiss="modal">
                                                    @lang('lang.cancel')
                                                </button>
                                                {{ Form::open(['route' =>
                                                array('pass-definition-delete',$definition->id), 'method' =>
                                                'DELETE', 'enctype' => 'multipart/form-data']) }}

                                                <button class="primary-btn fix-gr-bg" type="submit">
                                                    @lang('lang.delete')
                                                </button>
                                                {{ Form::close() }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            </tbody>
                            @endforeach
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
