<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$a = file_get_contents('http://www.sberbank-ast.ru/purchaseList.aspx');
//$b = preg_split("/purchID&gt;+[0-9]*+&lt;\/purchID/",$a,PREG_SPLIT_OFFSET_CAPTURE);var_dump($b);
$b = stristr($a,"id=\"phWorkZone_xmlData\"");
$b = stristr($b,">");
$b = substr($b,1);
$c = stristr($b,"</textarea>",TRUE);
//var_dump($c);
//$d = htmlspecialchars_decode($c,ENT_XML1);
//$v = preg_match_all("/purchID&gt+[0-9]*+&lt;\/purchID>/",$a);var_dump($v);

$r = preg_split("/purchID&gt;+([0-9]{1,20})+&lt;\/purchID/",$c,-1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);//var_dump($r);

for($i=0;$i<count($r);$i++){
  if(preg_match("/^[0-9]{7}/",$r[$i]) == 1){
    $id = $r[$i];var_dump($id);
    $page = "http://www.sberbank-ast.ru/purchaseview.aspx?id=".$r[$i];
  	$h = file_get_contents($page);
    $g = stristr($h,"id=\"phWorkZone_xmlData\"");
    $g = stristr($g,">");
    $g = substr($g,1);
    $j = stristr($g,"</textarea>",TRUE);
    //var_dump($j);die;
    $t = preg_split("/fileName&gt;(.*)&lt;\/fileName/U",$j,-1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
    //var_dump($t);die;
    for($iss=1;$iss<count($t);$iss++){
      if($iss%2==0){
        if(preg_match("/url&gt;.*&lt;\/url/",$t[$iss]) == 1){
          $res1 = preg_split("/url&gt;(.*)&lt;\/url/",$t[$iss],-1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
          //var_dump($res1);die;
          $docurl = $res1[1];var_dump($docurl);
        }
      } else {
      $docname = $t[$iss];var_dump($docname);
      }
    }
  } else if(preg_match("/purchCode&gt;.*&lt;\/purchCode/",$r[$i]) == 1){
    $code = preg_split("/purchCode&gt;(.*)&lt;\/purchCode/",$r[$i],-1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
  	$idCode = $code[1];var_dump($idCode);
  }
  
  
}
