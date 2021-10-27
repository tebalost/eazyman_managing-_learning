<?php

?>
@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.subject')</h1>
            <div class="bc-pages">
                <a href="{{route('teacher-dashboard')}}">@lang('lang.dashboard')</a>
                <a href="{{route('student_subject')}}">@lang('lang.subject')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">

        <div class="row">

            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('lang.subject_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">

                        <table id="table_id" class="display school-table" cellspacing="0" width="100%">

                            <thead>
                            <tr>
                                <th>@lang('lang.subject')</th>
                                <th>@lang('lang.class')</th>
                                <th>@lang('lang.subject_type')</th>
                                <th>@lang('lang.number_of_student')</th>
                                <th>@lang('lang.action')</th>

                            </tr>
                            </thead>
                            <tbody>

                            @foreach($teacherStudentsSubjects as $teacherStudentsSubjectsRecord)
                            <tr>
                                <td> {{$teacherStudentsSubjectsRecord['subject']->subject_name}} </td>
                                <td>

                                    {{$teacherStudentsSubjectsRecord['class']->class_name}}
                                    ({{@$teacherStudentsSubjectsRecord['section']->section_name}})
                                </td>
                                <td>
                                    {{@$teacherStudentsSubjectsRecord['subject']->subject_type == "T"? 'Theory': 'Practical'}}
                                </td>
                                <td>
                                    {{@$teacherStudentsSubjectsRecord['count']}}
                                </td>
                                <td>

                                    <a href="find-students/{{@$teacherStudentsSubjectsRecord['subject']->id}}/{{$teacherStudentsSubjectsRecord['section']->id}}/{{$teacherStudentsSubjectsRecord['class']->id}}"
                                       class="primary-btn small fix-gr-bg pull-center" target="_blank"><i class="ti-eye"> </i>
                                        @lang('lang.view')</a>

                                </td>
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
@endsection
