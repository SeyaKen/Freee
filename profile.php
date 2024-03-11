<?php  
  require "functions.php";

  // 👇エラーを表示してくれる関数
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  
  check_login();
  // 👇プロフィールを削除（退会）する処理
  if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['action']) && $_POST['action'] == 'real_delete') {
    $id = $_POST['id'];
    $query = "select image from users where id = '$id'";
    $img = mysqli_fetch_assoc(mysqli_query($con, $query))['image'];
    
    $query = "delete from users where id = '$id'";
    $result = mysqli_query($con, $query);
    $query = "delete from kyuuryoujikan where user_id = '$id'";
    $result = mysqli_query($con, $query);
    $query = "delete from kintai where user_id = '$id'";
    $result = mysqli_query($con, $query);

    // 👇退会するときに写真も消す処理
    if(file_exists($img)){
      unlink($img);
    }

    header("Location: profile_list.php");
    die;
  }
  // 👇プロフィールを更新？する処理
  elseif($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['username']))
  {
    $id = $_POST['id'];
    $image_added = false;
    if(!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0) {
      // echo $_FILES['image']['name'];die;
      // file was uploaded
      $folder = "uploads/";
      $image = $folder . $_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'], $image);

      // 👇古い写真を自動で消す処理
      // if(file_exists($_SESSION['info']['image'])){
      //   unlink($_SESSION['info']['image']);
      // }

      $image_added = true;

    }
    
    $username = addslashes($_POST['username']);
    // addslashedは自動で\を追加してくれる
    // \はKen's Breadなどの「'」を文字列として認識するためのもの
    $email = addslashes($_POST['email']);
    $password = addslashes($_POST['password']);

    if($image_added == true) {
      $query = "update users set username = '$username', email = '$email', password = '$password', image = '$image' where id = '$id' limit 1 ";
    } else {
      $query = "update users set username = '$username', email = '$email', password = '$password' where id = '$id' limit 1 ";
    }

    $result = mysqli_query($con, $query);
 
    header("Location: profile_list.php");
    die;
  }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile - my website</title>
  <link href="style.css" rel="stylesheet" type="text/css">
  <link href="https://use.fontawesome.com/releases/v6.2.0/css/all.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>
  
<div class="main-content">

  <?php require "header.php"; ?>

  <!-- 👇プロフィールを編集する処理 -->
  <?php if(!empty($_GET['action']) && $_GET['action'] == 'edit' && !empty($_GET['id'])):?>
    <?php
      $id = $_GET['id'];
      $query = "select * from users where id = '$id' limit 1";
      $result2 = mysqli_query($con, $query);
      $user_row = mysqli_fetch_assoc($result2);
    ?>
    <div style="margin: 0 auto; width: 650px;">
      <h2 style="margin: 30px 0 20px 0;">プロフィールを編集</h2>
      <div style="">

        <div style="display:flex;align-items: center;justify-content: space-between;width: 100%;">

        <div style="display:flex;align-items: center;">
        <?php if(file_exists($user_row['image'])): ?>
          <div class="preview" style="margin-right: 20px;">
          <img src="<?php echo $user_row['image'] ?>" style="width: 100px; height: 100px;object-fit: cover; border-radius: 50%;">
            
          </div>
        <?php else: ?>
          <div class="preview" style=""></div>
        <?php endif; ?>

          <div>
            <p style="color: #737373;font-size: 15px;"><?php echo $user_row['email']; ?></p>
            <p style="color: #737373;font-size: 15px;"><?php echo $user_row['username']; ?></p>
            <p style="color: #737373;font-size: 15px;">id: <?php echo $user_row['id'];?></p>
          </div>
        </div>


        <form method="post" enctype="multipart/form-data">
          <div>
            <label class="profile-list-button" style="padding: 0 10px;"><input id="imgFile" style="display: none;" type="file" name="image">ファイルを選択</label>
          </div>

          <input type="hidden" name="id" value="<?php echo $user_row['id'];?>">
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
            <h4>名前:</h4><input style="margin: 5px 0;" value="<?php echo $user_row['username'] ?>" type="text" name="username" placeholder="Username" required>
          </div>

          <div style="margin-top:10px;">
            <h4>メールアドレス:</h4><input style="margin: 5px 0;" value="<?php echo $user_row['email'] ?>" type="text" name="email" placeholder="Email" required>
          </div>

          <div style="margin-top:10px;">
            <h4>パスワード:</h4><input style="margin: 5px 0;" value="<?php echo $user_row['password'] ?>" type="text" name="password" placeholder="Password" required>
          </div>

          <div style="display:flex;margin-top: 20px;">
          <button class="button-delete" style="margin-right: 20px;background-color: #0095f6;">保存する</button>
          
         
          <div style="display:flex;align-items: center;width: 100px">
            <a href="<?php echo $_SERVER['HTTP_REFERER'];?>" style="width: 100%;">
              <p class="profile-list-button" style="padding: 0 10px;color: #060606;background-color: #dbdbdb;">キャンセル</p>
            </a>
          </div>
        </form>
          
        </div>

      </div>
    </div>
    </div>
  <!-- 👇プロフィールを削除する画面 -->
  <?php elseif(!empty($_GET['id']) && !empty($_GET['action']) && $_GET['action'] == 'delete'):?>
    <?php
      $id = $_GET['id'];
      $query = "select * from users where id = '$id' limit 1";
      $result2 = mysqli_query($con, $query);
      $user_row = mysqli_fetch_assoc($result2);
    ?>

