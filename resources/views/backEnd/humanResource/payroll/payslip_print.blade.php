<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('/')}}/public/backEnd/css/report/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="style.css"> -->
    <title>Mark Sheet Report</title>
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
            text-align: center;
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

        table#grade_table th{
            border: 1px solid #726E6D !important;
            padding: .1rem;
            background: #dddddd;
            font-weight: 600;
            color: #000000;
            font-size: 12px;
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
            background: #dddddd;
            color: #000000;
        }

        table#grade_table tr > td {
            border: 1px solid #000000;
            padding: 0;
            font-size: 10px;
            font-weight: 500;
        }


        table.custom_table thead tr th .fees_title {
            font-size: 14px;
            font-weight: 600;
            border-top: 1px solid #726E6D;
            padding-top: 5px;
            margin-top: 5px;
            text-transform: uppercase;

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

        @media print {
            footer {page-break-after: always;}
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

        .motto {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            color: #000;
            text-align: center;
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
                        {{isset(generalSetting()->school_name)?generalSetting()->school_name:'EazyMan School Management
                        ERP'}} </h1>
                    <p style="font-size: medium">{{isset(generalSetting()->address)?generalSetting()->address:'EazyMan
                        School Address'}}
                    <p style="font-size: medium; text-transform: lowercase;  "><strong>{{generalSetting()->phone}} |
                            {{generalSetting()->email}}</strong></p>
                    <hr style="color: #000000">
                    <p class="text_center" style="font-size: large"><strong> PERFORMANCE REPORT FOR </strong>
                        {{$exam_details->title}} ({{@$student_detail->academicYear->year}})</p>
                </div>
            </td>
        </tr>
        </thead>
    </table>
    <table class="custom_table">
        <thead>
            <tr>
                <th >
                  <div class="numbered_table_row" style="justify-content:center" >
                      <h3>
                        @lang('lang.payslip') @lang('lang.for_the_period_of') {{$payrollDetails->payroll_month}} {{$payrollDetails->payroll_year}}
                      </h3>
                  </div>
                </th>
            </tr>
        </thead>
    </table>
    <table class="custom_table custom_table2">
        <thead>
        <tr>
          <td class="border-0 full_name_header">
              <h4 class="muted_text font_14">
                  @lang('lang.payslip') #@if(isset($payrollDetails)){{$payrollDetails->id}} @endif
              </h4>
          </td>
          <td class="border-0 full_name_header"></td>
          <td class="border-0 full_name_header"></td>
          <td class="border-0 full_name_header">
                <h4 class="muted_text font_14">
                @lang('lang.payment') @lang('lang.date'): @if(isset($payrollDetails))
                    {{dateConvert($payrollDetails->payment_date)}}
                @endif
                </h4>
          </td>
        </tr>
            <tr>
                <th>@lang('lang.staff_ID')</th>
                <th> @if(isset($payrollDetails)){{$payrollDetails->staffs->staff_no}} @endif</th>
                <th>@lang('lang.name')</th>
                <th>@if(isset($payrollDetails)){{$payrollDetails->staffDetails->full_name}} @endif</th>
            </tr>
            <tr>
                <th>@lang('lang.departments')</th>
                <th> @if(isset($payrollDetails)){{$payrollDetails->staffDetails->departments->name}} @endif</th>
                <th>@lang('lang.designation')</th>
                <th>@if(isset($payrollDetails)){{$payrollDetails->staffDetails->designations->title}} @endif</th>
            </tr>
            <tr>
                <th>@lang('lang.payment')  @lang('lang.mode')</th>
                <th> @if($payrollDetails->payment_mode != "")
                        {{$payrollDetails->paymentMethods->method}}
                    @else
                        @lang('lang.unpaid')
                    @endif
                </th>
                <th>@lang('lang.basic_salary')</th>
                <th>@if(isset($payrollDetails)){{$payrollDetails->basic_salary}} @endif</th>
            </tr>
            <tr>
                <th>@lang('lang.gross_salary')</th>
                <th> @if(isset($payrollDetails)){{$payrollDetails->gross_salary}} @endif</th>
                <th>@lang('lang.net_salary')</th>
                <th>@if(isset($payrollDetails)){{$payrollDetails->net_salary}} @endif</th>
            </tr>
            @if ($payrollDetails->note)
            <tr>
              <th>@lang('lang.note')</th>
              <th> @if(isset($payrollDetails)){{$payrollDetails->note}} @endif</th>
            </tr>
            @endif
        </thead>
    </table>
</div>
</body>
    <script src="{{ asset('/') }}/public/backEnd/js/fees_invoice/jquery-3.2.1.slim.min.js"></script>
    <script src="{{ asset('/') }}/public/backEnd/js/fees_invoice/popper.min.js"></script>
    <script src="{{ asset('/') }}/public/backEnd/js/fees_invoice/bootstrap.min.js"></script>
</html>
