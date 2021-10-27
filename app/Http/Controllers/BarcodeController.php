<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    public function barcode()
    {
        return view('backEnd.admin.student_id_card_print_2');
    }
}
