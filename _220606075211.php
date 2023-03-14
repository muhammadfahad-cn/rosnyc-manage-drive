<?php
/**
 * @package WordPress
 * Template Name: Nova Slider Front View
 */

get_header();
?>
  <!-- Latest compiled and minified CSS -->
  
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> 
<?php $ver=strtotime(date('Y-m-d h:i:s')); ?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/novaplugin/novaslider.css?ver=<?php echo $ver; ?>"/>

<div id="main-content">
       <div class="container">
       	<div class="row">
		<div id="content-area" class="clearfix" style="display:flex;">
			<div id="left-area" class="left_area totle-full">
           
  <!-- Owl Stylesheets -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
  <style>
		
</style>
		
	<div class="col-md-7 top-panel">
    <div class="mid-slider">
	<div id="loading-image" style="display:none;"><img  style="z-index: 999;" src="https://novafiberglass.com/load.webp" class="ajax-loader"></div>
	<div class="carousel-wrap" id="testing">
	 <div class="owl-carousel owl-theme">
	 <?php
	   global $wpdb;
         $results = $wpdb->get_results('SELECT * FROM wp_nova_slider LIMIT 1' );
         if(count($results) > 0){
		 $slidearr=explode(',', $results[0]->slides);
            if(count($slidearr) > 0){
		      
		     foreach($slidearr as $idsarr){
			    $resultsslide = $wpdb->get_results('SELECT * FROM wp_posts WHERE ID='.$idsarr);
			      
			foreach($resultsslide as $data){
	    ?>
        <div class="item">
		   <img id="small<?php echo $data->ID?>" src="<?php echo $data->guid; ?>" />
		  <!--<span class="img-text"><?php //echo $data->post_title?></span>-->
		</div>
		
		
<?php  } } } } else {  echo "<span style=text-align:center;>No Slider Founds Please Contact Administrator !</span>"; } ?> 	
    </div>
	<!-- // popup modal -->
	<a class="glyphicon glyphicon-print printit" data-toggle="modal" data-target="#printModel"><span>Print</span></a>
    <a href="#" class="enlarge glyphicon glyphicon-resize-full" onclick="zoomDiv()"><span>Enlarge</span></a>  
 <!-- Modal -->
 <div id="printModel" class="printmodel fade" role="dialog">
  <div class="modal-dialog">
   <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><b>Enter your zip code to find the closest dealer.</b></h4>
      </div>
	 <form name="myform" id="matchform" action="" method="post">
	 <div id="loader-zipcode" style="display:none;"><img  style="z-index: 999;" src="https://novafiberglass.com/load.webp" class="ajax-loader"></div>
	     <div class="alert alert-danger response-error" style="display:none;" role="alert">
		            <div id="error-message"> </div>
        </div>
	 <div id="matchform1" style="display:block;">
      <div class="modal-body">
	    <label>ZIP code</label>
        <p><input type="text" name="zipcode" value="" /></p>
      </div>
	  <div class="modal-footer">
	    <button type="submit" class="btn btn-default" name="submit">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
	  </div>
	  <div id="matchform2" style="display:none;"> 
	  <?php
         $i=0;
           foreach($slidearr as $idsarr){
		     $resultsslide = $wpdb->get_results('SELECT * FROM wp_posts WHERE ID='.$idsarr);
               foreach($resultsslide as $data){
	             if($i == 0){
				 $guid1=$data->guid;
				 }
				 if($i == 1){
				 $guid2=$data->guid;
				 }
				 if($i == 2){
				 $guid3=$data->guid;
				 }
        	}
	   $i++;		
    }		
   ?>
    <div id="printarea">
	   <div class="modal-body">
       <table class="responsive">
         <tr>
          <td><img src="<?php echo $guid1; ?>" /></td>
          <td style="width: 114px;vertical-align: baseline;padding-left: 12px;"><img src="https://novafiberglass.com/wp-content/uploads/2021/08/novalogo2021.png" width="1128" height="612" alt="Nova Fiberglass" id="logo" data-height-percentage="79" />
		  <span><strong>Siding Details</strong></span>
			<span><?php echo $results[0]->type_siding?>, <?php echo $results[0]->type_corner_detail?>, <?php echo $results[0]->type_finishes?></span>
			<span><strong>Dealer Details</strong></span>
			<span id="dealer_name">N/A</span>
			<span id="dealer_address">N/A</span>
			<span id="dealer_phone">N/A</span>
			<span id="dealer_website">N/A</span>
		  </td>
       </tr>
		</table>
	   <table class="responsive" style="margin-top:20px;">	
       <tr>
         <td><img src="<?php echo $guid2; ?>" /></td>
         <td style="padding-left: 10px;"><img src="<?php echo $guid3; ?>" /></td>
      </tr>
     </table>
	  </div>
	  <div class="modal-footer">
	    <button type="button" class="btn btn-default printdivarea">Print</button>
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
      </div>
   </div>
   </div>
    </form>
  </div>
 </div>
