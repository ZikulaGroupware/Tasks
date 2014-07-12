<?php
/**
 * Copyright Tasks Team 2011
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Tasks
 * @link https://github.com/phaidon/Tasks
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

// Bootstrap - Used for global setup at module load time.
$helper = ServiceUtil::getService('doctrine_extensions');
$helper->getListener('timestampable');
$helper->getListener('standardfields');