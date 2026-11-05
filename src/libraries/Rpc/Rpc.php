<?php

/**
 * CI_Rpc
 *
 * Application-level JSON-RPC driver library.
 *
 * Acts as the entry point for application-specific JSON-RPC drivers,
 * providing a dedicated extension point for applications, modules,
 * and plugins.
 *
 * This class is intentionally lightweight by default and remains
 * inactive unless explicitly loaded by the application.
 *
 * @package     App\Libraries
 * @subpackage  Drivers
 * @category    JSON-RPC
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   2018-present, Kader Bouyakoub
 * @license     MIT
 * @link        https://opensource.org/license/mit
 *
 * SPDX-License-Identifier: MIT
 */

final class CI_Rpc extends CI_Driver_Library
{
	/**
	 * Reference to the CI super-object.
	 *
	 * Used to access the JSON-RPC server, hooks, and other
	 * shared services.
	 *
	 * @var CI_Controller
	 */
	protected $ci;

	/**
	 * List of allowed drivers.
	 *
	 * This list is filterable to allow modules and plugins to register
	 * their own drivers without modifying this class.
	 *
	 * @var string[]
	 */
	protected $valid_drivers = [];

	/**
	 * List of drivers to auto-load during runtime boot.
	 *
	 * These must also be registered in $valid_drivers.
	 *
	 * @var string[]
	 */
	protected $autoload_drivers = [];

	/**
	 * Flag indicating whether the library is available.
	 *
	 * @var bool
	 */
	private $enabled = false;

	// --------------------------------------------------------------------

	/**
	 * Class constructor.
	 *
	 * Initializes the driver library and prepares the list of valid drivers.
	 * Logic here is not executed unless the library is explicitly (auto)loaded.
	 *
	 * RPC drivers are only available during stateless requests and are
	 * disabled while the application is being installed.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// Skip initialization during installation or stateful requests.
		if (CI_INSTALL || !is_stateless_request()) {
			return;
		}

		// Cache the CI super-object for convenience and performance.
		$this->ci ??= CI_Controller::get_instance();

		// Load JSON-RPC server if not already loaded.
		if (!class_exists('CI_Jsonrpcs', false)) {
			$this->ci->load->library('jsonrpcs');
		}

		/**
		 * Filter the list of valid drivers.
		 *
		 * Modules and plugins may register their own drivers through
		 * the `rpc_drivers` filter.
		 */
		$custom_drivers = $this->ci->hooks->filter('rpc_drivers', [], $this->ci, $this);
		if (!empty($custom_drivers)) {
			$this->valid_drivers = array_merge_unique(
				$this->valid_drivers,
				$custom_drivers
			);

			// Reset drivers map cache.
			$this->drivers_map = null;
		}

		$this->enabled = true;

		// Allow the application to hook into runtime execution.
		$this->setup_runtime();

		/**
		 * Filter the list of drivers to auto-load.
		 *
		 * Modules and plugins may register drivers that should be
		 * eagerly loaded after runtime setup. Each auto-loaded driver
		 * must also be registered through the `rpc_drivers` filter.
		 */
		$autoload_drivers = $this->ci->hooks->filter('rpc_autoload_drivers', [], $this->ci, $this);
		if (!empty($autoload_drivers)) {
			$this->autoload_drivers = array_merge_unique(
				$this->autoload_drivers,
				$autoload_drivers
			);
		}
		$this->load_drivers($this->autoload_drivers);
	}

	// --------------------------------------------------------------------

	/**
	 * Determines whether the RPC library is available.
	 *
	 * @return bool
	 */
	public function is_enabled()
	{
		return $this->enabled;
	}

	// --------------------------------------------------------------------

	/**
	 * Applies RPC throttling for a given action.
	 *
	 * @param  string   $key       Logical throttle key.
	 * @param  int|null $attempts  Maximum allowed attempts within the window.
	 * @param  int|null $decay     Time window in seconds.
	 *
	 * @return void
	 * @throws JsonRpcException
	 */
	public function throttle($key, $attempts = null, $decay = null)
	{
		if (!$this->enabled) {
			return;
		}

		$attempts = (int) ($attempts ?? 5);
		$decay = (int) ($decay ?? MINUTE_IN_SECONDS);

		$retry_after = 0;
		$remaining = 0;

		if ($this->ci->throttle->is_throttled($key, $attempts, $decay, $retry_after, $remaining)) {
			$this->error(
				'too_many_requests',
				sprintf(
					'Too many requests were made. Please try again in %s.',
					duration($retry_after)
				),
				HttpStatusCode::TOO_MANY_REQUESTS,
				$retry_after,
				$remaining
			);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Throws a JSON-RPC exception.
	 *
	 * Used for application-level errors such as invalid parameters,
	 * missing resources, conflicts, and failed operations.
	 *
	 * @param  string   $line         Error identifier.
	 * @param  string   $message      Human-readable error message.
	 * @param  int      $status       HTTP status code.
	 * @param  int|null $retry_after  Optional retry time in seconds.
	 * @param  int|null $remaining    Optional remaining attempts.
	 * @return never
	 *
	 * @throws JsonRpcException
	 */
	public function error(
		$line,
		$message,
		$status = HttpStatusCode::BAD_REQUEST,
		$retry_after = null,
		$remaining = null)
	{
		throw new JsonRpcException(
			$line,
			$message,
			$status,
			$retry_after,
			$remaining
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Throws a JSON-RPC authentication exception.
	 *
	 * Used for authentication and authorization failures.
	 *
	 * @param  string   $line         Error identifier.
	 * @param  string   $message      Human-readable error message.
	 * @param  int      $status       HTTP status code.
	 * @param  int|null $retry_after  Optional retry time in seconds.
	 * @param  int|null $remaining    Optional remaining attempts.
	 * @return never
	 *
	 * @throws JsonRpcAuthException
	 */
	public function auth_error(
		$line,
		$message,
		$status = HttpStatusCode::UNAUTHORIZED,
		$retry_after = null,
		$remaining = null)
	{
		throw new JsonRpcAuthException(
			$line,
			$message,
			$status,
			$retry_after,
			$remaining
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Performs optional runtime setup.
	 *
	 * Intended for registering hooks, listeners, or other
	 * application-specific RPC behavior.
	 *
	 * This method is intentionally empty in the default distribution
	 * and should only be implemented when the application requires it.
	 *
	 * @return void
	 */
	private function setup_runtime()
	{
		// Intentionally left blank.
	}

}
