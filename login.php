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
      // 👇$_SESSIONに入れることでどのページでも使える値になる
      $_SESSION['info'] = $row;
      

      header("Location: kintai.php");
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
  <link href="https://use.fontawesome.com/releases/v6.2.0/css/all.css" rel="stylesheet">
</head>
<body>
  
<?php
        // ログインの時にエラーがあった時ユーザーに知らせる
        if(!empty($error)) {
          echo "<p style='text-align:center;margin-top: 30px;'>". "パスワードかメールアドレスが間違っています。"."</p>";
        }
      ?>
  <div style="height: 100vh;display:flex;">
  <?php if(!empty($_SESSION['info']['email']) && $_SESSION['info']['email'] == 'owner@mail.com'): ?>
  <?php require "header.php"; ?>
  <?php endif; ?> 
    <div style="margin: auto; width: 350px;border: 1px solid #dbdbdb;height: 400px;display: flex; align-items:center;">

      


    <div style="height: 80%;">
    <div style="text-align:center;">
        <img src="uploads/kintai.png" style="width: 52%;">
      </div>
      <div style="margin: 0 40px 6px;">
      <form method="post" style="margin: auto; pading: 10px;">

        <input style="margin: 5px 0;" type="email" name="email" placeholder="Email" required><br>
        <input style="margin: 5px 0;" type="text" name="password" placeholder="Password" required><br>

        <button class="button-delete" style="margin-top:80px;width: 100%;background-color: #0095f6;"><p>ログイン</p></button>
      </form>
      </div>
    </div>
      
      </div>
      </div>
  <?php require "footer.php"; ?>
  
</body>
</html>