<div style="margin: 0 auto; width: 650px;">
    <h2 style="margin: 30px 0 20px 0;">プロフィールを本当に削除しますか？</h2>
      <div style="display:flex;align-items: center;justify-content: space-between;">
        <div style="display:flex;align-items: center;">
  
        <?php if(!empty($user_row['image'])): ?>
          <img src="<?php echo $user_row['image'] ?>" style="width: 100px; height: 100px;object-fit: cover; border-radius: 50%;margin-right: 20px;">
        <?php else: ?>
          <img src="uploads/tokumei.jpeg" style="width: 100%; border-radius: 50%;height: 44px;width: 44px; object-fit:cover;margin-right: 10px;">
        <?php endif; ?>
          <div>
            <p style="color: #737373;font-size: 15px;"><?php echo $user_row['email']; ?></p>
            <p style="color: #737373;font-size: 15px;"><?php echo $user_row['username']; ?></p>
            <p style="color: #737373;font-size: 15px;">id: <?php echo $user_row['id'];?></p>
          </div>
        </div>

        <div style="display: flex;">
        <form method="post">
          <div style="display:flex;align-items: center;margin-right: 10px;">
              <!-- 👇ここで$_POST['action']にdeleteが追加？される -->
          <input type="hidden" name="action" value="real_delete">
          <input type="hidden" name="id" value="<?php echo $user_row['id'];?>">
          <button class="button-delete" style="margin-right: 20px;">削除する</button>
          
         
          <div style="display:flex;align-items: center;width: 100px">
            <a href="<?php echo $_SERVER['HTTP_REFERER'];?>" style="width: 100%;">
              <p class="profile-list-button" style="padding: 0 10px;">キャンセル</p>
            </a>
          </div>
        </form>
          </div>
        </div>

      </div>
    </div>

  <?php elseif(!empty($_GET['action'])):?>
    <?php
      $id = $_GET['action'];
      $query = "select * from users where id = '$id' limit 1";
      $result2 = mysqli_query($con, $query);
      $user_row = mysqli_fetch_assoc($result2);
    ?>
    <div style="margin: 0 auto; width: 650px;">
    <h2 style="margin: 30px 0 16px 0;">プロフィール</h2>
      <div style="display:flex;align-items: center;justify-content: space-between;    background-color: #efefef !important;border-radius: 20px;padding: 20px;">
        <div style="display:flex;align-items: center;">
        <?php if(file_exists($user_row['image'])): ?>
          <img src="<?php echo $user_row['image'] ?>" style="width: 100px; height: 100px;object-fit: cover; border-radius: 50%;margin-right: 10px;">
        <?php else: ?>
          <img src="uploads/tokumei.jpeg" style="width: 100%; border-radius: 50%;height: 44px;width: 44px; object-fit:cover;margin-right: 10px;">
        <?php endif; ?>

          <div>
            <p style="color: #737373;font-size: 13px;"><?php echo $user_row['email']; ?></p>
            <p style="color: #737373;font-size: 13px;"><?php echo $user_row['username']; ?></p>
            <p style="color: #737373;font-size: 13px;">id: <?php echo $user_row['id'];?></p>
            <?php if(($_SESSION['info']['email']) == 'owner@mail.com'): ?>
            <p><a style="font-size: 13px;color: #1967d2;" href="https://www.kenkemblog.com/">https://www.kenkemblog.com/</a></p>
            <?php endif; ?>
          </div>
        </div>
        <div style="display: flex; ">
          <div style="display:flex;align-items: center;margin-right: 10px;">
            <a style="width: 100%;" href="profile.php?action=edit&id=<?php echo $user_row['id'];?>">
              <p class="profile-list-button" style="padding: 0 10px;">プロフィールを編集</p>
            </a>
          </div>

          <div style="display:flex;align-items: center;">
            <a href="profile.php?action=delete&id=<?php echo $user_row['id'];?>" style="width: 100%;">
            <p class="profile-list-button" style="padding: 0 10px;background-color:#ed4956;">プロフィールを削除</p>
            </a>
          </div>
        </div>
      </div>


      
      <!-- 👇給料・労働時間の変更 -->
      <h2 style="margin: 60px 0 16px 0;">給料・労働時間</h2>
      <div style="display:flex;align-items: center;justify-content: space-between;background-color: #efefef !important;border-radius: 20px;padding: 20px;">
        <?php
            $id = $_GET['action'];
            $query = "select * from users where id = '$id' limit 1";
            $result2 = mysqli_query($con, $query);
            $user_row = mysqli_fetch_assoc($result2);
            // 👇給料のデータを持ってくる
            $query = "select * from kyuuryoujikan where user_id = '$id'";
            $kyuuryou_result = mysqli_query($con, $query);
            $sou_kyuuryou = 0;
            $sou_roudou  = 0;
            if (!empty($kyuuryou_result)) {
              while ($roww = mysqli_fetch_assoc($kyuuryou_result)) {
                $sou_kyuuryou += $roww['kyuuryou'];
                $sou_roudou += $roww['time'];
              }
            }
            $query = "select kintai from kintai where user_id = '$id'";
            $kintai_result = mysqli_query($con, $query);
            $kintai = 0;
            if (mysqli_num_rows($kintai_result) > 0) {
              $kintai = mysqli_fetch_assoc($kintai_result)['kintai'];
            }
          ?>
              <div style="display:flex;align-items: center;">
                
                <div>
                  <p style="color: #737373;font-size: 12px;"><?php echo nl2br(htmlspecialchars($user_row['username']));?></p>
                  <p style="color: #737373;font-size: 12px;">給料（今月）:<?php echo $sou_kyuuryou; ?>円</p>
                  <p style="color: #737373;font-size: 12px;">労働時間（今月）: <?php echo floor($sou_roudou/60);?>時間</p>
                </div>
              </div>
              
                  <div style="display:flex;align-items: center;">
                    <a href="kintai_list.php?id=<?php echo $id; ?>" style="width: 100%;" href="profile.php?action=edit&id=<?php echo $user_row['id'];?>">
                      <p class="profile-list-button" style="padding: 0 10px;">給料・労働時間の詳細</p>
                    </a>
                  </div>
              </div>
        <?php endif; ?>
    </div>

  </div>
  <?php require "footer.php"; ?>
  
</body>
</html>