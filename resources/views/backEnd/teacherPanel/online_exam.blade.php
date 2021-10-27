@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.online_exam')</h1>
            <div class="bc-pages">
                <a href="{{route('teacher-dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.examinations')</a>
                <a href="#">@lang('lang.online_exam')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">

            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('lang.online_exam') @lang('lang.list')</h3>
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
                                <th>@lang('lang.title')</th>
                                <th>@lang('lang.class_Sec')</th>
                                <th>@lang('lang.subject')</th>
                                <th>@lang('lang.exam_date')</th>
                                <th>@lang('lang.Status')</th>
                                <th>@lang('lang.action')</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($online_exams as $online_exam)
                            <tr>
                                <td>{{$online_exam->title}}</td>
                                <td>
                                    @php
                                    if($online_exam->class !="" && $online_exam->section !="" ){
                                    echo $online_exam->class->class_name.'  ('.$online_exam->section->section_name.')';
                                    }
                                    @endphp
                                </td>
                                <td>{{$online_exam->subject!=""?$online_exam->subject->subject_name:""}}</td>
                                <td>

                                    {{$online_exam->date != ""? dateConvert($online_exam->date):''}}

                                    <br> @lang('lang.time'): {{date("h:i A", strtotime($online_exam->start_time)).' - '.date("h:i A", strtotime($online_exam->end_time))}}</td>
                                <td>
                                    @php
                                        // TODO fix on the words published
                                    @endphp
                                    @if($online_exam->status == 0)
                                    <button class="primary-btn small bg-warning text-white border-0">@lang('lang.pending')</button>
                                    @elseif($online_exam->end_date_time < $present_date_time && $online_exam->status == 1)
                                    <button class="primary-btn small bg-danger text-white border-0">@lang('lang.passed')</button>
                                    @else
                                    <button class="primary-btn small bg-success text-white border-0">@lang('lang.published')</button>
                                    @endif
                                </td>
                                <td style="width: 30%">
                                    <div class="dropdown d-flex">
                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" @php ($online_exam->start_time < $present_time && $online_exam->status == 0)? "disabled":"" @endphp >
                                            @lang('lang.select')
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">

                                            @php
                                            $is_set_online_exam_questions = DB::table('sm_online_exam_question_assigns')->where('online_exam_id', $online_exam->id)->first();
                                            @endphp

                                            @if($online_exam->start_time < $present_time && $online_exam->status == 1)
                                            @if(userPermission(243))

                                            <a class="dropdown-item" href="{{route("online_exam_marks_register", [$online_exam->id])}}">@lang('lang.marks_register')</a>
                                            @endif
                                            @endif

                                            @if($online_exam->start_time < $present_time && $online_exam->status == 0)
                                            @if(userPermission(243))

                                            @endif
                                            @endif

                                        </div>



                                        @if($online_exam->end_date_time < $present_date_time && $online_exam->status == 1)
                                        @if(userPermission(244))
                                        <a class="ml-3" href="{{route('online_exam_result', [$online_exam->id])}}">
                                            <button class="primary-btn small bg-info text-white border-0">@lang('lang.result')</button>
                                        </a>
                                        @endif
                                        @endif
                                    </div>

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

<div class="modal fade admin-query" id="deleteOnlineExam">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('lang.delete') @lang('lang.online_exam')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="text-center">
                    <h4>@lang('lang.are_you_sure_to_delete')</h4>
                </div>

                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('lang.cancel')</button>
                    {{ Form::open(['route' => 'online-exam-delete', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    <input type="hidden" name="online_exam_id" id="online_exam_id">
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('lang.delete')</button>
                    {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
</div>



@endsection
@push('script')
<script>

    function examDelete(id){
        var modal = $('#deleteOnlineExam');
        modal.find('input[name=online_exam_id]').val(id)
        modal.modal('show');
    }

</script>
@endpush
