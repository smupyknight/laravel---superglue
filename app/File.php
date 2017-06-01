<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{

	protected $dates = ['date'];

	protected $guarded = [];

	public function getLocalPath()
	{
		return sprintf('files/%d/%d-%s', $this->account_id, $this->id, basename($this->name));
	}

	public function sizeForHumans()
	{
		$units = ['B', 'KB', 'MB'];
		$pow = min(2, floor(log($this->size) / log(1024)));
		$bytes = $this->size / (1 << (10 * $pow));

		return round($bytes, 2) . ' ' . $units[$pow];
	}

}
