@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.general_settings')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.system_settings')</a>
                <a href="#">@lang('lang.general_settings')</a>
            </div>
        </div>
    </div>
</section>
<section class="student-details">
    <div class="container-fluid p-0">
        @include('backEnd.partials.alertMessage')
        <div class="row">
            <div class="col-lg-4 col-md-6 col-xl-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('lang.change_logo')</h3>
                        </div>
                        @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'admin-dashboard',
                        'method' => 'GET', 'enctype' => 'multipart/form-data']) }}
                        @else
                        @if(userPermission(406))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'update-school-logo',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @endif
                        @endif


                        <div class="white-box">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="text-center">

                                @if(isset($editData->logo))

                                <img class="img-fluid Img-100" src="{{asset($editData->logo)}}" alt="">
                                @else
                                <img class="img-fluid" src="{{asset('public/uploads/settings/logo.png')}}" alt="">
                                @endif
                            </div>

                            <div class="mt-40">
                                <div class="text-center">
                                    <label class="primary-btn small fix-gr-bg"
                                           for="upload_logo">@lang('lang.upload')</label>
                                    <input type="file" class="d-none form-control" name="main_school_logo"
                                           id="upload_logo">
                                </div>
                            </div>
                            <div class="col-lg-12 text-center">

                                @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                <span class="d-inline-block" tabindex="0" data-toggle="tooltip"
                                      title="Disabled For Demo "> <button class="primary-btn small fix-gr-bg  demo_view"
                                                                          style="pointer-events: none;" type="button">@lang('lang.change_logo')</button></span>
                                @else
                                @if(userPermission(406))
                                <button class="primary-btn fix-gr-bg small  ">
                                    <span class="ti-check"></span>
                                    @lang('lang.change_logo')
                                </button>
                                @endif
                                @endif

                                @if ($errors->has('main_school_logo'))
                                <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('main_school_logo') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>


                <div class="row mt-40">
                    <div class="col-lg-12">
                        <div class="main-title">

                            <h3 class="mb-30">@lang('lang.change_fav') </h3>
                        </div>

                        @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'admin-dashboard',
                        'method' => 'GET', 'enctype' => 'multipart/form-data']) }}
                        @else
                        @if(userPermission(406))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'update-school-logo',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}

                        @endif
                        @endif

                        <div class="white-box">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="text-center">
                                @if(isset($editData->favicon) && !empty(@$editData->favicon))
                                <img class="img-fluid Img-50" src="{{@$editData->favicon}}" alt="">
                                @else
                                <img class="img-fluid" src="{{asset('public/uploads/settings/favicon.png')}}" alt="">
                                @endif
                            </div>

                            <div class="mt-40">
                                <div class="text-center">
                                    <label class="primary-btn small fix-gr-bg"
                                           for="upload_favicon">@lang('lang.upload')</label>
                                    <input type="file" class="d-none form-control" name="main_school_favicon"
                                           id="upload_favicon">
                                </div>
                            </div>
                            <div class="col-lg-12 text-center">
                                @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                <span class="d-inline-block" tabindex="0" data-toggle="tooltip"
                                      title="Disabled For Demo "> <button class="primary-btn small fix-gr-bg  demo_view"
                                                                          style="pointer-events: none;" type="button">@lang('lang.change_fav')</button></span>
                                @else
                                @if(userPermission(407))
                                <button class="primary-btn fix-gr-bg small white_space">
                                    <span class="ti-check"></span>
                                    @lang('lang.change_fav')
                                </button>
                                @endif
                                @endif
                                @if ($errors->has('main_school_favicon'))
                                <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('main_school_favicon') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>

                <div class="row mt-40">
                    <div class="col-lg-12">
                        <div class="main-title">

                            <h3 class="mb-30">@lang('lang.school_reopen') </h3>
                        </div>

                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'update-school-opening-date',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="white-box">
                            <div class="col-lg-12 text-center">
                                <div class="no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="input-effect">
                                            <input class="primary-input date form-control{{ $errors->has('date_of_opening') ? ' is-invalid' : '' }}"
                                                   id="startDate" type="text"
                                                   name="date_of_opening" value="{{ old('date_of_opening') }}"
                                                   autocomplete="off">
                                            <span class="focus-border"></span>
                                            <label>@lang('lang.date_of_opening')</label>
                                            @if ($errors->has('date_of_opening'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('date_of_opening') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="" type="button">
                                            <i class="ti-calendar" id="start-date-icon"></i>
                                        </button>
                                    </div>
                                </div>
                                <br><br>
                                <button class="primary-btn fix-gr-bg small white_space">
                                    <span class="ti-check"></span>
                                    @lang('lang.set_date')
                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-xl-8">
                <div class="row xm_3">
                    <div class="col-lg-7 col-xl-7 no-gutters col-sm-6">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('lang.general_settings') @lang('lang.view')</h3>
                        </div>
                    </div>
                    <div class=" col-lg-5 col-xl-5 text-right col-md-6 col-sm-6 sm2_10">
                        @if(userPermission(408))
                        <a href="{{route('update-general-settings')}}" class="primary-btn small fix-gr-bg "> <span
                                    class="ti-pencil-alt"></span> @lang('lang.edit')
                        </a>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="white-box">
                            <div class="student-meta-box">

                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.school_name')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{@$editData->school_name}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.motto')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{@$editData->motto}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.site_title')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{@$editData->site_title}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.address')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{@$editData->address}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.phone') @lang('lang.no')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{@$editData->phone}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.email') @lang('lang.address')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{@$editData->email}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.school_code')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{@$editData->school_code}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.registration_no')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{@$editData->registration_no}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.bank_name')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{@$editData->bank_name}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.bank_account')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{@$editData->bank_account}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.bank_branch_code')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{@$editData->branch_code}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.eazymoola_account')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{$ref_no}} ({{$balance}})
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.academic_year')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">

                                                {{@$editData->academic_Year->year }} -
                                                [ {{@$editData->academic_Year->title }} ]

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.date_of_opening')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">

                                                @if(isset($editData))
                                                {{@$editData->re_open_date}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.language')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">

                                                @if(isset($editData))

                                                {{@$editData->languages != ""? @$editData->languages->language_name:""}}

                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.date_format')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{@$editData->dateFormats != ""?
                                                @$editData->dateFormats->normal_view:""}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.time_zone')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{@$editData->timeZone->time_zone}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.currency')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{@$editData->currency}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.currency') @lang('lang.symbol')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{@$editData->currency_symbol}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.max_upload_file_size')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                {{@$editData->file_size}} MB
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.promossion_without') @lang('lang.exam')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(isset($editData))
                                                @if (@$editData->promotionSetting != "" && @$editData->promotionSetting
                                                == 1)
                                                Enable
                                                @else
                                                Disable
                                                @endif
                                                {{-- {{@$editData->promotionSetting != ""? @$editData->promotionSetting
                                                == 1:""}} --}}
                                                {{-- {{$editData->promotionSetting}} --}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name">
                                                @lang('lang.copyright_text')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left">
                                                @if(! is_null($editData->copyright_text))
                                                {!! @$editData->copyright_text !!}

                                                @else

                                                Copyright 2019 All rights reserved by Codethemes
                                                @endif
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
@endsection
