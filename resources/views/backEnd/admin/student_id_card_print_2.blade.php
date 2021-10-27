<!DOCTYPE html>
<html>
<head>
    <title>@lang('lang.student_id_card')</title>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/bootstrap.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/css/style.css"/>
    <style media="print">

        @media print {
            @page {
                size: landscape
            }
        }

        @media print {
            #button {
                display: none;
            }
        }

        td {
            border-right: 0px solid #ddd;
            border-left: 0px solid #ddd;
            border-bottom: 0px solid #ddd;
            padding-top: 0px;
            padding-bottom: 0px;
            border-radius: 15px;
        }
        .table_background {
            display: flex;
            background-color: white;
            margin-left: 3vw;
            margin-right: 3vw;
            margin-bottom: 10vh;
            margin-top: 5vh;
            box-shadow: 0px 0px 10px rgba(114, 114, 113, 0.5);
            border-radius: 15px;
        }
        table{
            border: 0 !important;
            border-radius: 15px;
            border-collapse: collapse;
            /* add this */
            overflow:hidden
        }

    </style>
    <style>
        .id_card {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            grid-gap: 10px;
            justify-content: center;
        }

        input#button {
            margin: 20px 0;
        }

        td {
            font-size: 18px;
            padding: 0 12px;
            line-height: 8px;
        }

        body#abc {
            max-width: 100%;
            margin: auto;
        }

        table {
            width: 100%;
        }
    </style>
