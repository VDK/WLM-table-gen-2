<?php $gem = $data->gemeente->gemeente;
?>
<p>
  De [[Nederlandse gemeente|gemeente]] [[<?php echo ($data->wikigem == $gem)?  $gem : $data->wikigem."|".$gem;?>]] heeft ## [[gemeentelijk monument|gemeentelijke monumenten]], hieronder een overzicht. Zie ook de [[lijst van rijksmonumenten in <?php echo $gem."|rijksmonumenten in ".$gem; ?>]].
  Dit is het CBS nr: <?php echo $data->gemeente->gemcode; ?>.
</p>

<?php // for ($i=0; $i <count($rows); $i++){

?>





<?php
//}
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