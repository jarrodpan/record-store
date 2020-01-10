<div class='container'>
<div class='row'>
	
<style>
.barcode-sku-img, .barcode-id-img {
	max-height: 40px;
	overflow: hidden;
	width: 100%;
}
.barcode-sku-img>img, .barcode-id-img>img {
	max-width: 100%;
}
.barcode-label {
	text-align: center;
	border: #efefef solid 1px;
}
.barcode-title, .barcode-nfs {
	font-weight: 700;
}
.barcode-price, .barcode-title, .barcode-artists, .barcode-nfs {
	font-size: 1.5em;
}
</style>
<?php

foreach($data as $label)
{
	[$item, $tag, $qty] = $label;
	for ($i = 0; $i < $qty; $i++)
	{
		$artists = [];
		foreach($tag['Artist'] as $artist)
		{
			$artists[] = $artist['tag'];
		}
		// add 's' to artist(s)
		//$artistPlural = (count($artists) > 1 ? 's' : '');
		?>
		<div class='col-sm-3 barcode-label'>
		<div class="barcode-price">$<?=$item->getPrice();?></div>
		<div class="barcode-title"><?=$item->title;?></div>
		<div class="barcode-artists"><?=implode(', ',$artists);?></div>
		<!--div class="barcode-sku-img"><img src="http://www.barcode-generator.org/zint/api.php?bc_number=60&bc_data=<?=urlencode($item->sku);?>" /></div-->
		<br />
		<div class="barcode-sku"><?=$item->sku;?></div>
		<div class="barcode-id-img"><img src="http://www.barcode-generator.org/zint/api.php?bc_number=60&bc_data=<?=urlencode($item->id);?>" /></div>
		<div class="barcode-sku">ID: <?=$item->id;?></div>
		<span class="barcode-nfs"><?=($item->available ? '&nbsp;' : 'NOT FOR SALE');?></span>
		</div>
		<?php
	}
}

?>
</div>
</div>