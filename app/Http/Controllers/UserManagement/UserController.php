<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
// use App\Repositories\Institute\InstituteRepository as InstituteInstituteRepository;
use App\Repositories\InstituteRepository ;
// use App\Repositories\InstituteRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    protected $repo;
    protected $img;

    public function __construct(InstituteRepository $repo, ImageRepository $img)
    {
        $this->repo = $repo;
        $this->img = $img;
    }

   


    public function register(Request $request)    
    {
        DB::beginTransaction();
        try {
           $validator = Validator::make($request->all(),[
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'phone' =>'required',
                'password' => 'required',
                'logo'=>'required',
                'description'=>'required',
                'registered_date'=>'required',  
                'user_type'=>'required'
                             

            ]);
            $validator->validate();

             // image
            $imagePath=[];
            $imageDirectory = "assets/institute/images";//image storing folder
            if($request->hasFile('image')){ //col name
                $productFiles = $request->file('image');//col name
                foreach($productFiles as $key => $value){
                    $imagePath[] = $this->img->uploadImage($value,$imageDirectory);
                }
            }



            //gallery
            $galleryPath = [];
            $imageDirectory = "assets/institute/gallery";//image storing folder
            if($request->hasFile('gallery')){
                $instuteFile = $request->file('gallery');
                foreach($instuteFile as $key => $value){
                    $galleryPath[]= $this->img->uploadImage($value,$imageDirectory);
                }
            }

            $InstituteImgPath = "assets/institute/register";
            $logoPath = "";
            if ($request->hasFile('logo')) {
                $logoPath = $this->img->uploadImage($request->file('logo'), $InstituteImgPath);
            }


            return  $this->repo->createUser($request->all(),$imagePath,$galleryPath,$logoPath);
           
        }
        catch (ValidationException $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->errors()], 422);
        }
         catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }


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
