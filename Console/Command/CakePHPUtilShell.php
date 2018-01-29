<?php
App::uses('AppShellErrorHandler', 'Lib/Util');
App::uses('Shell', 'Console');

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package       app.Console.Command
 *
 * @property MailTask $Mail
 */
class CakePHPUtilShell extends Shell {

	const SUCCESS_MESSAGE = 'Shell action ran successfully and without problems.';
	/**
	 * Tasks to use
	 *
	 * @var array
	 */
	public $tasks = array('Mail');

	/**
	 * Contains the debug value
	 *
	 * @var int
	 */
	protected $_debug;

	/**
	 * Contains all the exceptions made in the shell
	 *
	 * @var array
	 */
	protected $_exceptions = array();

	/**
	 * Set whether or not to mail shell result to an e-mail recipient.
	 *
	 * @var array
	 */
	protected $_mailTo = array(
		'enabled' => 0, # 0 = Never send mail; 1 = Only mail on errors; 2 = Always mail, regardless of exit status
		'recipient' => DEV_EMAIL
	);

	/**
	 * Set the starttime of the shell, that's nice for debugging shells that take very long
	 *
	 * @var
	 */
	protected $_startTime;

	/**
	 * Starts up the Shell and displays the welcome message.
	 * Allows for checking and configuring prior to command or main execution
	 *
	 * Override this method if you want to remove the welcome information,
	 * or otherwise modify the pre-command flow.
	 *
	 * @return void
	 * @link http://book.cakephp.org/2.0/en/console-and-shells.html#Shell::startup
	 */
	public function initialize() {
		parent::initialize();
		$this->_startTime = microtime(true);
		$this->_setDebug();
		if (!$this->_debug) {
			set_error_handler(array(new AppShellErrorHandler(), "warning_handler"), E_ALL);
		}
	}

/**
 * This method allows you to easily convert arguments into variables for your shell.
 * It accepts two input arrays, arguments and defaults, which are described below.
 * If the defaults array is not set, the default will be set to null for all args that are not passed.
 *
 * @author Jan Dorsman <jdorsman@expandonline.nl>
 *
 * @param array $args     The name you want the arguments to have.
 * @param array $defaults The default value you want the arguments to have if no value was passed for it.
 *
 * Example 1: $args = $this->processArgs(array('someVar', 'somethingElse', 'userId'));
 * Would set the vars: $someVar, $somethingElse and $userId with the values of $this->args[0], [1] and [2].
 * If the specific arg was not passed, it will be set to null.
 *
 * Example 2: $args = $this->processArgs(array('letter', 'symbol', 'userId'), array('a', '-', 14));
 * Would set the $letter, $symbol and $userId vars with the values of $this->args[0], [1] and [2].
 * If the args were not passed by the user, they would default to: $letter = 'a', $symbol = '-' and $userId = 14;
 *
 * Finally, in your shell, run this small foreach loop to set the vars:
 * foreach ($args as $arg => $val) {
 *     ${$arg} = $val;
 * }
 *
 * Or alternatively if there are only a few vars:
 * list($someVar, $somethingElse, $userId) = array_values($args);
 *
 * @return array
 */
	public function processArgs(array $args, array $defaults = null) {
		$vars = array();
		foreach ($args as $id => $arg) {
			if (array_key_exists($id, $this->args)) {
				$vars[$arg] = $this->args[$id];
			} else {
				if (is_array($defaults) && array_key_exists($id, $defaults)) {
					$vars[$arg] = $defaults[$id];
				} else {
					$vars[$arg] = null;
				}
			}
		}

		return $vars;
	}

