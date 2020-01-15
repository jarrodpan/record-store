<div class='container'>
	<div class="row">
<div class='card-columns'>
<?php for($i = 0; $i < 12; $i++) { ?>
<div class="card" style="" class="">
	<img class="card-img-top" src="<?=self::rootPath();?>/res/no-image.png" alt="Card image">
	<div class="card-body">
		<h4 class="card-title">John Doe</h4>
		<p class="card-text">Some example text.</p>
		<a href="#" class="btn btn-primary">See Profile</a>
	</div>
</div>

<?php } ?>
</div>
<?php


/*
foreach($data as $product)
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
*/
?>



</div>
</div>