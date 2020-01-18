<?php

class Item {
	
	private $conn;
	protected $var = [];
	
	// constructor
	public function __construct($db = null)
	{
		$this->conn = $db;
		//$var = [];
	}
	
	public static function getLastest($conn, $count = 15)
	{
		$st = <<<sql
			select * from items order by id desc limit ?
		sql;
		
		$q = $conn->preapre($st);
		$q->exec([$count]);
		
		$out = [];
		while($item = $q->fetchObject(__CLASS__))
		{
			$item->tags = Item::getTagsByID($item->id);
			$out[] = $item;
		}
		return $out;
	}
	
	public static function getByID($id, $conn = null)
	{
		$out = self::getItemByID($conn, $id);
		$tags = self::getTagsByID($id, $conn);
		
		//var_dump($tags);
		
		foreach($tags as $tag)
		{
			// isset didnt work - use work arround
			/*if(!is_null($out->{$tag['apiname']}))
			{
				$r = $out->{$tag['apiname']};
				array_push($r, [$tag['category'], $tag['tag']]);
				$out->{$tag['apiname']} = $r;
			}
			else
			{
				$out->{$tag['apiname']} = [[$tag['category'], $tag['tag']]];
			}*/
			// isset didnt work - use work arround
			if(@!is_null($out->{$tag['apiname']}))
			{
				$r = $out->{$tag['apiname']};
				array_push($r, $tag['tag']);
				$out->{$tag['apiname']} = $r;
			}
			else
			{
				$out->{$tag['apiname']} = ['tag' => $tag['category'], $tag['tag']];
			}
		}
		
		return $out;
	}
	
	public static function getItemByID($conn, $id)
	{
		//if(is_null($this->conn)) $this->conn = $db;
		$st = "select * from items where id = ?";
		$q = $conn->prepare($st);
		$q->execute([$id]);
		return $q->fetchObject('Item');
		//return $q->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public static function getTagsByID($id, $conn = null)
	{
		//if(is_null($this->conn)) $this->conn = $db;
		$st = <<<sql
			select C.apiname, C.category, T.tag from itemtags as IT
			left join Tags as T
			on IT.tagID = T.id
			left join categories as C
			on T.categoryID = C.id
			where IT.itemID = ?
		sql;
		$q = $conn->prepare($st);
		$q->execute([$id]);
		// todo: group tags
		return $q->fetchAll(PDO::FETCH_ASSOC);
	}
	
	// add new item to database and return the item ID
	public static function addItem($conn, $sku, $title, $price, $quantity="1", $taxable="0", $tangible="0", $available="0", $listed="0", $description="", $notes ="")
	{
		// add the item entry
		$st = <<<sql
			insert into Items (sku, title, price, quantity, taxable, tangible, available, listed, description, notes)
			values (:sku, :title, :price, :quantity, :taxable, :tangible, :available, :listed, :description, :notes);
		sql;
		
		// start transaction
		$conn->beginTransaction();
		
		$q = $conn->prepare($st);
		$q->execute([
			':sku' => $sku,
			':title' => $title,
			':price' => $price,
			':quantity' => $quantity,
			':taxable' => $taxable,
			':tangible' => $tangible,
			':available' => $available,
			':listed' => $listed,
			':description' => $description,
			':notes' => $notes,
			]);
		
		// get last ID entered and commit
		$id = $conn->query("select last_insert_id() as id from items");
		$output = ($id->fetch())['id'];
		//die();
		$conn->commit();
		
		return (int)$output;
	}
	
	
	// add new item to database and return the item ID
	public static function updateItem($conn, $id, $sku, $title, $price, $quantity="1", $taxable="0", $tangible="0", $available="0", $listed="0", $description="", $notes ="")
	{
		// add the item entry
		$st = <<<sql
			update Items 
			set
				sku = :sku,
				title = :title,
				price = :price,
				quantity = :quantity,
				taxable = :taxable,
				tangible = :tangible,
				available = :available,
				listed = :listed,
				description = :description,
				notes = :notes
			where id = :id
		sql;
		
		// start transaction
		$conn->beginTransaction();
		
		$q = $conn->prepare($st);
		$q->execute([
			':id' => $id,
			':sku' => $sku,
			':title' => $title,
			':price' => $price,
			':quantity' => $quantity,
			':taxable' => $taxable,
			':tangible' => $tangible,
			':available' => $available,
			':listed' => $listed,
			':description' => $description,
			':notes' => $notes,
			]);
		
		// get last ID entered and commit
		$id = $conn->query("select last_insert_id() as id from items");
		$output = ($id->fetch())['id'];
		//die();
		$conn->commit();
		
		return (int)$output;
	}
	
	public static function addTag($conn, $itemID, $tagID)
	{
		$st = <<<sql
			insert into itemtags (itemid, tagid) values (?, ?)
		sql;
		
		$q = $conn->prepare($st);
		if($q->execute([$itemID, $tagID])){
			return true;
		}
		var_dump($q->errorCode());
		die();
		return false;
	}
	
	public static function removeTag($conn, $itemID, $tagID)
	{
		$st = <<<sql
			delete from itemtags where itemid = ? and tagid = ?
		sql;
		
		$q = $conn->prepare($st);
		if($q->execute([$itemID, $tagID])){
			return true;
		}
		var_dump($q->errorCode());
		die();
		return false;
	}
	
	// links image to item
	public static function addImage($conn, $itemid, $imageid)
	{
		
	}
	
	//public static function uploadImage($conn, )
	
	public static function getImageByItemID($conn, $id)
	{
		$st = <<<sql
			select * from itemimages
			left join images on imageid = id
			where itemid = ?
			order by linked asc
			limit 1
		sql;
		
		$q = $conn->prepare($st);
		$row = $q->execute([$id]);
		
		return $row->fetch();
	}
	
	// Magic methods for get/set
	
	public function __get($name)
	{
		return $this->var[$name];
	}
	
	public function __set($name, $val)
	{
		$this->var[$name] = $val;
		return true;
	}
	
	public function getPrice()
	{
		// get total in cents ie 4000 = $40.00
		$total = intval($this->var['price']);
		$d = $total/100;
		$c = $total%100;
		return sprintf("%d.%02d",$d,$c);
		//return "$d.$c";
	}
	
	public function toArray()
	{
		return $this->var;
	}
	
	public function json()
	{
		$out = $this->var;
		
		/*
		foreach($out as $attr)
		{
			if (is_array($attr)) { continue; }
			else {
				unset($attr['tag']);
			}
		}
		*/
		return json_encode($out);
		//return json_encode($this->var);
	}
	
}
