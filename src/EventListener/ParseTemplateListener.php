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

use Contao\CalendarEventsModel;
use Contao\CalendarModel;
use Contao\Config;
use Contao\Input;
use Contao\PageModel;
use Contao\Template;

class ParseTemplateListener
{
    public function onParseTemplate(Template $template): void
    {
        if (null !== $template->type && 0 === stripos((string) $template->type, 'eventreader')) {
            if (null !== ($event = CalendarEventsModel::findPublishedByParentAndIdOrAlias(Input::get('events', false, true), $template->cal_calendar))) {
                /** @var CalendarModel $calendar */
                $calendar = $event->getRelated('pid');

                global $objPage;

                if ((int) $calendar->jumpTo !== (int) $objPage->id && null !== ($jumpTo = PageModel::findByPk($calendar->jumpTo))) {
                    $canonicalUri = ampersand($jumpTo->getFrontendUrl((Config::get('useAutoItem') ? '/' : '/events/').($event->alias ?: $event->id)));

                    $GLOBALS['TL_HEAD'][] = '<link rel="canonical" href="'.$canonicalUri.'">';
                }
            }
        }
    }
}
