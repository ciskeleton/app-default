<?php

/**
 * UserLevel
 *
 * Application-level implementation of {@see UserLevelInterface}.
 *
 * Provides the canonical user access levels inherited from the core
 * interface, along with application-specific access levels and aliases.
 *
 * Applications may define additional access levels and aliases here
 * without modifying the core interface.
 *
 * Default access levels:
 *
 * - REGULAR (1): Regular authenticated user.
 * - STAFF   (30): Trusted staff member.
 * - ADMIN   (40): Administrator.
 * - OWNER   (50): System owner.
 *
 * Application-specific aliases:
 *
 * - ACP: Minimum access level required for administrative panel access.
 * - DEMO: Minimum access level required to bypass demo restrictions.
 *
 * @package     App\Core
 * @subpackage  Keys
 * @category    User Levels
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   2018-present, Kader Bouyakoub
 * @license     MIT
 * @link        https://opensource.org/license/mit
 * @since       0.0.1
 *
 * SPDX-License-Identifier: MIT
 */

final class UserLevel implements UserLevelInterface
{
	// ----------------------------------------------------------------
	// Application-level access aliases.
	// ----------------------------------------------------------------

	/**
	 * Minimum access level required for dashboard/admin panel access.
	 * Adjust to widen or restrict who can log into the back-end.
	 *
	 * @var int
	 */
	public const ACP = self::STAFF;

	/**
	 * Minimum access level required to bypass demo mode restrictions.
	 * Adjust to define which users retain full access in demo mode.
	 *
	 * @var int
	 */
	public const DEMO = self::OWNER;

	// ----------------------------------------------------------------
	// Core and application roles are inherited/defined below.
	// ----------------------------------------------------------------

}
