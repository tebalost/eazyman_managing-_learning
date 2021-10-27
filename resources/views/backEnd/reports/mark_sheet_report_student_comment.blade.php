@extends('backEnd.master')
@section('mainContent')
<style>
    th {
        border: 1px solid black;
        text-align: center;
    }

    td {
        text-align: center;
    }

    td.subject-name {
        text-align: left;
        padding-left: 10px !important;
    }

    table.marksheet {
        width: 100%;
        border: 1px solid #828bb2 !important;
    }

    table.marksheet th {
        border: 1px solid #828bb2 !important;
    }

    table.marksheet td {
        border: 1px solid #828bb2 !important;
    }

    table.marksheet thead tr {
        border: 1px solid #828bb2 !important;
    }

    table.marksheet tbody tr {
        border: 1px solid #828bb2 !important;
    }

    .studentInfoTable {
        width: 100%;
        padding: 0px !important;
    }

    .studentInfoTable td {
        padding: 0px !important;
        text-align: left;
        padding-left: 15px !important;
    }

    h4 {
        text-align: left !important;
    }

    hr {
        margin: 0px;
    }

    #grade_table th {
        border: 1px solid black;
        text-align: center;
        background: #351681;
        color: white;
    }

    #grade_table td {
        color: black;
        text-align: center !important;
        border: 1px solid black;
    }

    .single-report-admit table tr td {
        border-bottom: 1px solid #c1c6d9;
        padding: 8px 8px;
    }
</style>
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.mark_sheet_report') @lang('lang.student') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.reports')</a>
                <a href="#">@lang('lang.mark_sheet_report') @lang('lang.student')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-8 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('lang.select_criteria')</h3>
                </div>
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

                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'mark_sheet_report_student',
                'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                <div class="row">
                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">

                    <div class="col-lg-4 mt-30-md">
                        <select class="w-100 bb niceSelect form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}"
                                name="exam">
                            <option data-display="@lang('lang.select_exam') *" value="">@lang('lang.select_exam') *
                            </option>
                            @foreach($exams as $exam)
                            <option value="{{$exam->id}}" {{isset($exam_type_id)? ($exam_type_id== $exam->id?
                                'selected':''):''}}>{{$exam->title}}
                            </option>

                            @endforeach
                        </select>
                        @if ($errors->has('exam'))
                        <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('exam') }}</strong>
                                </span>
                        @endif
                    </div>
                    <div class="col-lg-4 mt-30-md">
                        <select class="w-100 bb niceSelect form-control {{ $errors->has('class') ? ' is-invalid' : '' }}"
                                @if(!isset($section)) id="select_class" @endif
                                name="class">
                            <option data-display="@lang('lang.select_class') *" value="">@lang('lang.select_class')
                                *
                            </option>

                            <option value="{{$class->id}}" {{isset($class_id)? ($class_id== $class->id?
                                'selected':''):''}}>{{$class->class_name}}
                            </option>


                        </select>
                        @if ($errors->has('class'))
                        <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('class') }}</strong>
                                </span>
                        @endif
                    </div>
                    <div class="col-lg-4 mt-30-md" id="select_section_div">

                        @if(isset($section))

                        <select class="w-100 bb niceSelect form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section"
                                name="section">
                            <option data-display="@lang('lang.select_section') *" value="">@lang('lang.select_section')
                                *
                            </option>
                            <option value="{{@$section->id}}" {{isset($section_id)? ($section_id== $section->id?
                                'selected':''):''}}>{{@$section->section_name}}
                            </option>
                        </select>
                        @else
                        <select class="w-100 bb niceSelect form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section"
                                id="select_section" name="section">
                            <option data-display="@lang('lang.select_section') *"
                                    value="">@lang('lang.select_section') *
                            </option>
                            <option value="{{$section->id}}">{{$section->section_name}}</option>
                        </select>
                        @endif
                        @if ($errors->has('section'))
                        <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('section') }}</strong>
                                </span>
                        @endif
                    </div>


                    <div class="col-lg-12 mt-20 text-right">
                        <button type="submit" class="primary-btn small fix-gr-bg">
                            <span class="ti-search"></span>
                            @lang('lang.search')
                        </button>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</section>


