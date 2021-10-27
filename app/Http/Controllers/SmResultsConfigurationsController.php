<?php

namespace App\Http\Controllers;

use App\ApiBaseMethod;
use App\SmClass;
use App\SmResultsConfiguration;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\YearCheck;
use Illuminate\Support\Facades\Validator;

class SmResultsConfigurationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
            $results_config = SmResultsConfiguration::orderBy('percent_from', 'desc')->where('school_id',Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['results_config'] = $results_config->toArray();
                $data['classes'] = $classes->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.examination.results_configuration', compact('results_config','classes'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'percent_from' => "required|integer||min:0",
            'streams' => "required",
            'percent_upto' => "required|integer|min:".@$request->percent_from,
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // school wise uquine validation
        if($request->result_name!=="") {
            $is_duplicate = SmResultsConfiguration::where('school_id', Auth::user()->school_id)->where('result_name', $request->result_name)->where('academic_id', getAcademicId())->first();
            if ($is_duplicate) {
                Toastr::error('Duplicate result name found!', 'Failed');
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }

        try{
            $results_config = new SmResultsConfiguration();
            $results_config->result_name = $request->result_name;
            $results_config->class_teacher_remark = $request->class_teacher_remark;
            $results_config->principal_remark = $request->principal_remark;
            $results_config->percent_from = $request->percent_from;
            $results_config->percent_upto = $request->percent_upto;
            $results_config->description = $request->description;
            $results_config->created_at= YearCheck::getYear() .'-'.date('m-d h:i:s');
            $results_config->school_id = Auth::user()->school_id;
            $results_config->academic_id = getAcademicId();
            $results_config->streams = json_encode($request->streams);

            $result = $results_config->save();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Results Configuration has been created successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } else {
                    Toastr::error('Operation Failed for result', 'Failed');
                    return redirect()->back();
                }
            }
        }catch (\Exception $e) {
            dd($e);
            //Toastr::error('Operation Failed Outer', 'Failed');
            //return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try{
            if (checkAdmin()) {
                $result_config = SmResultsConfiguration::find($id);
            }else{
                $result_config = SmResultsConfiguration::where('id',$id)->where('school_id',Auth::user()->school_id)->first();
            }
            $results_config = SmResultsConfiguration::where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['results_config'] = $results_config->toArray();
                $data['result_config'] = $result_config->toArray();
                $data['classes'] = $classes->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.examination.results_configuration', compact('results_config', 'result_config','classes'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'percent_from' => "required|integer||min:0",
            'percent_upto' => "required|integer|min:".@$request->percent_from,
            'streams' => "required"
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // school wise uquine validation
        if($request->result_name!=="") {
                    $is_duplicate = SmResultsConfiguration::where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->where('result_name', $request->result_name)->where('id', '!=', $request->id)->first();
                    if ($is_duplicate) {
                        Toastr::error('Duplicate result name found!', 'Failed');
                        return redirect()->back()->withErrors($validator)->withInput();
                    }
        }

        try{
            if (checkAdmin()) {
                $results_config = SmResultsConfiguration::find($request->id);
            }else{
                $results_config = SmResultsConfiguration::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            $results_config->result_name = $request->result_name;
            $results_config->class_teacher_remark = $request->class_teacher_remark;
            $results_config->principal_remark = $request->principal_remark;
            $results_config->percent_from = $request->percent_from;
            $results_config->percent_upto = $request->percent_upto;
            $results_config->description = $request->description;
            $results_config->created_at= YearCheck::getYear() .'-'.date('m-d h:i:s');
            $results_config->school_id = Auth::user()->school_id;
            $results_config->academic_id = getAcademicId();
            $results_config->streams = json_encode($request->streams);
            $result = $results_config->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result) {
                    return ApiBaseMethod::sendResponse(null, 'Result Setting has been updated successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('results-configuration');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        try{
            if (checkAdmin()) {
                $result_config = SmResultsConfiguration::destroy($id);
            }else{
                $result_config = SmResultsConfiguration::where('id',$id)->where('school_id',Auth::user()->school_id)->delete();
            }
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($result_config) {
                    return ApiBaseMethod::sendResponse(null, 'Grdae has been deleted successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($result_config) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect('results-configuration');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
