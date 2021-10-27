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
            font-size: 12px;
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
            font-size: 10px;
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
            font-size: 12px;
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
                        <p class="text_center" style="font-size: large"><strong>
                                @if(isset($staffDetails)){{$staffDetails->full_name}}@endif | @lang('lang.staff')
                                @lang('lang.details') </strong></p>
                    </div>
                </td>
            </tr>
            </thead>
        </table>

        <table class="custom_table" border="0">
            <!-- first header  -->
            <tr style="border: #ffffff" colspan="1" class="text_left">

                <td>@lang('lang.role') &nbsp; : &nbsp; &nbsp;</td>
                <td><strong>
                        @if(isset($staffDetails)){{$staffDetails->roles->name}}@endif</strong></td>
                <td>@lang('lang.designation'):</td>
                <td>
                    <strong> @if(isset($staffDetails)){{
                        !empty($staffDetails->designations)?$staffDetails->designations->title:''}}@endif</strong></td>
            </tr>
            <tr>
                <td>@lang('lang.department'): &nbsp; &nbsp; &nbsp;</td>
                <td>
                    <strong> @if(isset($staffDetails)){{
                        !empty($staffDetails->departments)?$staffDetails->departments->name:''}}@endif</strong>
                </td>
                <td>@lang('lang.epf_no'): &nbsp; &nbsp; &nbsp;</td>
                <td><strong>
                        @if(isset($staffDetails)){{$staffDetails->epf_no}}@endif</strong>
                </td>
            </tr>
            <tr>

                <td>@lang('lang.contract_type'): &nbsp; &nbsp; &nbsp;</td>
                <td><strong>
                        @if(isset($staffDetails)){{$staffDetails->contract_type}}@endif</strong>
                </td>
                <td>@lang('lang.date_of_joining'): &nbsp; &nbsp; &nbsp;</td>
                <td><strong>
                        @if(isset($staffDetails))

                        {{$staffDetails->date_of_joining != ""? dateConvert($staffDetails->date_of_joining):''}}


                        @endif</strong>
                </td>
            </tr>
        </table>

    </div>

    <!-- Start Student Details -->
    <div class="teacher_details">
        <div class="col-lg-12 staff-details">

            <br>
            <div class="white-box">
                <h4 class="stu-sub-head">@lang('lang.personal') @lang('lang.info')</h4>
                <hr>
            <table class="staff_details_table">
           
                
                    <tr>
                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                                @lang('lang.mobile') @lang('lang.no')
                        </td>

                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                <strong> @if(isset($staffDetails)){{$staffDetails->mobile}}@endif</strong>

                        </td>
                    </tr>

                
                    <tr>
                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                @lang('lang.emergency_mobile')
                        </td>

                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                <strong> @if(isset($staffDetails)){{$staffDetails->emergency_mobile}}@endif</strong>
                        </td></tr>
                
                    <tr>
                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                @lang('lang.email')
                        </td>

                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                <strong>@if(isset($staffDetails)){{$staffDetails->email}}@endif</strong>
                        </td></tr>

                
                    <tr>
                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                @lang('lang.gender')
                        </td>
                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            

                                <strong> @if(isset($staffDetails)) {{$staffDetails->genders->base_setup_name}} @endif</strong>

                        </td></tr>

                
                    <tr>
                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                @lang('lang.date_of_birth')
                        </td>

                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                @if(isset($staffDetails))

                                <strong> {{$staffDetails->date_of_birth != ""? dateConvert($staffDetails->date_of_birth):''}}</strong>


                                @endif
                        </td></tr>
                
                    <tr>
                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                @lang('lang.marital_status')
                        </td>

                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                <strong> @if(isset($staffDetails)){{$staffDetails->marital_status}}@endif</strong>
                        </td></tr>

                
                    <tr>
                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                @lang('lang.next_of_kin1')
                        </td>

                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                <strong> @if(isset($staffDetails)){{$staffDetails->next_of_kin2}}@endif</strong>
                        </td></tr>

                
                    <tr>
                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                @lang('lang.next_of_kin2')
                        </td>

                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                <strong> @if(isset($staffDetails)){{$staffDetails->mothers_name}}@endif</strong>
                        </td></tr>

                
                    <tr>
                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                @lang('lang.qualifications')
                        </td>

                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                <strong>  @if(isset($staffDetails)){{$staffDetails->qualification}}@endif</strong>
                        </td></tr>

                
                    <tr>
                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                @lang('lang.work_experience')
                        </td>

                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                <strong> @if(isset($staffDetails)){{$staffDetails->experience}}@endif</strong>
                        </td>
                    </tr>

                <!-- Start Parent Part -->

                <tr><td colspan="2" style="padding: 2px; border-left: #ffffff; border-right: #ffffff"> <h4 class="stu-sub-head mt-40">@lang('lang.address')</h4></td></tr>
                
                    <tr>
                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                @lang('lang.current_address')
                        </td>

                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                <strong> @if(isset($staffDetails)){{$staffDetails->current_address}}@endif</strong>
                        </td></tr>

                
                    <tr>
                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                @lang('lang.permanent_address')
                        </td>

                        <td style="padding: 2px; border-left: #ffffff; border-right: #ffffff">
                            
                                <strong> @if(isset($staffDetails)){{$staffDetails->permanent_address}}@endif</strong>
                        </td></tr>
                </table>

        </div>

        <br>
        <h4 class="stu-sub-head mt-40">@lang('lang.subjects')</h4>
        <hr style="color: #0b0b0b">
        <div class="student_marks_table">
            <table class="custom_table" cellspacing="0" width="100%">

                <thead>
                <tr>
                    <th>@lang('lang.code')</th>
                    <th>@lang('lang.subject')</th>
                    <th>@lang('lang.class')</th>
                    <th>@lang('lang.subject_type')</th>
                    <th>@lang('lang.number_of_student')</th>


                </tr>
                </thead>
                <tbody>

                @foreach($teacherStudentsSubjects as $teacherStudentsSubjectsRecord)
                <tr>
                    <td><strong> {{$teacherStudentsSubjectsRecord['subject']->subject_code}}</strong></td>
                    <td><strong> {{$teacherStudentsSubjectsRecord['subject']->subject_name}}</strong></td>
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
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


</section>
</body>
</html>


