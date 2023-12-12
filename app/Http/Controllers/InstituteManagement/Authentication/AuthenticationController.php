<?php

namespace App\Http\Controllers\InstituteManagement\Authentication;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Repositories\Institute\AuthenticationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    protected $repo;
    protected $img;

    public function __construct(AuthenticationRepository $repo,ImageRepository $img)
    {
        $this->repo = $repo;
        $this->img = $img;
    }


    public function login(Request $request)
    {
        Log::warning($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            

            $user = $this->repo->getUser($request->input('username'));
            Log::warning($user);
            $credentials = [];
            $credentials['email'] = $user ? $user->email : null;
            $credentials['password'] = $request->input('password');
            return $this->repo->login($credentials);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function verifyOtp(Request $req){

        Log::warning($req->all());        
        $otp = $req->input('otp');        
        $id = $req->input('id');        
        Log::warning($id);

        return $this->repo->verifyotp($otp,$id);    
    }


    public function generateOtp(Request $req){
        Log::warning($req->all());
        $name = $req->input('name');
        return $this->repo->generateOtp($name);            
    }


    public function passwordResetVerifyotp(Request $req){
        Log::warning($req->all());
        $otp = $req->input('otp');
        $id = $req->input('id');
        return $this->repo->passwordResetVerifyotp($otp,$id);    
    }



    public function resetPassword(Request $req){
        Log::warning($req->all());
        $id = $req->input('id');
        $pass = $req->input('pass');
        $c_pass = $req->input('c_pass');
        return $this->repo->resetPassword($id,$pass,$c_pass);                    
    }
    


}
