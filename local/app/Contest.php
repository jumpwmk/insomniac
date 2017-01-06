<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;

class Contest extends Model {

	protected $table = 'contests';

	static public function isTrueType($id, $type) {
		if(Contest::find($id)->type != $type) return abort(404);
	}

}
