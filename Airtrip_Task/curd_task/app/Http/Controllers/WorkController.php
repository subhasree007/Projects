<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Department;
use App\Models\UserContact;
use Carbon\Carbon;

use Laravel\Lumen\Routing\Controller as BaseController;

class WorkController extends Controller
{
    public function __construct()
    {

    }

    public function adddepartment(Request $request){

        $validator = Validator::make($request->all(), [
            'dept_name'  => 'required|string'
        ]);
        if ($validator->fails()) {
            $message = $validator->errors();
            $data = ['message' => $message,'status_code'=>400];
            $status_code = 400;
            return response()->json($data,$status_code);
        }else{

            $id = $request->input('id');
            $data = [
                'dept_name'=>$request->input('dept_name'),
                'dept_status'=>1 // 2 - Inactive , 1 - Active
                ];

            $result = Department::duplicatecompney($data);

            if($result == 0){
                if(Department::insert($data)){
                    $data = ['data'=>'Department inserted successfully','status'=>200];
                    $status = 200;
                }else{
                    $data = ['data'=>'Some error happend during insertion','status'=>400];
                    $status = 400;
                }
            }else{
                $data = ['data'=>'Duplicate Department Not Allowed','status'=>400];
                $status = 400;
            }

            return response()->json($data,$status);

        }

    }
    public function updatedepartment(Request $request){

        $validator = Validator::make($request->all(), [
            'dept_name'  => 'required|string',
            'dept_id'  => 'required|numeric'
        ]);
        if ($validator->fails()) {
            $message = $validator->errors();
            $data = ['message' => $message,'status_code'=>400];
            $status_code = 400;
            return response()->json($data,$status_code);
        }else{

            $id = $request->input('dept_id');
            $data = [
                'cname'=>$request->input('dept_name')
                ];

            $result = Department::duplicatecompney($data);

            if($result == 0){
                if(Department::updateData($id,$data)){
                    $data = ['data'=>'Department Updated successfully','status'=>200];
                    $status = 200;
                }else{
                    $data = ['data'=>'Some error happend during Updation','status'=>400];
                    $status = 400;
                }
            }else{
                $data = ['data'=>'Duplicate Department Name Not Allowed','status'=>400];
                $status = 400;
            }

            return response()->json($data,$status);

        }

    }
    public function deletedepartment(Request $request){

        // Deletion we are not actually delete the dept row insted disbale the status,
        // other wise we will face data lose issue

        $validator = Validator::make($request->all(), [
            'dept_id'  => 'required|string'
        ]);
        if ($validator->fails()) {
            $message = $validator->errors();
            $data = ['message' => $message,'status_code'=>400];
            $status_code = 400;
            return response()->json($data,$status_code);
        }else{

            $id = $request->input('dept_id');
            $data = [
                'dept_status'=> 2 // 2 - Inactive , 1 - Active
                ];

            if(Department::updateData($id,$data)){
                $data = ['data'=>'Department Deleted successfully','status'=>200];
                $status = 200;
            }else{
                $data = ['data'=>'Some error happend during Deletion','status'=>400];
                $status = 400;
            }

            return response()->json($data,$status);

        }

    }
    public function viewdepartment(Request $request){
        return response()->json(['departments' =>  Department::where('dept_status',1)->get(),'status'=>200], 200);
    }
    public function searchuser(Request $request){

        $validator = Validator::make($request->all(), [
            'emp_name'  => 'string'
        ]);
        if ($validator->fails()) {
            $message = $validator->errors();
            $data = ['message' => $message,'status_code'=>400];
            $status_code = 400;
            return response()->json($data,$status_code);
        }else{
            $userdata = User::getemployee($request->input('emp_name'));
            $data = ['data'=>$userdata,'status'=>200];
            $status = 200;
            return response()->json($data,$status);
        }

    }
    public function addemp(Request $request){

        $validator = Validator::make($request->all(), [
            'emp_name'  => 'required|string',
            'dept_id'   => 'required|string',
            'mobile_numbers'=>'required|array|min:3',
            "mobile_numbers.*"  => "required|string|distinct|min:3",
            'address'=>'required|array|min:3',
            "address.*"  => "required|string|distinct|min:3",
        ]);
        if ($validator->fails()) {
            $message = $validator->errors();
            $data = ['message' => $message,'status_code'=>400];
            $status_code = 400;
            return response()->json($data,$status_code);
        }else{

            $data = [
                'emp_name'=>$request->input('emp_name'),
                'dept_id'=>$request->input('dept_id')
                ];

            $indata   = User::create($data);
            if($indata->id){

                $mobile_num = $request->input('mobile_number');
                $address    = $request->input('address');
                $mobile_arr = explode(',',$mobile_num);
                $address = explode(',',$address);

                for($i=0;$i<=count($mobile_arr);$i++){

                    $data[] = [
                            'mobile_number' => $mobile_arr[$i],
                            'address'       => $address[$i],
                            'user_id'       => $indata->id
                    ];
                }
                $condata   = UserContact::create($data);
                $data = ['data'=>'Employee added successfully','status'=>200];
                $status = 200;
            }else{
                $data = ['data'=>'Some error happend during insertion','status'=>400];
                $status = 400;
            }

            return response()->json($data,$status);

        }

    }

    public function viewemp(Request $request){
        return response()->json(['settings' =>  User::all(),'status'=>200], 200);
    }

}
