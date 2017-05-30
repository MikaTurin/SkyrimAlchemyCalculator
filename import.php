<?php namespace Skyrim;

use Import\Potions;
use Msz\Forms\Control\Select;
use Msz\Forms\Control\Textarea;
use Msz\Forms\Form;
use Import\IngredientsEffects;

require ('inc/inc.php');
header('Content-Type: text/html; charset=utf-8');

$frm = Form::make('upload')
    ->setCellspacing(5)
    ->setMethodPost()
    ->addControl(Mod::getSelectObject()->setValue('RQ'))
    ->addControl(Select::make('type')->loadArray(array('' => '', 'potions' => 'potions', 'effects' => 'effects')))
    ->addControl(Textarea::make('info')->setCols(150)->setRows(50));



echo getIndexBlock();
echo '<div align="center">';

if ($frm->process() && $frm->getField('type')->getValue()) {

    switch ($frm->getField('type')->getValue()) {
        case 'effects':
            $import = new IngredientsEffects($frm->getField('mod')->getValue());
            break;
        case 'potions':
            $import = new Potions($frm->getField('mod')->getValue());
            break;
        default:
            echo 'incorrect type';
            break;
    }

    if (isset($import)) {
        try {
            $c = $import->process($frm->getField('info')->getValue());
            echo 'done ' . $c . ' records';
        }
        catch (\Exception $e) {
            echo '<div style="width: 50%" align="left">';
            dump($e);
            echo '</div>';
        }

    }
}
else {
    $frm->draw();
}
echo '</div>';
