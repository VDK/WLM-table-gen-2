
<h2>Bonjour!</h2>

<p>
  Hoi! Welkom bij deze supercoole generator van Wiki Loves Monuments tabellen.
</p>

<h2>Zo werkt het!</h2>

<p>
  Selecteer onderstaand je CSV-bestand en klik op 'Go go go!'.
</p>

<form id="csv-submit-form" method="POST" action="<?php echo url('s=setup'); ?>" enctype="multipart/form-data">
  <input type="file" name="csv" />
  <input type="submit" value="Go go go!" />
</form>

