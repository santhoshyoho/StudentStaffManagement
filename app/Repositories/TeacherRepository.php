<?php

namespace App\Repositories;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherRepository implements BaseRepositoryInterface
{
    public function all()
    {
    } 
    public function staffRegister($request)
    {

        
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'password' => Hash::make($request['password']), //admin@123
            // 'profile_image' => $request['profile_image'],
            'user_type' => 'Staff',
            'is_active' => 'yes'
        ]);
        DB::commit();

        $academicPrefix = "STAFFID";
        $newId = self::generateUniqueAcademicId($academicPrefix);


        $staff = Teacher::create([
            'user_id' => $user->id,
            'staff_id' => $newId,
            'dob' => $request['email'],
            'position' => $request['position'],
            'type' => $request['type'],
            'gender' => isset($request['gender']) ? $request['gender'] : null,
            'qualification' => isset($request['qualification']) ? $request['qualification'] : null,
            'address_line_1' => isset($request['address_line_1']) ? $request['address_line_1'] : null,
            'address_line_2' => isset($request['address_line_2']) ? $request['address_line_2'] : null,
            'city' => isset($request['city']) ? $request['city'] : null,
            'state' => isset($request['state']) ? $request['state'] : null,
            'pincode' => isset($request['pincode']) ? $request['pincode'] : null,
            'qualification'  => isset($request['qualification']) ? $request['qualification'] : null,
            'work_experience' => isset($request['work_experience']) ? $request['work_experience'] : null,
            'specialization' => isset($request['specialization']) ? $request['specialization'] : null,
        ]);
      



    
        DB::commit();
        return ["status" => true, "data" => 'Staff created successfully'];
    }


}