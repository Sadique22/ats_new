<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class TaxDetails extends Model
{
    use HasFactory;
    protected $table ='tbl_tax_details';
    protected  $primaryKey = 'ttd_id';
}