@if(isset($mark_sheet))
@php
if(!empty($generalSetting)){
$school_name =$generalSetting->school_name;
$site_title =$generalSetting->site_title;
$school_code =$generalSetting->school_code;
$address =$generalSetting->address;
$phone =$generalSetting->phone;
$email =$generalSetting->email;
}

@endphp

<style>
    * {
        margin: 0;
        padding: 0;
    }

    body {
        font-size: 12px;
        font-family: 'Poppins', sans-serif;
    }

    .student_marks_table {
        width: 100%;
        margin: auto;
        padding-left: 10px;
        padding-right: 5px;
    }

    .text_center {
        text-align: center;
    }

    p {
        margin: 0;
        font-size: 12px;
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
        border: 1px solid #726E6D;
        padding: .3rem;
        text-align: center;
    }

    th {
        border: 1px solid #726E6D;
        text-transform: capitalize;
        text-align: center;
        padding: .5rem;
        white-space: nowrap;
    }

    thead {
        font-weight: bold;
        text-align: center;
        color: #415094;
        font-size: 10px
    }

    .custom_table {
        width: 100%;
    }

    table.custom_table thead th {
        padding-right: 0;
        padding-left: 0;
    }

    table.custom_table thead tr > th {
        border: 0;
        padding: 0;
    }

    table.custom_table thead tr th .fees_title {
        font-size: 12px;
        font-weight: 600;
        border-top: 1px solid #726E6D;
        padding-top: 1px;
        margin-top: 1px;
    }

    .border-top {
        border-top: 0 !important;
    }

    .custom_table th ul li {
    }

    .custom_table th ul li p {
        margin-bottom: 10px;
        font-weight: 500;
        font-size: 14px;
    }

    tbody td {
        padding: 0.8rem;
    }

    table {
        border-spacing: 10px;
        width: 65%;
        margin: auto;
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
        margin-top: 3px;
        margin-bottom: 3px;
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
        margin-top: 4px;
        align-items: center;
    }

    .numbered_table_row thead {
        border: 1px solid #415094
    }

    .numbered_table_row h3 {
        font-size: 24px;
        text-transform: uppercase;
        margin-top: 1px;
        font-weight: 500;
        display: inline-block;
        border-bottom: 2px solid #415094;
    }

    .ingle-report-admit .numbered_table_row td {
        border: 1px solid #726E6D;
        padding: .4rem;
        font-weight: 400;
        color: #415094;
    }

    .table#grade_table_new th {
        border: 1px solid #726E6D !important;
        padding: .6rem !important;
        font-weight: 600;
        color: #415094;
        font-size: 10px;
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
        border-bottom: 1px solid #415094;
    }

    .ssc_text {
        font-size: 20px;
        font-weight: 500;
        color: #415094;
        margin-bottom: 20px;
    }

    table#grade_table_new td {
        padding: 0 !important;
        font-size: 8px;
    }

    table#grade_table_new {
        border-bottom: 1px solid #726E6D !important;
    }

    .student_info {
        flex: 70% 0 0;
    }

    .marks_wrap_area {
        display: flex;
        align-items: center;
    }

    .numbered_table_row {
        display: flex;
        justify-content: center;
        margin-top: 4px;
        align-items: center;
    }

    tbody.mark_sheet_body tr:last-child {
        border-bottom: 1px solid #c1c6d9;
    }

    tbody.mark_sheet_body td {
        padding: 8px 4px;
    }
