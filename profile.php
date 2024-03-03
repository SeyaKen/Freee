<?php  
  require "functions.php";

  // 👇エラーを表示してくれる関数
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  
  check_login();
  if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['action']) && $_POST['action'] == 'delete') {
    // Profileのデータを消す処理
    $id = $_SESSION['info']['id'];
    $query = "delete from users where id = '$id' limit 1";
    $result = mysqli_query($con, $query);

    // 👇退会するときに写真も消す処理
    if(file_exists($_SESSION['info']['image'])){
      unlink($_SESSION['info']['image']);
    }

    // 👇退会するときに投稿も消す処理
    $query = "delete from posts where user_id ='$id'";
    $result = mysqli_query($con, $query);

    header("Location: logout.php");
    die;
  }
  elseif($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['username']))
  {
    $image_added = false;
    if(!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0) {
      // file was uploaded
      $folder = "uploads/";
      if(!file_exists($folder)){
        
        mkdir($folder, 0777, true);
      }
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
    $id = $_SESSION['info']['id'];

    if($image_added == true) {
      $query = "update users set username = '$username', email = '$email', password = '$password', image = '$image' where id = '$id' limit 1 ";
    } else {
      $query = "update users set username = '$username', email = '$email', password = '$password' where id = '$id' limit 1 ";
    }

    $result = mysqli_query($con, $query);

    $query = "select * from users where id = '$id' limit 1";
    $result = mysqli_query($con, $query);
    
    if(mysqli_num_rows($result) > 0) {
      // 更新した処理を$_SESSIONに保存する処理
      $_SESSION['info']  = mysqli_fetch_assoc($result);
      // $_SESSIONに入れることでどのページでも使える値になる
    }
 
    header("Location: profile.php");
    die;
  }
  // 👇投稿を追加する処理
  elseif($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['post']))
  {
    
    $image = "";
    if(!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0) {
      // file was uploaded
      $folder = "uploads/";
      if(!file_exists($folder)){
        
        mkdir($folder, 0777, true);
      }
      $image = $folder . $_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }
    
    $post = addslashes($_POST['post']);
    $user_id = $_SESSION['info']['id'];
    $date = date('Y-m-d H:i:s');

    $query = "insert into posts (user_id, post, image, date) value ('$user_id', '$post', '$image', '$date')";
    

    $result = mysqli_query($con, $query);
 
    header("Location: profile.php");
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
</head>
<body>
  
  <?php require "header.php"; ?>
    <div style="margin: auto; max-width: 600px;">

      <?php if(!empty($_GET['action']) && $_GET['action'] == 'edit'):?>
        <h2 style="text-align: center;">Edit Profile</h2>
          <form method="post" enctype="multipart/form-data" style="margin: auto; pading: 10px;">

            <img src="<?php echo $_SESSION['info']['image'] ?>" style="width: 100px; height: 100px;object-fit: cover;margin: auto; display: block">
            <input value="<?php echo $_SESSION['info']['image'] ?>" type="file" name="image"><br>
            <input value="<?php echo $_SESSION['info']['username'] ?>" type="text" name="username" placeholder="Username" required><br>
            <input value="<?php echo $_SESSION['info']['email'] ?>" type="text" name="email" placeholder="Email" required><br>
            <input value="<?php echo $_SESSION['info']['password'] ?>" type="text" name="password" placeholder="Password" required><br>

            <button>Save</button>

            <a href="profile.php">
              <button type="button">Cancel</button>
            </a>
          </form>

      <?php elseif(!empty($_GET['action']) && $_GET['action'] == 'delete'):?>
        <h2 style="text-align: center;">Are you sure you want to delete your profile?</h2>
          <div style="margin: auto; max-width: 600px;text-align: center;"> 
            <form method="post" style="margin: auto; pading: 10px;">
              <img src="<?php echo $_SESSION['info']['image'] ?>" style="width: 100px; height: 100px;object-fit: cover;margin: auto; display: block">
              <div><?php echo $_SESSION['info']['username'] ?></div>
              <div><?php echo $_SESSION['info']['email'] ?></div>

              <!-- 👇ここで$_POST['action']にdeleteが追加？される -->
              <input type="hidden" name="action" value="delete">
              <button>Delete</button>

              <a href="profile.php">
                <button type="button">Cancel</button>
              </a>
            </form>
          </div>
      <?php else:?>
      <h2 style="text-align: center;">User Profile</h2>
      
        <br>
        <div style="margin: auto; max-width: 600px;text-align: center;">
          <div>
            <td><img src="<?php echo $_SESSION['info']['image'] ?>" style="width: 150px; height: 150px;object-fit: cover;"></td>
          </div>
          <div>
            <th>Username:</th><td><?php echo $_SESSION['info']['username'] ?></td>
          </div>
          <div>
            <th>Email:</th><td><?php echo $_SESSION['info']['email'] ?></td>
          </div>

          <a href="profile.php?action=edit">
            <button>Edit profile</button>
          </a>

          <a href="profile.php?action=delete">
            <button>Delete profile</button>
          </a>

        </div>
        <br>
        <hr>
        <h5>Create a post</h5>
        <form method="post" enctype="multipart/form-data" style="margin: auto; pading: 10px;">

          <input type="file" name="image">
          <textarea name="post" rows="8"></textarea><br>

          <button>Post</button>
        </form>

        <hr>

        <!-- 👇投稿を表示するためのHTML -->
        <div>
            <?php 
              $id = $_SESSION['info']['id'];
              $query = "select * from posts where user_id = '$id' order by id desc";
              $result = mysqli_query($con, $query);
            ;?>
            <?php if(mysqli_num_rows($result) > 0):?>
              <?php while($row = mysqli_fetch_assoc($result)):?>
                <?php
                  $id = $row['user_id'];
                  $query = "select username, image from users where id = '$id' limit 1";
                  $result2 = mysqli_query($con, $query);

                  $user_row = mysqli_fetch_assoc($result2);
                ?>
                <div style="background-color: #efefef;display:flex;border: solid thin #aaa;border-radius: 10px; margin-bottom: 10px;">
                  <div style="flex: 1;text-align: center;">
                    <img src="<?= $user_row['image'];?>" style="border-radius: 50%;margin:10px;width: 100px;height:100px; object-fit:cover;">
                    <br>
                    <?= $user_row['username'];?>
                  </div>
                  <div style="flex: 8;">
                    <?php if(file_exists($row['image'])): ?>
                    <div>
                      <img src="<?= $row['image'];?>" style="width: 100%;height:200px; object-fit:cover;">
                      <!-- 「＜？=」は「＜？php echo」と同じ -->
                    </div>
                    <?php endif;?>
                    <div>
                    <div style="color:#888;">
                      <?php
                        $week = array( "日", "月", "火", "水", "木", "金", "土" );
                        echo date("m").'月'.date("d").'日'.'('.$week[date("w")].')';
                        // date("Y/M/D",strtotime($row['date']));
                      ?>
                    </div>
                      <?php echo $row['post'];?>
                    </div>
                  </div>
                </div>
              <?php endwhile;?> 
            <?php endif;?>
          
        </div>

        <?php endif;?>
      </div>
  <?php require "footer.php"; ?>
  
</body>
</html>