<?php

/**
 * VariableKey
 *
 * Application-level implementation of {@see VariableKeyInterface}.
 *
 * Provides centralized identifiers for per-entity variables stored in
 * the `variables` table.
 *
 * Core variable keys are inherited from the interface. Applications may
 * define additional identifiers for their own entity-bound state without
 * modifying the core interface.
 *
 * Variable keys should be used instead of raw strings when reading from
 * or writing to the `variables` table.
 *
 * @example
 * ```php
 * $this->db->get_where('variables', ['name' => VariableKey::ONLINE_TOKEN]);
 * ```
 *
 * @package     App\Core
 * @subpackage  Keys
 * @category    Variables
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   2018-present, Kader Bouyakoub
 * @license     MIT
 * @link        https://opensource.org/license/mit
 * @since       0.0.1
 *
 * SPDX-License-Identifier: MIT
 */

final class VariableKey implements VariableKeyInterface
{
	// Currently inherits all constants from VariableKeyInterface.
	// Add application-specific variable keys here if needed.
}