	/**
	 * Acts as a callback function which will be called if the SubCommand does not exist
	 */
	public function main() {
		if (isset($this->command)) {
			if (!$this->hasMethod($this->command)) {
				$this->_exceptions[] = sprintf('The SubCommand \'%s\' you are tyring to execute does not exist!', $this->command);
			} else {
				$this->_exceptions[] = sprintf('For some reason this SubCommand \'%s]\' could not be ran', $this->command);
			}
		} else {
			$this->_exceptions[] = 'you have to provide a SubCommand to execute!';
		}

		#TODO uncomment the line below, which echoes the help. This is used to display the getOptionsParser. Every Shell should be reformatted to the new structure first!
		#For an example: have a look at the FeedShell
		$this->out('-- The Help will be enabled when all Shells are restructured to the new layout -- AppShell line ' . (__LINE__ + 1));
//        $this->out($this->getOptionParser()->help());
		# ------------------------------------------------------------------------------------------------------------ #

		# since the main() will only be called if the command does not exist, we can stop it.
		$this->_stop();
	}

	/**
	 * Terminates execution of the script immediately and have a look if there are exceptions whatsoever and send them to the $_mailTo
	 *
	 * @param int  $code     This value is used as exit code for the script (0 meaning an 'expected/healthy' termination).
	 *
	 * @return void
	 */
	protected function _stop($code = 0) {
		# stop the timer
		$this->_out('TIME TO COMPLETE TASK: ' . date('H\h i\m s\s', microtime(true) - $this->_startTime - 3600));
		$this->_printWarnings();
		// Set arg names
		$trace = $this->_getTrace();
		$component = ($this->command) ? $this->command : 'Unknown';
		$args = $this->args;
		if (!isset($this->command) || !$this->hasMethod($this->command)) {
			# if the command is not found, or it does not exist, shift the arguments by one, because it will remain on position 0 until it has ran.
			array_shift($args);
		}
		$action = implode(' ', $args);
		$this->Mail->send($this->_mailTo, $trace[1]['class'], $component, $action, $code, $trace[0]['line'], $this->_exceptions);

		// Output result to console
		if ($code > 0 && !empty($trace['line']) && !empty($this->_exceptions)) {
			// Unexpected shutdown, with line specification and Exceptions
			$this->out('The ' . $trace[1]['class'] . ' has unexpectedly terminated the \'' . $component . '/' . $action . '\' action with exit code ' . $code . '. The error occurred on line ' . $trace[0]['line'] . ' of the shell script. Additionally, the following Exceptions were thrown: ' . "\n\n" . implode("\r\n", $this->_exceptions));
		} elseif ($code > 0 && !empty($trace['line'])) {
			// Unexpected shutdown, with line specification
			$this->out('The ' . $trace[1]['class'] . ' has unexpectedly terminated the \'' . $component . '/' . $action . '\' action with exit code ' . $code . '. The error occurred on line ' . $trace[0]['line'] . ' of the shell script.');
		} elseif (!empty($this->_exceptions)) {
			// Expected shutdown with Exceptions
			$this->out('The ' . $trace[1]['class'] . ' has terminated the \'' . $component . '/' . $action . '\' action with exit code ' . $code . '. The error occurred on line ' . $trace[0]['line'] . '. Additionally, the following Exceptions were thrown: ' . "\n\n" . implode("\r\n", $this->_exceptions));
		} else {
			$this->out(sprintf('The %s has ran successfully. No exceptions were thrown.', $trace[1]['class']));
		}
		$this->hr();

		// Exit the Shell
		parent::_stop($code);
	}

/**
 * Print warnings that were catched by the AppShellErrorHandler.
 */
	protected function _printWarnings() {
		if (AppShellErrorHandler::hasWarnings()) {
			$warnings = AppShellErrorHandler::flushWarnings();
			$this->out("The following warnings occurred during the cronjob:\n");
			foreach ($warnings as $warning) {
				$this->out(trim($warning[1]));
				foreach ($warning[2] as $backtrace) {
					// The warning_handler doesn't have a a file and line number in the backtrace.
					if (!isset($backtrace['line']) && !isset($backtrace['file'])) {
						continue;
					}
					$this->out($backtrace['class'] . $backtrace['type'] . $backtrace['function'] . ' in ' . $backtrace['file'] . ' on line ' . $backtrace['line']);
				}
				$this->out("\n");
			}
			if (AppShellErrorHandler::isListFull()) {
				$this->out('The messages buffer is full, no further messaged were cached.');
			}
		} else if(empty($this->_exceptions)) {
			$this->out(self::SUCCESS_MESSAGE);
		}
	}

