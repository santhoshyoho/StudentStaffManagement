<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institute extends Model
{
    use HasFactory;
    protected $guarded=[];
    public static function boot()
    {
        parent::boot();

        static::creating(function ($institute) {
            $institutePrefix = env('ID_PREFIX')."INS";
            $institute_var = self::latest('id')->first();
            if(!$institute_var){
                $institute->institute_id = "{$institutePrefix}0000000001";
            }
            else{
                $latestId = intval(substr($institute_var->institute_id, -10));
                $newId = str_pad($latestId + 1, 10, '0', STR_PAD_LEFT);
                $institute->institute_id = "{$institutePrefix}{$newId}"; 
            }
        });
    }

}
