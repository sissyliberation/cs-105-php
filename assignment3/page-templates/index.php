<?php
// openZdatabase.php -- PHP include to open database on 'Z' server
// Auction Web Application Project
//
// C S 105: PHP/SQL, Spring 2014, J. Thywissen
// The University of Texas at Austin
//

try {
    $dbh = new PDO('mysql:host=localhost;dbname=cs105_courtois;charset=utf8', 'courtois', 'OX4q3k+WRr', array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => true
    ));

$sth = $dbh->prepare("SELECT ITEM_CAPTION, ITEM_DESCRIPTION FROM AUCTION");
$sth->execute();

$result = $sth->fetchAll();

$f_name = $dbh->prepare("SELECT FORENAME FROM PERSON WHERE PERSON_ID = 1");
$f_name->execute(); 

$res_name = $f_name->fetchColumn();

echo '
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <title>Welcome</title>
  <link rel="shortcut icon" href="favicon.ico">
  <link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
  <link href="style.css" rel="stylesheet">
</head>
<body>
  <div class="nav">
    <img src="http://placekitten.com/g/260/100" alt="">
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="listings.php">Your Listings</a></li>
      <li><a href="pay.php">Pay</a></li>
      <li><a href="register.php">Not a member?</a></li>
    </ul>
  </div>
  <div class="wrapper">
    <div class="landing_page">
      <h1>Welcome, '; echo $res_name; echo'</h1>
    </div>
    <div class="items">';
    echo '<select style="margin-bottom:20px;"><option value="0">All</option>';
    $categories = $dbh->prepare("SELECT ITEM_CATEGORY_ID, NAME FROM ITEM_CATEGORY");
    $categories->execute();
    $res_cat = $categories->fetchAll();

    foreach($res_cat as $i) {
      echo '<option value="';
      echo $i['ITEM_CATEGORY_ID'];
      echo '">';
      echo $i['NAME'];
      echo '</option>';
    }
    echo '</select>';
      echo '<ul>';
        
      foreach($result as $key=>$value){
        // print_r($value);
        echo '<li>
              <img src="http://placekitten.com/g/100/100" alt=""/>
              <span class="itemTitle">';
              echo $value["ITEM_CAPTION"];
              echo '</span><br>
              <span class="itemDesc">';
              echo $value["ITEM_DESCRIPTION"];
              echo '</span><br>
              <span>current bid: $90.00</span><br>
              <input placeholder="enter bid">
              <input type="button" value="Place Bid" ></li>';
      }

  echo'</ul>
    </div>
  </div>
</body>
</html>';
   
} catch(PDOException $e) {
    error_log("{$e->getFile()}:{$e->getLine()}: PDO open failed: {$e->getCode()}: {$e->getMessage()}");
    header("HTTP/1.1 500 Internal Server Error");
    echo '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <title>Internal Server Error</title>
    <meta charset="utf-8" />
  </head>
  <body>
    <h1>Internal Server Error</h1>
    <p>Sorry, this Web site has encountered an unexpected condition, and is currently unable to respond to your request.</p>
    <p>Please retry later.</p>
    <p><small></small></p>
  </body>
</html>
';
    exit(1);
}
?>