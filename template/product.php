<?php

/**
 * @param $data call with $data = [$item, $tags];
 */

 [$item, $tags] = $data;
 //var_dump ($item, $tags);

foreach($item as $key => $val)
{
	//echo $key." ".$val;
	?>
	<div class="btn-group pt-1">
		<button type="button" class="btn btn-info"><?=strtoupper($key);?></button>
		<button type="button" class="btn btn-outline-info"><?=$val;?></button>
	</div>
	<?php
	
}
foreach($tags as $key => $val)
{
	//echo $key." ".$val;
	?>
	<div class="btn-group pt-1">
		<button type="button" class="btn btn-info"><?=strtoupper($val['category']);?></button>
		<button type="button" class="btn btn-outline-info"><?=$val['tag'];?></button>
	</div>
	<?php
	
}
?>