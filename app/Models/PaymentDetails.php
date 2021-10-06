<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class PaymentDetails extends Model
{
    use HasFactory;
    protected $table ='tbl_payment_details';
    protected  $primaryKey = 'payment_id';

    public function getUserPayouts($id)
    {
    return DB::table('tbl_payment_details')->select('tbl_payment_details.*', 'class_details.class_title','class_details.created_by')
        ->join('class_details', 'tbl_payment_details.class_id', 'class_details.id')
        ->where('tbl_payment_details.paid_by',$id)
        ->orderBy('tbl_payment_details.payment_id','desc')
        ->paginate(10);
    }
}
