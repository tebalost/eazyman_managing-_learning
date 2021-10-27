@extends('backEnd.master')
@section('mainContent')

@php
function showTimelineDocName($data){
$name = explode('/', $data);
$number = count($name);
return $name[$number-1];
}
function showDocumentName($data){
$name = explode('/', $data);
$number = count($name);
return $name[$number-1];
}
@endphp
@php  $setting = App\SmGeneralSettings::find(1);  if(!empty($setting->currency_symbol)){ $currency = $setting->currency_symbol; }else{ $currency = '$'; }   @endphp

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.student_details')</h1>
            <div class="bc-pages">
                <a href="{{url('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="{{route('student_list')}}">@lang('lang.student_list')</a>
                <a href="#">@lang('lang.student_details')</a>
            </div>
        </div>
    </div>
</section>

<section class="student-details">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-3">
                <!-- Start Student Meta Information -->
                <div class="main-title">
                    <h3 class="mb-20">@lang('lang.student_details')</h3>
                </div>
                <div class="student-meta-box">
                    <div class="student-meta-top"></div>
                    <img class="student-meta-img img-100"
                         src="{{ file_exists(@$student_detail->student_photo) ? asset($student_detail->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}"
                         alt="">

                    <div class="white-box radius-t-y-0">
                        <div class="single-meta mt-10">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.student') @lang('lang.name')
                                </div>
                                {{-- {{ dd($student_detail) }} --}}
                                <div class="value">
                                    {{@$student_detail->first_name.' '.@$student_detail->last_name}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.admission') @lang('lang.number')
                                </div>
                                <div class="value">
                                    {{@$student_detail->admission_no}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.roll') @lang('lang.number')
                                </div>
                                <div class="value">
                                    {{@$student_detail->roll_no}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.class')
                                </div>
                                <div class="value">
                                    @if($student_detail->className!="" && $student_detail->session_id!="")
                                    {{@$student_detail->className->class_name}}
                                    ({{@$academic_year->year}})
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.section')
                                </div>
                                <div class="value">
                                    {{@$student_detail->section->section_name}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.gender')
                                </div>
                                <div class="value">

                                    {{@$student_detail->gender !=""?$student_detail->gender->base_setup_name:""}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Student Meta Information -->

                <!-- Start EazyMoola Information -->
                <br><br>
                <div class="main-title">
                    <h3 class="mb-20">@lang('lang.eazymoola')</h3>
                </div>
                <div class="student-meta-box">
                    <div class="student-meta-top"></div>
                    <img class="student-meta-img img-100" src="{{ asset('public/uploads/staff/demo/wallet.jpg') }}"
                         alt="">

                    <div class="white-box radius-t-y-0">
                        <div class="single-meta mt-10">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.balance')
                                </div>
                                <div class="value">
                                    {{@$userWalletInfo->balance}}

                                </div>
                            </div>
                        </div>

                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.topup')
                                </div>
                                <div class="value">
                                    <div class="text-right mb-20">
                                        <button type="button" data-toggle="modal" data-target="#add_money_madal"
                                                class="primary-btn tr-bg text-uppercase bord-rad">
                                            @lang('lang.money_in')
                                            <span class="pl ti-plus"></span>
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.school_items')
                                </div>
                                <div class="value">
                                    <div class="text-right mb-20">
                                        <button type="button" data-toggle="modal" data-target="#add_timeline_madal"
                                                class="primary-btn tr-bg text-uppercase bord-rad">
                                            @lang('lang.pay')
                                            <span class="pl ti-plus"></span>
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.money_in_out')
                                </div>
                                <div class="value">
                                    <div class="text-right mb-20">
                                        <button type="button" data-toggle="modal" data-target="#add_timeline_madal"
                                                class="primary-btn tr-bg text-uppercase bord-rad">
                                            @lang('lang.view')
                                            <span class="pl ti-angle-right"></span>
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Student EazyMoola Information -->

                {{-- {{ dd($siblings) }} --}}
                @if(count($siblings) >0 )
                <!-- Start Siblings Meta Information -->
                <div class="main-title mt-40">
                    <h3 class="mb-20">@lang('lang.sibling') @lang('lang.information') </h3>
                </div>
                @foreach($siblings as $sibling)

                <div class="student-meta-box mb-20">
                    <div class="student-meta-top siblings-meta-top"></div>
                    <img class="student-meta-img img-100" src="{{asset(@$sibling->student_photo)}}" alt="">
                    <div class="white-box radius-t-y-0">
                        <div class="single-meta mt-10">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.sibling') @lang('lang.name')
                                </div>
                                <div class="value">
                                    {{isset($sibling->full_name)?$sibling->full_name:''}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.admission') @lang('lang.number')
                                </div>
                                <div class="value">
                                    {{@$sibling->admission_no}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.roll') @lang('lang.number')
                                </div>
                                <div class="value">
                                    {{@$sibling->roll_no}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.class')
                                </div>
                                <div class="value">
                                    {{@$sibling->className->class_name}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.section')
                                </div>
                                <div class="value">
                                    {{$sibling->section !=""?$sibling->section->section_name:""}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.gender')
                                </div>
                                <div class="value">
                                    {{$sibling->gender!=""? $sibling->gender->base_setup_name:""}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @endforeach
                <!-- End Siblings Meta Information -->

                @endif
            </div>

            <!-- Start Student Details -->
            <div class="col-lg-9 student-details up_admin_visitor">
                <ul class="nav nav-tabs tabs_scroll_nav" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link  @if (Session::get('studentDocuments') != 'active' && Session::get('studentTimeline') != 'active') active @endif"
                           href="#studentProfile" role="tab" data-toggle="tab">@lang('lang.profile')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#studentFees" role="tab" data-toggle="tab">@lang('lang.fees')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#studentSubjects" role="tab"
                           data-toggle="tab">@lang('lang.subjects')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#eazyMoola" role="tab" data-toggle="tab">@lang('lang.eazymoola')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#studentExam" role="tab" data-toggle="tab">@lang('lang.exam')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{Session::get('studentDocuments') == 'active'? 'active':''}}"
                           href="#studentDocuments" role="tab" data-toggle="tab">@lang('lang.document')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{Session::get('studentTimeline') == 'active'? 'active':''}} "
                           href="#studentTimeline" role="tab" data-toggle="tab">@lang('lang.timeline')</a>
                    </li>
                    <li class="nav-item edit-button">
                        @if(userPermission(66))
                        <a href="{{route('student_edit', [@$student_detail->id])}}"
                           class="primary-btn small fix-gr-bg">@lang('lang.edit')
                        </a>
                        @endif
                    </li>
                </ul>


                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- Start Profile Tab -->
                    <div role="tabpanel"
                         class="tab-pane fade  @if (Session::get('studentDocuments') != 'active' && Session::get('studentTimeline') != 'active') show active @endif"
                         id="studentProfile">
                        <div class="white-box">
                            <h4 class="stu-sub-head">@lang('lang.personal') @lang('lang.info')</h4>
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.admission') @lang('lang.date')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{ !empty($student_detail->admission_date)?
                                            dateConvert($student_detail->admission_date):''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('lang.student') @lang('lang.number')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{ !empty($student_detail->admission_id_number)?
                                            $student_detail->admission_id_number:''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('lang.date_of_birth')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{ !empty($student_detail->date_of_birth)?
                                            dateConvert($student_detail->date_of_birth):''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('lang.age')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{
                                            \Carbon\Carbon::parse($student_detail->date_of_birth)->diff(\Carbon\Carbon::now())->format('%y
                                            years')}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('lang.type')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{$student_detail->category != ""?
                                            $student_detail->category->catgeory_name:""}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('lang.sponsor')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{$student_detail->sponsor != ""?
                                            $student_detail->sponsor->base_setup_name:""}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('lang.orphan')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{$student_detail->orphan != ""?
                                            $student_detail->orphan->base_setup_name:""}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('lang.phone') @lang('lang.number')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{@$student_detail->mobile}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('lang.email') @lang('lang.address')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{@$student_detail->email}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('lang.present') @lang('lang.address')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{@$student_detail->current_address}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('lang.permanent_address')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{@$student_detail->permanent_address}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Start Parent Part -->
                            <h4 class="stu-sub-head mt-40">@lang('lang.Parent_Guardian_Details')</h4>
                            <div class="d-flex">
                                <div class="mr-20 mt-20">
                                    <img class="student-meta-img img-100"
                                         src="{{ file_exists(@$student_detail->parents->fathers_photo) ? asset($student_detail->parents->fathers_photo) : asset('public/uploads/staff/demo/father.png') }}"
                                         alt="">

                                </div>
                                <div class="w-100">
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.father_name')
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{@$student_detail->parents->fathers_name}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.occupation')
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{@$student_detail->parents!=""?@$student_detail->parents->fathers_occupation:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.phone') @lang('lang.number')
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{@$student_detail->parents
                                                    !=""?@$student_detail->parents->fathers_mobile:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex">
                                <div class="mr-20 mt-20">
                                    <img class="student-meta-img img-100"
                                         src="{{ file_exists(@$student_detail->parents->mothers_photo) ? asset($student_detail->parents->mothers_photo) : asset('public/uploads/staff/demo/mother.jpg')}}"
                                         alt="">
                                </div>
                                <div class="w-100">
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.mother_name')
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{$student_detail->parents
                                                    !=""?@$student_detail->parents->mothers_name:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.occupation')
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{$student_detail->parents
                                                    !=""?@$student_detail->parents->mothers_occupation:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.phone') @lang('lang.number')
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{$student_detail->parents
                                                    !=""?@$student_detail->parents->mothers_mobile:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex">
                                <div class="mr-20 mt-20">
                                    <img class="student-meta-img img-100"
                                         src="{{ file_exists(@$student_detail->parents->guardians_photo) ? asset($student_detail->parents->guardians_photo) : asset('public/uploads/staff/demo/guardian.jpg')}}"
                                         alt="">

                                </div>
                                <div class="w-100">
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.guardian_name')
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{$student_detail->parents
                                                    !=""?@$student_detail->parents->guardians_name:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.email') @lang('lang.address')
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{$student_detail->parents
                                                    !=""?@$student_detail->parents->guardians_email:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.phone') @lang('lang.number')
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{$student_detail->parents
                                                    !=""?@$student_detail->parents->guardians_mobile:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.relation_with_guardian')
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{$student_detail->parents
                                                    !=""?@$student_detail->parents->guardians_relation:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.occupation')
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{$student_detail->parents
                                                    !=""?@$student_detail->parents->guardians_occupation:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.guardian_address')
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{$student_detail->parents
                                                    !=""?@$student_detail->parents->guardians_address:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Parent Part -->

                            <!-- Start Transport Part -->
                            <h4 class="stu-sub-head mt-40">@lang('lang.transport_and_dormitory_info')</h4>


                            @if (!empty($student_detail->route_list_id))

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.route')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->route_list_id)? @$student_detail->route->title:
                                            ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @endif

                            @if (isset($student_detail->vehicle))
                            @if (!empty($vehicle_no))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.vehicle_number')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{$student_detail->vehicle != ""? @$student_detail->vehicle->vehicle_no:
                                            ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>


                            @endif


                            @endif


                            @if (isset($driver_info))
                            @if (!empty($driver_info->full_name))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.driver_name')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{$student_detail->vechile_id != ""? @$driver_info->full_name:''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @endif

                            @if (isset($driver_info))
                            @if (!empty($driver_info->mobile))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.driver') @lang('lang.phone') @lang('lang.number')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{$student_detail->vechile_id != ""? @$driver_info->mobile:''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif


                            @if (isset($student_detail->dormitory))
                            @if (!empty($student_detail->dormitory->dormitory_name))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.dormitory') @lang('lang.name')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->dormitory_id)?@$student_detail->dormitory->dormitory_name:
                                            ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif

                            <!-- End Transport Part -->

                            <!-- Start Other Information Part -->
                            <h4 class="stu-sub-head mt-40">@lang('lang.Other') @lang('lang.information')</h4>
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.blood_group')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->bloodgroup_id)?
                                            @$student_detail->bloodGroup->base_setup_name: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.height')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->height)? @$student_detail->height: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.Weight')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->weight)? @$student_detail->weight: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.previous_school_details')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->previous_school_details)?
                                            @$student_detail->previous_school_details: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.national_iD_number')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->national_id_no)? @$student_detail->national_id_no:
                                            ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.local_Id_Number')
                                        </div>
                                    </div>


                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->local_id_no)? @$student_detail->local_id_no: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.bank_account_number')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->bank_account_no)?
                                            @$student_detail->bank_account_no: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.bank_name')
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->bank_name)? @$student_detail->bank_name: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.IFSC_Code')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->ifsc_code)? @$student_detail->ifsc_code: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Other Information Part -->
                        </div>
                    </div>
                    <!-- End Profile Tab -->

                    <!-- Start Fees Tab -->
                    <div role="tabpanel" class="tab-pane fade" id="studentFees">
                        <div class="table-responsive">
                            <table class="display school-table school-table-style res_scrol" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>@lang('lang.fees_group')</th>
                                    <th>@lang('lang.fees_code')</th>
                                    <th>@lang('lang.due_date')</th>
                                    <th>@lang('lang.Status')</th>
                                    <th>@lang('lang.amount') ({{@$currency}})</th>
                                    <th>@lang('lang.payment_ID')</th>
                                    <th>@lang('lang.mode')</th>
                                    <th>@lang('lang.date')</th>
                                    <th>@lang('lang.discount') ({{@$currency}})</th>
                                    <th>@lang('lang.fine') ({{@$currency}})</th>
                                    <th>@lang('lang.paid') ({{@$currency}})</th>
                                    <th>@lang('lang.balance') ({{@$currency}})</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php
                                @$grand_total = 0;
                                @$total_fine = 0;
                                @$total_discount = 0;
                                @$total_paid = 0;
                                @$total_grand_paid = 0;
                                @$total_balance = 0;
                                @endphp
                                @foreach($fees_assigneds as $fees_assigned)
                                @php
                                @$grand_total += @$fees_assigned->feesGroupMaster->amount;


                                @endphp

                                @php
                                @$discount_amount = $fees_assigned->applied_discount;
                                @$total_discount += @$discount_amount;
                                @$student_id = @$fees_assigned->student_id;
                                @endphp
                                @php
                                @$paid = App\SmFeesAssign::discountSum(@$fees_assigned->student_id,
                                @$fees_assigned->feesGroupMaster->feesTypes->id, 'amount');
                                @$total_grand_paid += @$paid;
                                @endphp
                                @php
                                @$fine = App\SmFeesAssign::discountSum(@$fees_assigned->student_id,
                                @$fees_assigned->feesGroupMaster->feesTypes->id, 'fine');
                                @$total_fine += @$fine;
                                @endphp

                                @php
                                @$total_paid = @$discount_amount + @$paid;
                                @endphp
                                <tr>
                                    <td>{{@$fees_assigned->feesGroupMaster->feesGroups
                                        !=""?@$fees_assigned->feesGroupMaster->feesGroups->name:""}}
                                    </td>
                                    <td>
                                        {{@$fees_assigned->feesGroupMaster->feesTypes!=""?@$fees_assigned->feesGroupMaster->feesTypes->name:""}}
                                    </td>
                                    <td>
                                        @if(!empty(@$fees_assigned->feesGroupMaster))
                                        {{@$fees_assigned->feesGroupMaster->date != ""?
                                        dateConvert(@$fees_assigned->feesGroupMaster->date):''}}
                                        @endif
                                    </td>
                                    @php
                                    $total_payable_amount=$fees_assigned->fees_amount;
                                    $rest_amount = $fees_assigned->feesGroupMaster->amount - $total_paid;
                                    $total_balance += $total_payable_amount;
                                    $balance_amount=number_format($rest_amount+$fine, 2, '.', '');
                                    @endphp
                                    <td>

                                        @if($balance_amount ==0)
                                        <button class="primary-btn small bg-success text-white border-0">
                                            @lang('lang.paid')
                                        </button>
                                        @elseif($paid != 0)
                                        <button class="primary-btn small bg-warning text-white border-0">
                                            @lang('lang.partial')
                                        </button>
                                        @elseif($paid == 0)
                                        <button class="primary-btn small bg-danger text-white border-0">
                                            @lang('lang.unpaid')
                                        </button>
                                        @endif

                                    </td>
                                    <td>
                                        @php
                                        echo number_format($fees_assigned->feesGroupMaster->amount+$fine, 2, '.', '');
                                        @endphp
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td> {{@$discount_amount}}</td>
                                    <td>{{@$fine}}</td>
                                    <td>{{@$paid}}</td>
                                    <td>
                                        @php

                                        echo @$total_payable_amount;
                                        @endphp

                                    </td>
                                </tr>
                                @php
                                @$payments =
                                App\SmFeesAssign::feesPayment(@$fees_assigned->feesGroupMaster->feesTypes->id,
                                @$fees_assigned->student_id);
                                $i = 0;
                                @endphp

                                @foreach($payments as $payment)
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right"><img src="{{asset('public/backEnd/img/table-arrow.png')}}">
                                    </td>
                                    <td>
                                        @php
                                        @$created_by = App\User::find(@$payment->created_by);
                                        @endphp
                                        @if(@$created_by != "")
                                        <a href="#" data-toggle="tooltip" data-placement="right"
                                           title="{{'Collected By: '.@$created_by->full_name}}">{{@$payment->fees_type_id.'/'.@$payment->id}}</a>
                                    </td>
                                    @endif
                                    <td>
                                        @if(@$payment->payment_mode == "C")
                                        {{'Cash'}}
                                        @elseif(@$payment->payment_mode == "Cq")
                                        {{'Cheque'}}
                                        @elseif(@$payment->payment_mode == "bank")
                                        {{'Bank'}}
                                        @elseif('DD')
                                        {{'DD'}}
                                        @elseif('PS')
                                        {{'Paystack'}}
                                        @endif
                                    </td>
                                    <td>
                                        {{@$payment->payment_date != ""? dateConvert(@$payment->payment_date):''}}
                                    </td>
                                    <td>{{@$payment->discount_amount}}</td>
                                    <td>{{@$payment->fine}}</td>
                                    <td>{{@$payment->amount}}</td>
                                    <td></td>
                                </tr>
                                @endforeach
                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>@lang('lang.grand_total') ({{@$currency}})</th>
                                    <th></th>
                                    <th>{{@$grand_total+$total_fine}}</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>{{@$total_discount}}</th>
                                    <th>{{@$total_fine}}</th>
                                    <th>{{@$total_grand_paid}}</th>
                                    <th>{{@$total_balance}}</th>
                                    <th></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!-- End Profile Tab -->

                    <!-- Start Subjects Payment Tab -->
                    <div role="tabpanel" class="tab-pane fade" id="studentSubjects">

                        <div class="table-responsive">
                            <div class="white-box">
                                <h4 class="stu-sub-head">@lang('lang.section') @lang('lang.subjects')</h4>

                                    {{--All Optional Subjects--}}
                                    @php $optionalSubjects = []; @endphp
                                    @foreach($optional_subjects as $optionals)
                                    @php $optionalSubjects[] = $optionals->subject_id;
                                    @endphp
                                    @endforeach

                                    @php $optional = $optionalSubjects; @endphp

                                    @foreach($assigned_subjects as $assignSubject)
                                    @php

                                    $subject_id = $assignSubject->id;

                                    @endphp
                                    @if(!in_array($subject_id,$optional))

                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-6">
                                                <div class="">
                                                    {{@$assignSubject->subject_code}}
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <div class="">
                                                    {{$assignSubject->subject_name}}
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-7">
                                                <div class="">
                                                    {{@$assignSubject->teacher_name}}
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-7">
                                                <div class="">
                                                    {{@$assignSubject->subject_type == "T"? 'Theory': 'Practical'}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @endif
                                    @endforeach

                                <br>
                                <h4 class="stu-sub-head">@lang('lang.elective') @lang('lang.subjects')</h4>
                                @if(isset($assigned_optional_subjects))
                                   @foreach($assigned_optional_subjects as $assignSubject)

                                @php

                                $subject_id = $assignSubject->id;

                                $teacher= DB::table("sm_staffs")
                                ->whereIn("id", function($query) use ($student_detail, $assignSubject) {
                                $query->from("sm_assign_subjects")
                                ->select("teacher_id")
                                ->where("subject_id", "=", $assignSubject->id)
                                ->where("section_id", "=", $student_detail->section_id)
                                ->where("class_id", "=", $student_detail->class_id);
                                })
                                ->first();
                                @endphp
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-6">
                                                <div class="">
                                                    {{@$assignSubject->subject_code}}
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <div class="">
                                                    {{$assignSubject->subject_name}}
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <div class="">
                                                    {{$teacher->full_name}}
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-7">
                                                <div class="">
                                                    {{@$assignSubject->subject_type == "T"? 'Theory': 'Practical'}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach

                                @endif
                                <div class="white-box">
                                </div>
                            </div>
                        </div>
                    </div>
                            <!-- End Subjects Payment Tab -->

                            <!-- Start EazyMoola Payment Tab -->
                            <div role="tabpanel" class="tab-pane fade" id="eazyMoola">
                                <div class="table-responsive">
                                    <table class="display school-table school-table-style res_scrol" cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>@lang('lang.fees_group')</th>
                                            <th>@lang('lang.fees_code')</th>
                                            <th>@lang('lang.due_date')</th>
                                            <th>@lang('lang.Status')</th>
                                            <th>@lang('lang.amount') ({{@$currency}})</th>
                                            <th>@lang('lang.discount') ({{@$currency}})</th>
                                            <th>@lang('lang.fine') ({{@$currency}})</th>
                                            <th>@lang('lang.paid') ({{@$currency}})</th>
                                            <th>@lang('lang.balance') ({{@$currency}})</th>
                                            <th>@lang('lang.action')</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @php
                                        @$grand_total = 0;
                                        @$total_fine = 0;
                                        @$total_discount = 0;
                                        @$total_paid = 0;
                                        @$total_grand_paid = 0;
                                        @$total_balance = 0;
                                        @endphp
                                        @foreach($fees_assigneds as $fees_assigned)


                                        @php
                                        @$discount_amount = $fees_assigned->applied_discount;
                                        @$total_discount += @$discount_amount;
                                        @$student_id = @$fees_assigned->student_id;
                                        @endphp
                                        @php
                                        @$paid = App\SmFeesAssign::discountSum(@$fees_assigned->student_id,
                                        @$fees_assigned->feesGroupMaster->feesTypes->id, 'amount');
                                        @$total_grand_paid += @$paid;
                                        @endphp
                                        @php
                                        @$fine = App\SmFeesAssign::discountSum(@$fees_assigned->student_id,
                                        @$fees_assigned->feesGroupMaster->feesTypes->id, 'fine');
                                        @$total_fine += @$fine;
                                        @endphp

                                        @php
                                        @$total_paid = @$discount_amount + @$paid;

                                        $total_payable_amount=$fees_assigned->fees_amount;
                                        $rest_amount = $fees_assigned->feesGroupMaster->amount - $total_paid;
                                        $total_balance += $total_payable_amount;
                                        $balance_amount=number_format($rest_amount+$fine, 2, '.', '');
                                        @endphp
                                        @if($balance_amount !=0)
                                        @php
                                        @$grand_total_for_items += @$paid;
                                        @endphp
                                        <tr>
                                            <td>{{@$fees_assigned->feesGroupMaster->feesGroups
                                                !=""?@$fees_assigned->feesGroupMaster->feesGroups->name:""}}
                                            </td>
                                            <td>
                                                {{@$fees_assigned->feesGroupMaster->feesTypes!=""?@$fees_assigned->feesGroupMaster->feesTypes->name:""}}
                                            </td>
                                            <td>
                                                @if(!empty(@$fees_assigned->feesGroupMaster))
                                                {{@$fees_assigned->feesGroupMaster->date != ""?
                                                dateConvert(@$fees_assigned->feesGroupMaster->date):''}}
                                                @endif
                                            </td>

                                            <td>

                                                @if($balance_amount ==0)
                                                <button class="primary-btn small bg-success text-white border-0">
                                                    @lang('lang.paid')
                                                </button>
                                                @elseif($paid != 0)
                                                <button class="primary-btn small bg-warning text-white border-0">
                                                    @lang('lang.partial')
                                                </button>
                                                @elseif($paid == 0)
                                                <button class="primary-btn small bg-danger text-white border-0">
                                                    @lang('lang.unpaid')
                                                </button>
                                                @endif

                                            </td>
                                            <td>
                                                @php
                                                echo number_format($fees_assigned->feesGroupMaster->amount+$fine, 2,
                                                '.', '');
                                                @endphp
                                            </td>

                                            <td> {{@$discount_amount}}</td>
                                            <td>{{@$fine}}</td>
                                            <td>{{@$paid}}</td>
                                            <td>
                                                @php

                                                echo @$total_payable_amount;
                                                @endphp

                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn dropdown-toggle"
                                                            data-toggle="dropdown">
                                                        @lang('lang.select')
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">

                                                        @if(userPermission(111))

                                                        @if($balance_amount != 0)
                                                        <a class="dropdown-item modalLink" data-modal-size="modal-lg"
                                                           title="{{@$fees_assigned->feesGroupMaster->feesGroups->name.': '. $fees_assigned->feesGroupMaster->feesTypes->name}}"
                                                           href="{{route('fees-generate-modal', [$rest_amount+$fine, $fees_assigned->student_id, $fees_assigned->feesGroupMaster->fees_type_id,$fees_assigned->fees_master_id])}}">@lang('lang.add_fees') </a>
                                                        @else
                                                        <a class="dropdown-item" target="_blank">Payment Done</a>
                                                        @endif
                                                        @endif

                                                        @if(userPermission(112))

                                                        {{-- <a class="dropdown-item"
                                                                href="{{route('fees_group_print', [$fees_assigned->id])}}"
                                                                target="_blank">Print</a> --}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                        @php
                                        @$payments =
                                        App\SmFeesAssign::feesPayment(@$fees_assigned->feesGroupMaster->feesTypes->id,
                                        @$fees_assigned->student_id);
                                        $i = 0;
                                        @endphp


                                        @endforeach

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>@lang('lang.grand_total') ({{@$currency}})</th>
                                            <th></th>
                                            <th>{{@$grand_total+$total_fine}}</th>
                                            <th>{{@$total_discount}}</th>
                                            <th>{{@$total_fine}}</th>
                                            <th>{{@$grand_total_for_items}}</th>
                                            <th>{{@$total_balance}}</th>
                                            <th></th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <!-- End EazyMoola Payment Tab -->

                            <!-- Start Exam Tab -->
                            <div role="tabpanel" class="tab-pane fade" id="studentExam">

                                @php
                                $exam_count= count($exam_terms);
                                @endphp
                                @if($exam_count<1)
                                <div class="white-box no-search no-paginate no-table-info mb-2">
                                    <table class="display school-table" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>@lang('lang.subject')</th>
                                            <th>@lang('lang.full_marks')</th>
                                            <th>@lang('lang.passing_marks')</th>
                                            <th>@lang('lang.obtained_marks')</th>
                                            <th>@lang('lang.results')</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                                @endif

                                <div class="white-box no-search no-paginate no-table-info mb-2">
                                    @foreach($exam_terms as $exam)

                                    @php

                                    $get_results = App\SmStudent::getExamResult(@$exam->id, @$student_detail);


                                    @endphp


                                    @if($get_results)

                                    <div class="main-title">
                                        <h3 class="mb-0">{{@$exam->title}}</h3>
                                    </div>
                                    <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>@lang('lang.date')</th>
                                            <th>@lang('lang.subject')</th>
                                            <th>@lang('lang.full_marks')</th>
                                            <th>@lang('lang.obtained_marks')</th>
                                            <th>@lang('lang.grade')</th>
                                            <!-- <th>@lang('lang.results')</th> -->
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @php
                                        $grand_total = 0;
                                        $grand_total_marks = 0;
                                        $result = 0;
                                        @endphp

                                        @foreach($get_results as $mark)
                                        @php
                                        $subject_marks = App\SmStudent::fullMarksBySubject($exam->id,
                                        $mark->subject_id);

                                        $schedule_by_subject = App\SmStudent::scheduleBySubject($exam->id,
                                        $mark->subject_id, @$student_detail);

                                        $result_subject = 0;

                                        $grand_total_marks += @$subject_marks->exam_mark;

                                        if(@$mark->is_absent == 0){
                                        $grand_total += @$mark->total_marks;
                                        if($mark->marks < $subject_marks->pass_mark){
                                        $result_subject++;
                                        $result++;
                                        }
                                        }else{
                                        $result_subject++;
                                        $result++;
                                        }
                                        @endphp
                                        <tr>
                                            <td>{{ !empty($schedule_by_subject->date)?
                                                dateConvert($schedule_by_subject->date):''}}
                                            </td>
                                            <td>{{@$mark->subject->subject_name}}</td>
                                            <td>{{@$subject_marks->exam_mark}}</td>
                                            <td>{{@$mark->total_marks}}</td>
                                            <td>{{@$mark->total_gpa_grade}}</td>
                                            <!-- <td>
                                                @if($result_subject == 0)
                                                    <button
                                                        class="primary-btn small bg-success text-white border-0">
                                                        @lang('lang.pass')
                                                    </button>
                                                @else
                                                    <button class="primary-btn small bg-danger text-white border-0">
                                                        @lang('lang.fail')
                                                    </button>
                                                @endif
                                            </td> -->
                                        </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>@lang('lang.grand_total'): {{$grand_total}}/{{$grand_total_marks}}</th>
                                            <th></th>
                                            <th>@lang('lang.grade'):
                                                @php
                                                if($result == 0 && $grand_total_marks != 0){
                                                $percent = $grand_total/$grand_total_marks*100;


                                                foreach($grades as $grade){
                                                if(floor($percent) >= $grade->percent_from && floor($percent) <=
                                                $grade->percent_upto){
                                                echo $grade->grade_name;
                                                }
                                                }

                                                }else{
                                                echo "F";
                                                }
                                                @endphp
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>

                                    @endif

                                    @endforeach
                                </div>

                            </div>
                            <!-- End Exam Tab -->

                            <!-- Start Documents Tab -->
                            <div role="tabpanel"
                                 class="tab-pane fade {{Session::get('studentDocuments') == 'active'? 'show active':''}}"
                                 id="studentDocuments">
                                <div class="white-box">
                                    <div class="text-right mb-20">
                                        <button type="button" data-toggle="modal" data-target="#add_document_madal"
                                                class="primary-btn tr-bg text-uppercase bord-rad">
                                            @lang('lang.upload') @lang('lang.document')
                                            <span class="pl ti-upload"></span>
                                        </button>
                                    </div>
                                    <table id="" class="table simple-table table-responsive school-table"
                                           cellspacing="0">
                                        <thead class="d-block">
                                        <tr class="d-flex">
                                            <th class="col-3">@lang('lang.document') @lang('lang.title')</th>
                                            <th class="col-6">@lang('lang.name')</th>
                                            <th class="col-3">@lang('lang.action')</th>
                                        </tr>
                                        </thead>

                                        <tbody class="d-block">
                                        @if($student_detail->document_file_1 != "")
                                        <tr class="d-flex">
                                            <td class="col-3">{{$student_detail->document_title_1}}</td>
                                            <td class="col-6">{{showDocument(@$student_detail->document_file_1)}}</td>
                                            <td class="col-3">
                                                @if (file_exists($student_detail->document_file_1))
                                                <a class="primary-btn tr-bg text-uppercase bord-rad"
                                                   href="{{url('download-document/'.showDocumentName($student_detail->document_file_1))}}">
                                                    @lang('lang.download')<span class="pl ti-download"></span>
                                                </a>
                                                <a class="primary-btn icon-only bg-danger text-light delete-doc"
                                                   onclick="deleteDoc({{$student_detail->id}},1)" data-id="1" href="#">
                                                    <span class="ti-trash"></span>
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                        @if($student_detail->document_file_2 != "")
                                        <tr class="d-flex">
                                            <td class="col-3">{{$student_detail->document_title_2}}</td>
                                            <td class="col-6">{{showDocument(@$student_detail->document_file_2)}}</td>
                                            <td class="col-3">
                                                @if (file_exists($student_detail->document_file_2))
                                                <a class="primary-btn tr-bg text-uppercase bord-rad"
                                                   href="{{url('download-document/'.showDocumentName($student_detail->document_file_2))}}">
                                                    @lang('lang.download')<span class="pl ti-download"></span>
                                                </a>
                                                <a class="primary-btn icon-only bg-danger text-light delete-doc"
                                                   onclick="deleteDoc({{$student_detail->id}},2)" data-id="2" href="#">
                                                    <span class="ti-trash"></span>
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                        @if($student_detail->document_file_3 != "")
                                        <tr class="d-flex">
                                            <td class="col-3">{{$student_detail->document_title_3}}</td>
                                            <td class="col-6">{{showDocument(@$student_detail->document_file_3)}}</td>
                                            <td class="col-3">
                                                @if (file_exists($student_detail->document_file_3))
                                                <a class="primary-btn tr-bg text-uppercase bord-rad"
                                                   href="{{url('download-document/'.showDocumentName($student_detail->document_file_3))}}">
                                                    @lang('lang.download')<span class="pl ti-download"></span>
                                                </a>
                                                <a class="primary-btn icon-only bg-danger text-light delete-doc"
                                                   onclick="deleteDoc({{$student_detail->id}},3)" data-id="3" href="#">
                                                    <span class="ti-trash"></span>
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                        @if($student_detail->document_file_4 != "")
                                        <tr class="d-flex">
                                            <td class="col-3">{{$student_detail->document_title_4}}</td>
                                            <td class="col-6">{{showDocument(@$student_detail->document_file_4)}}</td>
                                            <td class="col-3">
                                                @if (file_exists($student_detail->document_file_4))
                                                <a class="primary-btn tr-bg text-uppercase bord-rad"
                                                   href="{{url('download-document/'.showDocumentName($student_detail->document_file_4))}}">
                                                    @lang('lang.download')<span class="pl ti-download"></span>
                                                </a>

                                                <a class="primary-btn icon-only bg-danger text-light delete-doc"
                                                   onclick="deleteDoc({{$student_detail->id}},4)" data-id="4" href="#">
                                                    <span class="ti-trash"></span>
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                        {{-- fgfdg --}}

                                        <div class="modal fade admin-query" id="delete-doc">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">@lang('lang.delete')</h4>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            &times;
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            <h4>@lang('lang.are_you_sure_to_delete')</h4>
                                                        </div>

                                                        <div class="mt-40 d-flex justify-content-between">
                                                            <form action="{{route('student_document_delete')}}"
                                                                  method="POST">
                                                                @csrf
                                                                <input type="hidden" name="student_id">
                                                                <input type="hidden" name="doc_id">
                                                                <button type="button" class="primary-btn tr-bg"
                                                                        data-dismiss="modal">@lang('lang.cancel')
                                                                </button>
                                                                <button type="submit" class="primary-btn fix-gr-bg">
                                                                    @lang('lang.delete')
                                                                </button>

                                                            </form>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        {{-- fgfdg --}}

                                        @foreach($documents as $document)

                                        @php
                                        // $name = explode('/', $document->file);
                                        // dd($name);

                                        // if(!function_exists('showDocumentName')){
                                        // function showDocumentName($data){
                                        // $name = explode('/', $data);
                                        // return $name[4];
                                        // }
                                        // }

                                        @endphp
                                        <tr class="d-flex">
                                            <td class="col-3">{{$document->title}}</td>
                                            <td class="col-6">{{showDocument($document->file)}}</td>
                                            <td class="col-3">
                                                @if (file_exists($document->file))
                                                <a class="primary-btn tr-bg text-uppercase bord-rad"
                                                   href="{{url('download-document/'.showDocumentName($document->file))}}">
                                                    @lang('lang.download')<span class="pl ti-download"></span>
                                                </a>
                                                @endif
                                                <a class="primary-btn icon-only fix-gr-bg" data-toggle="modal"
                                                   data-target="#deleteDocumentModal{{$document->id}}" href="#">
                                                    <span class="ti-trash"></span>
                                                </a>

                                            </td>
                                        </tr>
                                        <div class="modal fade admin-query" id="deleteDocumentModal{{$document->id}}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">@lang('lang.delete')</h4>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            &times;
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            <h4>@lang('lang.are_you_sure_to_delete')</h4>
                                                        </div>

                                                        <div class="mt-40 d-flex justify-content-between">
                                                            <button type="button" class="primary-btn tr-bg"
                                                                    data-dismiss="modal">@lang('lang.cancel')
                                                            </button>
                                                            <a class="primary-btn fix-gr-bg"
                                                               href="{{route('delete-student-document', [$document->id])}}">
                                                                @lang('lang.delete')</a>
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
                            <!-- End Documents Tab -->
                            <!-- Add Document modal form start-->
                            <div class="modal fade admin-query" id="add_document_madal">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title"> @lang('lang.upload') @lang('lang.document')</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="container-fluid">
                                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' =>
                                                'upload_document',
                                                'method' => 'POST', 'enctype' => 'multipart/form-data', 'name' =>
                                                'document_upload']) }}
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <input type="hidden" name="student_id"
                                                               value="{{$student_detail->id}}">
                                                        <div class="row mt-25">
                                                            <div class="col-lg-12">
                                                                <div class="input-effect">
                                                                    <input class="primary-input form-control{"
                                                                           type="text"
                                                                           name="title" value="" id="title">
                                                                    <label> @lang('lang.title')<span>*</span> </label>
                                                                    <span class="focus-border"></span>

                                                                    <span class=" text-danger" role="alert"
                                                                          id="amount_error">
                                                                    
                                                                </span>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 mt-30">
                                                        <div class="row no-gutters input-right-icon">
                                                            <div class="col">
                                                                <div class="input-effect">
                                                                    <input class="primary-input" type="text"
                                                                           id="placeholderPhoto" placeholder="Document"
                                                                           disabled>
                                                                    <span class="focus-border"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <button class="primary-btn-small-input" type="button">
                                                                    <label class="primary-btn small fix-gr-bg"
                                                                           for="photo"> @lang('lang.browse')</label>
                                                                    <input type="file" class="d-none" name="photo"
                                                                           id="photo">
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <!-- <div class="col-lg-12 text-center mt-40">
                                                        <button class="primary-btn fix-gr-bg" id="save_button_sibling" type="button">
                                                            <span class="ti-check"></span>
                                                            save information
                                                        </button>
                                                    </div> -->
                                                    <div class="col-lg-12 text-center mt-40">
                                                        <div class="mt-40 d-flex justify-content-between">
                                                            <button type="button" class="primary-btn tr-bg"
                                                                    data-dismiss="modal">@lang('lang.cancel')
                                                            </button>

                                                            <button class="primary-btn fix-gr-bg" type="submit">
                                                                @lang('lang.save')
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{ Form::close() }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- Add Document modal form end-->
                            <!-- delete document modal -->

                            <!-- delete document modal -->
                            <!-- Start Timeline Tab -->
                            <div role="tabpanel"
                                 class="tab-pane fade {{Session::get('studentTimeline') == 'active'? 'show active':''}}"
                                 id="studentTimeline">
                                <div class="white-box">
                                    <div class="text-right mb-20">
                                        <button type="button" data-toggle="modal" data-target="#add_timeline_madal"
                                                class="primary-btn tr-bg text-uppercase bord-rad">
                                            @lang('lang.add')
                                            <span class="pl ti-plus"></span>
                                        </button>

                                    </div>
                                    @foreach($timelines as $timeline)
                                    <div class="student-activities">
                                        <div class="single-activity">
                                            <h4 class="title text-uppercase">

                                                {{$timeline->date != ""? dateConvert($timeline->date):''}}

                                            </h4>
                                            <div class="sub-activity-box d-flex">
                                                <h6 class="time text-uppercase">10.30 pm</h6>
                                                <div class="sub-activity">
                                                    <h5 class="subtitle text-uppercase"> {{$timeline->title}}</h5>
                                                    <p>
                                                        {{$timeline->description}}
                                                    </p>
                                                </div>

                                                <div class="close-activity">

                                                    <a class="primary-btn icon-only fix-gr-bg" data-toggle="modal"
                                                       data-target="#deleteTimelineModal{{$timeline->id}}" href="#">
                                                        <span class="ti-trash text-white"></span>
                                                    </a>

                                                    @if (file_exists($timeline->file))
                                                    <a href="{{url('staff-download-timeline-doc/'.showTimelineDocName($timeline->file))}}"
                                                       class="primary-btn tr-bg text-uppercase bord-rad">
                                                        @lang('lang.download')<span class="pl ti-download"></span>
                                                    </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade admin-query" id="deleteTimelineModal{{$timeline->id}}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">@lang('lang.delete')</h4>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            &times;
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            <h4>@lang('lang.are_you_sure_to_delete')</h4>
                                                        </div>

                                                        <div class="mt-40 d-flex justify-content-between">
                                                            <button type="button" class="primary-btn tr-bg"
                                                                    data-dismiss="modal">@lang('lang.cancel')
                                                            </button>
                                                            <a class="primary-btn fix-gr-bg"
                                                               href="{{route('delete_timeline', [$timeline->id])}}">
                                                                @lang('lang.delete')</a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <!-- End Timeline Tab -->
                        </div>
                    </div>
                    <!-- End Student Details -->
                </div>


            </div>
</section>

<!-- timeline form modal start-->
<div class="modal fade admin-query" id="add_timeline_madal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('lang.add') @lang('lang.timeline')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="container-fluid">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student_timeline_store',
                    'method' => 'POST', 'enctype' => 'multipart/form-data', 'name' => 'document_upload']) }}
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="hidden" name="student_id" value="{{$student_detail->id}}">
                            <div class="row mt-25">
                                <div class="col-lg-12">
                                    <div class="input-effect">
                                        <input class="primary-input form-control{" type="text" name="title" value=""
                                               id="title" maxlength="200">
                                        <label>@lang('lang.title') <span>*</span> </label>
                                        <span class="focus-border"></span>

                                        <span class=" text-danger" role="alert" id="amount_error">
                                                
                                            </span>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-30">
                            <div class="no-gutters input-right-icon">
                                <div class="col">
                                    <div class="input-effect">
                                        <input class="primary-input date form-control" readonly id="startDate"
                                               type="text"
                                               name="date">
                                        <label>@lang('lang.date')</label>
                                        <span class="focus-border"></span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button class="" type="button">
                                        <i class="ti-calendar" id="start-date-icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-30">
                            <div class="input-effect">
                                    <textarea class="primary-input form-control" cols="0" rows="3" name="description"
                                              id="Description"></textarea>
                                <label>@lang('lang.description')<span></span> </label>
                                <span class="focus-border textarea"></span>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-40">
                            <div class="row no-gutters input-right-icon">
                                <div class="col">
                                    <div class="input-effect">
                                        <input class="primary-input" type="text" id="placeholderFileFourName"
                                               placeholder="Document"
                                               disabled>
                                        <span class="focus-border"></span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button class="primary-btn-small-input" type="button">
                                        <label class="primary-btn small fix-gr-bg"
                                               for="document_file_4">@lang('lang.browse')</label>
                                        <input type="file" class="d-none" name="document_file_4"
                                               id="document_file_4">
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-30">

                            <input type="checkbox" id="currentAddressCheck" class="common-checkbox"
                                   name="visible_to_student" value="1">
                            <label for="currentAddressCheck">@lang('lang.visible_to_this_person')</label>
                        </div>


                        <!-- <div class="col-lg-12 text-center mt-40">
                            <button class="primary-btn fix-gr-bg" id="save_button_sibling" type="button">
                                <span class="ti-check"></span>
                                save information
                            </button>
                        </div> -->
                        <div class="col-lg-12 text-center mt-40">
                            <div class="mt-40 d-flex justify-content-between">
                                <button type="button" class="primary-btn tr-bg" data-dismiss="modal">
                                    @lang('lang.cancel')
                                </button>

                                <button class="primary-btn fix-gr-bg" type="submit">@lang('lang.save')</button>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
</div>
<!-- timeline form modal end-->

<!-- TopUp form modal start-->
<div class="modal fade admin-query" id="add_money_madal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('lang.topup') @lang('lang.to') @lang('lang.eazymoola')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="container-fluid">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student_eazymoola_topup',
                    'method' => 'POST', 'enctype' => 'multipart/form-data', 'name' => 'eazymoola_topup']) }}
                    <div class="row">
                        <!-- Payment Method -->
                        <div class="col-lg-12">
                            <input type="hidden" name="student_id" value="{{$student_detail->id}}">
                            <!--TODO, Pupulate this form with EazyMoola Details, currently using student details for display -->
                            <div class="row mt-25">
                                <div class="col-lg-12">
                                    <div class="input-effect">

                                        <select class="niceSelect w-100 bb form-control{{ @$errors->has('payment_method') ? ' is-invalid' : '' }}"
                                                name="payment_method"
                                                id="payment_method">
                                            <option data-display="@lang('lang.payment_method') *"
                                                    value="">@lang('lang.payment_method') *
                                            </option>
                                            @foreach($payment_methods as $payment_method)
                                            <option value="{{@$payment_method->method}}">{{@$payment_method->method}}
                                            </option>
                                            @endforeach
                                        </select>
                                        @if (@$errors->has('payment_method'))
                                        <span class="invalid-feedback invalid-select" role="alert">
                                                <strong>{{ @$errors->first('payment_method') }}</strong>
                                            </span>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Name -->
                        <div class="col-lg-12 mt-30">
                            <div class="no-gutters input-right-icon">
                                <div class="col">
                                    <div class="input-effect">
                                        <input class="primary-input form-control" type="text"
                                               name="name" readonly
                                               value="{{@$student_detail->first_name.' '.@$student_detail->last_name}}">
                                        <label>@lang('lang.name')</label>
                                        <span class="focus-border"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- EazyMoola Reference -->
                        <div class="col-lg-12 mt-30">
                            <div class="no-gutters input-right-icon">
                                <div class="col">
                                    <div class="input-effect">
                                        <input class="primary-input form-control" type="text"
                                               name="reference" readonly
                                               value="nmC4533n">
                                        <label>@lang('lang.reference')</label>
                                        <span class="focus-border"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Mobile Number registered with -->
                        <div class="col-lg-12 mt-30">

                            <div class="no-gutters input-right-icon">
                                <div class="col">
                                    <div class="input-effect">
                                        <input class="primary-input form-control" type="text"
                                               name="msisdn" readonly
                                               value="{{@$student_detail->mobile}}">
                                        <label>@lang('lang.phone')</label>
                                        <span class="focus-border"></span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- TopUp Amount -->
                        <div class="col-lg-12 mt-30">
                            <div class="no-gutters input-right-icon">
                                <div class="col">
                                    <div class="input-effect">
                                        <input class="primary-input form-control" type="number" step="0.01"
                                               name="amount"
                                               value="">
                                        <label>@lang('lang.amount')</label>
                                        <span class="focus-border"></span>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <!-- <div class="col-lg-12 text-center mt-40">
                            <button class="primary-btn fix-gr-bg" id="save_button_sibling" type="button">
                                <span class="ti-check"></span>
                                save information
                            </button>
                        </div> -->
                        <div class="col-lg-12 text-center mt-40">
                            <div class="mt-40 d-flex justify-content-between">
                                <button type="button" class="primary-btn tr-bg" data-dismiss="modal">
                                    @lang('lang.cancel')
                                </button>

                                <button class="primary-btn fix-gr-bg" type="submit">@lang('lang.money_in')</button>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
</div>
<!-- TopUp form modal end-->

<!-- timeline form modal start-->
<div class="modal fade admin-query" id="pay_school_item_modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('lang.add') @lang('lang.timeline')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="container-fluid">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student_timeline_store',
                    'method' => 'POST', 'enctype' => 'multipart/form-data', 'name' => 'document_upload']) }}
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="hidden" name="student_id" value="{{$student_detail->id}}">
                            <div class="row mt-25">
                                <div class="col-lg-12">
                                    <div class="input-effect">
                                        <input class="primary-input form-control{" type="text" name="title" value=""
                                               id="title" maxlength="200">
                                        <label>@lang('lang.title') <span>*</span> </label>
                                        <span class="focus-border"></span>

                                        <span class=" text-danger" role="alert" id="amount_error">

                                                </span>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-30">
                            <div class="no-gutters input-right-icon">
                                <div class="col">
                                    <div class="input-effect">
                                        <input class="primary-input date form-control" readonly id="startDate"
                                               type="text"
                                               name="date">
                                        <label>@lang('lang.date')</label>
                                        <span class="focus-border"></span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button class="" type="button">
                                        <i class="ti-calendar" id="start-date-icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-30">
                            <div class="input-effect">
                                        <textarea class="primary-input form-control" cols="0" rows="3"
                                                  name="description"
                                                  id="Description"></textarea>
                                <label>@lang('lang.description')<span></span> </label>
                                <span class="focus-border textarea"></span>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-40">
                            <div class="row no-gutters input-right-icon">
                                <div class="col">
                                    <div class="input-effect">
                                        <input class="primary-input" type="text" id="placeholderFileFourName"
                                               placeholder="Document"
                                               disabled>
                                        <span class="focus-border"></span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button class="primary-btn-small-input" type="button">
                                        <label class="primary-btn small fix-gr-bg"
                                               for="document_file_4">@lang('lang.browse')</label>
                                        <input type="file" class="d-none" name="document_file_4"
                                               id="document_file_4">
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-30">

                            <input type="checkbox" id="currentAddressCheck" class="common-checkbox"
                                   name="visible_to_student" value="1">
                            <label for="currentAddressCheck">@lang('lang.visible_to_this_person')</label>
                        </div>


                        <!-- <div class="col-lg-12 text-center mt-40">
                            <button class="primary-btn fix-gr-bg" id="save_button_sibling" type="button">
                                <span class="ti-check"></span>
                                save information
                            </button>
                        </div> -->
                        <div class="col-lg-12 text-center mt-40">
                            <div class="mt-40 d-flex justify-content-between">
                                <button type="button" class="primary-btn tr-bg" data-dismiss="modal">
                                    @lang('lang.cancel')
                                </button>

                                <button class="primary-btn fix-gr-bg" type="submit">@lang('lang.save')</button>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
</div>
<!-- timeline form modal end-->

<script>
    function deleteDoc(id, doc) {
        // alert(doc);
        var modal = $('#delete-doc');
        modal.find('input[name=student_id]').val(id)
        modal.find('input[name=doc_id]').val(doc)
        modal.modal('show');
    }
</script>

@endsection
