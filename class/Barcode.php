<?php

class Barcode {
	
	protected Item $item;
	protected Tags $tags;
	protected $qty;
	
	public static function getBarcodeItems($conn = null)
	{
		$st = <<<sql
		select * from Barcodes
		sql;
		//$q = $conn->prepare($st);
		$q = $conn->query($st);
		
		// todo: make barcode objects
		$out = [];
		while(($row = $q->fetch(PDO::FETCH_ASSOC)) != false)
		{
			//var_dump($row['id']);
			$i = Item::getItemByID($conn, $row['itemID']);
			$t = Tags::getTagsByItemID($conn, $row['itemID'],true);
			$out[] = [$i, $t, intval($row['quantity'])];
		}
		
		return $out;
		
		//return $q->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public static function addItemToQueue($conn, $id, $quantity=1)
	{
		$sql = <<<sql
			insert into Barcodes (itemID, quantity) values (?, ?)
		sql;
		
		$q = $conn->prepare($sql);
		
		$q->execute([$id, $quantity]);
	}
	
	public function construct($item, $tags, $qty = 1)
	{
		$this->item = $item;
		$this->tags = $tags;
		$this->qty = $qty;
	}
	
}