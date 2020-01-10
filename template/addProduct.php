<script>
	
	var itemTitle = "", itemArtists = "";
	
	function formArrayToKV(arr)
	{
		var out = {};
		// for (const [k,v] in form.entries()) {
		// 	out[k] = v;
		// }
		arr.forEach(function(entry){
			//console.log(entry);
			out[entry.name] = entry.value;
		});
		
		return out;
	}
	
	
	
	$(document).ready(function () {
		
		function setItemTitle()
		{
			if (itemTitle != "" && itemArtists != "")
			$("#page-title").html(itemTitle + " &mdash; "+itemArtists);
		}
		
		function getItemTags (data, status)
		{
			console.log(data);
			var tags = data.tags;
			
			Object.keys(tags).forEach(function(key) {
				console.log(key+": "+tags[key].category);
				console.log(tags[key]);
				
				$("#tags-listing").append(`
				<tr>
					<td>`+tags[key].category+`</td>
					<td>`+tags[key].tag+`</td>
					<td>
						<form action="/DJJ/products/`+id+`/tags/remove" method="post" class="btn-group-form tagForm" method="post" data-usage="tagForm">
				<input type="hidden" name="item-id" value="`+id+`">
				<input type="hidden" name="tag-id" value="`+tags[key].id+`">
						<button type="submit" class="btn btn-outline-danger btn-sm">Unlink</button>
					</form>
						</td>
				</tr>
				`);
				
				if(tags[key].category == "Artist") {
					if(itemArtists == "") {
						itemArtists = tags[key].tag;
					} else itemArtists += ", "+tags[key].tag;
				}
				
				if(itemArtists == "") itemArtists = " ";
				
			});
			setItemTitle();
		}
		
		function getItemTagsSearch (data, status)
		{
			console.log(data);
			var tags = data.tags;
			
			$("#tags-search").empty();
			
			Object.keys(tags).forEach(function(key) {
				console.log(key+": "+tags[key].category);
				console.log(tags[key]);
				
				
				
				$("#tags-search").append(`
				<tr>
					<td>`+tags[key].category+`</td>
					<td>`+tags[key].tag+`</td>
					<td>
						<form action="/DJJ/products/`+id+`/tags/add" method="post" class="btn-group-form tagForm" method="post" data-usage="tagForm">
				<input type="hidden" name="item-id" value="`+id+`">
				<input type="hidden" name="tag-id" value="`+tags[key].id+`">
						<button type="submit" class="btn btn-outline-success btn-sm">Link</button>
					</form>
						</td>
				</tr>
				`);
				
				
			});
		}
		
		// ensure Listed and Available constraint is followed
		var validateAvailability = function () {
			// if listed on, available off
			if ($('#listed').prop('checked') && !$('#available').prop("checked")) {
				// if available turned off, turn off listed
				if ($(this).attr('id') == 'available') $('#listed').prop("checked", false);
				// if listed turned on, turn on available
				if ($(this).attr('id') == 'listed') $('#available').prop("checked", true);
			}

		}
		// bind callback for constraint
		$('#listed').click(validateAvailability);
		$('#available').click(validateAvailability);
		
		
		// check url 
		var path = window.location.pathname;
		var comp = path.split('/');
		//if (comp.slice(-2) == 'products')
		while(comp.slice(-1) == "") comp.pop();
		var id = parseInt(comp.slice(-1));
		console.log(id);
		if (Number.isInteger(id)) { // last part of path is an integer
			
			// request product json
			$.get('/DJJ/api/v1/products/'+id, function(data, status){
				//console.log(data);
				Object.keys(data).forEach(function(key){
					console.log(key+": "+data[key]);
					var element = $("#"+key);
					var setting = data[key];
					if (key == "title") itemTitle = data[key];
					// set checkboxes
					if (element.is(":checkbox")) {
						if(setting == "1") {element.prop("checked", true);}
						else element.prop("checked", false);
					// not checkboxes
					} else element.val(setting);
				});
				
				// set heading id
				$("#title-itemid").text(data["id"]);
				
				// change submit button text
				$("#btn-item-update").text("Update Item");
				// change update form action
				$("#itemForm").attr("action", "/DJJ/products/"+id+"/update");
				// turn off barcode switch
				$("#barcode").prop("checked", false);
				
				// enable images and tags
				$("#product-extras-disabled").css("display","none");
				
				//console.log(itemTitle);
				setItemTitle();
				
			});
			
			// request tag json
			$.get('/DJJ/api/v1/products/'+id+'/tags', getItemTags);
			
			// set image upload action
			$("#imageUpload").attr('action', '/DJJ/products/'+id+'/upload');
			
			// get image if exists
			$('#item-image').attr('src', '/DJJ/res/items/'+id+'.jpg').error(function() {
				//$('#item-image').attr('src', '/DJJ/res/no-image.png');
			});
			
			
			
				//console.log(itemArtists);
			//	itemArtists = 
				
				
			
		}
		
		// form submit handler
		$( "form" ).on('submit', function( event ) {
			// stop submission firing
			//event.preventDefault();
			//alert($(this).data("usage"));
			
			// get the array in a key: value format
			//console.log($( this ).serializeArray());
			//data = formArrayToKV($( this ).serializeArray());
			//console.log( data );
			
			// get the form id to determine what to do
			switch ($(this).data("usage")) {
				case 'itemForm':
					break;
				case 'tagForm':
					//alert("tag");
					break;
			}
			
		});
		
		// autocomplete into tags table
		// save previous ajax - so we can cancel as we type
		var prevSearch = "req";
		// binding autocomplete
		$("#tag-search").on("keyup", function(event) {
			var query = $("#tag-search").val();
			// skip blanks
			if(query == '') return;
			prevSearch = $.ajax({
				url: "/DJJ/api/v1/tags/search/"+query,
				beforeSend: function() {
					if (prevSearch != "req" && prevSearch.readyState < 4) prevSearch.abort();
				},
				success: function (data, status) {
					getItemTagsSearch(data, status);
				}
			});
			//alert(query);
		});
		
		
		//console.log(comp);
		// more functions here
		


	});
