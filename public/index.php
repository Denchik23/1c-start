<?php

require '../vendor/autoload.php';

use App\Parser\ImportEgrip;
use App\Parser\ImportEgrul;
use App\Parser\ParserOGRN;

$app_root = dirname(__DIR__);
define("APP_ROOT",     $app_root);

$smarty = new Smarty();
$smarty->template_dir = $app_root."/templates/";
$smarty->compile_dir = $app_root."/templates_c/";

$alert = [];

if (isset($_POST['start_pars']) && $_SERVER["REQUEST_METHOD"]=="POST") {
    $egrul = new ImportEgrul();
    $egrip = new ImportEgrip();
    $parserOGRN = new ParserOGRN($egrul, $egrip, 'public/parsefile');
    $parserOGRN->startPars();
    $alert = $parserOGRN->getLog();
}

$smarty->assign("alert", $alert);
$smarty->display('index.tpl');