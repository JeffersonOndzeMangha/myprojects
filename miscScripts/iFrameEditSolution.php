<?php
header('Access-Control-Allow-Origin: *');
if (isset($_POST)) {
   returnContent();
} else  {
    die();
}
function returnContent(){
  if(isset($_POST['doc'])){
    $n = $_POST['doc'];
    if($n == "NPAAtlanta"){
      $content = file_get_contents('https://docs.google.com/spreadsheets/d/e/2PACX-1vRzs53kxrdHm8CJ9b5LoPktKw1DoSVWmgxdjCe80Gmb5bnQKHnP_Q1o9uGM_Al1qNgW0dlwFLOPNl7O/pubhtml?gid=1034251541&single=true');
      $content = str_replace('</title>','</title><base href="http://docs.google.com" />', $content);
      echo $content.'<br>';
      die();
    }elseif($n == "NPASanDiego"){
      $content = file_get_contents('https://docs.google.com/spreadsheets/d/e/2PACX-1vRzs53kxrdHm8CJ9b5LoPktKw1DoSVWmgxdjCe80Gmb5bnQKHnP_Q1o9uGM_Al1qNgW0dlwFLOPNl7O/pubhtml?gid=1987306047&single=true');
      $content = str_replace('</title>','</title><base href="http://docs.google.com" />', $content);
      echo $content.'<br>';
      die();
    }elseif($n == "NPAPhilly"){
      $content = file_get_contents('https://docs.google.com/spreadsheets/d/e/2PACX-1vRzs53kxrdHm8CJ9b5LoPktKw1DoSVWmgxdjCe80Gmb5bnQKHnP_Q1o9uGM_Al1qNgW0dlwFLOPNl7O/pubhtml?gid=1729714689&single=true');
      $content = str_replace('</title>','</title><base href="http://docs.google.com" />', $content);
      echo $content.'<br>';
      die();
    }elseif($n == "NPADallas"){
      $content = file_get_contents('https://docs.google.com/spreadsheets/d/e/2PACX-1vRzs53kxrdHm8CJ9b5LoPktKw1DoSVWmgxdjCe80Gmb5bnQKHnP_Q1o9uGM_Al1qNgW0dlwFLOPNl7O/pubhtml?gid=1986020005&single=true');
      $content = str_replace('</title>','</title><base href="http://docs.google.com" />', $content);
      echo $content.'<br>';
      die();
    }elseif ($n == "NPACincy") {
      $content = file_get_contents('https://docs.google.com/spreadsheets/d/e/2PACX-1vRzs53kxrdHm8CJ9b5LoPktKw1DoSVWmgxdjCe80Gmb5bnQKHnP_Q1o9uGM_Al1qNgW0dlwFLOPNl7O/pubhtml?gid=1143563854&single=true');
      $content = str_replace('</title>','</title><base href="http://docs.google.com" />', $content);
      echo $content.'<br>';
      die();
    }elseif($n == "ManheimSV"){
      $content = file_get_contents('https://docs.google.com/spreadsheets/d/e/2PACX-1vRzs53kxrdHm8CJ9b5LoPktKw1DoSVWmgxdjCe80Gmb5bnQKHnP_Q1o9uGM_Al1qNgW0dlwFLOPNl7O/pubhtml?gid=203371126&single=true');
      $content = str_replace('</title>','</title><base href="http://docs.google.com" />', $content);
      echo $content.'<br>';
      die();
    }elseif($n == "ManheimIn"){
      $content = file_get_contents('https://docs.google.com/spreadsheets/d/e/2PACX-1vRzs53kxrdHm8CJ9b5LoPktKw1DoSVWmgxdjCe80Gmb5bnQKHnP_Q1o9uGM_Al1qNgW0dlwFLOPNl7O/pubhtml?gid=660066957&single=true');
      $content = str_replace('</title>','</title><base href="http://docs.google.com" />', $content);
      echo $content.'<br>';
      die();
    }elseif($n == "EP"){
      $content = file_get_contents('https://docs.google.com/spreadsheets/d/e/2PACX-1vRzs53kxrdHm8CJ9b5LoPktKw1DoSVWmgxdjCe80Gmb5bnQKHnP_Q1o9uGM_Al1qNgW0dlwFLOPNl7O/pubhtml?gid=853407582&single=true');
      $content = str_replace('</title>','</title><base href="http://docs.google.com" />', $content);
      echo $content.'<br>';
    }elseif($n == "DAA"){
      $content = file_get_contents('https://docs.google.com/spreadsheets/d/e/2PACX-1vRzs53kxrdHm8CJ9b5LoPktKw1DoSVWmgxdjCe80Gmb5bnQKHnP_Q1o9uGM_Al1qNgW0dlwFLOPNl7O/pubhtml?gid=1589982105&single=true');
      $content = str_replace('</title>','</title><base href="http://docs.google.com" />', $content);
      echo $content.'<br>';
    }
  }
}

?>
