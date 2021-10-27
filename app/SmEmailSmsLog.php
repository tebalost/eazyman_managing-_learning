<?php

namespace App;
use App\SmAcademicYear;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SmEmailSmsLog extends Model
{
    public  static function saveEmailSmsLogData($request,$to_mobile){

    	$selectTabb = '';
        if(empty($request->selectTab)){
            $selectTabb = 'G';
        }
        else{
            $selectTabb = $request->selectTab;
        }
        $academic_year = SmAcademicYear::where('active_status',1)->where('school_id',Auth::user()->school_id)->first();
        $emailSmsData = new SmEmailSmsLog();
        $emailSmsData->title = $request->email_sms_title;
        $emailSmsData->description = $request->description;
        $emailSmsData->send_through = $request->send_through;
        $emailSmsData->send_date = date('Y-m-d');
        $emailSmsData->send_to = $selectTabb;
        $emailSmsData->to_mobile = $to_mobile;
        $emailSmsData->school_id = Auth::user()->school_id;
        $emailSmsData->academic_id = $academic_year->id;
        $emailSmsData->created_by = Auth::user()->id;
        $emailSmsData->academic_id = Auth::user()->id;
        $success = $emailSmsData->save();
    }
}
