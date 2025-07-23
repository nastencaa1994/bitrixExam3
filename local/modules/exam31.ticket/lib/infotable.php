<?php
namespace Exam31\Ticket;

use Bitrix\Main\Entity;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Localization\Loc;

class InfoTable extends Entity\DataManager
{
    static function getTableName(): string
    {
        return 'exam31_ticket_info';
    }
    static function getMap(): array
    {
        return array(
            (new Entity\IntegerField('ID'))
                ->configurePrimary()
                ->configureAutocomplete(),
            (new Entity\StringField('TITLE'))
                ->configureRequired(),
            new Entity\IntegerField('ELEMENT_ID'),
        );
    }
}