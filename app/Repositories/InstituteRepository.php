<?php

namespace App\Repositories;

use App\Models\Institute;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InstituteRepository implements BaseRepositoryInterface
{
    public function all()
    {
    }
    public function instituteCreate($request,$filePath,$imagePaths){
        DB::beginTransaction();
        try{
            $imagePaths = json_encode($imagePaths) ? json_encode($imagePaths) : "[]";
            $Institute = Institute::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'logo' => $filePath,
                'image' => $imagePaths,
                'description'=> $request['description'],
                'registered_date'=> $request['registered_date'],
                'institute_category_id'=> $request['institute_category_id'],
                'institute_type'=> $request['institute_type_id'],
                'address_line_1'=> $request['address_1'],
                'address_line_2'=> $request['address_2'],
                'city'=>$request['city'],
                'state'=> $request['state'],
                'pincode'=> $request['pincode'],
                'phone'=> $request['phone'],
                'alternate_number'=> $request['alternate_number'],
                'official_website'=> $request['official_website'],
                'facebook'=> $request['facebook'],
                'linkedin'=> $request['linkedin'],
                'instagram'=> $request['instagram'],
                'twitter'=> $request['twitter'],
                'pinterest'=> $request['pinterest'] 
            ]);
            Log::warning($Institute);
            DB::commit();
            return ["status" => true, "message" => "Institute created successfully"];
        } catch (Exception $e) {
            DB::rollback();
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

     //UPDATE A INSTITUTEDETAILS
     public function updateInstitute($data, $filePath, $fileImg)
     {
        DB::beginTransaction();
        try {
            if (!isset($data['id'])) {
                DB::rollBack();
                return ["status" => false, "message" => "ID is mandatory"];
            }
 
            $updatableFields = [
                 'name',
                 'email',
                 'type',
                 'description',
                 'registered_date',
                 'institute_category_id',
                 'institute_type',
                 'address_line_1',
                 'address_line_2',
                 'city',
                 'state',
                 'pincode',
                 'phone',
                 'alternate_number',
                 'official_website',
                 'facebook',
                 'linkedin',
                 'instagram',
                 'twitter',
                 'pinterest',
            ];

            $instituteData = [];
 
            foreach ($updatableFields as $field) {
                if (isset($data[$field])) {
                $instituteData[$field] = $data[$field];
                }
            }

            $institute = Institute::where('id', $data['id'])->where('is_deleted', 'no')->first();
 
            if (!$institute) {
                DB::rollback();
                return ["status" => false, "message" => "id not found"];
            }

            if (!empty($filePath)) {
                $institute->logo = $filePath;
            }
 
            if (!empty($fileImg)) {
                $institute->image = $fileImg; 
            }
            $institute->update($instituteData);
            DB::commit();
            return ["status" => true, "message" => "Institute updated successfully"];
        } catch (Exception $e) {
            DB::rollback();
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    public function deleteInstitute($id){
        DB::beginTransaction();
        try{
            if (!$id) {
                DB::rollBack();
                return ["status" => false, "message" => "id is mandatory"];
            }
            $Institute = Institute::where('id', $id)
                ->where('is_deleted', 'no')
                ->first();
                $userId = $Institute ? $Institute->id : null;
            if (!$Institute) {
                DB::rollBack();
                return ["status" => false, "message" => "invalid data"];
            }
            $deleteQuerry = Institute::where('id', $id)
                ->update(['is_deleted' => 'yes']);
            return ["status" => true, "data" => $deleteQuerry, "message" => "Institute deleted sucessfully"];
        }
            catch (Exception $th) {
                Log::warning($th);  
                DB::rollBack();
                return ["status" => false, "message" => $th->getMessage()];
        }
    }

    public function instituteStatus($id,$status)
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
            $institute = Institute::find($id);
            if (!$institute) {
                return ["status" => false, "message" => "Id not found"];
            }
            $updateQuerry = Institute::where('id', $id)
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

    public function instituteListById($id){
        DB::beginTransaction();
        try {
            if (!$id) {
                DB::rollBack();
                return ["status" => false, "message" => "id is mandatory"];
            }
        
            $institutecategory = Institute::where('id', $id)->where('is_deleted', 'no')->get();
        
            if ($institutecategory->isEmpty()) {
                DB::rollBack();
                return ["status" => false, "message" => "Id is invalid"];
            }
        
            DB::commit();
            return ["status" => true, "data" => $institutecategory, "message" => "categoryId data fetched successfully"];
        } catch (Exception $e) {
            Log::warning($e);
            DB::rollBack();
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

     

}