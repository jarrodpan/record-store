<?php

/**
 * @param $data call with $data variable set
 */

foreach($data as $cat => $tags)
{
	//echo 
	?>
	<div class="container pt-4">
	<h3><?=$cat;?> <span class="" style="font-size: small;color:gray;">ID: <?=$tags[0]['c_id'];?> Permalink: <?=$tags[0]['c_permalink'];?></span></h3>
	<?php
	foreach($tags as $key => $val)
	{
		if(is_null($val['tag'])) continue;
		?>
		<div class='btn-group'>
		<button type='button' class='btn btn-info btn-sm dropdown-toggle' data-toggle="dropdown"><?=$val['tag'];?></button>
		<div class="dropdown-menu">
		<div class="dropdown-item" href="#">ID: <?=$val['id'];?></div>
		<div class="dropdown-item" href="#">Permalink: <?=$val['permalink'];?></div>
		</div>
		</div>
		<?php
	}
	?>
	<form action="<?=self::rootPath();?>/tags/add" method="post">
		<input type="hidden" name="c_id" value="<?=$tags[0]['c_id'];?>">
	<div class="input-group mb-3 mt-2">
    <div class="input-group-prepend">
      <span class="input-group-text">Add New Tag</span>
    </div>
	<input type="text" class="form-control" name="tag" placeholder="Tag name">
	<div class="input-group-append">
		<button type='submit' class='btn btn-outline-info'><i class="fa fa-plus"></i></button>
	</div>
  </div>
	
	</form>
	</div>
	<?php
	
	
}
?>
<div class="container pt-4">
<h3>Add Category</h3>
<form action="<?=self::rootPath();?>/tags/add/category" method="post">
	<div class="input-group mb-3 mt-2">
    <div class="input-group-prepend">
      <span class="input-group-text">Add New Category</span>
    </div>
	<input type="text" class="form-control" name="category" placeholder="Category name">
	<div class="input-group-append">
		<button type='submit' class='btn btn-outline-info'><i class="fa fa-plus"></i></button>
	</div>
  </div>
</div>