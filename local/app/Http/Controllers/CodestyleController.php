<?php namespace App\Http\Controllers;

use App\User;
use App\Config;
use App\Task;
use App\Submit;
use App\Queue;
use App\Grading;
use App\Codestyle;
use Auth;
use Request;
use Illuminate\Http\RedirectResponse;

class CodestyleController extends Controller {

	public function getCodestyles()
	{
		return json_encode(Codestyle::all());
	}

}
