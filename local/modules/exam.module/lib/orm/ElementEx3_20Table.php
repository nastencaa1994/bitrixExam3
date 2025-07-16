<?php
namespace Exam\Module\orm;

use Bitrix\Main\Entity;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Localization\Loc;

class ElementEx3_20Table extends Entity\DataManager
{
    static function getTableName(): string
    {
        return 'exam3_20_element';
    }

    static function getMap(): array
    {
        return array(
            (new Entity\IntegerField('ID'))
                ->configurePrimary()
                ->configureAutocomplete(),
            (new Entity\BooleanField('ACTIVE'))
                ->configureRequired(),
            (new Entity\DatetimeField('DATE_MODIFY'))
                ->configureRequired()
                ->configureDefaultValue(new DateTime()),
            (new Entity\StringField('TITLE'))
                ->configureRequired(),
            new Entity\TextField('TEXT'),
        );
    }
}