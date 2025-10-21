<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Bizproc\Activity\BaseActivity;
use Bitrix\Bizproc\FieldType;
use Bitrix\Main\ErrorCollection;
use Bitrix\Bizproc\Activity\PropertiesDialog;
use Bitrix\Main\Text\HtmlFilter;
use Exam31\Ticket\SomeElementTable;
use Bitrix\Main\Loader;

class CBPExamTicketActivity extends BaseActivity
{
	public function __construct($name)
	{
		parent::__construct($name);

		$this->arProperties = [
			'ID' => 0,
			'DEMO_VALUE' => null,
		];

		$this->SetPropertiesTypes([
			//'DEMO_VALUE' => ['Type' => FieldType::STRING],
			'DATE_MODIFY' => ['Type' => FieldType::DATETIME],
			'TEXT' => ['Type' => FieldType::STRING],
			'TITLE' => ['Type' => FieldType::STRING],
			'ACTIVE' => ['Type' => FieldType::BOOL],
		]);

        Loader::includeModule('exam31.ticket');

	}

	protected static function getFileName(): string
	{
		return __FILE__;
	}

	protected function internalExecute(): ErrorCollection
	{
		$errors = parent::internalExecute();

		/*
		/Демо
		*/
		$elementId = (int) $this->preparedProperties["ID"];

        $res = SomeElementTable::getById($elementId)->fetch();

        if($res)
		{
			//Значения найдены
			$this->preparedProperties['DATE_MODIFY'] = $res['DATE_MODIFY'];
			$this->preparedProperties['TITLE'] = $res['TITLE'];
			$this->preparedProperties['TEXT'] = $res['TEXT'];
			$this->preparedProperties['ACTIVE'] = $res['ACTIVE'];

			//$this->preparedProperties['DEMO_VALUE'] = HtmlFilter::encode('DEMO_VALUE');
		}
		else
		{
			//Если нет данных, отдаем пустые значения
			$this->preparedProperties['ID'] = 0;
            $this->preparedProperties['ELEMENT_FIELDS'] = '';

			//Пишем в журнал выполнения БП что данные не нашли
			$this->log(
				Loc::getMessage(
					'EXAM31_TICKET_ACTIVITY_LOG_TEXT_N',
					[
						'#ID#' => $elementId,
					]
				)
			);
		}
		/*
		*
		*/
		
		return $errors;
	}

	public static function getPropertiesDialogMap(?PropertiesDialog $dialog = null): array
	{
		$map = [
			'ID' => [
				'Name' => 'ID',
				'FieldName' => 'ID',
				'Type' => FieldType::INT,
				'Required' => true,
			],
		];
		
		return $map;
	}
}