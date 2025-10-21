<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use \Bitrix\Main\Localization\Loc;
use Bitrix\Bizproc\FieldType;

$arActivityDescription = [
	"NAME" => Loc::getMessage("EXAM31_TICKET_ACTIVITY_DESCR_NAME"),
	"DESCRIPTION" => Loc::getMessage("EXAM31_TICKET_ACTIVITY_DESCR_DESCR"),
	"TYPE" => "activity",
	"CLASS" => "ExamTicketActivity",
	"JSCLASS" => "BizProcActivity",
	"CATEGORY" => [
		"ID" => "other",
	],
	"RETURN" => [
		"ID" => [
			"NAME" => Loc::getMessage("EXAM31_TICKET_ACTIVITY_RESULT_ID"),
			"TYPE" => FieldType::INT,
		],
		"DATE_MODIFY" => [
			"NAME" => 'Дата изменения',
			"TYPE" => FieldType::DATETIME,
		],
		"TEXT" => [
			"NAME" => 'TEXT',
			"TYPE" => FieldType::STRING,
		],
		"TITLE" => [
			"NAME" => 'TITLE',
			"TYPE" => FieldType::STRING,
		],
		"ACTIVE" => [
			"NAME" => 'ACTIVE',
			"TYPE" => FieldType::BOOL,
		],
	],
];

?>