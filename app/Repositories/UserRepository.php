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
            $user_id = $createUser->id;
            $createPlatformUser = InstituteUser::create([
                'institute_id' => $instituteId,
                'institute_user_id' => $user_id
            ]);
            $createRolegroup = RoleGroup::create([
                'role_id' => $roleId,
                'user_id' => $user_id,
            ]);
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

    //update role id using user id function
    public function roleUpdate($userId, $roleId)
    {
        DB::beginTransaction();
        try {
            if (!$userId) {
                DB::rollBack();
                return ["status" => false, "message" => "User Id is mandatory"];
            }
            if (!$roleId) {
                DB::rollBack();
                return ["status" => false, "message" => "Role Id is mandatory"];
            }
            $updateQuerry = RoleGroup::where('user_id', $userId)->update(['role_id' => $roleId]);
            Log::warning($updateQuerry);
            DB::commit();
            return ["status" => true, "data" => $updateQuerry, "message" => "Role updated sucessfully"];
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
            $userId = $roles ? $roles->id : null;
            if (!$roles) {
                DB::rollBack();
                return ["status" => false, "message" => "invalid data"];
            }
            $deleteQuerryUser = User::where('id', $id)
                ->update(['is_deleted' => 'yes']);
            InstituteUser::where('institute_user_id', $id)->delete();
            RoleGroup::where('user_id', $userId)->delete();
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










    public function permissionCreate($screen, $module, $name)
    {
        DB::beginTransaction();
        try {
            if (!$screen) {
                DB::rollBack();
                return ["status" => false, "message" => "screen is mandatory"];
            }
            if (!$module) {
                DB::rollBack();
                return ["status" => false, "message" => "module is mandatory"];
            }
            if (!$name) {
                DB::rollBack();
                return ["status" => false, "message" => "name is mandatory"];
            }
            $createPermission = Permission::create([
                'screen' => $screen,
                'module' => $module,
                'name' => $name,
                'type' => "institute",
            ]);
            Log::warning($createPermission);
            DB::commit();
            return ["status" => true, "data" => $createPermission, "message" => "created sucessfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }


    public function permissionUpdate($id, $screen, $module, $name)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                DB::rollBack();
                return ["status" => false, "message" => "id is mandatory"];
            }
            $permission = Permission::find($id);
            if (!$permission) {
                return ["status" => false, "message" => "Id not found"];
            }
            if ($screen) {
                Permission::where('id', $id)
                    ->update(['screen' => $screen]);
            }
            if ($module) {
                Permission::where('id', $id)
                    ->update(['module' => $module]);
            }
            if ($name) {
                Permission::where('id', $id)
                    ->update(['name' => $name]);
            }

            Log::warning($permission);
            DB::commit();
            return ["status" => true, "data" => $permission, "message" => "users updated sucessfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }

    //delete permission in permission table
    public function permissionDelete($id)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                DB::rollBack();
                return ["status" => false, "message" => "id is mandatory"];
            }
            $permissions = Permission::find($id);
            if (!$permissions) {
                return ["status" => false, "message" => "Permission Id not found"];
            }
            Permission::where('id', $id)->delete();
            Log::warning($permissions);
            DB::commit();
            return ["status" => true, "data" => $permissions, "message" => "deleted sucessfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }

    //list particular permissions in permission table
    public function permissionListById($id)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                DB::rollBack();
                return ["status" => false, "message" => "id is mandatory"];
            }
            $permissions = Permission::find($id);
            if (!$permissions) {
                return ["status" => false, "message" => "Permission Id not found"];
            }
            $listPermission = Permission::where('id', $id)->get();
            Log::warning($listPermission);
            DB::commit();
            return ["status" => true, "data" => $listPermission, "message" => "particular id fetched sucessfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }

    //listAll permissions in permission table
    public function permissionGetAll($search)
    {
        DB::beginTransaction();
        try {
            $platform = Permission::when($search, function ($query) use ($search) {
                $query->where('screen', 'like', '%' . $search . '%')
                    ->orWhere('module', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%');
            })->paginate(10);
            Log::warning($platform);
            DB::commit();
            return ["status" => true, "data" => $platform, "message" => "listed sucessfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }

    //permission get by using user_id
    public function permissionGetByUserId($id)
    {
        DB::beginTransaction();
        try {
            $permissions = User::find($id);
            if (!$permissions) {
                DB::rollBack();
                return ["status" => false, "message" => "User Id not found"];
            }
            $user = User::where('id', $id)->get();
            $rolegroup = RoleGroup::where('user_id', $user[0]->id)->get();
            $rolePermissions = RolePermission::where('role_id', $rolegroup[0]->role_id)
                ->with('permissions')->get();
            Log::warning($rolePermissions);
            DB::commit();
            return ["status" => true, "data" => $rolePermissions, "message" => "fetched successfully"];
        } catch (Exception $th) {
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }






    public function createRole($name, $permissionId, $instituteId)
    {
        DB::beginTransaction();
        try {
            if (!$name) {
                DB::rollBack();
                return ["status" => false, "message" => "name is mandatory"];
            }
            if (!$permissionId) {
                DB::rollBack();
                return ["status" => false, "message" => "Permission Id is mandatory"];
            }
            if (!$instituteId) {
                DB::rollBack();
                return ["status" => false, "message" => "Institute Id is mandatory"];
            }
            $roleExists = Role::where('name', $name)
                ->where('is_deleted', 'no')
                ->where('is_institute', 'yes')->exists();

            if (!$roleExists) {
                $createQuerry = Role::create([
                    'name' => $name,
                    'is_institute' => "yes",
                    'institute_id' => $instituteId,
                ]);
                $this->generateRolePermission($createQuerry->id, $permissionId);
                $data = Role::where('is_deleted', 'no')->where('is_institute', 'yes')->with('rolePermission')->get();
                Log::warning($createQuerry);
                DB::commit();
                return ["status" => true, "data" => $createQuerry, "message" => "role created sucessfully"];
            }
            DB::rollBack();
            return ["status" => false, "message" => "Data is already exist"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }


    public function generateRolePermission($roleId, array $permissionId) //[1,2,3]
    {
        $roleArray = [];
        foreach ($permissionId as $key => $Id) {
            $permissionExists = RolePermission::where('role_id', $roleId)
                ->where('permission_id', $Id)->exists();
            if (!$permissionExists) { //con === 0
                Log::warning($Id);
                $role = [
                    "role_id" => $roleId,
                    "permission_id" => $Id,
                ];
                array_push($roleArray, $role);
            }
        }
        RolePermission::insert($roleArray);
    }






    //update Role table and Role_permission table
    public function updateRole($id, $name, $permissionId)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                DB::rollBack();
                return ["status" => false, "message" => "id is mandatory"];
            }
            $roleExists = Role::where('id', $id)
                ->where('is_institute', 'yes')
                ->where('is_deleted', 'no')
                ->exists();
            if (!$roleExists) {
                DB::rollBack();
                return ["status" => false, "message" => "invalid data"];
            }
            if ($name) {
                $update = Role::where('id', $id)
                    ->update(['name' => $name]);
            }
            if ($permissionId) {
                RolePermission::where('role_id', $id)->whereNotIn('id', $permissionId)->delete();
                $this->generateRolePermission($id, $permissionId);
            }
            $data = Role::where('is_deleted', 'no')->where('is_institute', 'yes')->with('rolePermission')->get();
            Log::warning($update);
            DB::commit();
            return ["status" => true, "data" => $roleExists, "message" => "role updated sucessfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }

    //delete roles soft delete and hard delete Role_permission table
    public function deleteRole($id)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                DB::rollBack();
                return ["status" => false, "message" => "id is mandatory"];
            }
            $roles = Role::where('id', $id)
                ->where('is_institute', 'yes')
                ->where('is_deleted', 'no')
                ->get();
            $count = count($roles);
            if (!$count) {
                DB::rollBack();
                return ["status" => false, "message" => "invalid data"];
            }
            $deleteQuerry = Role::where('id', $id)
                ->update(['is_deleted' => 'yes']);
            RolePermission::where('role_id', $id)->delete();
            Log::warning($deleteQuerry);
            DB::commit();
            return ["status" => true, "data" => $deleteQuerry, "message" => "role deleted sucessfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }

    //status update in Role Table
    public function roleStatus($id, $status)
    {
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
            $roles = Role::where('id', $id)
                ->where('is_deleted', 'no')
                ->where('is_institute', 'yes')
                ->get();
            $count = count($roles);
            if (!$count) {
                DB::rollBack();
                return ["status" => false, "message" => "invalid data"];
            }
            $updateQuerry = Role::where('id', $id)
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



    //list all role and role_permission table
    public function showRoles($search)
    {
        try {
            $roles = Role::where('is_deleted', 'no')->where('is_institute', 'yes')->get();
            $roleData = [];
            foreach ($roles as $role) {
                $userId = RoleGroup::where('role_id', $role->id)->pluck('user_id')->toArray();
                $roleGroupData = [
                    'name' => $role->name,
                    'user_id' => $userId,
                ];
                $userData = User::whereIn('id', $userId)->get();
                $roleData[] = [
                    'role' => $role,
                    'role_group' => $roleGroupData,
                    'users' => $userData,
                ];
            }
            Log::warning($roleData);
            DB::commit();
            return ["status" => true, "data" => $roleData, "message" => "listed successfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }




    //particular id details show in role and role_permission table
    public function getUserRoleById($id)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                DB::rollBack();
                return ["status" => false, "message" => "id is mandatory"];
            }
            $role = Role::find($id);
            if (!$role) {
                return ["status" => false, "message" => "Role Id not found"];
            }
            $listQuerry = Role::where('is_deleted', 'no')->where('is_institute', 'yes')->where('id', $id)->with('rolePermission.permissions')->get();
            Log::warning($listQuerry);
            DB::commit();
            return ["status" => true, "data" => $listQuerry, "message" => "particular id fetched sucessfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }

}