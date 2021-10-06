<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class MessageDetails extends Model
{
    use HasFactory;
    protected $table ='tbl_messages';
    protected  $primaryKey = 'm_id';

    public function GetUserMessages($sent_to){
     return DB::table('tbl_messages')->select('tbl_messages.*','users.name')
        ->leftjoin('users', 'tbl_messages.sent_by', 'users.id')
        ->where('tbl_messages.sent_to',$sent_to)->orderBy('tbl_messages.m_id','desc')->paginate(10);
    }

    public function GetUserSentMessages($sent_by){
     return DB::table('tbl_messages')->select('tbl_messages.*','users.name')
        ->leftjoin('users', 'tbl_messages.sent_to', 'users.id')
        ->where('tbl_messages.sent_by',$sent_by)->orderBy('tbl_messages.m_id','desc')->paginate(10);
    }

    public function GetAllMessagesInfo(){
    return DB::table('tbl_messages as msg')
       ->leftjoin('users as sb', 'msg.sent_by', '=' ,'sb.id')
       ->leftjoin('users as st', 'msg.sent_to', '=' ,'st.id')
       ->leftjoin('tbl_userrole as sbu', 'msg.sent_by', '=' ,'sbu.ur_id')
       ->leftjoin('tbl_userrole as stu', 'msg.sent_to', '=' ,'stu.ur_id')
       ->select('msg.message','msg.created_at','msg.m_id','sb.name as sent_by_name','st.name as sent_to_name','msg.sent_to','msg.sent_by','sbu.user_type as sent_by_usertype','stu.user_type as sent_to_usertype')
       ->orderBy('msg.m_id','desc')
       ->paginate(15);
    }

}