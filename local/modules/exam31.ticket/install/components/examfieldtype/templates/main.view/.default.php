<?php
defined('B_PROLOG_INCLUDED') || die;
?>

<span class="fields string field-wrap">
	<?php
	foreach ($arResult['PREPARED_VALUES'] as $item)
	{ ?>
		<span class="fields string field-item">
            <a href="<?=$item['URL_VALUE']?>"><?=$item['FORMATTED_VALUE']?></a>
		</span>
		<?php
	} ?>
</span>