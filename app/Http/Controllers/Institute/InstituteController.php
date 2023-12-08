<?php

namespace App\Http\Controllers\Institute;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Repositories\InstituteRepository;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class InstituteController extends Controller{
    protected $repo;
    protected $img;

    public function __construct(InstituteRepository $repo, ImageRepository $img)
    {
        $this->repo = $repo;
        $this->img = $img;
    }
    public function createInstitute(Request $request){
        
        $InstituteImgPath = "assets/institute/logo";
            $filePath = "";
            if ($request->hasFile('logo')) {
                $filePath = $this->img->uploadImage($request->file('logo'), $InstituteImgPath);
            }
            $imagePaths=[];
            $imageDirectory = "images";//image storing folder
            if($request->hasFile('image')){ //col name
                $productFiles = $request->file('image');//col name
                foreach($productFiles as $key => $value){
                    $imagePaths[] = $this->img->uploadImage($value,$imageDirectory);
                }
            }
            $validation = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'email' => 'required|email|unique:institutes',
                'logo' => 'required',
                'description' => 'required',
                'registered_date' => 'date',
                'institute_category_id' => 'required|exists:institute_categories,id',
                'institute_type' => 'required|exists:institute_types,id',
                'phone' => 'required',
            ]);
            if ($validation->fails()) {
                return response()->json(['errors' => $validation->errors()], 422);
            }
        return $this->repo->instituteCreate($request->all(),$filePath,$imagePaths);
    }

    //UPDATE AN INSTITUTE
    public function updateInstitute(Request $request)
    {
        $data = $request->all();
        $InstituteImgPath = "assets/institute/logo";
        $filePath = "";
        if ($request->hasFile('logo')) {
            $filePath = $this->img->uploadImage($request->file('logo'), $InstituteImgPath);
        }
        $imagePaths=[];
        $imageDirectory = "images";//image storing folder
        if($request->hasFile('image')){ //col name
            $productFiles = $request->file('image');//col name
            foreach($productFiles as $key => $value){
                $imagePaths[] = $this->img->uploadImage($value,$imageDirectory);
            }
        }
        return $this->repo->updateInstitute($data, $filePath, $imagePaths);
    }

    public function deleteInstitute(Request  $request){
        $id = $request->input('id');
        return $this->repo->deleteInstitute($id);
    }

    public function instituteStatus(Request $request){
        $id = $request->input('id');
        $status = $request->input('is_active');
        return $this->repo->instituteStatus($id,$status);
    }

    public function instituteListById(Request $req){
        $id = $req->input('id');
        return $this->repo->instituteListById($id);
    }


   }
