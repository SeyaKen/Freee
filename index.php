<?php  
  require "functions.php";
  ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
  
  check_login();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Website</title>
  <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
  
  <?php require "header.php"; ?>

  <?php require "footer.php"; ?>
  
</body>
</html>