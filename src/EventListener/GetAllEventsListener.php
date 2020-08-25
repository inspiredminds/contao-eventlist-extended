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

        if ((int) $module->skipFirst > 0) {
            $processed = [];
            $count = 0;

            foreach ($events as $groupKey => &$groupEvents) {
                foreach ($groupEvents as $dateKey => $dateEvents) {
                    foreach ($dateEvents as $event) {
                        ++$count;

                        if ($count > (int) $module->skipFirst) {
                            $processed[$groupKey][$dateKey][] = $event;
                        }
                    }
                }
            }

            $events = $processed;
        }

        return $events;
    }
}
