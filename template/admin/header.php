<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="/DJJ/styles.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

<style>
  .footer {
    margin-top: 1.5rem!important;
  }
  </style>

</head>
<body>

<div class="jumbotron jumbotron-fluid">
  <div class="container">
    <h1>Record Store</h1>
    <p>You spin me right round baby</p>
  </div>
</div>

<nav class="navbar navbar-expand-sm bg-light">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" href="<?=self::rootPath();?>/">Home</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="<?=self::rootPath();?>/products">Products</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="<?=self::rootPath();?>/products/1">Product ID: 1</a>
    </li>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="<?=self::rootPath();?>/products/add">Add Product</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="<?=self::rootPath();?>/tags">Tag Manager</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="<?=self::rootPath();?>/barcodes">Barcode Manager</a>
    </li>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="<?=self::rootPath();?>/users/add">Add User</a>
    </li>
  </ul>
</nav>

<div class="container pt-4">
<h2 id="page-title"><?=$data;?></h2>