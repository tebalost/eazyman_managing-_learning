@extends('backEnd.master')
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('lang.results_configuration') </h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                    <a href="#">@lang('lang.examinations')</a>
                    <a href="#">@lang('lang.results_configuration')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            @if(isset($results_settings))
             @if(userPermission(226))

                <div class="row">
                    <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                        <a href="{{route('results-configuration')}}" class="primary-btn small fix-gr-bg">
                            <span class="ti-plus pr-2"></span>
                            @lang('lang.add')
                        </a>
                    </div>
                </div>
            @endif
            @endif
            <div class="row">
                
                <div class="col-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">@if(isset($results_settings))
                                        @lang('lang.edit')
                                    @else
                                        @lang('lang.add')
                                    @endif
                                    @lang('lang.Result')
                                </h3>
                            </div>
                            @if(isset($result_config))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('results-configuration-update',$result_config->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                            @else
                            @if(userPermission(226))

                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'results-configuration',
                                'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @endif
                            @endif
                            <div class="white-box">
                                <div class="add-visitor">
                                    <div class="row">
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
                                                <input
                                                    class="primary-input form-control{{ $errors->has('result_name') ? ' is-invalid' : '' }}"
                                                    type="text" name="result_name" autocomplete="off"
                                                    value="{{isset($result_config)? $result_config->result_name:Request::old('result_name')}}">
                                                <input type="hidden" name="id"
                                                       value="{{isset($result_config)? $result_config->id: ''}}">
                                                <label> @lang('lang.result') @lang('lang.name')</label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('result_name'))
                                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('result_name') }}</strong>
                                            </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row mt-25">
                                        <div class="col-lg-12">
                                            <div class="input-effect">
                                                <input
                                                    class="primary-input form-control{{ $errors->has('percent_from') ? ' is-invalid' : '' }}"
                                                    type="number" name="percent_from" autocomplete="off" onkeypress="return isNumberKey(event)"
                                                    value="{{isset($result_config)? $result_config->percent_from:Request::old('percent_from')}}">
                                                <label>@lang('lang.average_from')<span>*</span></label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('percent_from'))
                                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('percent_from') }}</strong>
                                            </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row mt-25">
                                        <div class="col-lg-12">
                                            <div class="input-effect">
                                                <input
                                                    class="primary-input form-control{{ $errors->has('percent_upto') ? ' is-invalid' : '' }}"
                                                    type="number" name="percent_upto" autocomplete="off" onkeypress="return isNumberKey(event)"
                                                    value="{{isset($result_config)? $result_config->percent_upto:Request::old('percent_upto')}}">
                                                <input type="hidden" name="id"
                                                       value="{{isset($result_config)? $result_config->id: ''}}">
                                                <label>@lang('lang.average_upto')<span>*</span></label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('percent_upto'))
                                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('percent_upto') }}</strong>
                                            </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row mt-25">
                                        <div class="col-lg-12">
                                            <div class="input-effect">
                                                <textarea class="primary-input form-control" cols="0" rows="2"
                                                          name="description">{{isset($result_config)? $result_config->description: Request::old('description')}}</textarea>
                                                <label>@lang('lang.description') <span></span></label>
                                                <span class="focus-border textarea"></span>
                                                @if ($errors->has('description'))
                                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-25">
                                        <div class="col-lg-12">
                                            <div class="input-effect">
                                                <textarea class="primary-input form-control" cols="0" rows="2"
                                                          name="principal_remark">{{isset($result_config)? $result_config->principal_remark: Request::old('description')}}</textarea>
                                                <label>@lang('lang.principal_remark') <span></span></label>
                                                <span class="focus-border textarea"></span>
                                                @if ($errors->has('description'))
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-25">
                                        <div class="col-lg-12">
                                            <div class="input-effect">
                                                <textarea class="primary-input form-control" cols="0" rows="2"
                                                          name="class_teacher_remark">{{isset($result_config)? $result_config->class_teacher_remark: Request::old('description')}}</textarea>
                                                <label>@lang('lang.class_teacher_remark') <span></span></label>
                                                <span class="focus-border textarea"></span>
                                                @if ($errors->has('description'))
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('description') }}</strong>
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
                                        if(isset($result_config)){
                                        $streams = json_decode($result_config->streams);
                                        }
                                        @endphp
                                            @foreach($classes as $value)
                                            <div class="input-effect">
                                                <input type="checkbox" id="class_{{@$value->id}}" class="common-checkbox stream-checkbox" name="streams[]" value="{{@$value->id}}" {{ in_array($value->id, $streams) ? ' checked' : ''}}>
                                                <label for="class_{{@$value->id}}">{{@$value->class_name}}</label>
                                            </div>
                                            @endforeach
                                            <div class="input-effect">
                                                <input type="checkbox" id="all_streams" class="common-checkbox" name="all_streams[]" value="0" {{ (is_array(old('stream_ids')) and in_array($value->id, old('stream_ids'))) ? ' checked' : '' }}>
                                                <label for="all_streams">@lang('lang.select') @lang('lang.all')</label>
                                            </div>


                                        </div>

                                    </div>
	                                @php 
                                        $tooltip = "";
                                      if(userPermission(226)){
                                            $tooltip = "";
                                        }else{
                                            $tooltip = "You have no permission to add";
                                        }
                                    @endphp

                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                           <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{$tooltip}}">
                                                <span class="ti-check"></span>

                                                @if(isset($result_config))
                                                    @lang('lang.update')
                                                @else
                                                    @lang('lang.save')
                                                @endif
                                                @lang('lang.result')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('lang.Results') @lang('lang.list')</h3>
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
                                        <td colspan="4">
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
                                    <th>@lang('lang.sl')</th>
                                    <th>@lang('lang.result') @lang('lang.name')</th>
                                    <th>@lang('lang.percent_from')</th>
                                    <th>@lang('lang.percent_upto')</th>
                                    <th>@lang('lang.action')</th>
                                </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $i=1;
                                    @endphp
                                @foreach($results_config as $results_settings)
                                    <tr>
                                        <td>{{ @$i++}}</td>
                                        <td>{{ @$results_settings->result_name}}</td>
                                        <td>{{ @$results_settings->percent_from}}</td>
                                        <td>{{ @$results_settings->percent_upto}}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn dropdown-toggle"
                                                        data-toggle="dropdown">
                                                    @lang('lang.select')
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                   @if(userPermission(227))

                                                   <a class="dropdown-item" href="{{route('results-configuration-edit', [$results_settings->id
                                                    ])}}">@lang('lang.edit')</a>
                                                   @endif
                                                   @if(userPermission(228))

                                                   <a class="dropdown-item" data-toggle="modal"
                                                       data-target="#deleteResultsConfigModal{{@$results_settings->id}}"
                                                       href="#">@lang('lang.delete')</a>
                                               @endif
                                                    </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="modal fade admin-query" id="deleteResultsConfigModal{{@$results_settings->id}}">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">@lang('lang.delete') @lang('lang.result')</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        <h4>@lang('lang.are_you_sure_to_delete')</h4>
                                                    </div>
                                                    <div class="mt-40 d-flex justify-content-between">
                                                        <button type="button" class="primary-btn tr-bg"
                                                                data-dismiss="modal">@lang('lang.cancel')</button>
                                                        {{ Form::open(['route' => array('results-configuration-delete',$results_settings->id), 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
                                                        <button class="primary-btn fix-gr-bg"
                                                                type="submit">@lang('lang.delete')</button>
                                                        {{ Form::close() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
