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

// include me to make problems go away
require('config.php');

// connect to DB... might move to closures in future
$db = new Database();
$conn = $db->connect();

// determine application: store, admin, api etc
$app = explode('/',urldecode($_GET['p']));


// set templating system path
Template::root($_ROOT);
// start router
$router = new Router($_ROOT);

// ------------------------ Admin Panel
// index file
$router->get('/', function () {
	Template::header('Index');
});

// products view
$router->get('/products', function () {
	global $db, $conn;
	Template::header("Latest Products");
	Template::products();
	//Template::header($artist.' - '.$productArr['title']);
	//Template::product([$productArr, $tags]);
});

// add products view
$router->get('/products/add', function () {
	global $db, $conn;
	
	Template::header('Add New Product');
	Template::addProduct();
});

// add new product - products view
$router->post('/products/add', function () {
	global $db, $conn;
	
	//var_dump($_POST);
	$id = $_POST['id'];
	$sku = $_POST['sku'];
	$title = $_POST['title'];
	$price = $_POST['price'];
	$quantity = $_POST['quantity'];
	$taxable = (isset($_POST['taxable']) ? "1" : "0");
	$tangible = (isset($_POST['tangible']) ? "1" : "0");
	$available = (isset($_POST['available']) ? "1" : "0");
	$listed = (isset($_POST['listed']) ? "1" : "0");
	$description = $_POST['description'];
	$notes = $_POST['notes'];
	$barcode = (isset($_POST['barcode']) ? true : false);
	
	$new = Item::addItem($conn, $sku, $title, $price, $quantity, $taxable, $tangible, $available, $listed, $description, $notes);
	
	if ($new > 0)
	{
		// if barcode flag add to barcode queue
		if($barcode) Barcode::addItemToQueue($conn,$new,1);
		// go to new listing :)
		header("Location: /DJJ/admin/products/".$new);
		die();
	}
	
	//todo: failure message
	Template::header('Add New Product');
	Template::addProduct();
});

// update product - products view
$router->post('/products/{i}/update', function ($id) {
	global $db, $conn;
	
	//var_dump($_POST);
	$itemid = $_POST['id'];
	$sku = $_POST['sku'];
	$title = $_POST['title'];
	$price = $_POST['price'];
	$quantity = $_POST['quantity'];
	$taxable = (isset($_POST['taxable']) ? "1" : "0");
	$tangible = (isset($_POST['tangible']) ? "1" : "0");
	$available = (isset($_POST['available']) ? "1" : "0");
	$listed = (isset($_POST['listed']) ? "1" : "0");
	$description = $_POST['description'];
	$notes = $_POST['notes'];
	$barcode = (isset($_POST['barcode']) ? true : false);
	
	$new = Item::updateItem($conn, $itemid, $sku, $title, $price, $quantity, $taxable, $tangible, $available, $listed, $description, $notes);
	
	if ($new > 0)
	{
		// if barcode flag add to barcode queue
		if($barcode) Barcode::addItemToQueue($conn,$new,1);
		// go to new listing :)
		header("Location: /DJJ/products/".$new);
		die();
	}
	
	header("Location: /DJJ/products/".$itemid);
	die();
	//todo: failure message
	Template::header('Add New Product');
	Template::addProduct();
});

// get products - loads via javascript
$router->get('/products/{i}', function ($id) {
	global $db, $conn;
	
	Template::header('Loading Product');
	Template::addProduct();
});

// add product tag
$router->post('/products/{i}/tags/add', function ($id) {
	global $db, $conn;
	
	$itemid = $_POST['item-id'];
	$tagid = $_POST['tag-id'];
	
	//var_dump($_POST);
	//if ((int)$id != (int)$itemid) $router->redirect("/products/".$id);
	
	if(Item::addTag($conn, $itemid, $tagid)){
		header("Location: /DJJ/products/".$itemid);
		die();
	}
	die("ERERROR");
	
});

// remove product tag
$router->post('/products/{i}/tags/remove', function ($id) {
	global $db, $conn;
	
	$itemid = $_POST['item-id'];
	$tagid = $_POST['tag-id'];
	
	if ((int)$id != (int)$itemid) $router->redirect("/products/".$id);
	
	Item::removeTag($conn, $itemid, $tagid);
	header("Location: /DJJ/products/".$itemid);
	die();
});

