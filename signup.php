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
    $folder = "uploads/";
    $image_upload = false;
    if(!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0) {
      $image = $folder . $_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'], $image);
      $image_upload = true;
    }
    $date = date('Y-m-d H:i:s');

    if($image_upload) {
     $query = "insert into users (username, email, password, date, image) values ('$username', '$email', '$password', '$date', '$image')";
    } else {
     $query = "insert into users (username, email, password, date) values ('$username', '$email', '$password', '$date')";
    }
    
    $result = mysqli_query($con, $query);
    if (!$result) {
      die("Query failed: " . mysqli_error($con));
    }

    header("Location: profile_list.php");
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
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>
  <div class="main-content">
    <?php require "header.php"; ?>
      <div style="margin: 0 auto; width: 650px;">
        <h2 style="margin: 30px 0 20px 0;">プロフィールを編集</h2>
        <div style="">

          <div style="display:flex;align-items: center;justify-content: space-between;width: 100%;">

          <div style="display:flex;align-items: center;">
            <div class="preview" style=""></div>
          </div>

          <form method="post" enctype="multipart/form-data">
            <div>
              <label class="profile-list-button" style="padding: 0 10px;"><input id="imgFile" style="display: none;" type="file" name="image">ファイルを選択</label>
            </div>

            <!-- 👇imgを読み込みjQeryでプレビューを表示する処理 -->
            <script>
                $('#imgFile').change(
                function () {
                    if (!this.files.length) {
                        return;
                    }

                    var file = $(this).prop('files')[0];
                    var fr = new FileReader();
                    $('.preview').css('background-image', 'none');
                    fr.onload = function() {
                        $('.preview').css('background-image', 'url(' + fr.result + ')');
                    }
                    fr.readAsDataURL(file);
                    $(".preview img").css('opacity', 0);
                }
            );
            </script>

          </div>
            
            <div style="margin-top:10px;">
              <h4>名前:</h4><input style="margin: 5px 0;" type="text" name="username" placeholder="名前" required>
            </div>

            <div style="margin-top:10px;">
              <h4>メールアドレス:</h4><input style="margin: 5px 0;" type="text" name="email" placeholder="Email" required>
            </div>

            <div style="margin-top:10px;">
              <h4>パスワード:</h4><input style="margin: 5px 0;" type="text" name="password" placeholder="Password" required>
            </div>

            <div style="display:flex;margin-top: 20px;">
            <button class="button-delete" style="margin-right: 20px;background-color: #0095f6;">保存する</button>
            
          
            <div style="display:flex;align-items: center;width: 100px;">
              <a href="<?php echo $_SERVER['HTTP_REFERER'];?>" style="width: 100%;">
                <p class="profile-list-button" style="padding: 0 10px;color: #060606;background-color: #dbdbdb;">キャンセル</p>
              </a>
            </div>
          </form>
            
          </div>

        </div>
      </div>
    
  </div>
  <?php require "footer.php"; ?>
</body>
</html>