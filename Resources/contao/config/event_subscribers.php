<?php

/**
 * Copyright © ContaoBlackForest
 *
 * @package   contao-member-import
 * @author    Sven Baumann <baumann.sv@gmail.com>
 * @author    Dominik Tomasi <dominik.tomasi@gmail.com>
 * @license   GNU/LGPL
 * @copyright Copyright 2014-2016 ContaoBlackForest
 */

use ContaoBlackForest\Member\Import\Subscriber\PostPrepareData;
use ContaoBlackForest\Member\Import\Subscriber\PrePrepareData;

return array(
    new PrePrepareData(),
    new PostPrepareData()
);
