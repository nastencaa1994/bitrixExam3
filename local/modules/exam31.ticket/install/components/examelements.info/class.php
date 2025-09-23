<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Error;
use Bitrix\Main\Errorable;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\ErrorableImplementation;

use Exam31\Ticket\InfoTable;

class ExamElementsInfoComponent extends CBitrixComponent implements Controllerable, Errorable
{
	use ErrorableImplementation;
	private ?int $elementId = null;
	
	public function __construct($component = null)
	{
		parent::__construct($component);
		$this->errorCollection = new ErrorCollection();
	}

	function onPrepareComponentParams($arParams)
	{
		if (!Loader::includeModule('exam31.ticket'))
		{
			$this->errorCollection->setError(
				new Error(Loc::getMessage('EXAM31_TICKET_MODULE_NOT_INSTALLED'))
			);
			return $arParams;
		}

		if (isset($arParams['ELEMENT_ID']))
		{
			$this->elementId = (int) $arParams['ELEMENT_ID'] ?: null;
		}

		return $arParams;
	}

	private function displayErrors(): void
	{
		foreach ($this->getErrors() as $error)
		{
			ShowError($error->getMessage());
		}
	}

	function executeComponent(): void
	{
		if ($this->hasErrors())
		{
			$this->displayErrors();
			return;
		}

		$this->arResult['ELEMENTS'] = $this->getEntityData();

		$this->arResult['LIST_PAGE_URL'] = $this->arParams['LIST_PAGE_URL'];


		$this->includeComponentTemplate();

		global $APPLICATION;
		$APPLICATION->SetTitle(Loc::getMessage('EXAM31_ELEMENT_DETAIL_TITLE', ['#ID#' => $this->arResult['ELEMENT']['ID']]));
	}

	protected function getEntityData(): array
	{
		if (!$this->elementId)
		{
			return [];
		}

        $elements = InfoTable::getList(['filter'=>['ELEMENT_ID'=>$this->elementId]])->fetchAll();

		return $elements;
	}

    public function configureActions(): array
    {
        return [];
    }

}