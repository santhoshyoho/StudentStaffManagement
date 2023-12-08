<?php

namespace App\Repositories;

use App\Models\InstituteStudent;
use App\Models\InstituteUser;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentRepository implements BaseRepositoryInterface
{
    public function all()
    {
    } 
    public function studentCreate($name, $email, $phoneNo, $dob, $gender, $address_line_1, $address_line_2, $city, $state, $pincode, $qualification, $filePath, $password)
    {
        try {
            $id = Auth::user()->id;
            $instituteUser = InstituteUser::where('institute_user_id', $id)->first();
            $instituteId = $instituteUser->institute_id;


            $userCreate = User::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phoneNo,
                'password' => Hash::make($password),
                'profile_image' => $filePath,
                'user_type' => "student",
                'is_active' => "yes",

            ]);
            $userId = $userCreate->id;

            $studentCreate = Student::create([
                'user_id' => $userId,
                'dob' => $dob,
                'gender' => $gender,
                'address_line_1' => $address_line_1,
                'address_line_2' => $address_line_2,
                'city' => $city,
                'state' => $state,
                'pincode' => $pincode,
                'qualification' => $qualification

            ]);
            $studentId = $studentCreate->student_id;


            $instituteStudentCreate = InstituteStudent::create([
                'institute_id' => $instituteId,
                'student_id' => $studentId
            ]);
            return ["status" => true,  "message" => "institute Student created sucessfully"];
        } catch (Exception $th) {
            return ["status" => false, "message" => $th->getMessage()];
        }
    }


    public function updateStudent($dob, $gender, $address_line_1, $address_line_2, $city, $state, $pincode, $qualification)
    {
        try {
            $id = Auth::user()->id;
            $student = Student::where('user_id', $id)->first();
            $stuId = $student->student_id;


            if (!$stuId) {
                return ["status" => false, "message" => "StudentId is mandatory"];
            }
            if ($dob) {
                Student::where('student_id', $stuId)
                    ->update(['dob' => $dob]);
            }
            if ($gender) {
                Student::where('student_id', $stuId)
                    ->update(['gender' => $gender]);
            }
            if ($address_line_1) {
                Student::where('student_id', $stuId)
                    ->update(['address_line_1' => $address_line_1]);
            }
            if ($address_line_2) {
                Student::where('student_id', $stuId)
                    ->update(['address_line_2' => $address_line_2]);
            }
            if ($city) {
                Student::where('student_id', $stuId)
                    ->update(['city' => $city]);
            }
            if ($state) {
                Student::where('student_id', $stuId)
                    ->update(['state' => $state]);
            }
            if ($pincode) {
                Student::where('student_id', $stuId)
                    ->update(['pincode' => $pincode]);
            }
            if ($qualification) {
                Student::where('student_id', $stuId)
                    ->update(['pincode' => $qualification]);
            }

            return ["status" => true,  "message" => "student updated successfully"];
        } catch (Exception $th) {
            return ["status" => false, "message" => $th->getMessage()];
        }
    }


    public function statusUpdate($status)
    {
        try {
            $id = Auth::user()->id;
            if (!$id) {
                return ["status" => false, "message" => "id is mandatory"];
            }
            if (!$status) {
                return ["status" => false, "message" => "status field is mandatory"];
            }
            $user = User::where('id', $id)
                ->where('is_deleted', 'no') 
                ->get();
            if (!$user) { 
                return ["status" => false, "message" => "invalid data"];
            }
            $updateQuerry = User::where('id', $id)
                ->update(['is_active' => $status]);
            return ["status" => true, "data" => $updateQuerry, "message" => "status updated sucessfully"];
        } catch (Exception $th) {
            return ["status" => false, "message" => $th->getMessage()];
        }
    }


    public function listById($stuId)
    {
        try {
            // $id = Auth::user()->id;
            // $student = Student::where('user_id', $id)->first();
            // $stuId = $student->student_id;


            if (!$stuId) {
                return ["status" => false, "message" => "StudentId is mandatory"];
            }
            $student = Student::where('student_id', $stuId)->where('is_deleted', 'no')->get();
            return ["status" => true, "student" => $student,  "message" => "student listed successfully"];
        } catch (Exception $th) {

            return ["status" => false, "message" => $th->getMessage()];
        }
    }



    public function searchAndPagination($search)
    {
        try {
            $user = User::where('is_deleted', 'no')->with('student')->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            })->paginate(50);


            return ["status" => true, "data" => $user, "message" => "listed sucessfully"];
        } catch (Exception $th) {

            return ["status" => false, "message" => $th->getMessage()];
        }
    }


    public function getAll($id)
    {
        try {
            if (!$id) {
                return ["status" => false, "message" => "id is mandatory"];
            }
            // $id = Auth::user()->id;

            $instituteId = InstituteUser::where('institute_user_id', $id)->pluck('institute_id');
            $institute = InstituteStudent::where('institute_id', $instituteId)->pluck('student_id');
            $user = Student::whereIn('student_id',$institute)->with('user')->get();
            return ["status" => true, "data" => $user, "message" => "listed sucessfully"];


            
        } catch (Exception $th) {

            return ["status" => false, "message" => $th->getMessage()];
        }
    }

}