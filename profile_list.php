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
    <div style="display: flex;margin: 30px 0 20px 0;justify-content: space-between;">
      <h4>プロフィール一覧</h4>
      <a href="signup.php" style="color: #060606;"><p><i class="fa-regular fa-square-plus fa-lg"></i> 新規作成</p></a>
    </div>
      <?php 
        $query = "select * from users";
        $result = mysqli_query($con, $query);
      ?>
      <?php if(mysqli_num_rows($result) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
          <?php
            $id = $row['id'];
            $query = "select * from users where id = '$id' limit 1";
            $result2 = mysqli_query($con, $query);
            $user_row = mysqli_fetch_assoc($result2);
          ?>
            
              <div style="align-items:center;height: 68px;display:flex;justify-content: space-between;padding: 8px 0;">
              <div style="display:flex;align-items: center;">
              <?php if(file_exists($row['image'])): ?>
                <img src="<?= $row['image'];?>" style="width: 100%; border-radius: 50%;height: 44px;width: 44px; object-fit:cover;margin-right: 10px;">
              <?php else: ?>
                <img src="uploads/tokumei.jpeg" style="width: 100%; border-radius: 50%;height: 44px;width: 44px; object-fit:cover;margin-right: 10px;">
              <?php endif; ?>
                <div>
                  <p style="color: #737373;font-size: 12px;"><?php echo nl2br(htmlspecialchars($user_row['username']));?></p>
                  <p style="color: #737373;font-size: 12px;"><?php echo $user_row['email'];?></p>
                  <p style="color: #737373;font-size: 11px;">id: <?php echo $id;?></p>
                </div>
              </div>
                <div  style="width: 100px;display:flex;align-items: center;">
                  <a href="profile.php?action=<?php echo $id; ?>" style="width: 100%;">
                    <p class="profile-list-button">プロフィール</p>
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