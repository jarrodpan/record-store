<?php

class Tags {
    
    private $conn;
    
    public static function getTags($conn = null)
    {
		if (is_null($conn)) $conn = $this->conn;
		$st = <<<sql
			select C.*, T.* from tags as T
			left join categories as C
			on T.categoryID = C.id
			order by C.category asc, T.tag asc
		sql;
		$q = $conn->query($st);
		
		return $q->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public static function searchTags($conn = null, $query = "")
    {
		if (is_null($conn)) $conn = $this->conn;
		$st = <<<sql
		select C.*, T.* from tags as T
		left join categories as C
		on T.categoryID = C.id
		where (
			-- remove special characters from results
			regexp_replace(lower(T.tag), '[^[:alnum:]]+', '') LIKE ?
			or regexp_replace(lower(C.category), '[^[:alnum:]]+', '') LIKE ?
			)
		order by C.category asc, T.tag asc;
		sql;
		
		// replace special characters from search with wildcard
		$sqlSearch = '%'.preg_replace('/[\W_\s]+/','%',$query).'%';
		//$q = $conn->query($st);
		$q = $conn->prepare($st);
		$q->execute([$sqlSearch, $sqlSearch]);
		
		return $q->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getTagsByCategory($conn)
    {
		//if (is_null($conn)) $conn = $this->conn;
		
		$st = <<<sql
			select C.id as c_id, C.permalink as c_permalink, C.category, T.* from categories as C
			left join tags as T
			on C.id = T.categoryID
			order by C.category asc, T.tag asc
		sql;
		
		$q = $conn->query($st);
		
		// get data and sort as it is fetched
		$out = [];
		while(($row = $q->fetch(PDO::FETCH_ASSOC)) != false)
		{
			if(isset($out[$row['category']]))
			{
				$out[$row['category']][] = $row;
			}
			else
			{
				$out[$row['category']] = [$row];
			}
		}
		
		// return sorted array
		return $out;
	}
	
	public static function getTagsByItemID($conn, $id, $cats = false)
    {
		//if (is_null($conn)) $conn = $this->conn;
		
		$st = <<<sql
			select C.id as c_id, C.permalink as c_permalink, C.category, T.* from categories as C
			left join tags as T
			on C.id = T.categoryID
			left join itemtags as IT
			on IT.tagID = T.id
			where IT.itemID = ?
			order by C.category asc, T.tag asc
		sql;
		
		$q = $conn->prepare($st);
		$q->execute([$id]);
		
		// get data and sort as it is fetched
		if ($cats)
		{
			$out = [];
			while(($row = $q->fetch(PDO::FETCH_ASSOC)) != false)
			{
				if(isset($out[$row['category']]))
				{
					$out[$row['category']][] = $row;
				}
				else
				{
					$out[$row['category']] = [$row];
				}
			}
			
			// return sorted array
			return $out;
		}
		
		// return sorted array
		return $q->fetchAll(PDO::FETCH_ASSOC);
    }
	
	public static function addTag($conn, $tag, $permalink, $catID)
	{
		$sql = <<<sql
			insert into Tags (tag, permalink, categoryID)
			values (?, ?, ?)
		sql;
		
		$conn->beginTransaction();
		$q = $conn->prepare($sql);
		
		$q->execute([$tag, $permalink, $catID]);
		$last = ($conn->query("select last_insert_id() as id from tags")->fetch())['id'];
		
		$conn->commit();
		return (int)$last;
		
	}
	
	
	public static function addCategory($conn, $cat, $permalink, $apiName)
	{
		$sql = <<<sql
			insert into Categories (category, permalink, apiName)
			values (?, ?, ?)
		sql;
		
		$conn->beginTransaction();
		$q = $conn->prepare($sql);
		
		$q->execute([$cat, $permalink, $apiName]);
		$last = ($conn->query("select last_insert_id() as id from categories")->fetch())['id'];
		
		$conn->commit();
		return (int)$last;
		
	}
	
	public static function slugify($words)
	{
		return strtolower(preg_replace("/([\W_]++)/", "-", $words));
	}
    
    public function __construct($db = null)
	{
		$this->conn = $db;
	}
    
	public function __get($name)
	{
		return $this->var[$name];
	}
	
	public function __set($name, $val)
	{
		$this->var[$name] = $val;
		return true;
	}
    
}

?>