</script>

<style>
.item-tag-listing, .btn-group-form {
	margin-bottom: 0.5em!important;
}
.item-tag-listing:last-child, .btn-group-form:last-child {
	margin-bottom: 0px!important;
}

#product-extras-disabled {
    position: absolute;
    width: 100%;
    height: 100%;
	background-color: rgba(255,255,255,0.8);
	color: grey;
	text-align: center;
	z-index: 3;
	font-variant-caps: all-small-caps;
}
#product-extras-disabled>* {
	position: relative;
	top: 50%;
	vertical-align: middle;
	font-size: x-large;
	font-weight: 500;
}

.btn-group-form, .item-tag-listing {
	width:100%; 
}

.hidden {
	display: none!important;
}

</style>


<div class="row">
	<div class="col-sm-6">

		<h3>Item <span class="" style="font-size: small;color:gray;">ID: <span id="title-itemid">?</span></span></h3>

		<form action="" class="" id="discogsImportForm">
			<div class="input-group mb-3">
				<input type="text" class="form-control" placeholder="Discogs Release" id="importRelease" name="importRelease" disabled>
				<div class="input-group-append">
					<button class="btn btn-success" type="submit" disabled>Import from Discogs</button>
				</div>
			</div>
		</form>

		<form action="/DJJ/products/add" class="itemForm" id="itemForm" data-usage="itemForm" method="POST">
			<input type="hidden" id="id" name="id" value="">
			<div class="input-group mb-2 was-validated">
				<div class="input-group-prepend">
					<span class="input-group-text">SKU</span>
				</div>
				<input type="text" class="form-control" placeholder="Catalogue Number" name='sku' id="sku" required>
			</div>

			<div class="input-group  mb-2 was-validated">
				<div class="input-group-prepend">
					<span class="input-group-text">Title</span>
				</div>
				<input type="text" class="form-control" placeholder="Name of product" id="title" name='title' required>
			</div>

			<div class="input-group mb-2 was-validated">
				<div class="input-group-prepend">
					<span class="input-group-text">Price (&cent;)</span>
				</div>
				<input type="number" class="form-control" placeholder="1000 = $10.00" id="price" name="price" required>
			</div>

			<div class="input-group mb-2">
				<div class="input-group-prepend">
					<span class="input-group-text">Quantity</span>
				</div>
				<input type="number" class="form-control" placeholder="Number in stock" id="quantity" name="quantity" value="1" required>
			</div>

			<div class="row">
				<div class='col-sm-6'>
					<div class="input-group mb-2 custom-control custom-switch">
						<input type="checkbox" class="custom-control-input" id="taxable" name="taxable" checked>
						<label class="custom-control-label" for="taxable">Taxable item (GST applies)</label>
					</div>

					<div class="input-group mb-2 custom-control custom-switch">
						<input type="checkbox" class="custom-control-input" id="tangible" name="tangible" checked>
						<label class="custom-control-label" for="tangible">Tangible product</label>
					</div>
				</div>

				<div class='col-sm-6'>
					<div class="input-group mb-2 custom-control custom-switch">
						<input type="checkbox" class="custom-control-input" id="available" name="available" checked>
						<label class="custom-control-label" for="available">Available for sale</label>
					</div>

					<div class="input-group mb-2 custom-control custom-switch">
						<input type="checkbox" class="custom-control-input" id="listed" name="listed" checked>
						<label class="custom-control-label" for="listed">Listed for sale</label>
					</div>
				</div>

			</div>

			<div class="input-group  mb-2">
				<div class="input-group-prepend">
					<span class="input-group-text">Description</span>
				</div>
				<textarea class="form-control" rows="10" id="description" name="description"></textarea>
			</div>

			<div class="input-group  mb-2">
				<div class="input-group-prepend">
					<span class="input-group-text">Notes</span>
				</div>
				<textarea class="form-control" rows="5" id="notes" name="notes"></textarea>
			</div>
			
			<div class="input-group mb-2 custom-control custom-switch">
				<input type="checkbox" class="custom-control-input" id="barcode" name="barcode" checked>
				<label class="custom-control-label" for="barcode">Add to Barcode queue</label>
			</div>
			
			<button type="submit" id="btn-item-update" class="btn btn-primary">Create Item</button>
		</form>
	</div><!-- col-sm-6 -->




	<div class="col-sm-6">
		<div id="product-extras-disabled">
			<span>Enabled after Item is entered</span>
		</div>
		
		<h3>Image</h3>
		<img src="/DJJ/res/no-image.png" id="item-image" class="img-thumbnail mb-2">
		<form action="/DJJ/products/i/upload" id="imageUpload" method="post" enctype="multipart/form-data">
			<div class="input-group mb-2">
				<input type="file" class="form-control" name="upfile" id="image" required>
				<label class="custom-file-label" for="image">Click to choose file</label>
				<div class="input-group-append">
					<button class="btn btn-success" type="submit" name='upload' value='upload'>Upload</button>
				</div>
			</div>
		</form>
		
		<h3>Tag Search</h3>

		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<span class="input-group-text">Search Tags</span>
			</div>
			<input type="text" class="form-control" id="tag-search" name='tag-search' placeholder="Type to search, click to add">

		</div>
		<table class="table p-2 item-tags-search" id="tags-search">
		</table>
		
		<h3>Tags</h3>
		<table class="table p-2 item-tags-listing" id="tags-listing">
			
		</table>





	</div>

</div>

</form>