</div>
</div>

<script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script>
   jQuery(document).ready(function() {
     jQuery('.owl-carousel').owlCarousel({
            margin: 10,
            nav: true,
			items: 1,
            navText:["<div class='nav-btn prev-slide fas fa-angle-left'></div>","<div class='nav-btn next-slide fas fa-angle-right'></div>"],
        });
   var options={
    margin: 10,
    nav: true,
    items: 1
    };
   if(jQuery('.owl-carousel .owl-item').length>1){
       options.loop=true;
   }
   jQuery('.owl-carousel').owlCarousel(options);
  }); 
  </script>
 
  <!-- Popup Enlarge The Image -->
    <div id="myModal" class="modal show_image_popup">
       <div class="close">
          <button id="close-btn">X</button> 
       </div>
         <img class="modal-content" id="large-image" src="" alt="">
    </div>
  <!-- Popup Enlarge The Image -->	

  <!--<div id="show_image_popup">
     <div class="close-btn-area">
        <button id="close-btn">X</button> 
    </div>
    <div id="image-show-area">
       <img id="large-image" src="" alt="">
    </div>
   </div>-->
   
    <div class="entry-content"> </div>
        	 </div>
			
          </div>
		  
		
        	 <br>
    <div  class="col-md-5 side-panel">			 
	  <!-- <h2>Options</h2> -->
<button class="accordion">House</button>
<div class="panel">
<?php
global $wpdb;
$results = $wpdb->get_results('SELECT * FROM wp_novaslider_options WHERE optiontype=1 order by id desc');
  foreach ( $results as $result ) : ?>
  <p style="border-bottom: 1px solid #b3b3b3; margin-bottom: 15px;"><label><input type="radio" name="housechkradio" value="<?php echo esc_html($result->slider_option);?>"><span><?php echo esc_html($result->slider_option);?></span></label></p>
<?php endforeach; ?>
</div>

<button class="accordion">Siding</button>
<div class="panel">
<?php
	  $results = $wpdb->get_results('SELECT * FROM wp_novaslider_options WHERE optiontype=2 ');
      foreach ( $results as $result ) : ?>
  <p style="border-bottom: 1px solid #b3b3b3; margin-bottom: 15px;"><label><input type="radio" name="sidingchkradio" value="<?php echo $result->slider_option;?>"><span><?php echo esc_html($result->slider_option);?></span></label></p>
    <?php endforeach; ?>
</div>
<button class="accordion">Corner Detail</button>
<div class="panel">
<?php
	  $results = $wpdb->get_results('SELECT * FROM wp_novaslider_options WHERE optiontype=3 ');
	  foreach ( $results as $result ) : ?>
  <p style="border-bottom: 1px solid #b3b3b3; margin-bottom: 15px;"><label><input type="radio" name="cornerchkradio" value="<?php echo esc_html($result->slider_option);?>"><span><?php echo esc_html($result->slider_option);?></span></label><!-- </p> -->
  <?php endforeach; ?>
