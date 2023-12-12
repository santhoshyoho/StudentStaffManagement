<?php
namespace App\Repositories\student;

use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Repositories\BaseRepositoryInterface;

class AuthenticationRepository implements BaseRepositoryInterface
{
    public function all(){
        
    }

    public function login($credentials){
        try {
            if(Auth::attempt($credentials)){
                $user = Auth::user();
                Log::warning($user);
                $usertype = $user->user_type;

                if($usertype === 'student'){               
        
                $token = auth()->user()->createToken('AuthToken')->accessToken;
                $response = [
                    "user_id"=>$user->id,
                    "name"=>$user->name,
                    "email"=>$user->email,
                    "phone"=>$user->phone,
                    "token"=>$token
                ];
                return ["status"=>true,"message"=>"Login successfull","data"=>$response];

                }

                return["status" => true, "message" => "check the user type"];    

               
            }
            else {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
        } catch (Exception $e) {
            Log::warning($e->getMessage());
            return ["status"=>false,"message"=>$e->getMessage()];
            //throw $th;
        }

    }


    public function generateotp($id){
        try{
            if(!$id){
                DB::rollBack();
                return ["status"=>false,"message"=>"user id is mandatory"];
            }    

                $user = DB::table('users')
                ->select('id', 'name','is_two_step_enabled','otp')
                ->where('id', $id)
                ->where('user_type','student')
                ->first();

            
            $two_step_enabled = $user->is_two_step_enabled;
            Log::warning($two_step_enabled);

            if($two_step_enabled === 'yes'){   
                $otp = mt_rand(100001, 199999);
                Log::warning($otp);
                $update = "update users set otp =$otp where id =$id";
                DB::select($update);
                $responce['name'] = $user->name;
                $responce['is_two_step_enabled'] = $user->is_two_step_enabled;
                $responce['otp'] = $otp;
                return ["data" => $responce, "message" => "otp generated successfully"];

             }

            return ["data" => 'false', "message" => "given credential is wrong for 2 step verfication"];           
           
            

        }
        catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function verifyotp($otp,$id){ 
        try{
            if(!$otp){
                DB::rollBack();
                return ["status"=>false,"message"=>"otp is manidatory"];
            }  
            if(!$id){
                DB::rollBack();
                return ["status"=>false,"message"=>"id is mandatory"];
            }
            $user = User::where('id', $id)->where('otp',$otp)->where('user_type','student')
                ->first();

            // $otp_in_database = $user->otp;  
            // Log::warning($otp_in_database);
            if( $user){
                $responce['id'] = $user->id;
                $responce['name'] = $user->name;
                $responce['email'] = $user->email;
                $responce['phone'] = $user->phone;
                // $responce['otp'] = $user->otp;
                $token = $user->createToken('authToken')->accessToken;
                User::where('id',$id)->update(["otp"=>null]);
                return ["data" => $responce, "message" => "login successfully","Token"=>$token];
            }

            return ["data" => 'false', "message" => "otp is incorrect"];


        }
        catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function getUser($user){
        return User::where('is_deleted','no')->where('user_type','student')->where('is_active','no')->where('email',$user)->orWhere('phone',$user)->first();
    }


    public function twoStepGenerateOtp($name){
        try{
            if(!$name){
                DB::rollBack();
                return ["status"=>false,"message"=>"name is manidatory"];
            }  
            $user = User::where('name', $name)->first();
            if($user){
                $id = $user->id;
                $otp = mt_rand(100001, 199999);
                Log::warning($otp);
                $update = "update users set otp =$otp where id =$id ";
                DB::select($update);
                return ["status"=>true,"id"=>$id,"otp"=>$otp];
            }
            return ["status"=>false,"message"=>"user is not found"];

        }
        catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }





    public function twoStepVerifyOtp($otp,$id){
        try{
            if(!$otp){
                DB::rollBack();
                return ["status"=>false,"message"=>"otp is manidatory"];
            }  
            if(!$id){
                DB::rollBack();
                return ["status"=>false,"message"=>"id is mandatory"];
            }
            $user = User::where('id', $id)->where('otp',$otp)->where('user_type','student')
                ->first();

    
            if( $user){               
                User::where('id',$id)->update(["otp"=>null]);
                return ["status"=>true, "message" => "OTP verified",];
            }

            return ["data" => 'false', "message" => "otp is incorrect"];


        }
        catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }



    public function resetPassword($id,$pass,$c_pass){
        try{
        if(!$id){
            DB::rollBack();
            return ["status"=>false,"message"=>"id is manidatory"];
        }  
        if(!$pass){
            DB::rollBack();
            return ["status"=>false,"message"=>"password is manidatory"];
        }  
        if(!$c_pass){
            DB::rollBack();
            return ["status"=>false,"message"=>"confirm password is manidatory"];
        }  
        
        if ($pass === $c_pass) {
           
                // Assuming you have a User model
                $user = User::find($id);
    
                if (!$user) {
                    return ["status" => false, "message" => "User not found"];
                }
    
                $user->password = Hash::make($pass);//admin@123
                $user->save();
                $user->tokens()->delete();
                return ["status" => true, "message" => "Password updated successfully"];
            } 
            
        return ["status" => false, "message" => "Password and confirm password not matched"];
        
        }
        catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }

    }


    


    public function createUser($request){
        DB::beginTransaction();
       try{
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'password' => Hash::make($request['password']),//admin@123
            'is_active' => $request['is_active'],
            'user_type' => "student",
        ]);

        $Prefix =   "STD";
        $newId = self::generateUniqueAcademicId($Prefix);
        $student = Student::create([
            'gender'=> $request['gender'],
            'user_id'=> $user->id,
            'student_id'=>$newId,
    
        ]);
        DB::commit();
        return ["status"=>true,"data"=>$user,"message"=>"Student User created successfully"];        
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
    private static function generateUniqueAcademicId($prefix)
    {     
        $maxId = Student::where('student_id', 'like', $prefix . '%')->max('student_id');
        $numericPart = $maxId ? (int) substr($maxId, strlen($prefix)) + 1 : 1;
        $newId = $prefix . str_pad($numericPart, 4, '0', STR_PAD_LEFT);
        return $newId;
    }

}