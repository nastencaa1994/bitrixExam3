<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}
/**
 * @var array $arResult
 * @var CMain $APPLICATION
 * @var array $arParams
 */
$APPLICATION->IncludeComponent(
    'exam31.ticket:examelements.info',
    '.default',
    [
        'ELEMENT_ID' => $arResult['VARIABLES']['ID'] ?? null,
        'LIST_PAGE_URL' => $arResult['LIST_PAGE_URL'],
    ]
);