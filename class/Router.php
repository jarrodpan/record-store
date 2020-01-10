<?php

class Router {
	
	//    preg_match_all('/\/users\/([1-9]++)\/?/g','/users/1',$m); var_dump($m);
	
	private $callbacks;
	private $root = "";
	private $prefix = "";
	public static $ends = true;
	
	// on calling get() post() put() delete() put callbacks in array
	// todo: maybe make this some sort of tree?
	// right now gonna divide by http method (get post etc)
	
	// @usage map(['get','post',...], $path, function(){})
	public function map($methods, $path, $callback)
	{
		// eg ['POST', 'PUT']
		if (is_array($methods))
		{
			foreach($methods as $method)
			{
				$this->{$method}($path, $callback);
			}
		}
		else $this->{$methods}($path, $callback);
	}
	
	/**
	 * @usage get('/', function() {} );
	 */
	public function __call($name,$args)
	{
		$regex = urldecode($this->prefix.$args[0]);
		// magic to detect trailing slash
		$endSlash = ($regex[-1] == '/' ? '?' : '\/?');
		// special case: root only '/'
		// havent seen bugs just making sure they dont pop up
		// stupid strcmp() returns 0 if equal...
		if(strcmp($regex,'/') == 0) $endSlash = '';
		
		// could do this with regex for named parameters but eh
		// escape slashes and add trailling slash
		$regex = str_ireplace('/','\/',$regex).$endSlash;
		// replace parameters with regex
		$regex = str_ireplace('{i}', '([0-9]++)', $regex);
		//$regex = str_ireplace('{s}', '([A-Za-z0-9_\s]++)', $regex);
		$regex = str_ireplace('{s}', '(.++)', $regex);
		$regex = str_ireplace('{*}', '([A-Za-z0-9_\/\s]++)', $regex);
		// wrap in regex command syntax (for start and end of string)
		$regex = '/^'.$regex.'$/';
		
		// split path => function into array
		$this->callbacks[strtoupper($name)][$args[0]] = [
			'path' => $args[0],
			'function' => $args[1],
			'regex' => $regex,
			//'method' => $args[0],
			];
	}
	
	public function prepend($f)
	{
		$this->callbacks['PREPEND'][] = $f;
	}
	
	public function append($f)
	{
		$this->callbacks['APPEND'][] = $f;
	}
	
	public function addEnds($x)
	{
		self::$ends = $x;
	}
	
	public function __construct($root = "", $prefix = "")
	{
		$this->root = $root;
		$this->prefix = $prefix;
		// can put allowed methods here in future
	}
	
	public static function redirect($uri)
	{
		header("Location: ".$root.$prefix.$uri);
		die();
	}
	
	// this will probably go as it messes up API calls
	public static function fileNotFound()
	{
		header($_SERVER['SERVER_PROTOCOL']." 404 File Not Found");
		echo "File not found ", $_SERVER['REQUEST_URI'];
	}
	
	// route requests
	public function __destruct()
	{
		// echo "debug: show routes\n";
		// var_dump($this->callbacks);
		// echo "debug: show requested route\n";
		// var_dump($_SERVER['REQUEST_URI']);
		// echo "routing requests...\n";
		
		// regex example: \/users\/([1-9]++)\/?
		
		// run any prepended closures
		if(self::$ends) foreach ($this->callbacks['PREPEND'] as $f) $f();
		
		// save request method
		$req = $_SERVER['REQUEST_METHOD'];
		
		// extract path and remove specified root
		$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$pos = false;
		if (strlen($this->root) >= 0) // strpos does not work with empty strings
			$pos = strpos($path, $this->root);
		//echo "pos: ".$pos;
		if ($pos !== false && $pos == 0) // root is at start
			$path = substr($path, strlen($this->root));
		
		// use pattern matching
		$routes = $this->callbacks[$req];
		$pathFound = false;
		foreach ($routes as $route)
		{
			// match path to regex routes
			//preg_match_all($route['regex'],$path,$m);
			preg_match($route['regex'],$path,$m);
			//echo 'route:',$route['path'],' path:',$path,' regex:',$route['regex'],PHP_EOL;
			//var_dump($m);
			
			if (count($m) > 0) // we have a match
			{
				$pathFound = true;
				//echo "match",PHP_EOL;
				array_shift($m);
				//var_dump($m);
				//$routes[$path]['function']($m);
				// call callback and pass variables to it
				call_user_func_array($route['function'], $m);
				break; // foreach ($routes as $route)
			}
			
		}
		// todo: fix this to not break stuff
		if (!$pathFound) self::fileNotFound();
		
		
		// run any appended closures
		if(self::$ends) foreach ($this->callbacks['APPEND'] as $f) $f();
		
	}
	
}

?>