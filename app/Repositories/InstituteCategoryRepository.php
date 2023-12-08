<?php

namespace App\Repositories;


use App\Models\Institute;
use App\Models\InstituteCategory;
use App\Models\InstituteUser;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Repositories\BaseRepositoryInterface;

class InstituteCategoryRepository implements BaseRepositoryInterface
{
    public function all()
    {
    } 
    public function create($name, $filePath)
    {
        Log::warning($name);
        DB::beginTransaction();
        try {
            if (!$name) {
                return ["status" => false, "message" => "name is mandatory"];
            }
            if (!$filePath) {
                return ["status" => false, "message" => "filePath is mandatory"];
            }
            // Create a new InstituteCategory record using RawQue+ry
            $institutecategorys = "select * from  institute_categories where name='$name'and is_deleted='no'";
            $institutecategory = DB::select($institutecategorys);
            $institutecategorycount = count($institutecategory);
            if ($institutecategorycount) {
                return ["status" => false, "message" => "'$name' already exists"];
            }

            $insertQuery = "insert into  institute_categories(name,logo)values('$name','$filePath')";
            Log::warning($insertQuery);
            DB::select($insertQuery);
            DB::commit();
            return ["status" => true, "data" => [$insertQuery], "message" => "$name created successfully"];
        } catch (Exception $e) {
            Log::warning($e);
            DB::rollBack();
            return ["status" => false, "message" => $e->getMessage()];
        }
    }



    //Institutecategory update by id
    public function update($id, $name, $filePath)
{
    DB::beginTransaction();
    try {
        if (!$id) {
            DB::rollBack();
            return ["status" => false, "message" => "id is mandatory"];
            
        }

        $instituteCategory = DB::table('institute_categories')
            ->where('id', $id)
            ->first();

        if (!$instituteCategory) {
            DB::rollBack();
            return ["status" => false, "message" => "Data not available"];
        }

        $updateData = [];

        if ($name) {
            $updateData['name'] = $name;
        }

        if ($filePath) {
            $updateData['logo'] = $filePath;
        }

       $up= DB::table('institute_categories')
            ->where('id', $id)
            ->update($updateData);

        DB::commit();
        return ["status" => true, "message" => "$id updated successfully"];
    } catch (Exception $e) {
        Log::warning($e);
        DB::rollback();
        return ["status" => false, "message" => $e->getMessage()];
    }
}


    //Institutecategory 'is_deleted' update
    public function delete($id)
    {
        DB::beginTransaction();
        try {

            if (!$id) {
                DB::rollBack();
                return ["retun" => false, "message" => "category ID is mandatory"];
            }

            $institutecategorys = "select * from institute_categories where id=$id and is_deleted='no'";
            $institutecategory = DB::select($institutecategorys);
            $institutecategorycount = count($institutecategory);

            if (!$institutecategorycount) {
                DB::rollBack();
                return ["status" => false, "message" => "data not found"];
            }
            $institutecategoryupdate = "update institute_categories set is_deleted='yes' where id=$id ";

            DB::select($institutecategoryupdate);
            DB::commit();
            return ["status" => true, "data" => [], "message" => "{$institutecategory[0]->name} deleted successfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }

    //Institutecategory data list
    public function getAll($search)
    { {
            try {
              
                $institutecategory = InstituteCategory::where('is_deleted', 'no')->when($search, function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })->paginate(60);
                return ["status" => true, "data" => $institutecategory, "message" => " Institute_categories data list successfully"];
            } catch (Exception $e) {
                Log::warning($e);
                return ["status" => false, "message" => $e->getMessage()];
            }
        }
    }
    //Institutecategory status is update 
    public function status($id, $status)
    {
        DB::beginTransaction();
        try {

                 if (!$id) {
                     DB::rollBack();
                     return ["return" => false, "message" => "category ID is mandatory"];
                 }
                 
                 if (!$status) {
                    DB::rollBack();


                    
                    return ["return" => false, "message" => "Status is mandatory"];
                }

            $institutecategorys = "select * from institute_categories where id=$id ";
            $institutecategory = DB::select($institutecategorys);
            $institutecategorycount = count($institutecategory);

            if (!$institutecategorycount) {
                DB::rollBack();
                return ["status" => false, "message" => "data not found"];
            }
            // update Active status using RawQuery
            $updateQuery = "update institute_categories set is_active='$status' where id=$id ";;
            DB::select($updateQuery);
            DB::commit();
            return ["status" => true, "data" => [], "message" => "{$institutecategory[0]->name}  active status is upadated successfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }

    //Institutecategory list by id
    public function listByid($id)
    {
        try {
            $institutecategory = InstituteCategory::where('id', $id)
                ->where('is_deleted', 'no')
                ->first();
    
            if (!$institutecategory) {
                return ["status" => false, "message" => "Id is invalid"];
            }
    
            return ["status" => true, "data" => $institutecategory, "message" => "categoryId data fetched successfully"];
        } catch (Exception $e) {
            Log::warning($e);
            return ["status" => false, "message" => $e->getMessage()];
        }
    }
    
}