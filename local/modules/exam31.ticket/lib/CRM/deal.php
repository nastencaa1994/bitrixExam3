<?php
namespace Exam31\Ticket\CRM;
use \Bitrix\Lists\Internals\Error;
class Deal
{
    public static function blockFiled(&$date)
    {
        if(isset($date["UF_CRM_1760972018047"])) {
           $error = new Error\Error();
            $error->add('«Вы не можете изменить значение поля «Защищенное поле»»');
            return $error->getMessage();
        }
    }
}