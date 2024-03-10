<?php  
  require "functions.php";
  ini_set('display_errors', 1); 
  ini_set('display_startup_errors', 1); 
  error_reporting(E_ALL);
  
  check_login();

  // 👇給料・労働時間を編集する処理
  if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['time']))
  {
    $update_kyuurou = $_POST['kyuuryou'];
    $update_time = $_POST['time'];
    $id = $_GET['id'];
    $user_id = $_GET['user_id'];
    $query = "update kyuuryoujikan set kyuuryou = '$update_kyuurou', time = '$update_time' where id = '$id' limit 1 ";
    mysqli_query($con, $query);
 
    header("Location: kintai_list.php?id=$user_id");
    die;
  }
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

  <!-- 👇給料・労働時間を編集する処理 -->
  <?php if(!empty($_GET['action']) && $_GET['action'] == 'edit' && !empty($_GET['id'])):?>
    <?php
      $id = $_GET['id'];
      $query = "select * from kyuuryoujikan where id = '$id' limit 1";
      $res = mysqli_query($con, $query);
      $kyuuryou_row = mysqli_fetch_assoc($res);
    ?>
    <div style="margin: 0 auto; width: 650px;">
      <h2 style="margin: 30px 0 20px 0;">編集</h2>
      <div style="">

        <div style="display:flex;align-items: center;justify-content: space-between;width: 100%;">

        <form method="post" enctype="multipart/form-data">
          
          <div style="margin-top:10px;">
            <h4>給料（円）:</h4><input style="margin: 5px 0;" value="<?php echo $kyuuryou_row['kyuuryou'] ?>" type="number" name="kyuuryou" required>
          </div>

          <div style="margin-top:10px;">
            <h4>労働時間（分）:</h4><input style="margin: 5px 0;" value="<?php echo $kyuuryou_row['time'] ?>" type="number" name="time" required>
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
  <?php else: ?>
  <div class="main-right-content">
    <!-- 👇プロフィール一覧を表示するためのHTML -->
    <!-- <div style="display: flex;margin: 30px 0 20px 0;justify-content: space-between;"> -->
    <h3 style="margin: 30px 0 10px 0;">一覧</h3>
    <div>
      <?php
        $id = $_GET['id'];
        $query = "select * from kyuuryoujikan where user_id = '$id' order by date";
        // musqli_assocをやるとおかしくなるから$kyuuryou_resultを2個用意
        $kyuuryou_result = mysqli_query($con, $query);
        $kyuuryou_result1 = mysqli_query($con, $query);
        $nen_check = "";
        $tuki_check = "";
        if(mysqli_num_rows($kyuuryou_result1) > 0): 
          while($row = mysqli_fetch_assoc($kyuuryou_result1)):
            $kyuuryou_jikan_id = ($row['id']);
            $sou_kyuuryou = 0;
            $sou_roudou  = 0;
            // if($nen_check == "" && $tuki_check == "") {
            //   $nen_check = substr($row['date'], 0, 4);
            //   $tuki_check = substr($row['date'], 5, 2);
            // }
            if (!empty($kyuuryou_result)) {
              while ($roww = mysqli_fetch_assoc($kyuuryou_result)) {
                $sou_kyuuryou += $roww['kyuuryou'];
                $sou_roudou += $roww['time'];
              }
            }
      ?>
        <?php if($nen_check == substr($row['date'], 0, 4) && $tuki_check != substr($row['date'], 5, 2)): ?>
        <h4 style="padding: 8px 10px;"><?php echo substr($row['date'], 5, 2) ?>月</h4>
        <?php elseif($nen_check != substr($row['date'], 0, 4)): ?>
        <h4 style="padding: 8px 10px 0 5px;"><?php echo substr($row['date'], 0, 4) ?>年 <?php echo substr($row['date'], 5, 2) ?>月</h4>
        <?php endif ?>
              <div style="align-items:center;height: 68px;display:flex;justify-content: space-between;padding: 8px 10px;border-bottom-width: 1px;border-bottom -color: #dadcdb;border-bottom-style: solid;border-color: #dadcdb;">
                <div style="display:flex;align-items: center;justify-content: space-between;">
                  <p style="color: #737373;font-size: 12px;margin-right: 20px;"><?php echo $row['date'];?></p>

                  <div style="">
                    <p style="color: #737373;font-size: 12px;">給料（今月）: <?php echo $sou_kyuuryou; ?>円</p>
                    <p style="color: #737373;font-size: 12px;">労働時間（今月）: <?php echo floor($sou_roudou/60);?>時間</p>
                  </div>

                </div>
                <div  style="width: 100px;display:flex;align-items: center;">
                  <a href="kintai_list.php?action=edit&id=<?php echo $kyuuryou_jikan_id; ?>&user_id=<?php echo $id; ?>" style="width: 100%;">
                    <p class="profile-list-button">編集</p>
                  </a>
                </div>
              </div>

          <?php
            $nen_check = substr($row['date'], 0, 4);
            $tuki_check = substr($row['date'], 5, 2);
            endwhile;
          ?> 
        <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
  <?php require "footer.php"; ?>
  
</body>
</html>