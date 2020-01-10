<?php

/**
 * @param $data call with $data variable set
 */

//var_dump($data);
foreach($data as $cat => $tags)
{
	//echo 
	?>
	<h3><span class="badge badge-secondary">ID: <?=$tags[0]['c_id'];?></span> <?=$cat;?></h3>
	<table class="table table-hover table-sm">
	<?php
	foreach($tags as $key => $val)
	{
		//var_dump($key);echo "<br>";
		//var_dump($val);echo "<br>";
		if($key == 0)
		{
			?>
			<thead>
			<tr>
			<th>ID</th>
			<th>Tag</th>
			<th>Permalink</th>
			</tr>
			</thead>
			</tbody>
			<?php
		}
		
		?>
		<tr>
		<td><?=$val['id'];?></td>
		<td><?=$val['tag'];?></td>
		<td><?=$val['permalink'];?></td>
		</tr>
		<?php
	}
	?>
	</tbody>
	</table>
	<?php
	
	
}
?>