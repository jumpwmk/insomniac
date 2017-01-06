<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Config;
use App\User;
use App\Codestyle;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// Initial config
		$config = new Config;
		$config->root = '/codecube';
		$config->title = 'CodeCube · ชุมชนโปรแกรมเมอร์รุ่นใหม่';
		$config->logo = 'CodeCube<sup>beta</sup>';
		$config->save();

		// Add admin user
		$admin = new User;
		$admin->username = 'admin';
		$admin->password = Hash::make('12345678');
		$admin->email = 'admin@domain.com';
		$admin->admin = 1;
		$admin->display = 'Administrator';
		$admin->save();

		//Add codestyles
		$styles = [
			['Arta', 'arta'],
			['Ascetic', 'ascetic'],
			['Atelier Dune Dark', 'atelier-dune.dark'],
			['Atelier Dune Light', 'atelier-dune.light'],
			['Atelier Forest Dark', 'atelier-forest.dark'],
			['Atelier Forest Light', 'atelier-forest.light'],
			['Atelier Heath Dark', 'atelier-heath.dark'],
			['Atelier Heath Light', 'atelier-heath.light'],
			['Atelier Lakeside Dark', 'atelier-lakeside.dark'],
			['Atelier Lakeside Light', 'atelier-lakeside.light'],
			['Atelier Seaside Dark', 'atelier-seaside.dark'],
			['Atelier Seaside Light', 'atelier-seaside.light'],
			['Brown Paper', 'brown_paper'],
			['Codepen Embed', 'codepen-embed'],
			['Color Brewer', 'color-brewer'],
			['Dark', 'dark'],
			['Default', 'default'],
			['Docco', 'docco'],
			['Far', 'far'],
			['Foundation', 'foundation'],
			['Github', 'github'],
			['Googlecode', 'googlecode'],
			['Hybrid', 'hybrid'],
			['Idea', 'idea'],
			['Ir Black', 'ir_black'],
			['Kimbie Dark', 'kimbie.dark'],
			['Kimbie Light', 'kimbie.light'],
			['Magula', 'magula'],
			['Mono Blue', 'mono-blue'],
			['Monokai', 'monokai'],
			['Monokai Sublime', 'monokai_sublime'],
			['Obsidian', 'obsidian'],
			['Paraiso Dark', 'paraiso.dark'],
			['Paraiso Light', 'paraiso.light'],
			['Pojoaque', 'pojoaque'],
			['Railscasts', 'railscasts'],
			['Rainbow', 'rainbow'],
			['School Book', 'school_book'],
			['Solarized Dark', 'solarized_dark'],
			['Solarized Light', 'solarized_light'],
			['Sunburst', 'sunburst'],
			['Tomorrow Night Blue', 'tomorrow-night-blue'],
			['Tomorrow Night Bright', 'tomorrow-night-bright'],
			['Tomorrow Night Eighties', 'tomorrow-night-eighties'],
			['Tomorrow Night', 'tomorrow-night'],
			['Tomorrow', 'tomorrow'],
			['Vs', 'vs'],
			['Xcode', 'xcode'],
			['Zenburn', 'zenburn']
		];
		foreach ($styles as $style) {
			$codestyle = new Codestyle;
			$codestyle->name = $style[0];
			$codestyle->file_name = $style[1];
			$codestyle->save();
		}
	}

}
