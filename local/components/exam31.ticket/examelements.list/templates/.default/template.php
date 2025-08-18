<?php B_PROLOG_INCLUDED === true || die();
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\Extension;
use Bitrix\Main\Web\Json;
use Bitrix\UI\Toolbar\Facade\Toolbar;
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */
$APPLICATION->SetTitle(Loc::getMessage('EXAM31_ELEMENTS_LIST_PAGE_TITLE'));
\Bitrix\Main\UI\Extension::load('ui.sidepanel-content');
?>


<?
$APPLICATION->IncludeComponent(
	'bitrix:main.ui.filter',
	'',
	$arResult["filter"]
);

$APPLICATION->IncludeComponent(
	'bitrix:main.ui.grid',
	'',
	$arResult["grid"],
	$component,
);

?>

