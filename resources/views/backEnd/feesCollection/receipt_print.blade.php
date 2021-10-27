<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>@lang('lang.student') @lang('lang.fees')</title>
    <style>

        body {
            font-size: 12px;
            font-family: 'Tahoma', Helvetica, sans-serif;
            /*font-weight: bold;*/
            text-align: center;
            margin-top: 0;
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
        }

        thead {
            /*font-weight: bold;*/
            text-align: center;
            color: #000;
            font-size: 10px
        }

        .custom_table {
            width: 302px; /*80mm Printer*/
            /*width: 219px; for 58mm Printers*/
        }

        table.custom_table thead td {
            padding-right: 0;
            padding-left: 0;
        }

        table.custom_table thead tr > td {
            border: 0;
            padding: 0;
        }


        table.custom_table thead tr td .fees_title {
            font-size: 12px;
            border-top: 1px solid #726E6D;
        }

        .border-top {
            border-top: 0 !important;
        }

        .custom_table td ul li {
            display: flex;
            justify-content: space-between;
        }

        .custom_table td ul li p {
            margin-bottom: 0;
            font-size: 12px;
        }

        tbody td p {
            text-align: right;
        }

        tbody td {
            padding: 0.3rem;
        }

        table {
            border-spacing: 10px;
            width: 50%;
            margin: auto;
        }


        .border-0 {
            border: 0 !important;
        }


        .copyies_text li {
            text-transform: capitalize;
            color: #000;
            border-top: 1px dashed #ddd;
        }

        .school_name {
            font-size: 18px;
            font-weight: bold;
        }

        hr {
            border-top: 1px dashed black;
        }

    </style>

</head>
<script>
    var is_chrome = function () {
        return Boolean(window.chrome);
    }
    if (is_chrome) {
        //  window.print();
        //  setTimeout(function(){window.close();}, 10000);
        //give them 10 seconds to print, then close
    } else {
        window.print();
    }
</script>
<body onLoad="loadHandler();">

