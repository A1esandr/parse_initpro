<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$a = file_get_contents('http://www.sberbank-ast.ru/purchaseList.aspx');
$b = stristr($a,"id=\"phWorkZone_xmlData\"");
$b = stristr($b,">");
$b = substr($b,1);
$c = stristr($b,"</textarea>",TRUE);
//var_dump($c);
$d = htmlspecialchars_decode($c,ENT_XML1);
$f = new SimpleXMLElement($d);//var_dump($f);
for($i=0;$i<count($f);$i++){
	$au_id = (string) $f->row[$i]->purchID;var_dump($au_id);
    $au_code = (string) $f->row[$i]->purchCode;var_dump($au_code);
  	$page = "http://www.sberbank-ast.ru/purchaseview.aspx?id=".$au_id;
    $h = file_get_contents($page);
  	$g = stristr($h,"id=\"phWorkZone_xmlData\"");
    $g = stristr($g,">");
    $g = substr($g,1);
    $j = stristr($g,"</textarea>",TRUE);
  $jd = htmlspecialchars_decode($j,ENT_XML1);//var_dump($jd);die;
  libxml_use_internal_errors(false);
  $jf = new SimpleXMLElement($jd);var_dump($jf);
  //$jf = simplexml_load_string($jd);var_dump($jf);
  
  	
}
