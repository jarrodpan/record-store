<?php
global $conn;

$items = Item::getLastest($conn);

echo '<!--';
var_dump($items);
echo '-->';

?>
<style>
.card-price {
	padding-top:inherit;
	padding-bottom:inherit;
	text-align: right;
}

</style>

<div class='container-fluid'>
	<div class='row'>
		<?php
	$counter = 0;
	foreach($items as $item)
	{
		$counter++;
		?>
		<div class='col-sm-3'>
			<div class='card'>
			<img class="card-img-top" src="<?=$item->getImageURL();?>" alt="Card image">
			
			<div class='card-body d-flex'>
				<div class='flex-grow-1'>
				<h4 class='card-title'><?=$item->title;?></h4>
				<p class='card-text'><?=$item->tags['Artist'][0]['tag'];?></p>
				</div>
				<div class='card-price align-self-stretch'>
				$ <?=$item->getPrice();?>
				</div>
			</div>
			</div>
		</div>
		<?php
		
		if ($counter % 4 == 0)
		{
			?>
			</div><div class='row'>
			<?php
		}
		
	}
?>
	</div>
</div>