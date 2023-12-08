<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $repo;
    protected $img;
    public function __construct(UserRepository $repo,ImageRepository $img)
    {
        $this->repo = $repo;
        $this->img = $img;
    }

    
    public function createUser(Request $req)
    {

        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'password' => 'required',
            'role_id' => 'required',
            'institute_id' => 'required',
            'profile_image' => 'image', // adjust the image validation rules as needed
        ];
    
        // Validate the request
        $validator = Validator::make($req->all(), $rules);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }

        
        $name = $req->input('name');
        $email = $req->input('email');
        $phoneNo = $req->input('phone');
        $password = $req->input('password');
        $roleId = $req->input('role_id');
        $instituteId = $req->input('institute_id');
        $userImgPath = "assets/user/profile";
        $filePath = "";
        if ($req->hasFile('profile_image')) {
            $filePath = $this->img->uploadImage($req->file('profile_image'), $userImgPath);
        }
        return $this->repo->userCreate($name,$email,$phoneNo,$password,$roleId,$filePath,$instituteId);

    }






     //update user,platformUser details
     public function updateUser(Request $request){
        Log::warning($request);
        $id = $request->input('id');
        $name = $request->input('name');
        $email = $request->input('email');
        $phoneNo = $request->input('phone');
        $userImgPath = "assets/user/profile";
        $filePath = "";
        if ($request->hasFile('profile_image')) {
            $filePath = $this->img->uploadImage($request->file('profile_image'), $userImgPath);
        }    
        return $this->repo->userUpdate($id,$name,$email,$phoneNo,$filePath);
    }

    //update role id using user id function
    public function updateRole(Request $request){
        Log::warning($request);
        $userId = $request->input('user_id');
        Log::warning($userId);
        $roleId = $request->input('role_id');
        return $this->repo->roleUpdate($userId,$roleId);
    }

    //delete user,platformUser,roleGroup function
    public function deleteUser(Request $request){
        Log::warning($request);
        $id = $request->input('id');
        return $this->repo->userDelete($id);
    }

    //user status change function
    public function userStatus(Request $request){
        Log::warning($request);
        $id = $request->input('id');
        $status = $request->input('is_active');
        return $this->repo->userStatus($id,$status);
    }

    //list all users,platform user,rolegroup
    public function showAllUser(Request $request){
        Log::warning($request);
        $search=$request->input('search','');
        return $this->repo->userListAll($search);
    }









    public function createPermission(Request $request){
       
        $screen = $request->input('screen');
        $module = $request->input('module');
        $name = $request->input('name');
        return $this->repo->permissionCreate($screen,$module,$name);
    }

    //update permissions in permission table
    public function updatePlatform(Request $request){
        Log::warning($request);
        $id = $request->input('id');
        $screen = $request->input('screen');
        $module = $request->input('module');
        $name = $request->input('name');
        return $this->repo->permissionUpdate($id,$screen,$module,$name);
    }

     //delete permissions in permission table
     public function deletePermission(Request $request){
        Log::warning($request);
        $id = $request->input('id');
        return $this->repo->permissionDelete($id);
    }

    //list particular permissions in permission table
    public function listByIdPermission(Request $request){
        Log::warning($request);
        $id = $request->input('id');
        return $this->repo->permissionListById($id);
    }

    //listAll permissions in permission table
    public function getAllPermission(Request $request){
        Log::warning($request);
        $search=$request->input('search','');
        return $this->repo->permissionGetAll($search);
    }

    //permission get by using user_id
    public function getPermissionByUserId(Request $request){
        Log::warning($request);
        $id = $request->input('id');
        return $this->repo->permissionGetByUserId($id);
    }
















    public function create(Request $request){
        $name = $request->input('name');
        $permissionId = $request->input('permissions');
        $instituteId = $request->input('institute_id');
        return $this->repo->createRole($name,$permissionId,$instituteId);
    }


    public function update(Request $request){
        Log::warning($request);
        $id = $request->input('id');
        $name = $request->input('name');
        $permissionId = $request->input('permissions');
        return $this->repo->updateRole($id,$name,$permissionId);
    }

    //delete role soft delete and hard delete Role_permission table
    public function delete(Request $request){
        Log::warning($request);
        $id = $request->input('id');
        return $this->repo->deleteRole($id);
    }

    //status update in Role Table
    public function status(Request $request){
        Log::warning($request);
        $id = $request->input('id');
        $status = $request->input('is_active');
        return $this->repo->roleStatus($id,$status);
    }

    

    //list all role and role_permission table
    public function showRoles(Request $request){
        Log::warning($request);
        $search=$request->input('search','');
        return $this->repo->showRoles($search);
    }


    //particular id details show in role and role_permission table
    public function getUserRoleById(Request $request){
        Log::warning($request);
        $id = $request->input('id');
        return $this->repo->getUserRoleById($id);
    }



}
