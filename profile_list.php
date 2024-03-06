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
  <link href="https://use.fontawesome.com/releases/v6.2.0/css/all.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
  
<div class="main-content">
  <?php require "header.php"; ?>

  <div class="main-right-content">
    <!-- 👇プロフィール一覧を表示するためのHTML -->
    <h4 style="margin: 30px 0 20px 0;">プロフィール一覧</h4>
      <?php 
        $query = "select * from users";
        $result = mysqli_query($con, $query);
      ?>
      <?php if(mysqli_num_rows($result) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
          <?php
            $id = $row['id'];
            $query = "select username, image from users where id = '$id' limit 1";
            $result2 = mysqli_query($con, $query);
            $user_row = mysqli_fetch_assoc($result2);
          ?>
            
              <div style="align-items:center;height: 68px;display:flex;justify-content: space-between;padding: 8px 4px;">
              <div style="display:flex;">
              <?php if(file_exists($row['image'])): ?>
                <img src="<?= $row['image'];?>" style="width: 100%; border-radius: 50%;height: 44px;width: 44px; object-fit:cover;margin-right: 10px;">
              <?php else: ?>
                <img src="uploads/tokumei.jpeg" style="width: 100%; border-radius: 50%;height: 44px;width: 44px; object-fit:cover;margin-right: 10px;">
              <?php endif; ?>
                <div>
                  <p style="color: #737373;font-size: 14px;"><?php echo nl2br(htmlspecialchars($user_row['username']));?></p>
                  <p style="color: #737373;font-size: 13px;">id: <?php echo $id;?></p>
                </div>
              </div>
                <div   style="width: 84px;display:flex;align-items: center;">
                <a href="profile.php?action=<?php echo $id; ?>" style="width: 100%;">
                  <p class="pforile-list-button">編集する</p>
                </a>
                </div>
              </div>
          <?php endwhile; ?> 
        <?php endif; ?>
    </div>
  </div>
  <?php require "footer.php"; ?>
  
</body>
</html>