<?php namespace Skyrim;

use Msz\Forms\Control\Select;
use Msz\Forms\Control\Textarea;
use Msz\Forms\Form;

require ('inc/inc.php');

$frm = Form::make('upload')
    ->setMethodPost()
    ->addControl(Mod::getSelectObject()->setValue('RR'))
    ->addControl(Select::make('dlc_fix')->loadArray(array(0 => 'no', 1 => 'yes')))
    ->addControl(Textarea::make('info')->setCols(150)->setRows(50));

$frm->process();
$frm->draw();
    

if ($frm->isSubmited()) {


}