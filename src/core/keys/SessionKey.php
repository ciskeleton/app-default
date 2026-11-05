<?php

/**
 * SessionKey
 *
 * Application-level implementation of {@see SessionKeyInterface}.
 *
 * Provides centralized identifiers for session variables used throughout
 * the application. Core session keys are inherited from the interface.
 * Applications may define additional session keys here without modifying
 * the core interface.
 *
 * @package     App\Core
 * @subpackage  Keys
 * @category    Session
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   2018-present, Kader Bouyakoub
 * @license     MIT
 * @link        https://opensource.org/license/mit
 * @since       0.0.1
 *
 * SPDX-License-Identifier: MIT
 */

final class SessionKey implements SessionKeyInterface
{
	// Currently inherits all constants from SessionKeyInterface.
	// Add application-specific variable keys here if needed.
}
