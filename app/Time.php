<?php
use Illuminate\Database\Eloquent\Model;
namespace App;

class Time extends Model{

	public function getTimeAgo($ptime){

		$estimateTime = time() - $ptime;

		if ($estimateTime < 600){

			return "less than ten minutes ago";
		}
		else{

			return "more than 10 minutes ago";
		}

	}
}