</style>
<section class="student-details">
    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'mark_sheet_report_student_comment_store',
    'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
    <div class="container-fluid p-0">

        <div class="row mt-40">
            <div class="col-lg-4 no-gutters">
                <div class="main-title">
                    <h3 class="mb-30">@lang('lang.mark_sheet_report')</h3>
                </div>
            </div>
        </div>

        @foreach($students as $student)
        @php
        $mark_sheet = DB::table('sm_result_stores')
        ->where([['class_id', $class_id], ['exam_type_id', $exam_type_id], ['section_id', $section_id], ['student_id', $student->id]])
        ->where('sm_result_stores.school_id', Auth::user()->school_id)
        ->where('sm_result_stores.academic_id', $student->academic_id)
        ->join('sm_subjects','sm_subjects.id','=','sm_result_stores.subject_id')
        ->get();

        $comments = DB::table('sm_temporary_meritlists')->where([['class_id', $class_id], ['section_id', $section_id], ['exam_id', $exam_type_id], ['student_id', $student->id]])
        ->where('academic_id',getAcademicId())->first();

        $grades = DB::table('sm_marks_grades')->where('active_status', 1)->get();
        $total_marks = DB::table('sm_result_stores')->where([
        ['exam_type_id', $exam_type_id],
        ['class_id', $class_id],
        ['section_id', $section_id],
        ['student_id', $student->id]
        ])->where('academic_id', getAcademicId())->sum('total_marks');

        $results = DB::table('sm_result_stores')->where([
        ['exam_type_id', $exam_type_id],
        ['class_id', $class_id],
        ['section_id', $section_id],
        ['student_id', $student->id]
        ])->where('academic_id', getAcademicId())->where('total_marks', '>', 0)->where('school_id', Auth::user()->school_id)->get();

        $sum_of_mark = ($total_marks == 0) ? 0 : $total_marks;
        $average_mark = ($total_marks == 0) ? 0 : round($total_marks / $results->count(), 1); //get average number

        $is_result_available = DB::table('sm_result_stores')->where([['class_id', $class_id], ['exam_type_id', $exam_type_id], ['section_id', $section_id], ['student_id', $student->id]])->where('created_at', 'LIKE', '%' . App\YearCheck::getYear() . '%')->where('total_marks', '>', 0)->where('school_id', Auth::user()->school_id)->get();

        @endphp
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="single-report-admit">
                                {{--Start Result Table --}}
                                <div class="student_marks_table">
                                    <table class="custom_table">
                                        <thead>

                                        <tr class="numbered_table_row">
                                            <td class="border-0">

                                            </td>
                                            <td class="border-0">
                                                <div class="school_mark">
                                                    <p class="ssc_text"> {{$exam_details->title}} -
                                                        {{$class->class_name}}({{$section->section_name}})</p>
                                                    {{--
                                                    <div>
                                                        <img src="{{ $generalSetting->logo }}" alt="">
                                                    </div>
                                                    --}}
                                                    <h3>Performance Report</h3>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <!-- first header  -->
                                            <th colspan="1" class="text_left">
                                                <div>
                                                    <ul class="info_details">
                                                        <li><p>Student Name: &nbsp; &nbsp; </p> &nbsp; <p><strong>{{$student->full_name}}</strong>
                                                            </p></li>
                                                        <li><p>Student No: &nbsp; &nbsp; </p> &nbsp; <p><strong>{{$student->admission_id_number}}</strong>
                                                            </p></li>
                                                        <li><p>Admission No: &nbsp; &nbsp; </p> &nbsp; <p><strong>
                                                                    {{$student->admission_no}}</strong></p>
                                                        </li>
                                                        <li><p>Total Marks: &nbsp; &nbsp; </p> &nbsp; <p><strong>
                                                                    {{$sum_of_mark}}</strong></p>
                                                        </li>
                                                        <li><p>Average Marks: &nbsp; &nbsp; </p> &nbsp;
                                                            @if($average_mark>=50)
                                                            <p><strong>
                                                                    {{$average_mark}}
                                                                </strong>
                                                            </p>
                                                            @else
                                                            <p style="color: #ff0000"><strong>
                                                                    {{$average_mark}}
                                                                </strong>
                                                            </p>
                                                            @endif
                                                        </li>
                                                    </ul>
                                                </div>
                                            </th>
                                        </tr>
                                        </thead>
                                    </table>

                                    <table class="custom_table">
                                        <thead>

                                        </thead>
                                        <tbody class="mark_sheet_body">
                                        <tr>
                                            <!-- first header  -->
                                            <th>SI.NO</th>
                                            <th colspan="2">Name of subjects</th>
                                            <th>letter grade</th>
                                            <th>Total Marks</th>
                                            <th>Grade Point</th>
                                            <th>Evaluation</th>

                                        </tr>
                                        @php $count = $main_subject_total_gpa  = 0; @endphp
                                        @foreach($mark_sheet as $data)
                                        @if($data->total_marks>0)
                                        <tr>
                                            <td class="border-top"
                                                style="border-bottom: 1px solid black;">{{ $count }}
                                            </td>
                                            <td colspan="2" class="border-top"
                                                style="text-align: left;padding-left: 15px;border-bottom: 1px solid black;">
                                                <p>{{$data->subject_name}}</p></td>
                                            <td class="border-top"
                                                style="border-bottom: 1px solid black;">
                                                <p>
                                                    @php
                                                    $result = markGpa($data->total_marks);
                                                    @endphp
                                                    {{@$result->grade_name}}
                                                </p>
                                                {{-- {{@$subject_result[2][0]}} --}}
                                            </td>
                                            <td class="border-top"
                                                style="border-bottom: 1px solid black;">
                                                @if($data->total_marks>=50)
                                                <p>
                                                    {{@$data->total_marks}}
                                                </p>
                                                @else
                                                <p style="color: #FF0000">
                                                    {{@$data->total_marks}}
                                                </p>
                                                @endif
                                                {{-- {{@$subject_result[2][0]}} --}}
                                            </td>
                                            @php
                                            $main_subject_total_gpa += $result->gpa;
                                            @endphp
                                            <td class="border-top"
                                                style="border-bottom: 1px solid black;"><p>
                                                    {{@$result->gpa}}

                                                </p>
                                            </td>

                                            </p>
                                            </td>


                                            <td class="border-top"
                                                style="border-bottom: 1px solid black;">
                                                <p>
                                                    @php
                                                    $result = markGpa($data->total_marks);
                                                    @endphp
                                                    {{@$result->description}}
                                                </p>
                                            </td>


                                            @php
                                            $count++
                                            @endphp

                                        </tr>
                                        @endif
                                        @endforeach

                                        </tbody>
                                    </table>
                                    <br>
                                    @if(@Auth::user()->role_id==4)
                                        @if(isset($comments))
                                            @php $remark = $comments->class_teacher_remark; @endphp
                                        @endif
                                    @endif
                                    @if(@Auth::user()->role_id==1 || @Auth::user()->role_id==5)
                                        @if(isset($comments))
                                            @php $remark = $comments->principal_remark; @endphp
                                        @endif
                                    @endif
                                    <div class="input-effect">
                                        <textarea class="primary-input form-control" cols="0" rows="2"
                                                  name="comment[{{$student->id}}]" id="">{{@$remark}}</textarea>
                                        <label>@lang('lang.add_comment_here')</label>
                                        <span class="focus-border textarea"></span>
                                        <span class="invalid-feedback">
                                                            <strong>@lang('lang.error')</strong>
                                                        </span>
                                    </div>
                                    <script>
                                        function myFunction(value, subject) {
                                            if (value != "") {

                                                var res = Number(value / subject).toFixed(2);
                                            } else {
                                                var res = 0;
                                            }
                                            document.getElementById("main_subject_total_gpa").innerHTML = res;
                                        }


                                        myFunction({
                                        {
                                            $main_subject_total_gpa
                                        }
                                        },
                                        {
                                            {
                                                $Optional_subject_count
                                            }
                                        }
                                        )
                                        ;


                                    </script>


                                    <table style="width:100%" class="border-0">
                                        <tbody>
                                        <tr>
                                            <td class="border-0"><p class="result-date"
                                                                    style="text-align:left; float:left; display:inline-block; margin-top:50px; padding-left: 0;">

                                                    @lang('lang.date_of_publication_of_result') : <b>
                                                        {{@date_format(date_create($mark_sheet->first()->created_at),"F
                                                        j, Y, g:i a")}}</b></b>
                                                </p></td>
                                            <td class="border-0">
                                                <p style="text-align:right; float:right; border-top:1px solid #ddd; display:inline-block; margin-top:50px;">
                                                    ( {{@Auth::user()->full_name}} )</p>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                                {{--End Result Table --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        <input type="hidden" name="exam_id" value="{{@$exam_type_id}}">
        <input type="hidden" name="class_id" value="{{@$class_id}}">
        <input type="hidden" name="section_id" value="{{@$section_id}}">
        @if(@Auth::user()->role_id==4)
        <input type="hidden" name="comment_type" value="class_teacher">
        @endif
        @if(@Auth::user()->role_id==1 || @Auth::user()->role_id==5)
        <input type="hidden" name="comment_type" value="principal">
        @endif
    </div>
    <div class="row mt-40">
        <div class="col-lg-12 text-center">
            <button class="primary-btn fix-gr-bg">
                <span class="ti-check"></span>
                @lang('lang.save') @lang('lang.comments')
            </button>
        </div>
    </div>
    {{ Form::close() }}
</section>

@endif


@endsection
