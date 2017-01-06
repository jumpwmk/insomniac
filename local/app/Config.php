<?php namespace App;

use Illuminate\Database\Eloquent\Model;

define('CONFIGID',1);

class Config extends Model {

	protected $table = 'configs';

	static public function id() {
		return CONFIGID;
	}

	static public function title() {
		return Config::find(CONFIGID)->title;
	}

	static public function logo() {
		return Config::find(CONFIGID)->logo;
	}

	static public function custom() {
		return Config::find(CONFIGID)->custom;
	}

	static public function online() {
		return Config::find(CONFIGID)->online;
	}

	static public function allow_register() {
		return Config::find(CONFIGID)->allow_register;
	}

	static public function root() {
		return Config::find(CONFIGID)->root;
	}

	static public function main() {
		return Config::find(CONFIGID);
	}

}
