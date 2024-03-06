<?php
  require "functions.php";

  ini_set('display_errors', 'On');
  error_reporting(E_ALL);
  // ↑エラーを全て表示してくれる関数

  if($_SERVER['REQUEST_METHOD'] == "POST")
  {
    $username = addslashes($_POST['username']);
    // addslashedは自動で\を追加してくれる
    // \はKen's Breadなどの「'」を文字列として認識するためのもの
    $email = addslashes($_POST['email']);
    $password = addslashes($_POST['password']);
    $date = date('Y-m-d H:i:s');

    $query = "insert into users (username, email, password, date) values ('$username', '$email', '$password', '$date')";
    
    $result = mysqli_query($con, $query);
    if (!$result) {
      die("Query failed: " . mysqli_error($con));
    }

    header("Location: login.php");
    die;
  }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup - my website</title>
  <link href="style.css" rel="stylesheet" type="text/css">
  <link href="https://use.fontawesome.com/releases/v6.2.0/css/all.css" rel="stylesheet">
</head>
<body>
  
  <?php require "header.php"; ?>
    <div style="margin: auto; max-width: 600px;">
      <h2 style="text-align: center;">Signup</h2>
      <form method="post" style="margin: auto; pading: 10px;">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="text" name="email" placeholder="Email" required><br>
        <input type="text" name="password" placeholder="Password" required><br>

        <button>Signup</button>
      </form>
      </div>
  <?php require "footer.php"; ?>
  
</body>
</html>