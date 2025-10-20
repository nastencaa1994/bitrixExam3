<?php B_PROLOG_INCLUDED === true || die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Error;
use Bitrix\Main\Errorable;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\ErrorableImplementation;
use Bitrix\Main\Grid\Options as GridService;

use Exam31\Ticket\SomeElementTable;
use Exam31\Ticket\InfoTable;
use Exam31\Ticket\PageNavigationFactory;


class ExamElementsListComponent extends CBitrixComponent implements Errorable
{
    use ErrorableImplementation;

    protected const DEFAULT_PAGE_SIZE = 20;
    protected const GRID_ID = 'EXAM31_GRID_ELEMENT';
    protected const FILTER_ID = 'EXAM31_FILTER_ELEMENT';
    protected array $filter = [];
    protected object $gridService;
    protected object $navigation;
    const SORTABLE_FIELDS = array('ID', 'ACTIVE');

    public function __construct($component = null)
    {
        parent::__construct($component);
        $this->errorCollection = new ErrorCollection();

        $this->gridService = new GridService(static::GRID_ID);
        $navigationParameters = $this->gridService->GetNavParams();
        $PageNavigationFactory = new PageNavigationFactory();

        $filterOptions = new \Bitrix\Main\UI\Filter\Options($this->arResult["filter"]['FILTER_ID']);

        $filterFields = $filterOptions->getFilter([
            ['id' => 'TITLE', 'type' => 'string', 'name' => 'TITLE'],
        ]);


        foreach ($filterFields as $key => $val) {
            if ($key == 'TITLE') {
                $this->filter['%TITLE'] = $val;
            }
        }

        $this->navigation = $PageNavigationFactory->create(
            $navigationParameters['nPageSize'], SomeElementTable::count($this->filter)
        );

    }
    private function displayErrors(): void
    {
        foreach ($this->getErrors() as $error) {
            ShowError($error->getMessage());
        }
    }

    public function executeComponent(): void
    {
        if ($this->hasErrors()) {
            $this->displayErrors();
            return;
        }

        $this->arResult['filter'] = [
            'FILTER' => [
                ['id' => 'TITLE',
                    'type' => 'string',
                    'name' => 'TITLE'
                ]

            ],
            'GRID_ID' => static::GRID_ID,
            'FILTER_ID' => static::FILTER_ID,
            'ENABLE_LIVE_SEARCH' => true,
            'ENABLE_LABEL' => true
        ];

        $params['filter'] = $this->filter;
        $params['offset'] = $this->navigation->getCurrentPage() == 1 ? 0 : ($this->navigation->getCurrentPage() - 1) * $this->navigation->getPageSize();
        $params['limit'] = $this->navigation->getPageSize();
        $params['count_total'] = SomeElementTable::count($this->filter);
        $this->arResult['ITEMS'] = $this->getSomeElementList($params);
        $this->arResult['grid'] = $this->prepareGrid($this->arResult['ITEMS']);

        $this->arResult['toolbar']['button']['addElement'] =  new \Bitrix\UI\Buttons\Button([
            "link" => $this->getDetailPageUrl('0'),
            "text" => "Добавить"
        ]);
        echo '</pre>';

        $this->includeComponentTemplate();
    }

    protected function getSomeElementList($paramsSomeElement): array
    {

        $items = SomeElementTable::getList($paramsSomeElement)->fetchAll();
        $infoList = InfoTable::getList()->fetchAll();

        $preparedItems = [];
        foreach ($items as $item) {
            if (!isset($item['INFO'])) {
                $item['INFO'] = 0;
            }
            foreach ($infoList as $info) {
                if ($info['ELEMENT_ID'] == $item['ID']) {

                    $item['INFO'] = $item['INFO'] + 1;
                }
            }
            $item['INFO'] = 'Инфо: ' . $item['INFO'];
            $item['DETAIL_URL'] = $this->getDetailPageUrl($item['ID']);
            $item['DATE_MODIFY'] = $item['DATE_MODIFY'] instanceof DateTime
                ? $item['DATE_MODIFY']->toString()
                : null;

            $preparedItems[] = $item;
        }

        return $preparedItems;
    }

