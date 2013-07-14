<?php
$gem = $data->cbs->gemeente->get();
$plaatsnaam = $gem;
$previousPlace = "";
echo load_plugin('jquery_ui');
?>
<div id="progressbar" class="ui-progressbar"></div>
<p>
  De [[Nederlandse gemeente|gemeente]] [[<?php echo ($data->wikigem == $gem)?  $gem : $data->wikigem."|".$gem;?>]] heeft <?php echo count($data->rows->get());?> [[gemeentelijk monument|gemeentelijke monumenten]], hieronder een overzicht.
  Zie ook de [[lijst van rijksmonumenten in <?php echo $gem."|rijksmonumenten in ".$gem; ?>]].
</p>
<?php
foreach ($data->rows as $key => $value  ){
	if ($previousPlace != $value['plaats']->get()  || $key == 0){
      if ( $key != '0'){ 
      	echo "|}<br/>";
      }
	  if (isset($value['plaats'])){?>
			==<?php echo $value['plaats'];?>==<br/>
			De plaats [[<?php echo $value['plaats'];?>]] kent <?php echo $data->placestats[$value['plaats']]['count']->get()." "; 
			echo ($data->placestats[$value['plaats']]['count']->get() ==1)? "gemeentelijk monument" : "gemeentelijke monumenten";?>:<br/>
			<?php
			$plaatsnaam = $value['plaats'].", ".$gem;
		}
      //create start of table
      ?>
      {{Tabelkop gemeentelijke monumenten|prov-iso=<?php echo $data->ISO->get();?>|gemeente=[[<?php echo $data->wikigem;?>]]}}
      <?php echo "\n<br/>";
    }
	?><span class="row">
	{{Tabelrij gemeentelijk monument
	| object =<?php 	echo ucfirst($value['object']);?>
	| bouwjaar =<?php 	echo $value['bouwjaar'];?>
	| architect =<?php 	echo $value['architect'];?>
	| adres =<span class='adres'<?php echo "name='".$plaatsnaam."'>".$value['adres'];?></span>
	| postcode =<?php 	echo $value['postcode'];
	echo (empty($value['coordinates']['lat'] ))? " |lat =<span class='lat' ></span> " : ' |lat ='.$value['coordinates']['lat'];
	echo (empty($value['coordinates']['long']))? " |lon =<span class='lon' ></span> " : ' |lon ='.$value['coordinates']['long'];?>
	| objnr =<?php 		echo $value['objectnr'];?>
	| gemcode =<?php 	echo $data->cbs->gemcode->get();
	echo (empty($value['mip'] 		))? "" : '|MIP_nr ='.$value['mip'];
	echo (empty($value['kadaster']	))? "" : '|kadaster ='. $value['kadaster'];?>
	| rijksmonument =<?php 	echo $value['rijksmonument'];?>
	| aangewezen =<?php 	echo $value['aangewezen'];?>
	| oorspr_fun =<?php 	echo $value['oorspr-fun'];
	echo (empty($value['url-obj']))? "" : '| url ='.$value['url-obj'];?> 
	| commonscat=
	| image=
	}}</span>
<?php
	$previousPlace = $value['plaats']->get() ;
	echo "\n<br/>&lt; -- --&gt;<br/>\n";
	}
