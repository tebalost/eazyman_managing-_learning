<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class SmEazymoolaController extends Controller
{
//    puublic function __construct(){
//        private $client = \guzzle\Http;
//    }
    // EazyMoola
    public function studentEazyMoolaTopup(Request $request)
    {
        try {
            if ($request->amount != "") {
                $request->validate([
                    "payment_method" => "required",
                    "name" => "required",
                    "reference" => "required",
                    "msisdn" => "required",
                    "amount" => "required"
                ]);

                $data = array(
                    "description"=> "This is the top",
                    "transactionAmount" => 100,
                    "walletAccount" => array(
                        "balance" => 100,
                        "msisdn" => "63351428",
                        "userName" => "Mpho",
                        "userWalletReferenceNumber" => "wd09pK",
                        "walletAccountId" => 1
                    )
                );

                $body = json_encode($data);
                var_dump($body);


                $response = Http::post("http://35.225.221.35:2025/api/deposit-into-user-wallet/wd09pK", [$body]);
//                if($response->successful())
//                    dd($response->body());
//                else
//                    echo $response->body();
//                exit;

                /*
                 $topup = new SmStudentEazyMoolaTopUp();
                 $topup->staff_student_id = $request->payment_method;
                 $topup->payment_method = $request->payment_method;
                 $topup->date = date('Y-m-d', strtotime($request->date));
                 $topup->name = $request->name;
                 $topup->amount = $request->amount;
                 $topup->save();
                */
            }
            Toastr::success('Operation successful', 'Success');
            return redirect()->back()->with(['eazyMoola' => 'active']);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back()->with(['eazyMoola' => 'active']);
        }
    }
    public function payStudentItems(Request $request){
        $request->validate([

        ]);

        dd($request);

         $data = array(
            "description" => "",
            "transactionAmount" => $request->amount,
            "walletAccount" => array("balance"=> 0, "msisdn"=>"", "userName" => "", "userWalletReferenceNumber" => "", "wallet" => "")
        );
        dd($request);
    }
}
