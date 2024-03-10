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
            // 👇給料のデータを持ってくる
            $query = "select * from kyuuryoujikan where user_id = '$id'";
            $kyuuryou_result = mysqli_query($con, $query);
            $sou_kyuuryou = 0;
            $sou_roudou  = 0;
            if (!empty($kyuuryou_result)) {
              while ($roww = mysqli_fetch_assoc($kyuuryou_result)) {
                if(substr(date('Y-m-d'), 0, 4) == substr($roww['date'], 0, 4) && substr(date('Y-m-d'), 5, 2) == substr($roww['date'], 5, 2)) {
                  $sou_kyuuryou += $roww['kyuuryou'];
                  $sou_roudou += $roww['time'];
                }
              }
            }
            $query = "select kintai from kintai where user_id = '$id'";
            $kintai_result = mysqli_query($con, $query);
            $kintai = 0;
            if (mysqli_num_rows($kintai_result) > 0) {
              $kintai = mysqli_fetch_assoc($kintai_result)['kintai'];
            }
          ?>
            
              <div style="align-items:center;height: 68px;display:flex;justify-content: space-between;padding: 8px 0;">
              <div style="display:flex;align-items: center;">
                <div style="position: relative; width: 44px; height: 44px;margin-right: 10px;">
                  <?php if(file_exists($row['image'])): ?>
                  <img src="<?= $row['image'];?>" style="width: 100%; border-radius: 50%;height: 44px;width: 44px; object-fit:cover;">
                  <?php else: ?>
                    <img src="uploads/tokumei.jpeg" style="width: 100%; border-radius: 50%;height: 44px;width: 44px; object-fit:cover;">
                  <?php endif; ?>
                  <p style="color: <?php echo $kintai == 1 ? '#06c755' : 'red';?>;position: absolute;top: 24px;right: -4px;-webkit-text-stroke: 2.5px #FFF;font-size: 20px;">⚫︎</p>
                </div>
                <div>
                  <p style="color: #737373;font-size: 12px;"><?php echo nl2br(htmlspecialchars($user_row['username']));?></p>
                  <p style="color: #737373;font-size: 12px;">給料（今月）:<?php echo $sou_kyuuryou; ?>円</p>
                  <p style="color: #737373;font-size: 12px;">労働時間（今月）: <?php echo floor($sou_roudou/60);?>時間</p>
                </div>
              </div>
              
                <div  style="width: 100px;display:flex;align-items: center;">
                  <a href="profile.php?action=<?php echo $id; ?>" style="width: 100%;">
                    <p class="profile-list-button">詳細</p>
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