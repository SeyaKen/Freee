<?php  
  require "functions.php";

  ini_set('display_errors', 'On');
  error_reporting(E_ALL);
  // ↑エラーを全て表示してくれる関数

  if($_SERVER['REQUEST_METHOD'] == "POST")
  {
   
    $email = addslashes($_POST['email']);
    $password = addslashes($_POST['password']);
    // addslashedは自動で\を追加してくれる
    // \はKen's Breadなどの「'」を文字列として認識するためのもの

    $query = "select * from users where email = '$email' && password = '$password' limit 1";
    
    $result = mysqli_query($con, $query);
    // if (!$result) {
    //   die("Query failed: " . mysqli_error($con));
    // }

    if(mysqli_num_rows($result) > 0) {
      // ログインできた時の処理
      $row = mysqli_fetch_assoc($result);
      // $rowの中身は連想配列(key: value)
      $_SESSION['info'] = $row;
      // $_SESSIONに入れることでどのページでも使える値になる

      header("Location: profile.php");
      die;
    } else {
      // ログインに失敗した時の処理
      $error = "wrong email or password";
    }
    
  }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - my website</title>
  <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
  
  <?php require "header.php"; ?>
    <div style="margin: auto; max-width: 600px;">

      <?php
        // ログインの時にエラーがあった時ユーザーに知らせる
        if(!empty($error)) {
          echo "<div>". $error."<div>";
        }
      ?>

      <h2 style="text-align: center;">Login</h2>
      <form method="post" style="margin: auto; pading: 10px;">

        <input type="email" name="email" placeholder="Email" required><br>
        <input type="text" name="password" placeholder="Password" required><br>

        <button>Login</button>
      </form>
      </div>
  <?php require "footer.php"; ?>
  
</body>
</html>