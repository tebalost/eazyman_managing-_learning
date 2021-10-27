<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('/')}}/public/backEnd/css/report/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="style.css"> -->
    <title>Staff Profile</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-size: 14px;
            font-family: 'Leelawadee', sans-serif;
        }

        .student_marks_table {
            width: 100%;
            margin: 30px auto 0 auto;
        }

        .text_center {
            text-align: center;
        }

        p {
            margin: 0;
            font-size: 14px;
            text-transform: capitalize;
        }

        ul {
            margin: 0;
            padding: 0;
        }

        li {
            list-style: none;
        }

        td {
            border: 1px solid #000000;
            padding: .3rem;
            text-align: left;
        }

        th {
            border: 1px solid #000000;
            text-transform: capitalize;
            text-align: center;
            padding: 1rem;
            white-space: nowrap;
        }

        thead {
            font-weight: bold;
            text-align: center;
            color: #222;
            font-size: 14px
        }

        .custom_table {
            width: 90%;
        }

        .grade_table {
            width: 50%;
            padding: 0;
        }

        table#grade_table th {
            border: 1px solid #726E6D !important;
            padding: .1rem;
            background: #351681;
            font-weight: 600;
            color: #FFFFFF;
            font-size: 14px;
        }

        .staff_details_table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
            border: 0px;
        }

        .staff_details_table th, td {
            text-align: left;
            padding: 0px;
        }

        .staff_details_table tr:nth-child(even) {
            background-color: #f2f2f2;
            border-left: #ffffff;
            padding: 0px;
            border-right: #ffffff;
        }

        table.custom_table thead th {
            padding-right: 0;
            padding-left: 0;
            text-transform: uppercase;
        }

        table.custom_table thead tr > th {
            border: 1px solid #000000;
            padding: 0.3em;
            text-transform: uppercase;
            background: #351681;
            color: #ffffff;
        }

        table#grade_table tr > td {
            border: 1px solid #000000;
            padding: 0;
            font-size: 14px;
            font-weight: 500;
        }

        /* tr:last-child td {
            border: 0 !important;
        }
        tr:nth-last-child(2) td {
            border: 0 !important;
        }
        tr:nth-last-child(3) td {
            border: 0 !important;
        } */

        table.custom_table thead tr th .fees_title {
            font-size: 14px;
            font-weight: 600;
            border-top: 1px solid #726E6D;
            padding-top: 5px;
            margin-top: 5px;
            text-transform: uppercase;

        }

        .teacher_details {
            margin-left: 35px;
            margin-right: 35px;
        }

        .border-top {
            border-top: 0 !important;
        }

        .custom_table th ul li {
        }

        .custom_table th ul li p {
            margin-bottom: 5px;
            font-weight: 500;
            font-size: 14px;
            text-transform: uppercase;
        }

        /* tbody td p{
          text-align: right;
        } */
        tbody td {
            padding: 0.5rem;
            font-size: 14px;
        }

        table {
            border-spacing: 0px;
            width: 90%;
            margin: auto;
            font-size: 14px;
        }

        .fees_pay {
            text-align: center;
        }

        .border-0 {
            border: 0 !important;
        }

        .copy_collect {
            text-align: center;
            font-weight: 500;
            color: #000;
        }

        .copyies_text {
            display: flex;
            justify-content: space-between;
            margin: 30px 0;
        }

        .copyies_text li {
            text-transform: capitalize;
            color: #000;
            font-weight: 500;
            border-top: 1px dashed #ddd;
        }

        .text_left {
            text-align: left;
        }

        .italic_text {
        }

        .student_info {

        }

        .student_info li {
            display: flex;
        }

        .info_details {
            display: flex;
            flex-wrap: wrap;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .info_details li > p {
            flex-basis: 20%;
        }

        .info_details li {
            display: flex;
            flex-basis: 50%;
        }

        .school_name {
            text-align: center;
        }

        .numbered_table_row {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            align-items: center;
        }

        .numbered_table_row thead {
            border: 1px solid #222
        }

        .numbered_table_row h3 {
            font-size: 24px;
            text-transform: uppercase;
            margin-top: 15px;
            font-weight: 500;
            display: inline-block;
            border-bottom: 2px solid #222;
        }

        .numbered_table_row td {
            border: 1px solid #726E6D;
            padding: .4rem;
            font-weight: 400;
            color: #222;
        }

        table.grade_table th td {
            border: 1px solid #726E6D !important;
            padding: 0;
            width: 90%;
            margin: auto;
            font-weight: 600;
            color: #222;
        }

        td.border-top.border_left_hide {
            border-left: 0;
            text-align: left;
            font-weight: 600;
        }

        .devide_td {
            padding: 0;
        }

        .devide_td p {
            border-bottom: 1px solid #222;
        }

        .ssc_text {
            font-size: 20px;
            font-weight: 500;
            color: #222;
            margin-bottom: 20px;
        }
    </style>
    @php
    $generalSetting= App\SmGeneralSettings::find(1);
    if(!empty($generalSetting)){
    $school_name =$generalSetting->school_name;
    $site_title =$generalSetting->site_title;
    $school_code =$generalSetting->school_code;
    $address =$generalSetting->address;
    $phone =$generalSetting->phone;
    $email =$generalSetting->email;
    }

    @endphp
</head>
<script>
    var is_chrome = function () {
        return Boolean(window.chrome);
    }
    if (is_chrome) {
        window.print();
        //    setTimeout(function(){window.close();}, 10000);
        //give them 10 seconds to print, then close
    } else {
        window.print();
        //    window.close();
    }
</script>
<body onLoad="loadHandler();">

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

<section class="mb-40 student-details">
    @if(session()->has('message-success'))
    <div class="alert alert-success">
        {{ session()->get('message-success') }}
    </div>
    @elseif(session()->has('message-danger'))
    <div class="alert alert-danger">
        {{ session()->get('message-danger') }}
    </div>
    @endif

    <div class="student_marks_table">
        <table class="custom_table">
            <thead>
            <tr>
                <td width="20%" border="0" style="border: #ffffff ">
                    <div class="student-meta-img img-100">
                        <img style="max-width: 200px; max-height: 230px; height: auto; border-radius: 6px;"
                             src=" {{asset('/')}}{{generalSetting()->logo }}" alt="">
                    </div>
                </td>
                <td border="0" style="border: #ffffff">
                    <div class="school_name">
                        <h1 style="font-size: xxx-large">
                            {{isset(generalSetting()->school_name)?generalSetting()->school_name:'EazyMan School
                            Management
                            ERP'}} </h1>
                        <p style="font-size: medium">
                            {{isset(generalSetting()->address)?generalSetting()->address:'EazyMan
                            School Address'}}
                        <p style="font-size: medium; text-transform: lowercase;  "><strong>{{generalSetting()->phone}} |
                                {{generalSetting()->email}}</strong></p>
                        <hr style="color: #000000">

                    </div>
                </td>
            </tr>
            </thead>
        </table>

        @if(isset($students))
        <section class="student-details">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 style="text-align: center" class="mb-30 mt-30">@lang('lang.class_report_for_class') <strong>{{@$search_class->class_name}}</strong> {{$section != ""? ' ('. $section->section_name.')': ''}}</h3>
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">

                        <div class="white-box">
                               <div class="student-meta-box mb-40">
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left text-uppercase">
                                               <strong> @lang('lang.class') @lang('lang.information')</strong>
                                            </div>
                                            <hr>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left text-uppercase">
                                               <strong>@lang('lang.quantity')</strong>
                                            </div>
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name text-left">
                                                @lang('lang.number_of_student')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name text-left">
                                                {{$students->count()}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name text-left">
                                                @lang('lang.total_subjects_assigned')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name text-left">
                                                {{count($assign_subjects)}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
<br>
                            <div class="student-meta-box mb-40">
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left text-uppercase">
                                               <strong>@lang('lang.subjects')</strong>
                                            </div>
                                            <hr>
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left text-uppercase">
                                                <strong>@lang('lang.teacher')</strong>
                                            </div>
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                                @foreach($assign_subjects as $assign_subject)
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name text-left">
                                                {{$assign_subject->subject !=""?$assign_subject->subject->subject_name:""}}
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name text-left">
                                                @if($assign_subject->teacher_id != "")
                                                {{$assign_subject->teacher->full_name}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                            </div>
<br>
                            @if($assign_class_teachers != "")

                            <div class="student-meta-box mb-40">
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left text-uppercase">
                                               <strong>@lang('lang.class_teacher')</strong>
                                            </div>
                                            <hr>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="value text-left text-uppercase">
                                                <strong>@lang('lang.information')</strong>
                                            </div>
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name text-left">
                                                @lang('lang.name')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name text-left">
                                                {{$assign_class_teachers->teacher !=""?$assign_class_teachers->teacher->full_name:""}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name text-left">
                                                @lang('lang.mobile')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name text-left">
                                                {{$assign_class_teachers !=""?$assign_class_teachers->teacher->mobile:""}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name text-left">
                                                @lang('lang.email')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name text-left">
                                                {{$assign_class_teachers->teacher !=""?$assign_class_teachers->teacher->email:""}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name text-left">
                                                @lang('lang.address')
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="name text-left">
                                                {{$assign_class_teachers->teacher !=""?$assign_class_teachers->teacher->current_address:""}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @endif
<br>
                            <div class="student-meta-box">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="single-meta">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4">
                                                    <div class="value text-left text-uppercase">
                                                        <strong>@lang('lang.type')</strong>
                                                    </div>
                                                    <hr>
                                                </div>
                                                <div class="col-lg-4 col-md-4">
                                                    <div class="value text-left text-uppercase">
                                                        <strong>@lang('lang.collection')({{generalSetting()->currency_symbol}})</strong>
                                                    </div>
                                                    <hr>
                                                </div>
                                                <div class="col-lg-4 col-md-4">
                                                    <div class="value text-left text-uppercase">
                                                        <strong>@lang('lang.due')({{generalSetting()->currency_symbol}})</strong>
                                                    </div>
                                                    <hr>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="single-meta">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4">
                                                    <div class="name text-left">
                                                        @lang('lang.fees_collection')
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4">
                                                    <div class="name text-left">
                                                        {{number_format($total_collection, 2)}}<input type="hidden" id="total_collection" name="total_collection" value="{{$total_collection}}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-4">
                                                    <div class="name text-left">
                                                        {{number_format($total_assign - $total_collection-$applied_discount, 2)}}<input type="hidden" id="total_assign" name="total_assign"
                                                                                                                                        value="{{@$total_assign-$applied_discount}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-lg-4">
                                        <div class="single-meta">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="value text-left text-uppercase bb-15 pb-7">
                                                        <strong>@lang('lang.fees_details')</strong>
                                                    </div>
                                                    <hr>

                                                    <!-- <div id="commonBarChart" height="150px"></div> -->
                                                    <div id="donutChart" height="200px"></div>
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

    </div>

    <!-- Start Student Details -->

</section>
</body>
</html>


