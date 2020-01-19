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
	font-size: large;
	font-weight: bold;
	color: #6B5B95;
}

.item-img {
	max-width: 100%;
	padding-top: 100%;
	position: relative;
	overflow: hidden;
}

.card-img-top {
	position: absolute;
	top: 0;
	left: 0;
	bottom: 0;
	right: 0;
}

.card {
	height: calc(100%);
}

</style>

<div class='container-fluid'>
	<div class='row'>
		<?php
	$counter = 0;
	
	$perRow = 6; // make sure this is divisible by 12
	$colCSS = 'col-sm-'.(12/$perRow); // 
	
	foreach($items as $item)
	{
		$counter++;
		?>
		<div class='<?=$colCSS;?>'>
			<div class='card'>
				<div class='item-img'>
			<img class="card-img-top" src="<?=$item->getImageURL();?>" alt="Card image">
				</div>
			<div class='card-body d-flex'>
				<div class='flex-grow-1'>
				<h4 class='card-title align-self-stretch'><?=$item->title;?></h4>
				<p class='card-text'><?=$item->tags['Artist'][0]['tag'];?></p>
				</div>
				<div class='card-price align-self-stretch'>
				$<?=$item->getPrice();?>
				</div>
			</div>
			</div>
		</div>
		<?php
		
		if ($counter % $perRow == 0)
		{
			?>
			</div><div class='row'>
			<?php
		}
		
	}
?>
	</div>
</div>