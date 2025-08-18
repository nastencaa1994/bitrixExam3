<?php

namespace Exam31\Ticket\SidePanel;

use Bitrix\Main\Page\Asset;

class SideConfiguration
{

    public static function addUrlSidePanel()
    {
        Asset::getInstance()->addString('
        <script>
        BX.SidePanel.Instance.bindAnchors({
	rules:
	[
		
		{
			condition: [ 
				new RegExp("/exam31/info/[0-9]+/", "i") 
			],
        		loader: "crm:entity-details-loader"
		},
		{
			condition: [ 
				new RegExp("/exam31/detail/[0-9]+/", "i") 
			],
        		loader: "crm:entity-details-loader"
		},
		
	]
});
</script>
        ');


    }
}