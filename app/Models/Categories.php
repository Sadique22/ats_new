<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Categories extends Model
{
    use HasFactory;
    protected $table ='tbl_categories';
    protected  $primaryKey = 'c_id';

  public function getCategories()
  {
    return DB::table('tbl_categories')->where('c_status',1)->orderBy('c_id','desc')->get();
  }

  public function deleteCategory($c_id)
  {
    return DB::table('tbl_categories')->where('c_id', $c_id)->delete();
  }

  public function CategoryData($id)
  {
    return DB::table('tbl_categories')->where('c_id', $id)->get();
  }


}