<?php

class User {
	
	private $var;
	
	
	public static function addUser($conn, $uname,$fname,$email, $pw, $access)
	{
		// todo: check for existing names
		$st = <<<sql
			insert into users (username, firstname, pword, email, access)
			values (?,?,?,?,?);
		sql;
		
		//$conn->beginTransaction();
		$pass = hash("sha256",$pw);
		//die($pass);
		$q = $conn->prepare($st);
		$args = [$uname, $fname, $pass, $email, $access];
		//var_dump($args);
		$q->execute($args);
		
		// 24048638d9fd0361c3d8d49d61db8955299b074795f72070ab9bffd49351cb46
		
		// get last ID entered and commit
		
		//die();
		//$conn->commit();
		
		$id = $conn->query("select last_insert_id() as id from users");
		$output = ($id->fetch())['id'];
		
		if(!$output) var_dump($conn->errorInfo());
		
		return $output;
		
	}
	
	public static function loginUser($conn, $login, $password)
	{
		trim($login);
		trim($password);
		$hash = hash("sha256", $password);
		
		// TODO: remove select * and only get relevant columns
		$st = <<<sql
			select * from users
			where pword = :pass and (username = :login or email = :login)
			limit 1;
		sql;
		
		$q = $conn->prepare($st);
		
		$q->execute([':login' => $login, ':pass' => $hash]);
		
		//return $q->fetch(PDO::FETCH_ASSOC);
		$user = $q->fetchObject(__CLASS__);
		
		// touch last login if successful
		if ($user) $conn->query("update users set lastLogin = CURRENT_TIMESTAMP where id = ".$user->id);
		
		return $user;
	}
	
	public static function checkAuth($path = '/login', $successPath = false)
	{
		// the idea here is to direct to login page, and if logged in direct away from login page
		// if not logged in, direct to login form
		// if logged in and redirect set, go there instead
		// may need to refine the logic
		
		// not logged in? go away
		if (!isset($_SESSION['loggedin']) && !$successPath) Router::redirect($path);
		// if logged in 
		if (isset($_SESSION['loggedin']) && $successPath) Router::redirect($successPath);
	}
	
	public static function isAuthenticated()
	{
		return isset($_SESSION['loggedin']);
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