</div>
<button class="accordion">Finish</button>
<div class="panel" style="position: relative;">
<?php
  $resultsfinishes = $wpdb->get_results('SELECT * FROM wp_novaslider_options WHERE optiontype=4 ');
  foreach ( $resultsfinishes as $result ) : ?>
  <span style="display: flex; padding:1px; float: left; width: 100%; position: relative; border-bottom: 1px solid #b3b3b3; margin-bottom: 15px;">
        <p><label>
        <label> <input type="radio" name="finishchkradio" value="<?php echo esc_html($result->slider_option);?>"/> <span><?php echo esc_html($result->colour_name);?></span>
        <div class="finishes" style="position:absolute; background-color:<?php echo esc_html($result->slider_option);?>"></div>
    </label>
</label>
</p>

  </span>
 <?php endforeach; ?>
</div>
</div>
</div>
	</div>

<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}

jQuery(document).ready(function(){
	  $('input:radio[name="housechkradio"],input:radio[name="sidingchkradio"],input:radio[name="cornerchkradio"],input:radio[name="finishchkradio"]').change(function(){
        var rantime = new Date().getTime();
        var house = $('input[name="housechkradio"]:checked').val();
		var siding= $('input[name="sidingchkradio"]:checked').val();
		var corner= $('input[name="cornerchkradio"]:checked').val();
		var finish= $('input[name="finishchkradio"]:checked').val();
    
		jQuery.ajax({
        url: '/wp-admin/admin-ajax.php?ver='+rantime,
		type : 'POST',
		dataType: "json",
        data: {
            action:'get_cc_ajax_request',
            house:house,
			siding:siding,
         	corner:corner,
            finish:finish			
        },
		beforeSend: function() {
              $("#loading-image").show();
		},
		complete: function(){
		     $("#loading-image").hide();
		},
        success:function(obj) {
			var sliderview=obj.sliderview;
               if(sliderview){
			       //console.log(sliderview);
		$("#testing").html(sliderview);
		
		
				  
		jQuery('.owl-carousel').owlCarousel({
            margin: 10,
            dots:true,
            nav: true,
			items: 1,
            navText:["<div class='nav-btn prev-slide fas fa-angle-left'></div>","<div class='nav-btn next-slide fas fa-angle-right'></div>"],
          
        });
		
 var options={
    margin: 10,
    nav: true,
    items: 1,
	navText:["<div class='nav-btn prev-slide fas fa-angle-left'></div>","<div class='nav-btn next-slide fas fa-angle-right'></div>"]
    };
   if(jQuery('.owl-carousel .owl-item').length>1){
       options.loop=true;
   }
   jQuery('.owl-carousel').owlCarousel(options);
		  }
		   	
		},  
        error: function(errorThrown){
            console.log(errorThrown);
		}
		
		//$(".nav-btn.next-slide").before('content: "\f105"');
    });
	  
   });	
});

</script>

<script>
/*function printDiv(){
        var printContents = document.getElementById("printarea").innerHTML;
        var originalContents = document.body.innerHTML;
		
        //document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
}*/
function printDiv()
{
  var printBlock = $(this).parents('#printarea').siblings('.print');
  printBlock.hide();
  window.print();
  printBlock.show();
}

</script>

<script>
$( document ).ready(function(){
   // with animation  

    $("#close-btn").click(function(){
       // remove active class from all images
      $(".small-image").removeClass('active');
      $(".show_image_popup").slideUp();
    })

    $(".small-image").click(function(){
        // remove active class from all images
	   $(".small-image").removeClass('active');
       // add active class
       $(this).addClass('active');

      var image_path = $(this).attr('src'); 
      $("#show_image_popup").fadeOut();
      // now st this path to our popup image src
      $("#show_image_popup").fadeIn();
      $("#large-image").attr('src',image_path);

    })
})

