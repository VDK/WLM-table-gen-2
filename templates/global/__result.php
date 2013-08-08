<?php
$gem = $data->cbs->gemeente->get();
$plaatsnaam = $gem;
$previousPlace = "";
echo load_plugin('jquery_ui');
echo load_plugin('jquery_postpone');
echo load_plugin('deference');
?>
<title>Test</title>
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
	echo "\n<br/>&lt;!-- --&gt;<br/>\n";
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


<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
var placenames = new Array(<?php echo  $data->placenames->get(); ?>);   // $a = array( 'Harlingen', 'Steenwijk' );  echo json_encode($a);  // ['Harlingen', 'Steenwijk']
var falseCoords = new Array();
var finished = 0;
var finishedRows = 0;
var numItems = 0;
$(document).ready(function() {
  numItems = $('.row').length + placenames.length;

  $.serial(placenames, function(placename){
    return $.when(getLatLong(placename).done(function(cords){
         //Insert the coordinates into the HTML.
        if (cords){ 
        	falseCoords.push(cords);
        }
            
      //If there weren't results for the address, do nothing.
      if(!cords) return;
            
        }), $.wait(1500))
        .always(function(){
  		    updateProgress();
  	   });
  }).done( geocodeRows );
  
// When the retrieval of the cords fails...
//   .fail(function(error){
// console.log( " Error: " + error);
//   });
});

function geocodeRows(){

        var locations = $('.adres').map(function(){
        	return $(this).text() + ", " + $(this).attr("name");

        });

        $.serial(locations, function(location){
                return $.when(getLatLong(location).done(function(cords){
                 //Insert the coordinates into the HTML.
                if (cords && ($.inArray(cords, falseCoords) == -1)){
      			      $('.row').eq(finishedRows).children('.lat').text(cords.lat);
      			      $('.row').eq(finishedRows).children('.lon').text(cords.lon);   
                }
                else if ($.inArray(cords, falseCoords) != -1){
                	console.log("falseCoord!");
                }
                    
                    //If there weren't results for the address, do nothing.
              if(!cords) return;
                    
                }), $.wait(1500))
                .always(function(){
                finishedRows++;
      			updateProgress();});
        });
        
	    //When the retrieval of the cords fails...
	  //   .fail(function(error){
			// console.log( " Error: " + error);
	  //   });
    
}
function updateProgress(){
    finished++;
    var percentage = finished* 100/numItems;
    if (percentage == 100){
        $('#progressbar').hide();
    }
    else{
        $( "#progressbar" ).progressbar({ value: Math.round(percentage) });
    }

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
           switch(status){
               case google.maps.GeocoderStatus.ZERO_RESULTS: return D.resolve(null) && false;
               default: return D.reject(status) && false;
            }
        }
        
        //Create the latlon object.
        var latlon = {
            lat: results[0].geometry.location.lat(),
            lon: results[0].geometry.location.lng()
        };
        //Resolve the deferred with it.
        D.resolve(latlon);
        
    });
    
   
   //Return the promise object.
    return P;
}
</script>