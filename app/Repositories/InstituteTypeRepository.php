<?php

namespace App\Repositories;

use App\Models\InstituteType;
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

class InstituteTypeRepository implements BaseRepositoryInterface
{
    public function all()
    {
    }
    public function create($name)
    {
        DB::beginTransaction();
        try {
            if (!$name) {
                DB::rollback(); 
                return ["status" => false, "message" => "name is mandatory"];
            }
            $institutetypes = "select * from  institute_types where name='$name'and is_deleted='no'";
            $institutetype = DB::select($institutetypes);
            $institutetypecount = count($institutetype);
            if ($institutetypecount) {
                return ["status" => false, "message" => "'$name' already exists"];
            }

            // Create a new InstituteCategory record using RawQuery
            $insertQuery = "insert into  institute_types(name)values('$name')";
            DB::select($insertQuery);
            DB::commit();
            return ["status" => true, "data" => [], "message" => "$name added successfully"];
        } catch (Exception $e) {
            Log::warning($e);
            DB::rollBack();
            return ["status" => false, "message" => $e->getMessage()];
        }
    }


    //Institutetype update by id   
    public function update($id, $name)
    {


        DB::beginTransaction();
        try {
            if (!$id) {
                return ["status" => false, "message" => "id is mandatory"];
            }
           

            // update a new InstituteCategory record using RawQuery

            $institutetypes = "select * from institute_types where id=$id ";
            $institutetype = DB::select($institutetypes);
            $institutetypecount = count($institutetype);
            if (!$institutetypecount) {
                DB::rollBack();
                return ["status" => false, "message" => "data not available"];
            }

            $updateconditions = [];
            $conditions = "";
            if ($name) {
                $updateconditions[] = "name='$name'";
            }
            $conditions = implode(" ", $updateconditions);
            $updateQuery = "update institute_types set $conditions where id=$id";
            $insertOutput = DB::select($updateQuery);
            DB::commit();
            return ["status" => true, "message" => "$id updated successfully"];
        } catch (Exception $e) {
            Log::warning($e);
            DB::rollBack();
            return ["status" => false, "message" => $e->getMessage()];
        }
    }


    //Institutetype 'is_deleted' update
    public function delete($id)
    {

        DB::beginTransaction();
        try {

            if (!$id) {
                DB::rollBack();
                return ["retun" => false, "message" => "category ID is mandatory"];
            }

            $institutetypes = "select * from institute_types where id=$id and is_deleted='no'";
            $institutetype = DB::select($institutetypes);
            $institutetypecount = count($institutetype);

            if (!$institutetypecount) {
                DB::rollBack();
                return ["status" => false, "message" => "data not found"];
            }
            $institutetypeupdate = "update institute_types set is_deleted='yes' where id=$id ";
            DB::select($institutetypeupdate);
            DB::commit();
            return ["status" => true, "data" => [], "message" => "{$institutetype[0]->name} deleted successfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }

    //Institutetype data list
    public function getAll($search)
    {
        try {
            
            $institutetype = InstituteType::where('is_deleted', 'no')->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->paginate(40);
            return ["status" => true, "data" => $institutetype, "message" => " Institute_types data list  successfully"];
        } catch (Exception $e) {
            Log::warning($e);
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    //Institutetype status is update 
    public function status($id, $status)
    {


        DB::beginTransaction();
        try {

            if (!$id) {
                DB::rollBack();
                return ["retun" => false, "message" => "category ID is mandatory"];
            }
            if (!$status) {
                DB::rollBack();
                return ["retun" => false, "message" => "category status is mandatory"];
            }

            $institutetypes = "select * from institute_types where id=$id ";
            $institutetype = DB::select($institutetypes);
            $institutetypecount = count($institutetype);

            if (!$institutetypecount) {
                DB::rollBack();
                return ["status" => false, "message" => "data not found"];
            }

            // update Active status using RawQuery
            $updateQuery = "update institute_types set is_active='$status' where id=$id ";;
            DB::select($updateQuery);
            DB::commit();
            return ["status" => true, "data" => [], "message" => "{$institutetype[0]->name}  active status is upadated successfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }

    //Institutetype list by id
    public function listById($id)
    {

        DB::beginTransaction();

        try {

            if (!$id) {
                DB::rollBack();
                return ["status" => false, "message" => "ID is mandatory"];
            }
            $institutetypes = "select * from institute_types where id=$id and is_deleted='no'";
            $institutetype = DB::select($institutetypes);
            $institutetypecount = count($institutetype);
            if (!($institutetypecount)) {
                DB::rollBack();
                return ["status" => false, "message" => "Id is invalid"];
            }
            DB::commit();
            return ["status" => true, "data" =>  $institutetype, "message" => "categoryId data fetched successfully"];
        } catch (Exception $e) {
            Log
            ::warning($e);
            DB::rollBack();
            return ["status" => false, "message" => $e->getMessage()];
        }
    }
}