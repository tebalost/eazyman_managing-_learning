<style type="text/css">
    #bank-area, #cheque-area{
        display: none;
    }
    .primary-input ~ label {
        top: -15px;
    }
</style>

<div class="container-fluid">
    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'fees-payment-store',
    'method' => 'POST', 'enctype' => 'multipart/form-data', 'name' => 'myForm', 'onsubmit' => "return validateFormFees()"]) }}
    <div class="row">
        <div class="col-lg-12">
            <div class="row mt-25">
                <div class="col-lg-12">
                    <div class="no-gutters input-right-icon">
                        <div class="col">
                            <div class="input-effect">
                                <input class="primary-input date form-control" id="startDate" type="text"
                                       name="date" value="{{date('m/d/Y')}}" readonly>
                                <label>@lang('lang.date')</label>
                                <span class="focus-border"></span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button class="" type="button">
                                <i class="ti-calendar" id="start-date-icon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
            <input type="hidden" name="master_id" id="master_id" value="{{$master}}">
            <input type="hidden" name="real_amount" id="real_amount" value="{{$amount}}">
            <input type="hidden" id="student_id" name="student_id" value="{{$student_id}}">
            <input type="hidden" name="fees_type_id" value="{{$fees_type_id}}">
            <input type="hidden" name="fees_discount_id" value="{{@$discounts->fees_discount_id}}">
            <input type="hidden" name="applied_amount" value="{{@$discounts->applied_amount}}">

            <div class="row mt-25">
                <div class="col-lg-12" id="sibling_class_div">
                    <div class="input-effect">
                        <input oninput="numberMinZeroCheck(this)" class="primary-input form-control" type="text" max="{{$amount}}" name="amount" value="{{$amount}}" id="amount" required>
                        <label>@lang('lang.amount') <span>*</span> </label>
                        <span class="focus-border"></span>
                        <span class=" text-danger" role="alert" id="amount_error"></span>
                    </div>
                </div>
            </div>
            <div class="row mt-25">
                <div class="col-lg-6 d-none">
                    <div class="input-effect">
                        <input oninput="numberCheckWithDot(this)" class="primary-input form-control" type="text" name="discount_amount" id="discount_amount" value="0">
                        <label>@lang('lang.discount') <span></span> </label>
                        <span class="focus-border"></span>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="input-effect">
                        <input class="primary-input form-control" type="text" name="fine" value="0" id="fine_amount" onblur="checkFine()">
                        <label>@lang('lang.fine') <span></span> </label>
                        <span class="focus-border"></span>
                    </div>
                </div>
            </div>
            <div class="row mt-25" id="fine_title" style="display:none">

                <div class="col-lg-12">
                    <div class="input-effect">
                        <input class="primary-input form-control"  type="text" name="fine_title" >
                        <label>@lang('lang.fine') @lang('lang.title') <span></span> </label>
                        <span class="focus-border"></span>
                    </div>
                </div>
            </div>
            <script>
                function checkFine(){
                    var fine_amount=document.getElementById("fine_amount").value;
                    var fine_title=document.getElementById("fine_title");
                    if (fine_amount>0) {
                        fine_title.style.display = "block";
                    } else {
                        fine_title.style.display = "none";
                    }
                }
            </script>
            <div class="row mt-50">
                <div class="col-lg-1">
                    <p class="text-uppercase fw-500 mb-10"> @lang('lang.mode')</p>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex radio-btn-flex ml-40">
                        <div class="mr-30">
                            <input type="radio" name="payment_mode" id="cash" value="cash" class="common-radio relationButton" onclick="relationButton('cash')">
                            <label for="cash">@lang('lang.cash')</label>
                        </div>
                        @if(@$method['bank_info']->active_status == 1)
                        <div class="mr-30">
                            <input type="radio" name="payment_mode" id="bank" value="bank" class="common-radio relationButton" onclick="relationButton('bank')">
                            <label for="bank">@lang('lang.bank')</label>
                        </div>
                        @endif
                        @if(@$method['cheque_info']->active_status == 1)
                        <div class="mr-30">
                            <input type="radio" name="payment_mode" id="cheque" value="cheque" class="common-radio relationButton"  onclick="relationButton('cheque')">
                            <label for="cheque">@lang('lang.cheque')</label>
                        </div>
                        @endif
                        @if(@$method['eazymoola']->active_status == 1)
                        <div class="mr-30">
                            <input type="radio" name="payment_mode" id="eazymoola" value="eazymoola" class="common-radio" >
                            <label for="eazymoola">@lang('lang.eazymoola')</label>
                        </div>
                        @endif
                        @if(@$method['ecocash']->active_status == 1)
                        <div class="mr-30">
                            <input type="radio" name="payment_mode" id="ecocash" value="ecocash" class="common-radio">
                            <label for="ecocash">@lang('lang.ecocash')</label>
                        </div>
                        @endif
                        @if(@$method['mpesa']->active_status == 1)
                        <div class="mr-30">
                            <input type="radio" name="payment_mode" id="mpesa" value="mpesa" class="common-radio">
                            <label for="mpesa">@lang('lang.mpesa')</label>
                        </div>
                        @endif
                        @if(@$method['sponsor']->active_status == 1)
                        <div class="mr-30">
                            <input type="radio" name="payment_mode" id="sponsor" value="sponsor" class="common-radio relationButton"  onclick="relationButton('sponsor')" >
                            <label for="sponsor">@lang('lang.sponsor')</label>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mt-50" id="feesBankPayment">
                <div class="col-lg-3">
                    <p class="text-uppercase fw-500 mb-10">@lang('lang.select') @lang('lang.bank')</p>
                </div>
                <div class="col-lg-9">
                    <div class="input-effect">
                        <select class="niceSelect1 w-100 bb form-control{{ $errors->has('bank_id') ? ' is-invalid' : '' }}" name="bank_name">
                            @if(isset($banks))
                            @foreach($banks as $value)
                            <option value="{{$value->account_name}}">{{$value->account_name}}</option>
                            @endforeach
                            @endif
                        </select>
                        <span class="focus-border"></span>
                        @if ($errors->has('bank_id'))
                        <span class="invalid-feedback invalid-select" role="alert">
                                <strong>{{$errors->first('bank_id')}}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mt-50" id="feesSponsorPayment">
                <div class="col-lg-3">
                    <p class="text-uppercase fw-500 mb-10">@lang('lang.select') @lang('lang.sponsor')</p>
                </div>
                <div class="col-lg-9">
                    <div class="input-effect">
                        <select class="niceSelect1 w-100 bb form-control{{ $errors->has('sponsor_id') ? ' is-invalid' : '' }}" name="sponsor_name">
                            @if(isset($sponsors))
                            @foreach($sponsors as $value)
                            <option value="{{$value->base_setup_name}}">{{$value->base_setup_name}}</option>
                            @endforeach
                            @endif
                        </select>
                        <span class="focus-border"></span>
                        @if ($errors->has('sponsor_id'))
                        <span class="invalid-feedback invalid-select" role="alert">
                                <strong>{{$errors->first('sponsor_id')}}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{--  Start Bank and cheque info --}}
            <div class="row">
                <div class="col-md-6 bank-details" id="bank-area">
                    <strong>{!!$data['bank_info']->bank_details!!}</strong>
                </div>
                <div class="col-md-6 cheque-details" id="cheque-area">
                    <strong>{!!$data['cheque_info']->cheque_details!!}</strong>
                </div>
            </div>
            {{--  End Bank and cheque info --}}
            <div class="row mt-25">
                <div class="col-lg-12" id="sibling_name_div">
                    <div class="input-effect mt-20">
                        <textarea class="primary-input form-control" cols="0" rows="3" name="note" id="note"></textarea>
                        <label>@lang('lang.note') </label>
                        <span class="focus-border textarea"></span>

                    </div>
                </div>


            </div>
            <div class="row no-gutters input-right-icon mt-35">
                <div class="col">
                    <div class="input-effect">
                        <input class="primary-input form-control {{ $errors->has('file') ? ' is-invalid' : '' }}"
                               id="placeholderInput"
                               type="text"
                               placeholder="{{isset($visitor)? ($visitor->slip != ""? getFilePath3($visitor->slip):'File Name'):'File Name'}}"
                        readonly>
                        <span class="focus-border"></span>

                        @if ($errors->has('file'))
                        <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ @$errors->first('file') }}</strong>
                                    </span>
                        @endif

                    </div>
                </div>
                <div class="col-auto">
                    <button class="primary-btn-small-input" type="button">
                        <label class="primary-btn small fix-gr-bg"
                               for="browseFile">@lang('lang.browse')</label>
                        <input type="file" class="d-none" id="browseFile" name="slip">
                    </button>
                </div>
            </div>
        </div>


        <!-- <div class="col-lg-12 text-center mt-40">
            <button class="primary-btn fix-gr-bg" id="save_button_sibling" type="button">
                <span class="ti-check"></span>
                save information
            </button>
        </div> -->
        <div class="col-lg-12 text-center mt-40">
            <div class="mt-40 d-flex justify-content-between">
                <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('lang.cancel')</button>

                <button class="primary-btn fix-gr-bg submit" type="submit">@lang('lang.save') @lang('lang.information')</button>
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#feesBankPayment").hide();
        $("#feesSponsorPayment").hide()
    });

    relationButton = (status) => {
        var sponsor = document.getElementById("sponsor");
        var bank_area = document.getElementById("bank-area");
        if(status == "bank"){
            sponsor.style.display = "none";
            bank_area.style.display = "block";
            $("#feesBankPayment").show();
        }else{
            sponsor.style.display = "none";
            bank_area.style.display = "none";
            $("#feesBankPayment").hide();
        }
        if(status == "sponsor"){
            sponsor.style.display = "block";
            $("#feesSponsorPayment").show();
        }else{
            sponsor.style.display = "none";
            $("#feesSponsorPayment").hide();
        }
    }

    $("#search-icon").on("click", function() {
        $("#search").focus();
    });

    $("#start-date-icon").on("click", function() {
        $("#startDate").focus();
    });

    $("#end-date-icon").on("click", function() {
        $("#endDate").focus();
    });

    $(".primary-input.date").datepicker({
        autoclose: true,
        setDate: new Date(),
    });
    $(".primary-input.date").on("changeDate", function(ev) {
        // $(this).datepicker('hide');
        $(this).focus();
    });

    $(".primary-input.time").datetimepicker({
        format: "LT",
    });

    var fileInput = document.getElementById("browseFile");
    if (fileInput) {
        fileInput.addEventListener("change", showFileName);

        function showFileName(event) {
            var fileInput = event.srcElement;
            var fileName = fileInput.files[0].name;
            document.getElementById("placeholderInput").placeholder = fileName;
        }
    }
    var fileInp = document.getElementById("browseFil");
    if (fileInp) {
        fileInp.addEventListener("change", showFileName);

        function showFileName(event) {
            var fileInp = event.srcElement;
            var fileName = fileInp.files[0].name;
            document.getElementById("placeholderIn").placeholder = fileName;
        }
    }

    if ($(".niceSelect1").length) {
        $(".niceSelect1").niceSelect();
    }



</script>