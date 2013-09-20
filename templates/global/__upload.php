<div id="wrapper">

<h2>Bon<?php echo (date('H') >= 18 ? 'soir' : 'jour'); ?>!</h2>

<p>
  Hoi! Welkom bij deze supercoole generator van Wiki Loves Monuments tabellen.
</p>

<h2>Zo werkt het!</h2>

<p>
  Selecteer onderstaand je CSV-bestand en klik op 'Go go go!'.
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