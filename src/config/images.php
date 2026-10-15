<?php

/**
 * View Image Sizes
 *
 * Adds extra images sizes used for image upload.
 *
 * @package     App\Config
 * @subpackage  View
 * @category    Images
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   2018-present, Kader Bouyakoub
 * @license     MIT
 * @link        https://opensource.org/license/mit
 *
 * SPDX-License-Identifier: MIT
 */

$config['thumb'] = [
	'width'  => 260,
	'height' => 180,
	'crop'   => true,
];

$config['featured'] = [
	'width'  => 850,
	'height' => 350,
	'crop'   => true
];
