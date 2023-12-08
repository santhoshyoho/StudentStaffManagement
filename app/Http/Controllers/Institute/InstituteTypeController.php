<?php

namespace App\Http\Controllers\Institute;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Repositories\InstituteTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InstituteTypeController extends Controller
{
    protected $repo;
    protected $img;
  
    public function __construct(InstituteTypeRepository $repo, ImageRepository $img)
    {
  
      $this->repo = $repo;
      $this->img = $img;
    }
  
    //Institutetype create
  
    public function create(Request $req)
    {
      Log::warning($req);
             
      $name = $req->input('name');
  
      return $this->repo->create($name);
    }
    //Institutetype update by id
  
  
    public function update(Request $req)
    {
      Log::warning($req);
      $id = $req->input('id');
      $name = $req->input('name');
      return $this->repo->update($id, $name);
    }
    //Institutetype 'is_deleted' update
  
    public function delete(Request $req)
    {
      Log::warning($req);
      $id = $req->input('id');
      return $this->repo->delete($id);
    }
    //Institutetype data list
  
    public function getAll(Request $req)
    {
      Log::warning($req);
      $search = $req->input('search', '');
      return $this->repo->getAll($search);
    }
    //Institutetype status is update 
  
    public function status(Request $req)
    {
      Log::warning($req);
      $id = $req->input('id');
      $status = $req->input('status');
      return $this->repo->status($id, $status);
    }
    //Institutetype list by id
  
    public function listById(Request $req)
    {
      Log::warning($req);
      $id = $req->input('id');
      return $this->repo->listById($id);
    }
}
