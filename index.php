<?php

/**
 * index.php
 * 
 * Main entry point for web store/database access
 * 
 * @author Jarrod Pan
 * @param  p Logical path to resource
 */

// timing function
//$stime = microtime(true);

// start session for logins
session_start();

// include me to make problems go away
require('config.php');

// connect to DB... might move to closures in future
$conn = (new Database())->connect();

// determine application: store, admin, api etc
//$app = explode('/',urldecode($_GET['p']));

// check the first part of the path to detemine application
// default will be store front
// other paths: admin, api, images, stylesheets/js, etc
//switch($app[0])
switch($_SERVER['HTTP_HOST'])
{
	case 'djjadmin.localhost':
		$router = new Router($_ROOT);
		Template::root($_ROOT,'/admin');
		include('routes/admin.php');
		//var_dump($_SERVER);
		die();
		break;
	default:
		$router = new Router();//$_ROOT);
		Template::root();//$_ROOT);
}


// set templating system path

// start router
//$router = new Router($_ROOT);

// ------------------------ Admin Panel
// index file
$router->get('/', function () {
	Template::header('Latest Products');
});

// login function
$router->get('/login', function () {
	global $conn;
	Template::header('Login');
	//$user = User::loginUser($conn, 'jarrod', 'jarrod');
	var_dump($user);
	//$barcodes = Barcode::getBarcodeItems($conn);
	//var_dump($barcodes);
	//Template::addUser();
});

// ------------------- API interface
// tag autocomplete
$router->get('/api/v1/tags/search/{s}', function ($str) {
	global $conn;
	Template::addEnds(false);
	Router::$ends = false;
	// urldecode to get rid of %20 etc crap which messes up matching
	$tags = Tags::searchTags($conn,urldecode($str));
	
	// dummy class to output as json object
	$out = new stdClass();
	$out->tags = $tags;
	header('Content-Type: application/json');
	echo json_encode($out);
	//echo $str;
});

// api get tag by category by item id
$router->get('/api/v1/products/{i}/tags', function ($id) {
	global $conn;
	Template::addEnds(false);
	Router::$ends = false;
	// urldecode to get rid of %20 etc crap which messes up matching
	//$tags = Tags::getTagsByCategory($conn,$id,true);
	$tags = Tags::getTagsByItemID($conn,$id,false);
	
	// dummy class to output as json object
	$out = new stdClass();
	$out->tags = $tags;
	header('Content-Type: application/json');
	echo json_encode($out);
	//echo $str;
});

// api get item by id
$router->get('/api/v1/products/{i}', function ($id) {
	global $conn;
	Template::addEnds(false);
	Router::$ends = false;
	// urldecode to get rid of %20 etc crap which messes up matching
	//$tags = Tags::getTagsByCategory($conn,$id,true);
	$item = Item::getItemByID($conn,$id);
	// dummy class to output as json object
	//$out = new stdClass();
	//$out->tags = $tags;
	header('Content-Type: application/json');
	echo $item->json();
	//echo $str;
});

// add new item via api post request
$router->map(['post','put'],'/api/v1/products/add', function () {
	global $conn;
	Template::addEnds(false);
	Router::$ends = false;
	
	// retrive raw post data
	$data = file_get_contents("php://input");
	$parse = json_decode($data);
	//var_dump($data);
	// validate input
	
	// send sql insert
	
	// report status
	
	// dummy class to output as json object
	$out = new stdClass();
	$out->method = $_SERVER['REQUEST_METHOD'];
 	$out->data = $parse;
	//$out->tags = $tags;
	header('Content-Type: application/json');
	echo json_encode($out);
	//echo $str;
});

// $router->map(['PUT', 'DELETE'], '{*}', function () {
// 	global $conn;
// 	Template::addEnds(false);
// 	Router::$ends = false;
	
// 	// retrive raw post data
// 	$data = file_get_contents("php://input");
	
// 	//var_dump($data);
// 	// validate input
	
// 	// send sql insert
	
// 	// report status
	
// 	// dummy class to output as json object
// 	$out = new stdClass();
// 	$out->method = $_SERVER['REQUEST_METHOD'];
// 	$out->data = $data;
// 	//$out->tags = $tags;
// 	header('Content-Type: application/json');
// 	echo json_encode($out);
// 	//echo $str;
// });

// POST routes - used for updating data

// add any other routes that may be needed

// add prepending routing here ... should be template
$router->prepend(function () {
	//var_dump();
	//Template::header();
	//echo 'mysql time: ',(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]),PHP_EOL;
});

// add appending routing here
$router->append(function () {
	Template::footer();
	// show time
	// global $stime;
	// //$t = microtime(true) - $stime;
	// $t = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
	// echo 'Render time: ',$t,'s '.PHP_EOL;
	// $m = memory_get_usage() / (1024);
	// echo 'Memory (used): ',$m,'kB '.PHP_EOL;
	// $m = memory_get_usage(true)/ (1024);
	// echo 'Memory (total): ',$m,'kB '.PHP_EOL;
	
});

// router resolves on destruction

?>