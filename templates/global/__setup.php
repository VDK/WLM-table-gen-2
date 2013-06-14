<?php

echo load_plugin('jquery_ui');
?>
<form method="POST" id="setup_form" action="<?php echo url('s=result'); ?>">
  <h2>Gemeente</h2>
  <table>
    <tr>
      <td><label for="gemeente">Gemeente:</label></td>
      <td><?php

      echo $data->cbs_nrs->as_options(
        'cbs_nr_id',  // select name, dus:  <select name="gemeente">
        'gemeente',  // option text, dus:  <option>$gemeente</option>
        'id'    // option value, dus: <option value="$gemcode">
      );

      ?></td>
    </tr>
    <tr>
      <td>Wikipedia artikel:</td>
      <td><input type=text name="wikigem" value=""/></td>
      <td><a href="" target="blanc" id="testurl" class="external" style="view:hidden">test</a></td>
    </tr>
  </table>
  <h2>Match tabel kolommen</h2>
  <table>
    <tr>
      <td><label for "object">Object:</td>
      <td>???</td>
    </tr>
    <tr>
      <td><label for "bouwjaar">Bouwjaar:</td>
      <td>???</td>
    </tr>
    <tr>
      <td><label for "architect">Architect:</td>
      <td>???</td>
    </tr>
    <tr>
      <td><label for "adres">Adres:</td>
      <td>???</td>
    </tr>
    <tr>
      <td><label for "plaats">Plaats:</td>
      <td>???</td>
    </tr>
    <tr>
      <td><label for "objectnr">Unieke code voor het object:</td>
      <td>???</td>
    </tr>
    <tr>
      <td><label for "mip"><a href="http://nl.wikipedia.org/wiki/Monumenten_Inventarisatie_Project" target="_blanc">MIP</a> nummer:</td>
      <td>???</td>
    </tr>
    <tr>
      <td><label for "kadaster">Kadaster nummer:</td>
      <td>???</td>
    </tr>
    <tr>
      <td><label for "rijksmonument">Rijksmonumentnummer:</td>
      <td>???</td>
    </tr>
     <tr>
      <td><label for "aangewezen">Datum van aanwijzing:</td>
      <td>???</td>
    </tr>
     <tr>
      <td><label for "oorspr_fun">Oorspronkelijke functie:</td>
      <td>???</td>
    </tr>
     <tr>
      <td><label for "url_obj">Externe URL:</td>
      <td>???</td>
    </tr>
  </table>

  <h2>Broninformatie</h2>
  <p>Optioneel:</p>
  <table>
    <tr>
      <td><label for="url" class="label">Bron url:</label></td>
      <td><input type=text name="url" value="http://"/></td>
    </tr>
    <tr>
      <td><label for="titel" class="label" >Titel</label></td>
      <td><input type=text name="titel" value="Monumentenlijst"/></td>
    </tr>
    <tr>
      <td><label for="uitgever" class="label">Uitgever:</label></td>
      <td><input type=text name="uitgever"/></td>
    </tr>
     <tr>
      <td><label for="formaat" class="label">Formaat (bijv. PDF):</label></td>
      <td><input type=text name="formaat"/></td>
    </tr>
    <tr>
      <td><label for="datum" class="label">Publicatie datum:</label></td>
      <td><input type=text id="datum" name="datum"/></td>
    </tr>
  </table>  
  <p>
    <input type=submit value="Go Go Go!"/>
  </p>
</form>

<script  type="text/javascript">

$(function(){
$('setup_form input[id="datum"]' ).datepicker(
  { monthNames: [ "januari", "februari",  "maart",   "april",  "mei",   "juni", "juli",   "augustus",   "september", "oktober",  "november", "december"] 
  , dateFormat: 'd MM yy' }).val();

});

$('#setup_form select[name="cbs_nr_id"]').change(function() {
  var selectedGem=$('#setup_form select[name="cbs_nr_id"]').find(":selected").text();
  $('#setup_form input[name="wikigem"]').val(selectedGem);
  $('#setup_form input[name="uitgever"]').val("[["+$('#setup_form input[name="wikigem"]').val()+"|Gemeente "+selectedGem+"]]");
  $("a#testurl").attr("href", "http://nl.wikipedia.org/wiki/"+selectedGem); 
});
$('#setup_form input[name="wikigem"]').change(function() {
  var selectedGem=$('#setup_form input[name="wikigem"]').val();
   $("a#testurl").attr("href", "http://nl.wikipedia.org/wiki/"+selectedGem);
   $('#setup_form input[name="uitgever"]').val("[["+selectedGem+"|Gemeente "+$('#setup_form select[name="cbs_nr_id"]').find(":selected").text()+"]]");
});

</script>