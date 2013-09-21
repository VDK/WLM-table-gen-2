<?php
echo load_plugin('jquery_ui');
?>
<div id="wrapper">

<h2>Bon<?php echo (date('H') >= 18 ? 'soir' : 'jour'); ?>!</h2>

<p>
  Hoi! Welkom bij de Wiki Loves Monuments tabellen-generator.
</p>

<h2>Zo werkt het!</h2>

<p>
  <div id="accordion">
   <h3>Zie de instructievideo</h3>
  <div>
   <iframe width="420" height="270" src="//www.youtube.com/embed/0amGm9mVG4k" frameborder="0" allowfullscreen></iframe>
  </div>
  <h3>Zie tekstuele instructies</h3>
  <div>
    	<ul>
  		<li>Maak een CSV bestand in bijvoorbeeld Excel met daarin de lijst van gemeentelijke monumenten van een bepaalde gemeente.
	  	<ul>
	  		<li>De eerste rij is gereserveerd voor de tabelkoppen</li>
	  		<li>De sortering van het bestand wordt aangehouden, sorteer dus op adres en plaatsnaam</li>
	  		<li>Op dit moment is er een limiet van ongeveer 300 rijen die gegeocodeerd kunnen worden</li>
	  	</ul>
	  	<li>Upload hier het CSV-bestand en klik op "Go! Go! Go!"</li>
	  	<li>Associeer de kollomen uit het CSV-bestand met de Wiki-tabel kollommen</li>
	  	<li>Klik weer "Go! Go! Go!" en WACHT todat de app klaar is met geocoderen</li>
  	</ul>
  </div>
  </div>
</p>

<form id="csv-submit-form" method="POST" action="<?php echo url('s=setup'); ?>" enctype="multipart/form-data">

   <p>
<span class="file-wrapper">
  <input type="file" name="csv" id="csv" />
  <span class="button">Upload CSV</span>
</span>
  </p><p>
  <strong>Delimiter:</strong><input type="input" name="delimiter" id="delimiter" value=";" maxlength="1" size="1"/></p><p>
  <input class="bluebutton" type="submit" id="submit" value="Go go go!" disabled="disabled"/>
  </p>
</form>

<?php echo $data;?>
</div>

<script type="text/javascript">

  $(function() {
    var icons = {
      header: "ui-icon-circle-arrow-e",
      activeHeader: "ui-icon-circle-arrow-s"
    };
    $( "#accordion" ).accordion({
      icons: icons,
    	active: false,
      collapsible: true
    });
    $( "#toggle" ).button().click(function() {
        $( "#accordion" ).accordion( "option", "icons", icons );
    });
  });

var file = document.getElementById('csv');

file.onchange = function(e){
    var ext = this.value.match(/\.([^\.]+)$/)[1];
    switch(ext)
    {
        case 'csv':
			document.getElementById('submit').removeAttribute('disabled');
            break;
        default:
            alert('Alleen CSV-bestanden worden ondersteund');
            this.value='';
    }
};

var SITE = SITE || {};
 
SITE.fileInputs = function() {
  var $this = $(this),
      $val = $this.val(),
      valArray = $val.split('\\'),
      newVal = valArray[valArray.length-1],
      $button = $this.siblings('.button'),
      $fakeFile = $this.siblings('.file-holder');
  if(newVal !== '') {
    $button.text('Bestand geselecteerd');
    if($fakeFile.length === 0) {
      $button.text( newVal);
    } else {
      $fakeFile.text(newVal);
    }
  }
};
 
$(document).ready(function() {
  $('.file-wrapper input[type=file]').bind('change focus click', SITE.fileInputs);
});
</script>