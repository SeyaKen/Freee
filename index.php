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
  
  <?php require "header.php"; ?>

  <!-- 👇投稿を表示するためのHTML -->
  <h3 style="text-align:center;">Time Line</h3>
  <div style="max-width: 600px;margin:auto;">
      <?php 
        $query = "select * from posts order by id desc";
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
          <div style="background-color: #efefef;display:flex;border: solid thin #aaa;border-radius: 10px; margin: 10px 0px;">
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
                <!-- nl2brは<br>を反映してくれる（1行じゃなくて改行が適応される） -->
                <!-- htmlspecialcharsはコードがうま込まれてもただの文字として認識する -->
                <?php echo nl2br(htmlspecialchars($row['post']));?>
              </div>
            </div>
          </div>
        <?php endwhile;?> 
      <?php endif;?>
    
  </div>

  <?php require "footer.php"; ?>
  
</body>
</html>