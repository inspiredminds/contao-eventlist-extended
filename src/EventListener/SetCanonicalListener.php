<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoEventlistExtendedBundle\EventListener;

use Contao\CalendarEventsModel;
use Contao\CalendarModel;
use Contao\Config;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\Routing\ResponseContext\HtmlHeadBag\HtmlHeadBag;
use Contao\CoreBundle\Routing\ResponseContext\ResponseContextAccessor;
use Contao\Input;
use Contao\PageModel;
use Contao\Template;

#[AsHook('parseTemplate')]
class SetCanonicalListener
{
    public function __construct(private readonly ResponseContextAccessor $responseContextAccessor)
    {
    }

    public function __invoke(Template $template): void
    {
        if (!$template->type || !str_contains((string) $template->type, 'eventreader')) {
            return;
        }

        if (!\is_array($template->cal_calendar)) {
            return;
        }

        if (!$responseContext = $this->responseContextAccessor->getResponseContext()) {
            return;
        }

        if (!$responseContext->has(HtmlHeadBag::class)) {
            return;
        }

        $event = CalendarEventsModel::findPublishedByParentAndIdOrAlias(Input::get('auto_item', false, true), $template->cal_calendar);

        if (!$event) {
            return;
        }

        /** @var CalendarModel $calendar */
        if (!$calendar = $event->getRelated('pid')) {
            return;
        }

        /** @var PageModel $page */
        $page = $GLOBALS['objPage'] ?? null;

        if ((int) $calendar->jumpTo === (int) $page?->id) {
            return;
        }

        if (!$jumpTo = PageModel::findById($calendar->jumpTo)) {
            return;
        }

        $canonicalUri = $jumpTo->getAbsoluteUrl((false !== Config::get('useAutoItem') ? '/' : '/events/').$event->alias ?: $event->id);
        $responseContext->get(HtmlHeadBag::class)
            ->setCanonicalUri($canonicalUri)
        ;
    }
}
