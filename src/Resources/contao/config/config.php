<?php

declare(strict_types=1);

/*
 * This file is part of the ContaoEventlistExtendedBundle.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

use InspiredMinds\ContaoEventlistExtendedBundle\EventListener\GetAllEventsListener;
use InspiredMinds\ContaoEventlistExtendedBundle\EventListener\ParseTemplateListener;

$GLOBALS['TL_HOOKS']['getAllEvents'][] = [GetAllEventsListener::class, 'onGetAllEvents'];
$GLOBALS['TL_HOOKS']['parseTemplate'][] = [ParseTemplateListener::class, 'onParseTemplate'];