</head>
<body id="abc">
<input type="button" onclick="printDiv('abc')" id="button" class="primary-btn small fix-gr-bg" value="print"/>
@foreach($students as $student)
<table cellpadding="0" cellspacing="0" border="0" align="center" class="table_background">

    <tr>
        <td><img src="{{ @$general_settings->logo != "" ? asset(@@$general_settings->logo) :
            asset('public/backEnd/img/student/id-card-img.jpg') }}" alt="" >
        </td>
        <td colspan="2" style="text-align: center">
            <h1 style="font-size: xxx-large"><strong>{{ @$school->school_name }}</strong></h1>
            <p style="font-size: x-large"><strong>{{ @$school->address }} </strong></p>
            <hr>
            <p style="font-size: x-large"><strong>{{ @$school->phone }} | {{ @$school->email }}</strong></p>

        </td>
    </tr>
    <tr style=" border-right: 0px solid #ddd; border-left: 0px solid #ddd;  height: 0px; ">
        <td colspan="2"
            style="border-radius: 5px; position: relative; text-align: center; background-color: #A9A9A9; border:0px solid #A9A9A9">
            <!--  <center>
                 <img src="{{asset('public/backEnd/img/student/id-card-bg.png')}}" style="width: 100%; height: auto; padding: 0px; margin: 0px" >
             </center> -->
            <h2 style="font-size: xx-large; padding: 0px; text-align: center; margin-bottom: 0px;  color: #fff; ">
                <strong>@lang('lang.student_id_card')</strong></h2>
        </td>
        <td colspan="1"
            style="border-radius: 10px;position: relative; text-align: center; background-color: #A9A9A9; border:0px solid #A9A9A9">
            <!--  <center>
                 <img src="{{asset('public/backEnd/img/student/id-card-bg.png')}}" style="width: 100%; height: auto; padding: 0px; margin: 0px" >
             </center> -->
            <h2 style="font-size: xx-large; padding: 0px; text-align: center; margin-bottom: 0px; color: #fff; ">
                <strong>School Reg: {{ @$general_settings->registration_no }}</strong></h2>
        </td>
    </tr>


    @if(@$id_card->student_name == 1)
    <tr>
        <td>
            <div style="font-size: xx-large; color: #0a529c"> <strong>@lang('lang.student') @lang('lang.number'):</strong></div>
        </td>
        <td>
            <div style="font-size: xx-large"> <strong>{{@$student->admission_id_number}}</strong></div>
        </td>
        <td rowspan="4" style="text-align: center;   border-right: 0px solid #ddd; border-left: 1px solid #ddd;">
            <br>
            <img style="border-radius: 5px;" src="{{ @$student->student_photo != "" ? asset(@$student->student_photo) :
            asset('public/backEnd/img/student/id-card-img.jpg') }}" alt="" >
            <br><br>
            @if(@$id_card->admission_no == 1)
            <div style="font-size: xx-large"><strong>{{ @$student->full_name}}</strong></div>
            <hr><br>
            <div style="font-size: x-large">{{@$id_card->designation}}:</div> <br><img
                    src="{{asset($id_card->signature)}}" width="40%" style="margin-right: 0px !important;">
            @endif
        </td>
    </tr>
    @endif


    @if(@$id_card->dob == 1)
    <tr>
        <td>
            <div style="font-size: xx-large; color: #0a529c"><strong>
                @lang('lang.date_of_birth'):
            </strong></div>
        </td>
        <td>
            <div style="font-size: xx-large"><strong>{{@dateConvert($student->date_of_birth)}}</strong></div>
        </td>
    </tr>
    @endif


    @if(!empty($id_card->academic_id))
    <tr>
        <td>
            <div style="font-size: xx-large; color: #0a529c "><strong>@lang('lang.academic_year') @lang('lang.issued') :</strong></div>
        <td>
            <div style="font-size: xx-large"><strong>{{@$student->academicYear->year}}</strong>
            </div>
        </td>
    </tr>
    @endif

    @if(@$student->className->class_name == "FORM E" || @$student->className->class_name == "GRADE 11")
    <tr>
        <td>
            <div style="font-size: xx-large; color: #0a529c "><strong>@lang('lang.valid') @lang('lang.until') :</strong></div>
        <td>
            <div style="font-size: xx-large"><strong>31-Dec-{{@$student->academicYear->year}}</strong>
            </div>
        </td>
    </tr>
    @endif
    @if(@$student->className->class_name == "FORM D")
    <tr>
        <td>
            <div style="font-size: xx-large; color: #0a529c"><strong>@lang('lang.valid') @lang('lang.until') :</strong></div>
        <td>
            <div style="font-size: xx-large"><strong>31-Dec-{{@$student->academicYear->year+1}}</strong>
            </div>
        </td>
    </tr>
    @endif
    @if(@$student->className->class_name == "GRADE 10")
    <tr>
        <td>
            <div style="font-size: xx-large;color: #0a529c"><strong>@lang('lang.valid') @lang('lang.until') :</strong></div>
        <td>
            <div style="font-size: xx-large"><strong>31-Dec-{{@$student->academicYear->year+2}}</strong>
            </div>
        </td>
    </tr>
    @endif
    @if(@$student->className->class_name == "GRADE 9")
    <tr>
        <td>
            <div style="font-size: xx-large; color: #0a529c"><strong>@lang('lang.valid') @lang('lang.until') :<strong></div>
        <td>
            <div style="font-size: xx-large"><strong>31-Dec-{{@$student->academicYear->year+3}}</strong>
            </div>
        </td>
    </tr>
    @endif
    @if(@$student->className->class_name == "GRADE 8")
    <tr>
        <td>
            <div style="font-size: xx-large; color: #0a529c"><strong>@lang('lang.valid') @lang('lang.until') :</strong></div>
        <td>
            <div style="font-size: xx-large"><strong>31-Dec-{{@$student->academicYear->year+4}}</strong>
            </div>
        </td>
    </tr>
    @endif
    <tr style="border-right: 0px solid #ddd;  height: 0px; ">
        <td colspan="2" style="border-right: 1px solid #ddd;">
            <!--  <center>
                 <img src="{{asset('public/backEnd/img/student/id-card-bg.png')}}" style="width: 100%; height: auto; padding: 0px; margin: 0px" >
             </center> -->
            <div style="padding: 0px; text-align: center; margin-bottom: 0px;  color: #fff; ">
            <h2 style="font-size: xx-large;">
                <strong>{{@$general_settings->motto}}</strong> </h2></div>
        </td>
        <td style="text-align: center">
            <img src="{{asset('public/backEnd/')}}/img/student/barcode.png" alt="No Picture">
        </td>
    </tr>
    {{--

    @if(@$id_card->class == 1)
    @lang('lang.class'): {{
    @$student->className!=""?@$student->className->class_name:""}} ({{
    @$student->section!=""?@$student->section->section_name:""}})
    <br>
    @endif
    @if(@$id_card->father_name == 1)
    @lang('lang.father_name')
    {{@$student->parents
    !=""?@$student->parents->fathers_name:""}}
    <br>
    @endif

    @if(@$id_card->mother_name == 1)
    @lang('lang.mother_name'):
    {{@$student->parents
    !=""?@$student->parents->mothers_name:""}}
    <br>
    @endif
    @if(@$id_card->student_address == 1)
    @lang('lang.student')
    @lang('lang.address')
    :
    {{@$student->current_address!=""?@$student->current_address:""}}
    <br>
    @endif
    @if(@$id_card->blood == 1)
    @lang('lang.blood_group'):
    {{@$student->bloodGroup!=""?@$student->bloodGroup->base_setup_name:""}}
    <br>
    @endif
    @if(@$id_card->phone == 1)
    @lang('lang.phone'):
    {{@$student->mobile}}
    <br>
    @endif
    --}}



</table>
<div class="single_record"></div>
@endforeach
<script src="{{asset('public/backEnd/')}}/vendors/js/jquery-3.2.1.min.js"></script>
<script>

    function printDiv(divName) {

        // document.getElementById("button").remove();

        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>
</body>
</html>

