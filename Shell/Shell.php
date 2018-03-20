<?php

/**
 *
 */

namespace NWP;

abstract class Shell {
	protected $commands = [];
	protected $options = ['--help'];

	protected $argv;
	protected $inputCmd;
	protected $inputOpts = [];

	protected function __construct() {
		global $argv;

		$this->argv = $argv;
		$this->parseCommand($argv[1]);
		$this->parseOption($argv);

		$this->run();
	}

	/**
	 * ************************
	 * ABSTRACT METHOD
	 * ************************
	 */
	abstract protected function help();

	/**
	 * ************************
	 * PROTECTED METHOD
	 * ************************
	 */
	protected function parseCommand($shell_input) {
		print_r($this->commands);
		if ( !in_array($shell_input, $this->commands) )
			throw new \Exception("Command `$shell_input` not found.");
			
		$this->cmd = $this->toMethod($shell_input);
	}

	// Exclude unregistered option from user-given options
	protected function parseOption($shell_inputs) {
		$options = array_slice($shell_inputs, 2);

		array_walk($options, function($opt) {
			if ( in_array($opt, $this->options) )
				$this->options[] = $opt;
		});
	}

	protected function run() {
		$this->{ $this->cmd }();
	}

	protected function output($msg) {
		echo $msg . PHP_EOL;
	}

	protected function toMethod($cmd) {
		return preg_replace_callback('/\:(.)/', function($matches) {
			return ucfirst($matches[1]);
		}, $cmd);
	}

	protected function getArgument() {
		// If expected argument is in registered options, or its name contains '-' or '--',
		// that means it's not an argument.
		if ( preg_match('/^-{1,2}[-\w]+$/') || in_array($this->argv[2], $this->options) )
			throw new \Exception("Argument for `$this->inputCmd` is required.");
			
		return $this->argv[2];
	}

	protected function getOption($opt_name, $value_required = false) {
		// If given option is not in our option list
		if ( !in_array($opt_name, $this->options) )
			throw new \Exception("Option `$opt_name` does not exist in registered command options.");

		// If user-given option doesn't match our command method' specific option
		if ( !in_array($opt_name, $this->inputOpts) )
			return null;
			//throw new \Exception("Missing command option `$opt_name`");
		
		// If given option not accept any argument
		if ( !$value_required )
			return $opt_name;

		// If accept argument, get the $argv element 
		// which is ordered next to the option.
		return $this->argv[ array_search($opt_name, $this->argv) + 1 ];
	}

	protected function runShell($command) {
		$cmds = (array) $command;
		$last = end($cmds);
		$cmd_str = '';

		array_walk($cmds, function($cmd) use ($last, &$cmd_str) {
			if ( $cmd !== $last ) $cmd .= ' && ';

			$cmd_str .= $cmd;
		});

		return shell_exec($cmd_str);
	}
}

?>