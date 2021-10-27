<?php

namespace App;

use App\SmGeneralSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SmAcademicYear extends Model
{

    protected $fillable = ['school_id'];

	public function scopeActive($query){
            
		return $query->where('active_status',1);
	}
  public static function API_ACADEMIC_YEAR($school_id)
	{
		try{
			 return SmGeneralSettings::where('school_id',$school_id)->first('session_id')->session_id;
		}catch(\Exception $e){
			return 1;
		}

	}
  public static function SINGLE_SCHOOL_API_ACADEMIC_YEAR()
	{
	    //Todo get the school id based on the authenticated user
		try{
			 return SmGeneralSettings::where('school_id', 6)->where('active_status',1)->first('session_id')->session_id;
		}catch(\Exception $e){
			return 1;
		}

	}
}
