<?php

namespace Exam31\Ticket;

use Bitrix\Main\UI\PageNavigation;

final class PageNavigationFactory
{

    public function create(int $size, int $count): PageNavigation
    {
        $nav = new PageNavigation('n');
        $nav->allowAllRecords(true)
            ->setPageSize($size)
            ->setPageSizes([
                ['NAME' => '20', 'VALUE' => '20'],
                ['NAME' => '10', 'VALUE' => '10'],
                ['NAME' => '50', 'VALUE' => '50'],
            ])
            ->setRecordCount($count)
            ->initFromUri();

        return $nav;
    }
}