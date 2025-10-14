<?php
namespace Exam31\Ticket;

use Bitrix\Main\Entity;
use Bitrix\Main\ORM\Query\Filter\ConditionTree;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Localization\Loc;

class SomeElementTable extends Entity\DataManager
{
	static function getTableName(): string
	{
		return 'exam31_ticket_someelement';
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

	static function getFieldsDisplayLabel(): array
	{
		$fields = SomeElementTable::getMap();
		$res = [];
		foreach ($fields as $field)
		{
			$title = $field->getTitle();
			$res[$title] = Loc::getMessage("EXAM31_SOMEELEMENT_{$title}_FIELD_LABEL") ?? $title;
		}
		return $res;
	}

    public static function count($filter): int
    {
        $criteria = new ConditionTree();
        if (isset($filter['%TITLE'])) {

            $criteria->whereLike('TITLE', "%{$filter['%TITLE']}%");
        }

        try {
            return self::query()->where($criteria)->queryCountTotal();
        } catch (SystemException $e) {
            throw new ServiceException('Failed to count projects', previous: $e);
        }
    }
}