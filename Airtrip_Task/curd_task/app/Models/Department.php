<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{

    protected $fillable = [
        'dept_name','dept_status'
    ];

    public static function duplicatecompney($data){
        $result = DB::table('department')
                ->where('dept_name', 'like', $data['dept_name'].'%')
                ->get()->count();

        return $result;

    }

    public static function updateData($id,$data){
        DB::table('department')
        ->where('id', $id)
        ->update($data);
    }

}
