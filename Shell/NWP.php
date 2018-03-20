<?php

/**
 *
 */

namespace NWP;

use NWP\Shell;

class CLI extends Shell {
	const THEME_PATH = 'wp-content/themes/';
	protected $commands = ['make:theme'];
	protected $options = [
		'-f',
		'--author'
	];

	public function __construct() {
		global $argv;

		if ( count($argv) === 1 ) self::help();

		parent::__construct();
	}

	/**
	 * ************************
	 * PUBLIC METHOD
	 * ************************
	 */
	protected function makeTheme() {
		/*if ( !isset($this->inputCmd) ) 
			self::help('Error: Theme name is required. :$ make:theme <theme_name>');*/

		$name = strtolower(str_replace(' ', '', $this->inputCmd));
		$path = self::THEME_PATH . $name;

		if ( file_exists($path) ) {
			// If does not force
			if ( !is_null($this->getOption('-f')) ) {
				self::output('Directory already exists.');
				return;
			}

			self::output(shell_exec('rm -rvf ' . $path));
		}

		self::output('Creating theme ' . $name . ' at ' . $path . ' ...');

		$this->_makeTheme($path);

		/*if ( mkdir($path) ) {
			self::output('Theme ' . $name . ' created.');
		} else {
			self::output("Failed to create directory");
		}*/
	}

	protected function help($headline = null) {
		if ( !is_null($headline) ) {
			self::output($headline);
			self::output('');
		}

		self::output('<<<<<< NawawishWP >>>>>>');
		self::output('');
		self::output('This is help text.');

		exit;
	}

	/**
	 * ************************
	 * PRIVATE METHOD
	 * ************************
	 */
	private function _makeTheme($path) {
		$theme_name = $this->inputCmd;
		$theme_author = $this->getOption('--author', true);
		$theme_textdomain = strtolower(str_replace(' ', '', $this->inputCmd));

		print_r($this->getOption('--author', true));

		$content = include __DIR__ . "/src/style.php";
		//echo $s;
		$style = "echo \"$content\" > $path/style.css";
		$index = "cp -v " . __DIR__ . "/src/index.php $path/index.php";
		$func = "cp -v " . __DIR__ . "/src/functions.php $path/functions.php";
		$views = $path . '/views';
		$comp = $views . '/components';
		$temp = $views . '/templates';
		
		//$status = shell_exec("mkdir -v $path && mkdir -v $path/views");

		self::output($this->runShell([
			"mkdir -v $path", "mkdir -v $views", "mkdir -v $comp", "mkdir -v $temp",
			$index, $func, $style
		]));
	}
}

?>