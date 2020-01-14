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
	.primary-color {
		background-color: #6B5B95;
		color: white;
	}
	
	a, a:visited {
		color: white;
	}
	
	a:hover,  a:focus {
		color: #ddd;
	}
	
  footer {
	  margin-bottom: 0 !important;
	  padding-bottom: 50px;
  }
  
  .top-pad {
	  padding-top: 100px;
  }
  </style>

</head>
<body>

<nav class="navbar navbar-expand-sm primary-color">
<a class="navbar-brand" href="#">Logo</a>
  <!-- Links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" href="#">Latest Additions</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Products</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Link 3</a>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto">
	<li class="nav-item">
	<div class="input-group">
		<input type="text" class="form-control" placeholder="Search">
		<div class="input-group-append">
		  <button class="btn btn-success" type="submit"><span class="fa fa-search"></span></button>
		</div>
	  </div>
	</li>
    <li class="nav-item">
      <a class="nav-link" href="#">Login</a>
    </li>
  </ul>

</nav>

<div class="jumbotron jumbotron-fluid">
  <div class="container">
    <h1>Record Store</h1>
    <p>You spin me right round baby, like a record baby...</p>
  </div>
</div>