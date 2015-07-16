<?php
require_once('MysqliDb.php');
require_once('config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

function getXMLPart($page) { //Функция для выделения XML-части из страницы
		
		$result = stristr($page,"id=\"phWorkZone_xmlData\"");
		$result = stristr($result,">");
		$result = substr($result,1);
		$result = stristr($result,"</textarea>",TRUE);

		return $result;
	}

function getDocs($auctionPage,$au_id){
	global $result;
    global $db;
	  
  	$h = file_get_contents($auctionPage);
      
    $j = getXMLPart($h);
      
    $t = preg_split("/fileName&gt;(.*)&lt;\/fileName/U",$j,-1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
  // Получаем массив с именами документов и ссылками на них
  
    $allDocs = array(); //Массив для сбора имен документов 
  	$allLinks = array();//Массив для сбора ссылок на документы 
  
    for($iss=1;$iss<count($t);$iss++){
      
      if($iss%2==0){
        
        if(preg_match("/url&gt;.*&lt;\/url/",$t[$iss]) == 1){
          
          $res1 = preg_split("/url&gt;(.*)&lt;\/url/",$t[$iss],-1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
          // Получаем ссылку на документ
          
          array_push($allLinks,$res1[1]);
        }
        
      } else {
        
        //Записываем имя документа
        array_push($allDocs,$t[$iss]);
        
      }
      
    }
  
  //Запись результатов в таблицу
    for($w=0;$w<count($allDocs);$w++){
      
      $datas = Array ("auction_id" => $au_id,
                      "au_doc_name" => $allDocs[$w],
                      "au_doc_link" => $allLinks[$w]
                     );
      
      $db->insert ('au_doc', $datas);
      
    }
  
  	unset($allDocs,$allLinks);//Освобождаем переменные для следующего аукциона
}

function mainFunction(){
  
  global $db;
  
  $a = file_get_contents('http://www.sberbank-ast.ru/purchaseList.aspx');
  
  $c = getXMLPart($a);
  
  $r = preg_split("/purchID&gt;+([0-9]{1,20})+&lt;\/purchID/",$c,-1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
  //Получаем массив id аукционов и их кодов
  
  $auCodes = array();
  $auPages = array();
  
    for($i=0;$i<count($r);$i++){
      
      if(preg_match("/^[0-9]{7}/",$r[$i]) == 1){
        //Если елемент является id
        
        $idpage = "http://www.sberbank-ast.ru/purchaseview.aspx?id=".$r[$i];
        
        array_push($auPages,$idpage);
        
      } else if(preg_match("/purchCode&gt;.*&lt;\/purchCode/",$r[$i]) == 1){
        
        $code = preg_split("/purchCode&gt;(.*)&lt;\/purchCode/",$r[$i],-1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
        
        array_push($auCodes,$code[1]);
        
      }
      
    }
  
  	for($i=0;$i<count($auCodes);$i++){//Записываем таблицу аукционов
    
      $data = Array ("auction_code" => $auCodes[$i],
                 "auction_page" => $auPages[$i]
      );
      $db->insert ('auction', $data);
      
    }
    
  //Получаем и записываем документы для аукционов
  
    $cols = Array ("id", "auction_page");
  
    $results = $db->get ("auction", null, $cols);
  
    if ($db->count > 0)
      
        foreach ($results as $result) { 
      
            getDocs($result["auction_page"],$result["id"]);
      
        }
  
  //Выводим результаты на страницу
    $toPages = $db->get ("auction");
  
    if ($db->count > 0)
      
      foreach ($toPages as $toPage) { 
      
          echo "<tr><td>".$toPage["auction_code"]."</td><td>".$toPage["auction_page"]."</td>";
      
          $db->where ("auction_id", $toPage["id"]);
      
          $pageDocs = $db->get ("au_doc");
      
          if ($db->count > 0)
            
            echo "<td>";
      
            foreach ($pageDocs as $pageDoc) { 
              
              echo $pageDoc["au_doc_name"]." - ".$pageDoc["au_doc_link"]."<br><br>";
              
            }
      
            echo "</td></tr>";
      
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
 <table> 
   <tr>
     <th>Код аукциона</th>
     <th>Страница аукциона</th>
     <th>Документы аукциона</th>
   </tr>
  <?php mainFunction();?>
  
  </table> 

</body>
</html>
