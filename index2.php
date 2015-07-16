<?php
require_once('MysqliDb.php');
require_once('config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

function getXMLPart($page) {
		
		$result = stristr($page,"id=\"phWorkZone_xmlData\"");
		$result = stristr($result,">");
		$result = substr($result,1);
		$result = stristr($result,"</textarea>",TRUE);

		return $result;
	}
function mainFunction(){
global $db;

$a = file_get_contents('http://www.sberbank-ast.ru/purchaseList.aspx');

$c = getXMLPart($a);

$r = preg_split("/purchID&gt;+([0-9]{1,20})+&lt;\/purchID/",$c,-1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);

  for($i=0;$i<count($r);$i++){
    if(preg_match("/^[0-9]{7}/",$r[$i]) == 1){
      $id = $r[$i];var_dump($id);
      $idpage = "http://www.sberbank-ast.ru/purchaseview.aspx?id=".$r[$i];
      $h = file_get_contents($idpage);
      
      $j = getXMLPart($h);
      
      $t = preg_split("/fileName&gt;(.*)&lt;\/fileName/U",$j,-1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
      
      for($iss=1;$iss<count($t);$iss++){
        if($iss%2==0){
          if(preg_match("/url&gt;.*&lt;\/url/",$t[$iss]) == 1){
            $res1 = preg_split("/url&gt;(.*)&lt;\/url/",$t[$iss],-1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
            
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
  
}

$db = new MysqliDb (DB_SERVER, DB_USER, DB_PASS, DB_NAME);

?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>InitPro</title>
  </head>
<body>
  
  <?php //mainFunction();?>
  
  

</body>
</html>