?>
|}<br/>
{{Commonscat|Gemeentelijke monumenten in <?php echo $gem."}}";
?><br/>
{{Appendix|2=*{{Citeer web|url=<?php echo $data->bron->url;?> |titel=<?php echo $data->bron->titel;?>  |uitgever=<?php echo $data->bron->uitgever;?> |formaat=<?php echo $data->bron->formaat;?>  |datum=<?php echo $data->bron->pubDate;?>  |bezochtdatum=<?php echo $data->bron->accessDate;?> }}<br/>
----<br/>
{{references}}}}<br/>
[[Categorie:<?php echo $gem;?>]]<br/>
[[Categorie:Lijsten van gemeentelijke monumenten in <?php echo $data->provinceCat."|".$gem;?>]]<br/>
[[Categorie:Lijsten van gemeentelijke monumenten naar gemeente|<?php echo $gem; ?>]]
<br/>&lt;!-- Deze lijst was gegenereerd met WLM-table-gen, zie het script op http://tinyurl.com/WLM-table-gen --&gt;


<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="50" height="50" id="flashSleep" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="allowFullScreen" value="false" />
<param name="movie" value="site/components/wlm_table_gen/includes/flashSleep.swf" />
<param name="quality" value="high" /><param name="bgcolor" value="#ffffff" />	
<embed src="site/components/wlm_table_gen/includes/flashSleep.swf" quality="high" bgcolor="#ffffff" width="50" height="50" name="flashSleep" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
var placenames = new Array(<?php echo  $data->placenames->get(); ?>);
var requestcount = 0;
var finished = 0;
var numItems = 0;
$(document).ready(function() {
// if($('#flashSleep').(doesExist()) { 
	numItems = $('.row').length + placenames.length;

setTimeout(geocode(), 	20);

});
function updateProgress(){
	requestcount++; 
	finished++;
	var percentage = finished* 100/numItems;
	if (percentage == 100){
		$('#progressbar').hide();
	}
	else{
		$( "#progressbar" ).progressbar({ value: Math.round(percentage) });
	}

}
function geocode () {
	var cutoff = finished +3;

	console.log('hj');
	//$.when(!(finished < cutoff)).then(function () {
		// if (finished < placenames.length){

		// 	$.each(placenames, function( index, value ) {
		// 		if (index == finished){	
		// 			getLatLong(value)

		        
		// 	        //When the retrieval of the cords is complete...
		// 	        .done(function(cords){

		// 	            // row.children('.lat').text(cords.lat);
		// 	            // row.children('.lon').text(cords.lon);

		// 	        })
			        
		// 	        //When the retrieval of the cords fails...
		// 	        .fail(function(error){
		// 	            console.log(index+ "Error: " + error);
		// 	        })

		// 	        .always( function() {
		// 			  updateProgress();
		// 			} );
		//    		}
		// 	});
		// }
		// else{
			$( ".row" ).each(function( index ) {
				if (index == finished ){//- placenames.length){
				     //Make the address.
			        var address = $(this).children('.adres').text() + ", " + $(this).children('.adres').attr("name");
			        var row = $(this);
			        //Get the cords.
			        // triggerTimeout();
			        getLatLong(address)
			        
			        //When the retrieval of the cords is complete...
			        .done(function(cords){
			            row.children('.lat').text(cords.lat);
			            row.children('.lon').text(cords.lon);	

			        })
			        
			        //When the retrieval of the cords fails...
			        .fail(function(error){
			            console.log(index+ " Error: " + error);
			        })

			        .always( function() {
					  updateProgress();
					} );
				}
				if (index == cutoff){
					this.break;
					console.log('ola');
				}
			});	
		// }
	//});	
}

function sleep(milliSeconds){
	// call sleep method in flash
	getFlashMovie("flashSleep").flashSleep(milliSeconds);
}
 
function getFlashMovie(movieName){
	// source: http://kb2.adobe.com/cps/156/tn_15683.html
	var isIE = navigator.appName.indexOf("Microsoft") != -1;
	return (isIE) ? window[movieName] : document[movieName];
}
function getLatLong(address) {
    
    //Create the Deferred object that handles the callbacks.

    var D = $.Deferred();
    var P = D.promise();
    
    //Create the geocoder instance.
    var geocoder = new google.maps.Geocoder();
    
    //Send the geocode request for the given address.
    geocoder.geocode( { 'address': address, 'region': 'nl' }, function(results, status) {
        
        //The deferred fails if this request failed.
        if(status !== google.maps.GeocoderStatus.OK){
            return D.reject(status) && false;
        }
        
        //Create the latlon object.
        var latlon = {
            lat: results[0].geometry.location.jb,
            lon: results[0].geometry.location.kb
        };
        
        //Resolve the deferred with it.
        D.resolve(latlon);
        
    });
    
   
   //Return the promise object.
    return P;
}
</script>