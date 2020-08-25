<?php

declare(strict_types=1);

/*
 * This file is part of the ContaoEventlistExtendedBundle.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;

PaletteManipulator::create()
    ->addLegend('redirect_legend', 'template_legend', PaletteManipulator::POSITION_BEFORE)
    ->addField('jumpTo', 'redirect_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('skipFirst', 'perPage', PaletteManipulator::POSITION_BEFORE)
    ->applyToPalette('eventlist', 'tl_module')
;

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_ignoreDynamic']['eval']['tl_class'] .= ' clr';
