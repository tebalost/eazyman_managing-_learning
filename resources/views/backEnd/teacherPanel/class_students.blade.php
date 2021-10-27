
@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.manage') @lang('lang.student')</h1>
            <div class="bc-pages">
                <a href="{{route('teacher-dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.student') @lang('lang.information')</a>
                <a href="#">@lang('lang.student_list')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">

        @if (@$students)
        <div class="row mt-40 full_wide_table">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('lang.student_list') ({{@$students ? @$students->count() : 0}})</h3>
                        </div>
                    </div>
                </div>
                <div class="row  ">
                    <div class="col-lg-12">
                        <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                            <thead>
                            @if(session()->has('message-success') != "" ||
                            session()->get('message-danger') != "")
                            <tr>
                                <td colspan="10">
                                    @if(session()->has('message-success'))
                                    <div class="alert alert-success">
                                        {{ session()->get('message-success') }}
                                    </div>
                                    @elseif(session()->has('message-danger'))
                                    <div class="alert alert-danger">
                                        {{ session()->get('message-danger') }}
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <th>@lang('lang.admission')@lang('lang.no')</th>
                                <th>@lang('lang.roll') @lang('lang.no')</th>
                                <th>@lang('lang.name')</th>
                                <th>@lang('lang.class')</th>
                                <th>@lang('lang.section')</th>
                                <th>@lang('lang.father_name')</th>
                                <th>@lang('lang.date_of_birth')</th>
                                <th>@lang('lang.gender')</th>
                                <th>@lang('lang.type')</th>
                                <th>@lang('lang.phone')</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach(@$students as $student)
                            <tr>
                                <td>{{$student->admission_no}}</td>
                                <td>{{$student->roll_no}}</td>
                                <td>{{$student->first_name.' '.$student->last_name}}</td>
                                <td>{{!empty($student->className)?$student->className->class_name:''}}</td>
                                <td>{{!empty($student->section)?$student->section->section_name:''}}</td>

                                <td>{{!empty($student->parents->fathers_name)?$student->parents->fathers_name:''}}</td>
                                <td  data-sort="{{strtotime($student->date_of_birth)}}" >

                                    {{$student->date_of_birth != ""? dateConvert($student->date_of_birth):''}}

                                </td>
                                <td>{{$student->gender != ""? $student->gender->base_setup_name :''}}</td>
                                <td>{{!empty($student->category)? $student->category->category_name:''}}</td>
                                <td>{{$student->mobile}}</td>

                            </tr>

                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

{{-- disable student  --}}
<div class="modal fade admin-query" id="deleteStudentModal" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('lang.disable') @lang('lang.student')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <div class="text-center">
                    <h4>@lang('lang.are_you_sure_to_disable')</h4>
                </div>

                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('lang.cancel')</button>
                    {{ Form::open(['route' => 'student-delete', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    <input type="hidden" name="id" value="{{@$student->id}}" id="student_delete_i">  {{-- using js in main.js --}}
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('lang.disable')</button>
                    {{ Form::close() }}
                </div>

            </div>

        </div>
    </div>
</div>
{{-- disable student  --}}

@endsection
