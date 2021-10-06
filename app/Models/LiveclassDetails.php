<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class LiveclassDetails extends Model
{
    use HasFactory;
    protected $table ='liveclass_links';
    protected  $primaryKey = 'lc_id';

    public function getClassLinks($id)
	{
	  return DB::table('liveclass_links')->select('liveclass_links.*', 'class_details.class_title','tbl_schedule.schedule_desc')
	  ->join('class_details', 'liveclass_links.class_id', 'class_details.id')
	  ->join('tbl_schedule', 'liveclass_links.for_schedule', 'tbl_schedule.s_id')
	  ->where('liveclass_links.class_id',$id)
	  ->paginate(10);
	}

	public function getPublishedLinks($id)
	{
	  return DB::table('liveclass_links')->select('liveclass_links.*', 'class_details.class_title','tbl_schedule.*')
	  ->join('class_details', 'liveclass_links.class_id', 'class_details.id')
	  ->join('tbl_schedule', 'liveclass_links.for_schedule', 'tbl_schedule.s_id')
	  ->where('liveclass_links.class_id',$id)
	  ->paginate(10);
	}
}