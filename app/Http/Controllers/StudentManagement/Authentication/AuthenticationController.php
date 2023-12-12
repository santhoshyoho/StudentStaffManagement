<?php

namespace App\Http\Controllers\StudentManagement\Authentication;

use App\Http\Controllers\Controller;
use App\Repositories\student\AuthenticationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    protected $repo;
    public function __construct(AuthenticationRepository $repo)
    {
        $this->repo = $repo;
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $credentials = [];
            $credentials['email'] = $request->input('username');
            $credentials['password'] = $request->input('password');
            return $this->repo->login($credentials);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function verifyotp(Request $req){
        Log::warning($req->all());
        $otp = $req->input('otp');
        $id = $req->input('id');
        Log::warning($id);
        return $this->repo->verifyotp($otp,$id);
    }


    public function twoStepGenerateOtp(Request $req){
        $name = $req->input('name');
        return $this->repo->twoStepGenerateOtp($name);
    }

    public function twoStepVerifyOtp(Request $req){
        $otp = $req->input('otp');
        $id = $req->input('id');
        return $this->repo->twoStepVerifyOtp($otp,$id);
    }


    public function resetPassword(Request $req){
        $id = $req->input('id');
        $pass = $req->input('password');
        $c_pass = $req->input('confirm_password');
        return $this->repo->resetPassword($id,$pass,$c_pass);
    }



    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
           $validator = Validator::make($request->all(),[
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'phone' =>'required|unique:users,phone',
                'password' => 'required',
                'is_active' => 'required',
                'user_type' => 'student',
                'gender' =>'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return $this->repo->createUser($request->all());
        }
        catch (ValidationException $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

}