    protected function prepareGrid($items): array
    {

        $sort = $this->gridService->getSorting(['sort' => ['ID' => 'DESC']]);

        return [
            'GRID_ID' => static::GRID_ID,
            'COLUMNS' => $this->getGridColums(),
            'ROWS' => $this->getGridRows($items),
            'AJAX_MODE' => 'Y',
            'AJAX_OPTION_JUMP' => 'N',
            'CURRENT_PAGE' => $this->navigation->getCurrentPage(),
            'NAV_PARAM_NAME' => 'number_page',
            'AJAX_OPTION_HISTORY' => 'Y',
            'NAV_OBJECT' => $this->navigation,
            'SHOW_PAGESIZE' => true,
            'PAGE_SIZES' => $this->navigation->getPageSizes(),
            'TOTAL_ROWS_COUNT' => $this->navigation->getRecordCount(),
        ];
    }

    protected function getGridColums(): array
    {
        $fieldsLabel = SomeElementTable::getFieldsDisplayLabel();
        return [
            [
                'id' => 'ACTIVE',
                'default' => true,
                'name' => $fieldsLabel['ACTIVE'] ?? 'ACTIVE',
                'sort' => 'ACTIVE',
                'first_order' => 'desc',
                'type' => 'int',
            ],
            [
                'id' => 'ID',
                'default' => true,
                'name' => $fieldsLabel['ID'] ?? 'ID',
                'sort' => 'ID',
                'first_order' => 'desc',
                'type' => 'int',
            ],
            ['id' => 'DATE_MODIFY', 'default' => true, 'name' => $fieldsLabel['DATE_MODIFY'] ?? 'DATE_MODIFY'],
            ['id' => 'TITLE', 'default' => true, 'name' => $fieldsLabel['TITLE'] ?? 'TITLE'],
            ['id' => 'TEXT', 'default' => true, 'name' => $fieldsLabel['TEXT'] ?? 'TEXT'],
            ['id' => 'INFO', 'default' => true, 'name' => Loc::getMessage('EXAM31_ELEMENTS_LIST_GRIG_COLUMN_INFO_NAME')],
            ['id' => 'DETAIL', 'default' => true, 'name' => Loc::getMessage('EXAM31_ELEMENTS_LIST_GRIG_COLUMN_DETAIL_NAME')],
        ];
    }


    protected function getGridRows(array $items): array
    {
        if (empty($items)) {
            return [];
        }

        $rows = [];
        foreach ($items as $key => $item) {
            $rows[$key] = [
                'id' => $item["ID"],
                'columns' => [
                    'ID' => $item["ID"],
                    'DATE_MODIFY' => $item["DATE_MODIFY"],
                    'TITLE' => $item["TITLE"],
                    'TEXT' => $item["TEXT"],
                    'ACTIVE' => $item["ACTIVE"] ? 'Да' : 'Нет',
                    'DETAIL' => $this->getDetailHTMLLink($item["DETAIL_URL"], Loc::getMessage('EXAM31_ELEMENTS_LIST_GRIG_COLUMN_DETAIL_NAME')),
                    'INFO' => $this->getDetailHTMLLink('/exam31/info/' . $item["ID"] . '/', $item["INFO"]),
                ],
                'actions' => [
                    [
                        'text' => Loc::getMessage('EXAM31_ELEMENTS_LIST_GRIG_COLUMN_DETAIL_NAME'),
                        'href' => $item["DETAIL_URL"],
                    ],
                    [
                        'text' => Loc::getMessage('EXAM31_ELEMENTS_LIST_GRIG_COLUMN_INFO_NAME'),
                        'href' => '/exam31/info/' . $item["ID"] . '/', $item["INFO"],
                    ],
                ]
            ];
        }

        return $rows;
    }

    protected function getDetailPageUrl(int $id): string
    {
        return str_replace('#ELEMENT_ID#', $id, $this->arParams['DETAIL_PAGE_URL']);
    }

    protected function getDetailHTMLLink(string $detail_url, string $name): string
    {
        return "<a href=\"" . $detail_url . "\">" . $name . "</a>";
    }
}