<?php

/**
 * RelationKey
 *
 * Application-level implementation of {@see RelationKeyInterface}.
 *
 * Provides centralized identifiers for logical relationships between
 * application entities. Core relation keys are inherited from the
 * interface, while applications may define additional relationship
 * identifiers specific to their domain.
 *
 * @package     App\Core
 * @subpackage  Keys
 * @category    Relations
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   2018-present, Kader Bouyakoub
 * @license     MIT
 * @link        https://opensource.org/license/mit
 * @since       0.0.1
 *
 * SPDX-License-Identifier: MIT
 */

final class RelationKey implements RelationKeyInterface
{
	// Currently inherits all constants from RelationKeyInterface.
	// Add application-specific variable keys here if needed.
}