function zoomDiv(){
		//console.log(zoomid);
		$(".small-image").removeClass('active');
		
		//$('#small'+zoomid).addClass('active');
		//$(this).addClass('active');
		
		var dataSrc = $('div.owl-item.active').find("img").attr('src');
        console.log(dataSrc);
		//var image_path = $('#small'+zoomid).attr('src'); 
		//console.log('image_path=>'+image_path);
		$(".show_image_popup").fadeOut();
		// now st this path to our popup image src
		$(".show_image_popup").fadeIn();
		$("#large-image").attr('src',dataSrc);
	}
	
  $(document).click(function (e) {
    if ($(e.target).is('#myModal')) {
        $('#myModal').fadeOut(500);
    }
 });
</script>
 
<script>
 $(document).ready(function(){
	$(document).on("submit", "#matchform", function(e){
	    e.preventDefault();
		let zip = $('input[name = zipcode]').val();
		let chkstr=/^\d{5}(-\d{4})?$/;
			if(zip == ''){
				errorMessage = "*Zipcode required!";
				alert(errorMessage);
				 return false;
			}

			else if ((zip.length)< 5 || (zip.length)>5 ){
			   errorMessage = "*zipcode should only be 5 digits";
			   alert(errorMessage);
				 return false;
			}   
			else if ( !chkstr.test(zip)){
			   errorMessage = "*zipcode should be numbers only";
			   alert(errorMessage);
			   return false;
			}
    var zipcode = zip; // for Demo
    $.ajax({
       url : "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyBgrtPa2VwLd2tjV1S261LMrN7jC7oaSPo&address=components=postal_code:+"+zipcode+"+&sensor=false",
       method: "POST",
       crossDomain: true,
       dataType: 'json',
	   beforeSend: function() {
              $("#loader-zipcode").show();
	   },
	   complete: function(){
		     $("#loader-zipcode").hide();
	  },
       success:function(data){
		    //call again the ajax function to get the dealer details
		           
		    latitude = data.results[0].geometry.location.lat;
            longitude= data.results[0].geometry.location.lng;
			console.log("Lat = "+latitude+"- Long = "+longitude);
			if(latitude != ''  &&  longitude != ''){
				   var res=getStoreDealers(latitude, longitude);
				   console.log("getStoreDealers==>"+res);
				   if(res == 0){
				   $(".response-error").show();
						$("#error-message").html('Sorry No Dealer Found on this Zipcode !');
						$('.response-error').delay(3000).hide(0); 
				        return false;
				   }
				   else {
				   $(".response-error").hide();	   
						$(".modal-title").hide();
						$('#matchform1').hide();
						$('#matchform2').show();
				   }
				}
			  else {
				  alert('lat long not found');
				  return false;
			  }
			},
		  error: function(errorThrown){
			console.log(errorThrown);
		 }
        });
   });
	
	// This Function Get The Data From WP Store
	function getStoreDealers(latitude, longitude){
	   let returnValue = null;
	   $.ajax({
       url : "https://novafiberglass.com/wp-admin/admin-ajax.php?action=store_search&lat="+latitude+"&lng="+longitude+"&max_results=1&search_radius=100",
       method: "POST",
	   async: false,
       crossDomain: true,
       dataType: 'json',
	   success:function(data){
		      if ( data.length == 0 ) 
					{
					  returnValue = data.length;
					}
					else {
					  $('#dealer_name').html(data[0].store);
					  var addstr= data[0].city+','+data[0].state+','+data[0].zip;
                      $('#dealer_address').html(addstr);
                      $('#dealer_phone').html(data[0].phone);
                      returnValue = data.length;	
                	}
			},
		  error: function(errorThrown){
			console.log(errorThrown);
		    }
        });
		
		 return returnValue;
	}
 });

$('#printModel').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
	$(".response-error").hide();
	$("#error-message").html("");
	$(".modal-title").show();
	$("#matchform1").show();
	$("#matchform2").hide();
})

$('.printdivarea').click(function(e) {
     //Do stuff here
	 e.preventDefault();
	 alert('Here');
	 printDiv();
	  $('#matchform1').show();
	  $('#matchform2').hide();
	  $("#modal .close").click();
	 return false;
});
</script>
          </div>
		</div>
	</div>
</div>
<?php
get_footer();