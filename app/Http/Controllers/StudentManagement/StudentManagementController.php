<?php

namespace App\Http\Controllers\StudentManagement;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Repositories\StudentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentManagementController extends Controller
{
    protected $repo;
    protected $img;
    public function __construct(StudentRepository $repo, ImageRepository $img)
    {
        $this->repo = $repo;
        $this->img = $img;
    }


    public function create(Request $req)
    {

        $rule = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'password' => 'required',
            // 'profile_image' => 'image',
            // 'dob' => 'required',
            // 'gender' => 'required',
            // 'address_line_1' =>'required',
            // 'address_line_2' =>'required',
            // 'qualification' => 'required',   
            // 'institute_id' => 'required',
            // 'password' => 'required',
        ];
        $validation = Validator::make($req->all(),$rule);
        
        if($validation->fails()){
            return response()->json(['status' => false, 'message' => $validation->errors()], 422);
        }

        $name = $req->input('name');
        $email = $req->input('email');
        $phoneNo = $req->input('phone');
        $dob = $req->input('dob');
        $password = $req->input('password') ;
        $gender = $req->input('gender');
        $address_line_1 = $req->input('address_line_1');
        $address_line_2 = $req->input('address_line_2');
        $city = $req->input('city');
        $state = $req->input('state');
        $pincode = $req->input('pincode');
        $qualification = $req->input('qualification');
        // $instituteId = $req->input('institute_id');
        $userImgPath = "assets/user/profile";
        $filePath = "";
        if ($req->hasFile('profile_image')) {
            $filePath = $this->img->uploadImage($req->file('profile_image'), $userImgPath);
        }
        return $this->repo->studentCreate($name,$email,$phoneNo,$dob,$gender,$address_line_1,$address_line_2,$city,$state,$pincode,$qualification,$filePath,$password);
    }


    public function updateStudent(Request $req){
        // $stuId =$req->input('student_id');
        $dob = $req->input('dob');;
        $gender = $req->input('gender');
        $address_line_1 = $req->input('address_line_1');
        $address_line_2 = $req->input('address_line_2');
        $city = $req->input('city');
        $state = $req->input('state');
        $pincode = $req->input('pincode');
        $qualification = $req->input('qualification');
        return $this->repo->updateStudent($dob,$gender,$address_line_1,$address_line_2,$city,$state,$pincode,$qualification);
    }

    public function statusUpdate(Request $req){
        // $id = $req->input('id');
        $status = $req->input('status');
        return $this->repo->statusUpdate($status);
    }
    public function listById(Request $req){
        $stuId =$req->input('student_id');
        return $this->repo->listById($stuId);        
    }

    public function searchAndPagination(Request $req){
        $search=$req->input('search','');
        return $this->repo->searchAndPagination($search);

    }


    public function getAll(Request $req){
        $id = $req->input('id');
        return $this->repo->getAll($id); 
    }

}
