<?php
namespace Exam31\Ticket;

use Bitrix\Main\Page\Asset;

class Handler
{
    public static function addButtonMenu()
    {
        global $USER;
        if(!$USER->isAdmin()) return;

        Asset::getInstance()->addString(
            "<script>
            BX.ready(function () {                
                const extraBtnBox = document.querySelector('.menu-extra-btn-box');
                if (extraBtnBox === null) {
                    console.warn('Extra btn box is missing.');
                    return;
                }
                
                const menuItem = document.createElement('a');
                menuItem.classList.add('ui-btn')
                menuItem.innerText = 'админка';
                menuItem.style.display = 'block';
                menuItem.style.marginLeft = '15px';
                menuItem.href = '/bitrix/admin/';
           
                extraBtnBox.parentNode.insertBefore(menuItem, extraBtnBox);
            
            });
            </script>"

        );
    }

    public static function addEvent()
    {
        $eventManager = \Bitrix\Main\EventManager::getInstance();

        $eventManager->addEventHandlerCompatible(
            'crm',
            'OnBeforeCrmDealUpdate',
            function( &$arFields )
            {
                if(isset($arFields["UF_CRM_1760972018047"])) {

                    $arFields['RESULT_MESSAGE'] ='Вы не можете изменить значение поля «Защищенное поле»';
                    return false;
                } else {
                    return true;
                }
            }
        );
    }
}