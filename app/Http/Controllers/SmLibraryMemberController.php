<?php

namespace App\Http\Controllers;
use App\Role;
use App\SmClass;
use App\SmStudent;
use App\YearCheck;
use App\SmBookIssue;
use App\ApiBaseMethod;
use App\SmLibraryMember;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\RolePermission\Entities\InfixRole;

class SmLibraryMemberController extends Controller
{
    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
	}

    public function index(Request $request)
    {

        try{
            $libraryMembers = SmLibraryMember::where('active_status', '=', 1)->where('school_id',Auth::user()->school_id)->get();
            $roles = InfixRole::where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->get();
            $classes = SmClass::where('school_id',Auth::user()->school_id)->where('academic_id', getAcademicId())->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['libraryMembers'] = $libraryMembers->toArray();
                $data['roles'] = $roles->toArray();
                $data['classes'] = $classes->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.library.members', compact('libraryMembers', 'roles', 'classes'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        $input = $request->all();
        // return $input;
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            if ($request->member_type == "") {
                $validator = Validator::make($input, [
                    'member_type' => "required",
                ]);
            } elseif ($request->member_type == "2") {

                $validator = Validator::make($input, [
                    'member_type' => "required",
                    'student' => "required",
                ]);
            } else {
                $validator = Validator::make($input, [
                    'member_type' => "required",
                    'staff' => "required",
                ]);
            }
        } else {
            if ($request->member_type == "") {
                $validator = Validator::make($input, [
                    'member_type' => "required",
                ]);
            } elseif ($request->member_type == "2") {
                $validator = Validator::make($input, [
                    'member_type' => "required",
                    'student' => "required",
                ]);
            } else {
                $validator = Validator::make($input, [
                    'member_type' => "required",
                    'staff' => "required",
                ]);
            }
        }

        $student_staff_id = '';
        $isStudentData = $isStaffData = '';

        if ($request->member_type == "2") {
            $student_staff_id = $request->student;
            $class_id = $request->class;
            $section_id = $request->section;
            $isStudentData = SmStudent::where('class_id', '=', $class_id )->where('section_id', '=', $section_id )->where('active_status', '=', 1)->get();

        }
        if (!empty($request->staff)) {

            $student_staff_id = $request->staff;
            $isStaffData = SmLibraryMember::where('student_staff_id', '=', $student_staff_id)->where('active_status', '=', 1)->first();

        }

        try{

            $isExist_staff_id = SmLibraryMember::where('student_staff_id', '=', $student_staff_id)->first();
            //return $isExist_staff_id;
            if (!empty($isExist_staff_id)) {
                $members = SmLibraryMember::where('student_staff_id', '=', $student_staff_id)->first();
                ///return $members;
                $members->active_status = 1;
                $results = $members->update();
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            }
            else {
                if($request->member_type == "2"){

                    foreach ($isStudentData as $data) {

                        $members = SmLibraryMember::where('student_staff_id', '=', $data->user_id)->first();
                        if($members) {
                            $members->active_status = 1;
                            $results = $members->update();
                        }else {
                            $members = new SmLibraryMember();
                            $members->member_type = $request->member_type;
                            $members->student_staff_id = $data->user_id;;
                            $members->member_ud_id = rand(10000000, 99999999);
                            $members->created_by = Auth::user()->id;
                            $members->school_id = Auth::user()->school_id;
                            $members->academic_id = getAcademicId();
                            $results = $members->save();
                        }
                    }
                }
                else if(empty($isStaffData)){

                        $members = new SmLibraryMember();
                        $members->member_type = $request->member_type;
                        $members->student_staff_id = $student_staff_id;
                        $members->member_ud_id = $request->member_ud_id;
                        $members->created_by = Auth::user()->id;
                        $members->school_id = Auth::user()->school_id;
                        $members->academic_id = getAcademicId();
                        $results = $members->save();

                }

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    if ($results) {
                        return ApiBaseMethod::sendResponse(null, 'New Member has been added successfully');
                    } else {
                        return ApiBaseMethod::sendError('Something went wrong, please try again.');
                    }
                } else {
                    if ($results) {
                        Toastr::success('Operation successful', 'Success');
                        return redirect()->back();
                    } else {
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
                }
            }
        }catch (\Exception $e) {
            dd($e);
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function cancelMembership(Request $request, $id)
    {
        try{
        $tables = "";

        try {

            $isExist_member_id = SmBookIssue::select('id', 'issue_status')
                ->where('member_id', '=', $id)
                ->where('issue_status', '=', 'I')
                ->first();
            //return $isExist_member_id;


            if (!empty($isExist_member_id)) {
                Toastr::error('This member have to return book', 'Failed');
                return redirect()->back();
            } else {
                $members = SmLibraryMember::find($id);
                $members->active_status = 0;
                $results = $members->update();

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    if ($results) {
                        return ApiBaseMethod::sendResponse(null, 'Membership has been successfully cancelled');
                    } else {
                        return ApiBaseMethod::sendError('Something went wrong, please try again.');
                    }
                } else {
                    if ($results) {
                        Toastr::success('Operation successful', 'Success');
                        return redirect()->back();
                    } else {
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
                }
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
            Toastr::error('This item already used', 'Failed');
            return redirect()->back();
        }
        }catch (\Exception $e) {
                //dd($e->getMessage(), $e->errorInfo);
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }

        // $members = SmLibraryMember::find($id);
        // $members->active_status = 0;
        // $results = $members->update();

        // if (ApiBaseMethod::checkUrl($request->fullUrl())) {
        //     if ($results) {
        //         return ApiBaseMethod::sendResponse(null, 'Membership has been successfully cancelled');
        //     } else {
        //         return ApiBaseMethod::sendError('Something went wrong, please try again.');
        //     }
        // } else {
        //     if ($results) {
        //         return redirect()->back()->with('message-success-delete', 'Membership has been successfully cancelled');
        //     } else {
        //         return redirect()->back()->with('message-danger-delete', 'Something went wrong, please try again');
        //     }
        // }

    }
}