<table class="custom_table">
    <thead>
    <tr>
        <!-- first header  -->
        <td colspan="2">
           <!-- <div style="float:left; width:30%; text-align: left">
                <img src="{{url($school->logo)}}" style="width:100px; height:auto"   alt="">
            </div>-->
            <h2 class="school_name">{{$school->school_name}}</h2>
            <p><b>{{$school->address}}</b></p>
            <p><b>{{$school->phone}}</b></p>
            <hr>
        </td>
    </tr>

    <tr>
        <td style="text-align: left"><p>Student Name:</p></td>
        <td style="text-align: right"><p><b>{{@$student->full_name}} </b></p></td>
    </tr>
    <tr>
        <td style="text-align: left"><p>Student Number:</p></td><td style="text-align: right"><p> <b>{{@$student->admission_id_number}}</b></p></td>
    </tr>
    <tr>
        <td style="text-align: left"><p>Date:</p></td><td style="text-align: right"><p> <b>{{date('d/m/Y')}}</b></p></td>
    </tr>
    <tr>
        <td style="text-align: left"><p>Class: <b>{{@$student->class->class_name}} ({{@$student->section->section_name}})</b></p></td>
        <td style="text-align: right"><p>Admission No: {{@$student->admission_no}}</p></td>
    </tr>

    </td>
    <!-- space  -->
    <th class="border-0" rowspan="9"></th>

    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="2"><h2>@lang('lang.payment') Receipt</h2></td>
    </tr>
    <tr>
        <!-- first header  -->
        <th>fees Details</th>
        <th>Amount</th>
        <!-- space  -->
        <th class="border-0" rowspan="{{4+count($fees_assigneds)}}"></th>


    </tr>

    @php
    $grand_total = 0;
    $total_fine = 0;
    $total_discount = 0;
    $total_paid = 0;
    $total_grand_paid = 0;
    $total_balance = 0;
    $totalpayable=0;
    @endphp
    @foreach($fees_assigneds as $fees_assigned)
    @php $grand_total += $fees_assigned->feesGroupMaster->amount; @endphp

    @php
    $discount_amount = $fees_assigned->applied_discount;
    $total_discount += $discount_amount;
    $student_id = $fees_assigned->student_id;
    @endphp
    @php
    //Sum of total paid amount of single fees type
    $paid =
    \App\SmFeesAssign::feesPayment($fees_assigned->feesGroupMaster->feesTypes->id,$fees_assigned->student_id)->sum('amount');
    $total_grand_paid += $paid;
    @endphp
    @php
    //Sum of total fine for single fees type
    $fine =
    \App\SmFeesAssign::feesPayment($fees_assigned->feesGroupMaster->feesTypes->id,$fees_assigned->student_id)->sum('fine');

    $total_fine += $fine;
    @endphp

    @php
    $total_paid += ($discount_amount + $paid);
    @endphp

    <tr>
        @php

        $assigned_main_fees=number_format((float)@$fees_assigned->feesGroupMaster->amount, 2, '.','');
        $p_amount= $assigned_main_fees-$paid+$fine-$discount_amount;

        // $totalpayable+=number_format((float)@$fees_assigned->feesGroupMaster->amount, 2, '.','');
        $totalpayable+=$p_amount;

        @endphp
        <!-- first td wrap  -->
        {{-- @if ($p_amount>0) --}}
        <td class="border-top">
            {{-- {{'fine: '.$fine}} --}}
            <p>
                {{--$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesGroups->name:""--}}
                {{$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesTypes->name:""}}
            </p>
            @if ($discount_amount>0)
            <p><b>Discount(-)</b></p>
            @endif
            @if ($fine>0)
            <p><b>Fine(+)</b></p>
            @endif
            @if ($paid>0)
            <p><b>Paid(+)</b></p>
            @endif


            <p><b>Balance</b></p>
        </td>
        <td class="border-top" style="text-align: right">
            {{@$assigned_main_fees}}
            @if ($discount_amount>0)
            <br>
            {{number_format($discount_amount, 2, '.', ',')}}
            @endif
            @if ($fine>0)
            <br>
            {{number_format($fine, 2, '.', ',')}}
            @endif
            @if ($paid>0)
            <br>
            {{number_format($paid, 2, '.', ',')}}
            @endif
            <br>
            <b>{{number_format(@$p_amount, 2, '.', ',')}}</b>
        </td>
        {{-- @endif --}}


    </tr>

    @endforeach

    @php

    $totalpayable=$totalpayable-$unapplied_discount_amount;

    if ($totalpayable<0) {
    $totalpayable=0.00;
    } else {
    $totalpayable=$totalpayable;
    }


    @endphp

    <tr>
        <!-- 1st td wrap  -->
        <td>
            <p><strong>Total Paid</strong></p>
        </td>
        <td style="text-align: right">
            <strong> {{ number_format((float) $total_paid, 2, '.', ',')}} </strong>
        </td>


    </tr>
    <tr>
        <!-- 1st td wrap  -->
        <td>
            <p><strong>Total Balance</strong></p>
        </td>
        <td style="text-align: right">
            <strong> {{ number_format((float) $totalpayable, 2, '.', ',')}} </strong>
        </td>


    </tr>

    <tr>
    </tr>

    <tr>
        <td colspan="2">Thank you for your payment</td>
    </tr>
    <tr>
        <td colspan="2"><p class="parents_num text_center"> Parents phone number :
                <span>{{@$parent->guardians_mobile}}</span>
            </p>
        </td>

    </tr>

    </tbody>
</table>


<script>
    window.print();
</script>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->

<script src="{{ asset('/') }}/public/backEnd/js/fees_invoice/jquery-3.2.1.slim.min.js"></script>
<script src="{{ asset('/') }}/public/backEnd/js/fees_invoice/popper.min.js"></script>
<script src="{{ asset('/') }}/public/backEnd/js/fees_invoice/bootstrap.min.js"></script>

{{--
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
--}}
</body>
</html>
