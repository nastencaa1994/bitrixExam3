<?php B_PROLOG_INCLUDED === true || die();

\Bitrix\Main\UI\Extension::load('ui.sidepanel-content');
$APPLICATION->ShowHead();

$APPLICATION->IncludeComponent(
    'bitrix:ui.form',
    '.default',
    $arResult['form']
);


?>
