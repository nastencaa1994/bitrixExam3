<?php

namespace Exam\Module\orm;

use Bitrix\Main\Entity;

class InfoEx3_20Table extends Entity\DataManager
{
    static function getTableName(): string
    {
        return 'exam3_20_info';
    }

    static function getMap(): array
    {
        return array(
            (new Entity\IntegerField('ID'))
                ->configurePrimary()
                ->configureAutocomplete(),

            (new Entity\StringField('TITLE'))
                ->configureRequired(),
            (new Entity\IntegerField('ELEMENT_ID'))
                ->configureRequired(),
        );
    }
}