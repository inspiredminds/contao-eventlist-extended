<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_skipCurrent'] = [
    'inputType' => 'checkbox',
    'exclude' => true,
    'eval' => ['tl_class' => 'w50'],
    'sql' => ['type' => 'boolean', 'default' => false],
];

PaletteManipulator::create()
    ->addLegend('redirect_legend', 'template_legend', PaletteManipulator::POSITION_BEFORE)
    ->addField('jumpTo', 'redirect_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('skipFirst', 'perPage', PaletteManipulator::POSITION_BEFORE)
    ->addField('cal_skipCurrent', 'config_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('eventlist', 'tl_module')
;

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_ignoreDynamic']['eval']['tl_class'] .= ' clr';
