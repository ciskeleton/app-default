<?php

/**
 * CI_App
 *
 * Application-level driver library.
 *
 * Acts as the main entry point for application-specific logic and domain
 * drivers, fully isolated from the framework/core layer (CI_Hub).
 *
 * This class is intentionally lightweight by default and may remain unused
 * unless explicitly autoloaded by the application..
 *
 * @package     App\Libraries
 * @subpackage  Drivers
 * @category    App
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   2018-present, Kader Bouyakoub
 * @license     MIT
 * @link        https://opensource.org/license/mit
 *
 * SPDX-License-Identifier: MIT
 */

final class CI_App extends CI_Driver_Library
{
	/**
	 * Reference to the CI super-object.
	 *
	 * Used to access loader, hooks, config, and other shared services.
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
	 * @var array<string>
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

	// --------------------------------------------------------------------

	/**
	 * Class constructor.
	 *
	 * Initializes the driver library and prepares the list of valid drivers.
	 * Logic here is not executed unless the library is explicitly (auto)loaded.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// Cache the CI super-object for convenience and performance.
		$this->ci ??= CI_Controller::get_instance();

		/**
		 * Filter the list of valid drivers.
		 *
		 * Modules and plugins may register their own drivers through
		 * the `app_drivers` filter.
		 */
		$custom_drivers = $this->ci->hooks->filter('app_drivers', [], $this->ci, $this);
		if (!empty($custom_drivers)) {
			$this->valid_drivers = array_merge_unique(
				$this->valid_drivers,
				$custom_drivers
			);

			// Reset drivers map cache.
			$this->drivers_map = null;
		}

		// Skip runtime setup during installation.
		if (CI_INSTALL) {
			return;
		}

		// Allow the application to hook into runtime execution.
		$this->setup_runtime();

		/**
		 * Filter the list of drivers to auto-load.
		 *
		 * Modules and plugins may register drivers that should be
		 * eagerly loaded after runtime setup. Each auto-loaded driver
		 * must also be registered through the `app_drivers` filter.
		 */
		$autoload_drivers = $this->ci->hooks->filter('app_autoload_drivers', [], $this->ci, $this);
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
	 * Performs optional runtime setup.
	 *
	 * Intended for registering hooks, menus, listeners, or other
	 * application-specific behavior.
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
