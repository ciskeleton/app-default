<?php

/**
 * CookieKey
 *
 * Application-level implementation of {@see CookieKeyInterface}.
 *
 * Provides a centralized collection of cookie identifiers used throughout
 * the application. Core cookie keys are inherited from the interface.
 * Applications may define additional cookie keys in this class.
 *
 * @package     App\Core
 * @subpackage  Keys
 * @category    Cookies
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   2018-present, Kader Bouyakoub
 * @license     MIT
 * @link        https://opensource.org/license/mit
 * @since       0.0.1
 *
 * SPDX-License-Identifier: MIT
 */

final class CookieKey implements CookieKeyInterface
{
	// Currently inherits all constants from CookieKeyInterface.
	// Add application-specific variable keys here if needed.
}
