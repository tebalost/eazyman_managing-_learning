<?php

namespace App\Http\Controllers;

use App\tableList;
use App\YearCheck;
use App\SmSetupAdmin;
use App\ApiBaseMethod;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmUserWalletController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}






public function doDeposit(Request $request)
{

    try{
//        {
//            "description": "string",
//"transactionAmount": 0,
//"walletAccount": {
//            "balance": 0,
//"msisdn": "string",
//"userName": "string",
//"userWalletReferenceNumber": "string",
//"walletAccountId": 0
//}
//}
        return View();
//        return view('backEnd.admin.setup_admin', compact('admin_setups', 'admin_setup'));
    }catch (\Exception $e) {
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }
}



}