$router->post('/products/{i}/upload', function($id) {
	global $db, $conn;
	
	// stolen from https://www.php.net/manual/en/features.file-upload.php
	//var_dump($_FILES);
	//echo is_array($_FILES['upfile']['error']);
	if(!isset($_FILES['upfile']['error'])
	|| is_array($_FILES['upfile']['error']))
	{
		die("some sort of error");
		header("Location: /DJJ/products/".$id);
		die();
	}
	
	switch ($_FILES['upfile']['error'])
	{
		case UPLOAD_ERR_OK:
			break;
		case UPLOAD_ERR_NO_FILE:
			//throw new RuntimeException('No file sent.');
			die("no file sent");
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			die('file too big');
			//throw new RuntimeException('Exceeded filesize limit.');
		default:
			die("idk wtf is going on");
			//throw new RuntimeException('Unknown errors.');
	}
	
	if ($_FILES['upfile']['size'] > 2000000) {
		//throw new RuntimeException('Exceeded filesize limit.');
	}
	
	$finfo = new finfo(FILEINFO_MIME_TYPE);
	if (false === $ext = array_search(
	$finfo->file($_FILES['upfile']['tmp_name']),
	array(
	'jpg' => 'image/jpeg',
	'jpeg' => 'image/jpeg',
	'png' => 'image/png',
	),
	true
	)) {
	//throw new RuntimeException('Invalid file format.');
	}

	if (!move_uploaded_file(
		$_FILES['upfile']['tmp_name'],
		sprintf(getcwd().DIRECTORY_SEPARATOR.'www'.DIRECTORY_SEPARATOR.'DJJ'.DIRECTORY_SEPARATOR.'res'.DIRECTORY_SEPARATOR.'items'.DIRECTORY_SEPARATOR.'%s.%s',
		$id,
		$ext
		)
		)) {
	//throw new RuntimeException('Failed to move uploaded file.');
	die("failed to move file");
	}
	
	header("Location: /DJJ/products/".$id);
	die();
	
});

// add new products
/*
$router->post('/products/add', function ($id=1) {
	global $db, $conn;
	Template::addEnds(false);
	Router::$ends = false;
	
	var_dump($_POST);
	
	//Template::header('Add New Product');
	//Template::addProduct();
});
*/

// product view
/*
$router->get('/products/{i}', function ($id) {
	global $db, $conn;
	$tags = Tags::getTagsByItemID($conn, $id);
	$tagsC = Tags::getTagsByItemID($conn, $id, true);
	$product = Item::getItemByID($conn, $id);
	//var_dump($tagsC,$product);
	// extract artists
	$artists = [];
	foreach ($tagsC['Artist'] as $artist)
	{
		$artists[] = $artist['tag'];
	}
	$artistsText = implode(', ', $artists);
	Template::header($artistsText.' &mdash; '.$product->title);
	Template::product([$product->toArray(), $tags]);
});
*/


// tag manager
$router->get('/tags', function () {
	global $db, $conn;
	Template::header('Tag Manager');
	$tags = Tags::getTagsByCategory($conn);
	Template::tagManager($tags);
});

// add tag
$router->post('/tags/add', function () {
	global $db, $conn;
	$tag = $_POST['tag'];
	$permalink = Tags::slugify($tag);
	$c_id = $_POST['c_id'];
	$id = Tags::addTag($conn, $tag, $permalink, $c_id);
	header("Location: /DJJ/tags");
	die();
});

// add category
$router->post('/tags/add/category', function () {
	global $db, $conn;
	$cat = $_POST['category'];
	$slug = Tags::slugify($cat);
	$id = Tags::addCategory($conn, $cat, $slug, $slug);
	header("Location: /DJJ/tags");
	die();
});




// barcode printer
$router->get('/barcodes', function () {
	global $db, $conn;
	Template::header('Barcode Manager');
	$barcodes = Barcode::getBarcodeItems($conn);
	//var_dump($barcodes);
	Template::barcodes($barcodes);
});





// add user
$router->get('/users/add', function () {
	global $db, $conn;
	Template::header('Add New User');
	//$barcodes = Barcode::getBarcodeItems($conn);
	//var_dump($barcodes);
	Template::addUser();
});

// add user
$router->post('/users/add', function () {
	global $db, $conn;
	//Template::header('Add New User');
	//$barcodes = Barcode::getBarcodeItems($conn);
	//var_dump($barcodes);
	$uname = $_POST['username'];
	$fname = $_POST['firstname'];
	$email = $_POST['email'];
	$pword = $_POST['password'];
	$access = $_POST['access'];
	
	$r = User::addUser($conn, $uname,$fname,$email, $pword, $access);
	
	//var_dump($_POST);
	//die;
	header("Location: /DJJ/users/add");
	die($r);
	//Template::addUser();
});













// ------------------- API interface
// tag autocomplete
$router->get('/api/v1/tags/search/{s}', function ($str) {
	global $db, $conn;
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
	global $db, $conn;
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
	global $db, $conn;
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
	global $db, $conn;
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
// 	global $db, $conn;
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
	global $stime;
	//$t = microtime(true) - $stime;
	$t = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
	echo 'Render time: ',$t,'s '.PHP_EOL;
	$m = memory_get_usage() / (1024);
	echo 'Memory (used): ',$m,'kB '.PHP_EOL;
	$m = memory_get_usage(true)/ (1024);
	echo 'Memory (total): ',$m,'kB '.PHP_EOL;
	
});

// router resolves on destruction

?>