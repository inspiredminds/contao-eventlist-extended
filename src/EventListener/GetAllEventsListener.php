<?php

declare(strict_types=1);

/*
 * This file is part of the ContaoEventlistExtendedBundle.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoEventlistExtendedBundle\EventListener;

use Contao\Config;
use Contao\Module;
use Contao\PageModel;

class GetAllEventsListener
{
    public function onGetAllEvents(array $events, array $calendars, int $start, int $end, Module $module): array
    {
        if ($module->jumpTo && null !== ($jumpTo = PageModel::findByPk($module->jumpTo))) {
            foreach ($events as &$eventsPerDay) {
                foreach ($eventsPerDay as &$eventsOnThatDay) {
                    foreach ($eventsOnThatDay as &$event) {
                        if (empty($event['source']) || 'default' === $event['source']) {
                            $event['href'] = ampersand($jumpTo->getFrontendUrl((Config::get('useAutoItem') ? '/' : '/events/').($event['alias'] ?: $event['id'])));
                        }
                    }
                }
            }
        }

        return $events;
    }
}