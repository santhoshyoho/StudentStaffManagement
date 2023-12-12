<?php

namespace App\Repositories;

use App\Models\InstituteUser;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleGroup;
use App\Models\RolePermission;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserRepository implements BaseRepositoryInterface
{
    public function all()
    {
    }
    public function userCreate($name, $email, $phoneNo, $password, $roleId, $filePath, $instituteId)
    {
        DB::beginTransaction();
        try {
            $createUser = User::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phoneNo,
                'profile_image' => $filePath,
                'user_type' => "institute",
                'password' => Hash::make($password),
            ]);
            DB::commit();
            // $user_id = $createUser->id;
            // $createPlatformUser = InstituteUser::create([
            //     'institute_id' => $instituteId,
            //     'institute_user_id' => $user_id
            // ]);
          
            Log::warning($createUser);
            DB::commit();
            return ["status" => true, "data" => $createUser, "message" => "institute User created sucessfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }



    public function userUpdate($id, $name, $email, $phoneNo, $filePath)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                DB::rollBack();
                return ["status" => false, "message" => "id is mandatory"];
            }
            $userExists = User::where('id', $id)
                ->where('is_deleted', 'no')
                ->exists();
            if (!$userExists) {
                DB::rollBack();
                return ["status" => false, "message" => "invalid data"];
            }
            if ($name) {
                User::where('id', $id)
                    ->update(['name' => $name]);
            }
            if ($email) {
                User::where('id', $id)
                    ->update(['email' => $email]);
            }
            if ($phoneNo) {
                User::where('id', $id)
                    ->update(['phone' => $phoneNo]);
            }
            if ($filePath) {
                User::where('id', $id)
                    ->update(['profile_image' => $filePath]);
            }
            Log::warning($name);
            DB::commit();
            return ["status" => true, "message" => "users updated sucessfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }

  

    //delete user,instituteUser,roleGroup function
    public function userDelete($id)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                DB::rollBack();
                return ["status" => false, "message" => "user id is mandatory"];
            }
            $roles = User::where('id', $id)
                ->where('is_deleted', 'no')
                ->first();
           
            $deleteQuerryUser = User::where('id', $id)
                ->update(['is_deleted' => 'yes']);
            InstituteUser::where('institute_user_id', $id)->delete();

            Log::warning($deleteQuerryUser);
            DB::commit();
            return ["status" => true, "data" => $deleteQuerryUser, "message" => "institute user deleted sucessfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }

    //user status change function
    public function userStatus($id, $status)
    { {
            DB::beginTransaction();
            try {
                if (!$id) {
                    DB::rollBack();
                    return ["status" => false, "message" => "id is mandatory"];
                }
                if (!$status) {
                    DB::rollBack();
                    return ["status" => false, "message" => "status field is mandatory"];
                }
                $user = User::where('id', $id)
                    ->where('is_deleted', 'no')
                    ->get();
                $count = count($user);
                if (!$count) {
                    DB::rollBack();
                    return ["status" => false, "message" => "invalid data"];
                }
                $updateQuerry = User::where('id', $id)
                    ->update(['is_active' => $status]);
                Log::warning($updateQuerry);
                DB::commit();
                return ["status" => true, "data" => $updateQuerry, "message" => "status updated sucessfully"];
            } catch (Exception $th) {
                Log::warning($th);
                DB::rollBack();
                return ["status" => false, "message" => $th->getMessage()];
            }
        }
    }

    //list all users,institute user,rolegroup
    public function userListAll($search)
    {
        DB::beginTransaction();
        try {
            $user = user::where('is_deleted', 'no')->with('instituteUser', 'roleGroup')->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            })->paginate(50);
            Log::warning($user);
            DB::commit();
            return ["status" => true, "data" => $user, "message" => "listed sucessfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }










   
}