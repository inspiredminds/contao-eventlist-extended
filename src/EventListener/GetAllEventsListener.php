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
        $this->applySkipFirst($events, $start, $end, $module);
        $this->overrideJumpTo($events, $module);
        $this->addCounts($events, $module);

        return $events;
    }

    private function overrideJumpTo(array &$events, Module $module): void
    {
        if (empty($module->jumpTo)) {
            return;
        }

        $jumpTo = PageModel::findByPk($module->jumpTo);

        if (null === $jumpTo) {
            return;
        }

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

    private function applySkipFirst(array &$events, int $start, int $end, Module $module): void
    {
        if ((int) $module->skipFirst <= 0) {
            return;
        }

        $processed = [];
        $count = 0;

        // If sort order is descending, we need to reverse the arrays
        if ('descending' === $module->cal_order) {
            $events = array_reverse($events, true);
        }

        foreach ($events as $groupKey => $groupEvents) {
            if ('descending' === $module->cal_order) {
                $groupEvents = array_reverse($groupEvents, true);
            }

            foreach ($groupEvents as $dateKey => $dateEvents) {
                if ('descending' === $module->cal_order) {
                    $dateEvents = array_reverse($dateEvents);
                }

                foreach ($dateEvents as $event) {
                    // Skip any events outside the scope
                    if ((int) $event['begin'] < $start || (int) $event['end'] > $end) {
                        continue;
                    }

                    ++$count;

                    if ($count > (int) $module->skipFirst) {
                        $processed[$groupKey][$dateKey][] = $event;
                    }
                }
            }
        }

        // Reverse arrays back again
        if ('descending' === $module->cal_order) {
            $processed = array_reverse($processed, true);

            foreach ($processed as &$groupEvents) {
                $groupEvents = array_reverse($groupEvents, true);

                foreach ($groupEvents as &$dateEvents) {
                    $dateEvents = array_reverse($dateEvents);
                }
            }
        }

        $events = $processed;
    }

    private function addCounts(array &$events, Module $module): void
    {
        $total = 0;

        foreach ($events as $groupKey => &$groupEvents) {
            foreach ($groupEvents as $dateKey => &$dateEvents) {
                foreach ($dateEvents as &$event) {
                    ++$total;
                }
            }
        }

        $count = 0;

        foreach ($events as $groupKey => &$groupEvents) {
            foreach ($groupEvents as $dateKey => &$dateEvents) {
                foreach ($dateEvents as &$event) {
                    ++$count;
                    $countAscending = $count;
                    $countDescending = $total - ($count - 1);
                    $event['countNumber'] = 'descending' === $module->cal_order ? $countDescending : $countAscending;
                    $event['countAscending'] = $countAscending;
                    $event['countDescending'] = $countDescending;
                    $event['countTotal'] = $total;
                }
            }
        }
    }
}
