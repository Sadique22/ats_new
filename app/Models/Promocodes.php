<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Promocodes extends Model
{
    use HasFactory;
    protected $table ='tbl_promocodes';
    protected  $primaryKey = 'promo_id';
}