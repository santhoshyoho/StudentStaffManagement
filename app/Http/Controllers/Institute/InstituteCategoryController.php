<?php

namespace App\Http\Controllers\Institute;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Repositories\InstituteCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InstituteCategoryController extends Controller
{
    protected $repo;
    protected $img;
    public function __construct(InstituteCategoryRepository $repo, ImageRepository $img)
    {
      $this->repo = $repo;
      $this->img = $img;
    }
    //Institutecategory create
  
    public function create(Request $req)
    {
      Log::warning($req);
      $name = $req->input('name');
  
      $logoPath = "assets/institute/categories/images";
      $filePath = "";
  
  
      if ($req->hasFile('logo')) {
        $filePath = $this->img->uploadImage($req->file('logo'), $logoPath);
      }
      return $this->repo->create($name, $filePath);
    }
    //Institutecategory update by id
  
    public function update(Request $req)
    { 
      Log::warning($req);
  
        $id = $req->input('id');
        $name = $req->input('name');
  
        $logoPath = "assets/institute/categories/images";
        $filePath = "";
  
        if ($req->hasFile('logo')) {
          $filePath = $this->img->uploadImage($req->file('logo'), $logoPath);
        }
        return $this->repo->update($id, $name, $filePath);
      }
    
    //Institutecategory 'is_deleted' update
  
    public function delete(Request $req)
    {
      Log::warning($req);
      $id = $req->input('id');
      return $this->repo->delete($id);
    }
  
    //Institutecategory data list
  
    public function getAll(Request $req)
    {
      Log::warning($req);
      $search = $req->input('search', '');
      return $this->repo->getAll($search);
    }
  
    //Institutecategory status is update 
    public function status(Request $req)
    {
      Log::warning($req);
      $id = $req->input('id');
      $status = $req->input('status');
      return $this->repo->status($id, $status);
    }
  
  
    //Institutecategory list by id
  
    public function listById(Request $req)
    {
      Log::warning($req);
      $id = $req->input('id');
      return $this->repo->listById($id);
    }
  
}
