@extends('backEnd.master')
@section('mainContent')



@php  @$setting = App\SmGeneralSettings::find(1);  if(!empty(@$setting->currency_symbol)){ @$currency = @$setting->currency_symbol; }else{ @$currency = '$'; }   @endphp

<section class="student-details">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <!-- Start Student Meta Information -->
                <div class="main-title">
                    <h3 class="mb-20">@lang('lang.welcome') - {{@Auth::user()->school->school_name}} | <strong> {{@$teacher_detail->full_name}}</strong> </h3>
                </div>

            </div>
        </div>
        <div class="row">
            @if(userPermission(2))
            <div class="col-lg-3 col-md-6">
                <a href="{{url('teacher-subjects')}}" class="d-block">
                    <div class="white-box single-summery">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3>@lang('lang.subject')</h3>
                                <p class="mb-0">@lang('lang.total') @lang('lang.subject')</p>
                            </div>
                            <h1 class="gradient-color2">

                                @if(isset($totalSubjects))
                                {{count(@$totalSubjects)}}
                                @endif
                            </h1>
                        </div>
                    </div>
                </a>
            </div>
            @endif
            @if(userPermission(3))
            <div class="col-lg-3 col-md-6">
                <a href="{{url('notice-list')}}" class="d-block">
                    <div class="white-box single-summery">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3>@lang('lang.notice')</h3>
                                <p class="mb-0">@lang('lang.total') @lang('lang.notice')</p>
                            </div>
                            <h1 class="gradient-color2">
                                @if(isset($totalNotices))
                                {{count(@$totalNotices)}}
                                @endif
                            </h1>
                        </div>
                    </div>
                </a>
            </div>
            @endif
            @if(userPermission(4))
            <div class="col-lg-3 col-md-6">
                <a href="{{route('exams-schedule')}}" class="d-block">
                    <div class="white-box single-summery">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3>@lang('lang.exam')</h3>
                                <p class="mb-0">@lang('lang.total') @lang('lang.exam')</p>
                            </div>
                            <h1 class="gradient-color2">
                                @if(isset($exams))
                                {{@$exams}}
                                @endif
                            </h1>
                        </div>
                    </div>
                </a>
            </div>
            @endif
            @if(userPermission(5))
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('teacher-online-exams') }}" class="d-block">
                    <div class="white-box single-summery">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3>@lang('lang.online_exam')</h3>
                                <p class="mb-0">@lang('lang.total') @lang('lang.online_exam')</p>
                            </div>
                            <h1 class="gradient-color2">
                                @if(isset($online_exams))
                                {{@$online_exams}}
                                @endif
                            </h1>
                        </div>
                    </div>
                </a>
            </div>
            @endif
            @if(userPermission(6))

            <div class="col-lg-3 col-md-6">
                <a href="#" class="d-block">
                    <div class="white-box single-summery">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3>@lang('lang.student')</h3>
                                <p class="mb-0">@lang('lang.total') @lang('lang.students')</p>
                            </div>
                            <h1 class="gradient-color2"> @if(isset($teachers))
                                {{count(@$teachers)}}
                                @endif</h1>
                        </div>
                    </div>
                </a>
            </div>
            @endif
            @if(userPermission(7))
            <div class="col-lg-3 col-md-6">
                <a href="#" class="d-block">
                    <div class="white-box single-summery">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3>@lang('lang.issued') @lang('lang.book')</h3>
                                <p class="mb-0">@lang('lang.total') @lang('lang.issued') @lang('lang.book')</p>
                            </div>
                            <h1 class="gradient-color2">
                                @if(isset($issueBooks))
                                {{count(@$issueBooks)}}
                                @endif
                            </h1>
                        </div>
                    </div>
                </a>
            </div>
            @endif
            @if(userPermission(8))
            <div class="col-lg-3 col-md-6">
                <a href="homework-list" class="d-block">
                    <div class="white-box single-summery">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3>@lang('lang.pending') @lang('lang.home_work')</h3>
                                <p class="mb-0">@lang('lang.total') @lang('lang.pending') @lang('lang.home_work')</p>
                            </div>
                            <h1 class="gradient-color2">
                                @if(isset($homeworkLists))
                                {{$homeworkLists}}
                                @endif
                            </h1>
                        </div>
                    </div>
                </a>
            </div>
            @endif
            @if(userPermission(9))
            <div class="col-lg-3 col-md-6">
                <a href="#" class="d-block">
                    <div class="white-box single-summery">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3>@lang('lang.attendance') @lang('lang.in')  @lang('lang.current_month')</h3>
                                <p class="mb-0">@lang('lang.total') @lang('lang.attendance') @lang('lang.in')  @lang('lang.current_month')</p>
                            </div>
                            <h1 class="gradient-color2">
                                @if(isset($attendances))
                                {{count(@$attendances)}}
                                @endif
                            </h1>
                        </div>
                    </div>
                </a>
            </div>
            @endif


        </div>
        @if(userPermission(10))
        <section class="mt-50">
            <div class="container-fluid p-0">
                <div class="row">

                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="main-title">
                                    <h3 class="mb-30">@lang('lang.calendar')</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="white-box">
                                    <div class='common-calendar'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        @endif
    </div>
    </div>
</section>

<div id="fullCalModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                <h4 id="modalTitle" class="modal-title"></h4>
            </div>
            <div class="modal-body text-center">
                <img src="" alt="There are no image" id="image" height="150" width="auto">
                <div id="modalBody"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="primary-btn tr-bg" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<?php

$count_event =0;
@$calendar_events = array();

foreach($holidays as $k => $holiday) {

    @$calendar_events[$k]['title'] = $holiday->holiday_title;

    $calendar_events[$k]['start'] = $holiday->from_date;

    $calendar_events[$k]['end'] = $holiday->to_date;

    $calendar_events[$k]['description'] = $holiday->details;

    $calendar_events[$k]['url'] = $holiday->upload_image_file;

    //
    $count_event++;
}





?>
@endsection
@section('script')

<script type="text/javascript">
    /*-------------------------------------------------------------------------------
       Full Calendar Js 
    -------------------------------------------------------------------------------*/
    if ($('.common-calendar').length) {
        $('.common-calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            eventClick:  function(event, jsEvent, view) {
                $('#modalTitle').html(event.title);
                $('#modalBody').html(event.description);
                $('#image').attr('src',event.url);
                $('#fullCalModal').modal();
                return false;
            },
            height: 650,
            events: <?php echo json_encode($calendar_events);?> ,
        });
    }


</script>

@endsection
