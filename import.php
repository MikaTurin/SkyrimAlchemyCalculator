<?php namespace Skyrim;

use Msz\Forms\Control\Select;
use Msz\Forms\Control\Textarea;
use Msz\Forms\Form;
use Tes5Edit\IngredientsEffectsImport;

require ('inc/inc.php');
header('Content-Type: text/html; charset=utf-8');

$frm = Form::make('upload')
    ->setCellspacing(5)
    ->setMethodPost()
    ->addControl(Mod::getSelectObject()->setValue('RQ'))
    ->addControl(Select::make('type')->loadArray(array('' => '', 'potions' => 'potions', 'effects' => 'effects')))
    ->addControl(Textarea::make('info')->setCols(150)->setRows(50));



    

if ($frm->process() && $frm->getField('type')->getValue()) {
    $import = new IngredientsEffectsImport($frm->getField('mod')->getValue());
    //$import->process($frm->getField('info')->getValue());
    echo 'done';

}
else {
    echo '<div align="center">';
    $frm->draw();
    echo '</div>';
}
