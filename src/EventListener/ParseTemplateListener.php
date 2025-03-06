<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoEventlistExtendedBundle\EventListener;

use Contao\CalendarEventsModel;
use Contao\CalendarModel;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\Input;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\Template;

#[AsHook('parseTemplate')]
class ParseTemplateListener
{
    public function onParseTemplate(Template $template): void
    {
        if (version_compare(ContaoCoreBundle::getVersion(), '5.3', '>=')) {
            return;
        }

        if (null !== $template->type && 0 === stripos((string) $template->type, 'eventreader')) {
            if (null !== ($event = CalendarEventsModel::findPublishedByParentAndIdOrAlias(Input::get('auto_item', false, true), $template->cal_calendar))) {
                /** @var CalendarModel $calendar */
                $calendar = $event->getRelated('pid');

                global $objPage;

                if ((int) $calendar->jumpTo !== (int) $objPage->id && null !== ($jumpTo = PageModel::findById($calendar->jumpTo))) {
                    $canonicalUri = StringUtil::specialcharsAttribute(StringUtil::ampersand($jumpTo->getFrontendUrl('/'.$event->alias ?: $event->id)));

                    $GLOBALS['TL_HEAD'][] = '<link rel="canonical" href="'.$canonicalUri.'">';
                }
            }
        }
    }
}
