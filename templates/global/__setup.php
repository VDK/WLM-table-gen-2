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
      <td><a href="" target="blanc" id="testurl" class="external">test</a></td>
    </tr>
  </table>
  <h2>Match tabel kolommen</h2>
  <table class="table">
    <tr>
      <tr>
        <td><label for="object">Object:</label></td>
        <td> 
          <select name="object" >
            <option value="n">  </option>
            <?php echo $data->table_headers; ?>
          </select>
        </td>
        <td class="buttons">
          <input type="button" class="add button" value="+" />
          <input type="button" class="remove button" value="-" />
        </td>
      </tr>
    <tr >
      <td><label for="bouwjaar">Bouwjaar:</label></td>
      <td >
        <select name="bouwjaar">
          <option value="n">  </option>
          <?php echo $data->table_headers; ?>
        </select>
      </td>
      <td class="buttons">
        <input type="button"  class="add button" value="+" />
        <input type="button"  class="remove button" value="-" />
      </td>
    </tr>
    <tr>
      <td><label for "architect">Architect:</td>
      <td>
        <select name="architect">
          <option value="n">  </option>
          <?php echo $data->table_headers; ?>
        </select>
      </td>
      <td class="buttons">
        <input type="button"  class="add button" value="+" />
        <input type="button"  class="remove button" value="-" />
      </td>
    </tr>
    <tr>
      <td><label for="adres">Adres/straat:</label></td>
      <td>
        <select name="adres">
          <?php echo $data->table_headers; ?>
        </select>
      </td>
    </tr>
    <tr>
      <td><label for="huisnummer">Huisnummer:</label></td>
      <td>
        <select name="huisnummer">
          <option value="n">  </option>
          <?php echo $data->table_headers; ?>
        </select>
      </td>
    </tr>
    <tr>
      <td><label for="toevoegsel">Toevoegsel:<small> (bijv. A of Bis)</small></label></td>
      <td>
        <select name="toevoegsel">
          <option value="n">  </option>
          <?php echo $data->table_headers; ?>
        </select>
      </td>
    </tr>
    <tr>
      <td><label for="positionering">Positionering:<small>(bijv.: "bij")</smalll></label></td>
      <td>
        <select name="positionering">
          <option value="n">  </option>
          <?php echo $data->table_headers; ?>
        </select>
      </td>
    </tr>
    <tr>
      <td><label for="postcode">Postcode:</label></td>
      <td>
        <select name="postcode">
          <option value="n">  </option>
          <?php echo $data->table_headers; ?>
        </select>
      </td>
      <td class="buttons">
        <input type="button"  class="add button" value="+" />
        <input type="button"  class="remove button" value="-" />
      </td>
    </tr>
    <tr>
      <td><label for="plaats">Plaats:</label></td>
      <td>
        <select name="plaats">
          <option value="n">  </option>
          <?php echo $data->table_headers; ?>
        </select>
      </td>
    </tr>
    <tr>
      <td><label for="objectnr">Unieke code van object:</label></td>
      <td>
        <select name="objectnr">
          <option value="n">  </option>
          <?php echo $data->table_headers; ?>
        </select>
      </td>
      <td class="buttons">
        <input type="button"  class="add button" value="+" />
        <input type="button"  class="remove button" value="-" />
      </td>
    </tr>
    <tr>
      <td><label for "mip"><a href="http://nl.wikipedia.org/wiki/Monumenten_Inventarisatie_Project" target="_blanc">MIP</a> nummer:</td>
      <td>
        <select name="mip">
          <option value="n">  </option>
          <?php echo $data->table_headers; ?>
        </select>
      </td>
      <td class="buttons">
        <input type="button" id="btnAdd2" class="add button" value="+" />
        <input type="button"  class="remove button" value="-" />
      </td>
    </tr>
    <tr>
      <td><label for "kadaster">Kadaster nummer:</td>
      <td>
        <select name="kadaster">
          <option value="n">  </option>
          <?php echo $data->table_headers; ?>
        </select>
      </td>
      <td class="buttons">
        <input type="button"  class="add button" value="+" />
        <input type="button"  class="remove button" value="-" />
      </td>
    </tr>
    <tr>
      <td><label for "rijksmonument">Rijksmonumentnummer:</td>
      <td>
        <select name="rijksmonument">
          <option value="n">  </option>
          <?php echo $data->table_headers; ?>
        </select>
      </td>
      <td class="buttons">
        <input type="button"  class="add button" value="+" />
        <input type="button"  class="remove button" value="-" />
      </td>
    </tr>
     <tr>
      <td><label for "aangewezen">Datum van aanwijzing:</td>
      <td>
        <select name="aangewezen">
          <option value="n">  </option>
          <?php echo $data->table_headers; ?>
        </select>
      </td>
      <td class="buttons">
        <input type="button"  class="add button" value="+" />
        <input type="button"  class="remove button" value="-" />
      </td>
    </tr>
     <tr>
      <td><label for="oorspr-fun">Oorspronkelijke functie:</label></td>
      <td>
        <select name="oorspr-fun">
          <option value="n">  </option>
          <?php echo $data->table_headers; ?>
        </select>
      </td>
      <td class="buttons">
        <input type="button"  class="add button" value="+" />
        <input type="button"  class="remove button" value="-" />
      </td>
    </tr>
     <tr>
      <td><label for="url-obj">Externe URL:</label></td>
      <td>
        <select name="url-obj">
          <option value="n">  </option>
          <?php echo $data->table_headers; ?>
        </select>
      </td>
    </tr>
  </table>
  <h3>Rijksdriehoekcoördinaten</h3>
