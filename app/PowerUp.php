<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Storage;

class PowerUp extends Model
{

	public function setImage(UploadedFile $file)
	{
		if ($this->image) {
			Storage::delete('public/powerups/'.$this->image);
		}

		$filename = $this->id.date('Ymdhis').'.'.$file->getClientOriginalExtension();
		Storage::put('public/powerups/'.$filename, file_get_contents($file));

		$this->image = $filename;
		$this->save();
	}

}