	/**
	 * Does the exact same thing as parent::out(), but it checks if the $this->debug is set to 1
	 * Outputs a single or multiple messages to stdout. If no parameters
	 * are passed outputs just a newline.
	 *
	 * ### Output levels
	 *
	 * There are 3 built-in output level.  Shell::QUIET, Shell::NORMAL, Shell::VERBOSE.
	 * The verbose and quiet output levels, map to the `verbose` and `quiet` output switches
	 * present in  most shells.  Using Shell::QUIET for a message means it will always display.
	 * While using Shell::VERBOSE means it will only display when verbose output is toggled.
	 *
	 * @param string|array $message  A string or a an array of strings to output
	 * @param integer      $newlines Number of newlines to append
	 * @param integer      $level    The message's output level, see above.
	 *
	 * @return integer|boolean Returns the number of bytes returned from writing to stdout.
	 * @link http://book.cakephp.org/2.0/en/console-and-shells.html#Shell::out
	 */
	protected function _out($message = null, $tag = false, $newlines = 1, $level = Shell::NORMAL) {
		if ($this->_debug === 1) {
			if ($tag) {
				$message = sprintf('<%1$s>%2$s</%1$s>', $tag, $message);
			}
			return $this->out($message, $newlines, $level);
		}
		# because we did not write anything to the stdout
		return 0;
	}

	/**
	 * Sets the protected _debug variable to a certain value.
	 *
	 * @param int $value The debug value you would like to have.
	 *                   By default it will read the debug value from the Configure class
	 */
	protected function _setDebug($value = null) {
		if ($value === null) {
			if (is_null($this->_debug)) {
				# no value has been given, so we are going to read the value of the Config
				$this->_debug = Configure::read('debug') === 0 ? 0 : 1;
			}
		} else {
			$this->_debug = $value;
		}
	}

	/**
	 * Add an exception to the _exceptions array.
	 *
	 * @param Exception $e The exception, as thrown by the Try Catch block.
	 */
	protected function _exception($e) {
		$options = (defined('DEBUG_BACKTRACE_IGNORE_ARGS')) ? DEBUG_BACKTRACE_IGNORE_ARGS : null;
		$trace = debug_backtrace($options);
		$msg = sprintf('%d: %s (%s:%d)', $e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
		$this->_exceptions[] = $msg;
		$this->out(sprintf('[!] PARSE ERROR %s', $msg));
		$this->_out(sprintf('-- Triggered in %s on line %d', $trace[1]['class'], $trace[0]['line']));
	}

	public function testProtected($methodName, array $args) {
		return call_user_func_array(array($this, $methodName), $args);
	}

	/**
	 * @return array
	 */
	protected function _getTrace() {
		$options = (defined('DEBUG_BACKTRACE_IGNORE_ARGS')) ? DEBUG_BACKTRACE_IGNORE_ARGS : null;
		$trace = debug_backtrace($options);

		return $trace;
	}

	/**
	 * @param $data
	 * @param $table
	 *
	 * @return Shell|string
	 */
	protected function tableToFile($data, $table) {
		$this->filepath = TMP . uniqid() . '.xls';
		ob_start([
			$this,
			'_bufferToFile'
		], 102400);
		$table->printTable($data);
		ob_end_clean();

		return $this->filepath;
	}

	protected function _bufferToFile($text) {
		file_put_contents($this->filepath, $text, FILE_APPEND);
		return '';
	}

	/**
	 *
	 */
	protected function _confirmRun() {
		$handle = fopen ("php://stdin","r");
		echo "\nThis application is in debug mode, are you sure you want to continue? (y/n)\n";
		$response = trim(fgets($handle));
		fclose($handle);
		if ($response === 'y') {
			return;
		}
		$this->_stop();
	}

	/**
	 * @return \Symfony\Component\DependencyInjection\ContainerInterface
	 */
	protected function getContainer(): \Symfony\Component\DependencyInjection\ContainerInterface {
		return (new \CakePHPUtil\Lib\Container\ContainerBuilder())->getContainer();
	}

}