<table>
  <tr>
    <td >Zijn er Rijksdriehoekcoördinaten aangeleverd?</td>
    <td align="right">
      <input type="radio" name="rd" id="ja" value="true">
      <label for="ja">ja</label>
      <input type="radio" name="rd" id="nee" value="false" checked>
      <label for="nee">nee</label>
    </td>
  </tr>
  <tr>
      <td><label for="xcoord">X coördinaat:</label></td>
      <td> 
        <select name="xcoord" disabled="disabled">
          <?php
          echo $data->table_headers;
          ?>
        </select>  
      </select>
    </td>
    </tr>
     <tr>
      <td><label for="ycoord">Y coördinaat:</label></td>
      <td>
       <select name="ycoord" disabled="disabled">
          <?php
          echo $data->table_headers;
          ?>
        </select>  
      </td>
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
      <td><label for="formaat" class="label">Formaat:<small>(bijv. PDF)</small></label></td>
      <td><input type=text name="formaat"/></td>
    </tr>
    <tr>
      <td><label for="datum" class="label">Publicatie datum:</label></td>
      <td><input type=text id="datum" name="datum"/></td>
    </tr>
  </table>  
  <p>
    <input type="hidden" value='<?php echo $data->table_data;?>' name="table_data" />
    <input type=submit value="Go Go Go!"/>
  </p>
</form>

<script  type="text/javascript">

$('#testurl').hide();

$(function(){
$('#datum' ).datepicker(
  { monthNames: [ "januari", "februari",  "maart",   "april",  "mei",   "juni", "juli",   "augustus",   "september", "oktober",  "november", "december"] 
  , dateFormat: 'd MM yy' }).val();

});
$('input[name="rd"]').on("click",function(e){
  console.log($(e.target).val());
  if($(e.target).val() == "false"){
    $('select[name="xcoord"]').attr("disabled", "disabled");
    $('select[name="ycoord"]').attr("disabled", "disabled");
  }
  else{
    $('select[name="xcoord"]').removeAttr("disabled");
    $('select[name="ycoord"]').removeAttr("disabled");
  }
});
$('#setup_form select[name="cbs_nr_id"]').change(function() {
  $('#testurl').show();
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

$('.remove').hide();
$('.add').on('click', function(){
  $(this).parent().children(".remove").show();
  $(this).parent().append("<span class='extra'>" 
    + "<select class='tussenvoegsel' name='"+ $(this).parent().parent().children("td").children("select").attr("name") + "_"+ $(this).parent().children(".extra").length+"_0' >"
    +   "<option value='0'>spatie</option>"
    +   "<option value='1'>geen spatie</option>"
    +   "<option value='2'>spatie + cursief</option>"
    +   "<option value='3'>streepje</option>"
    +   "<option value='4'>comma</option>"
    + "</select><select name='"+ $(this).parent().parent().children("td").children("select").attr("name") + "_"+$(this).parent().children(".extra").length+"_1'>"
    +   "<?php  echo $data->table_headers; ?>"
    + "</select></span>");
});

$('.remove').on('click', function(){
 $(this).parent().children(".extra:last-child").remove();
 if ($(this).parent().children(".extra").length == 0){
    $(this).hide();
 }
});

</script>
