<?php  
  require "functions.php";
  ini_set('display_errors', 1); 
  ini_set('display_startup_errors', 1); 
  error_reporting(E_ALL);
  
  check_login();

  // echo date("Y-m-d");die;

  if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['action']) && $_POST['action'] == 'edit') {
    $id = $_GET['id'];
    $query = "select shift from users where id = '$id'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $shift = json_decode($row['shift'], true);
    if(empty($row['shift'])) {
      $shift = array($_GET['year']."-".$_GET['day'] => array("01" => $_POST['time1'], "02" => $_POST['time2']));
    } else if(!empty($shift[$_GET['year']."-".$_GET['day']])) {
      $shift[$_GET['year']."-".$_GET['day']] = array("01" => $_POST['time1'], "02" => $_POST['time2']);
    } else {
      $for_json = array($_GET['year']."-".$_GET['day'] => array("01" => $_POST['time1'], "02" => $_POST['time2']));
      // print_r(array_merge($shift, $for_json));die;
      $shift = array_merge($shift, $for_json);
      
      // print_r($shift);die;
    }
    $value = json_encode($shift);
    $query = "update users set shift = '$value' where id = '$id'";

    $result = mysqli_query($con, $query);

    // print_r($result);die;

    header("Location: shift.php");
    die;
  } elseif($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['action']) && $_POST['action'] == 'real_delete') {
    $hinichi_id = $_POST['hinichi_id'];
    $id = $_POST['id'];
    $query = "select shift from users where id = '$id'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $shift = json_decode($row['shift'], true);
    unset($shift[$hinichi_id]);
    // print_r($hinichi_id);die;
    $value = json_encode($shift);
    $query = "update users set shift = '$value' where id = '$id'";

    $result = mysqli_query($con, $query);

    header("Location: shift.php");
    die;
  }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=, initial-scale=1.0">
  <title>Document</title>
  <link href="style.css" rel="stylesheet" type="text/css">
  <link href="https://use.fontawesome.com/releases/v6.2.0/css/all.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>

  <?php if(!empty($_GET['action']) && $_GET['action'] == "edit"): ?>
    <div style="margin: 0 auto; width: 650px;">
      <h2 style="margin: 30px 0 20px 0;text-align: center;">編集</h2>
      <div style="">

        <div style="display:flex;align-items: center;justify-content: center;width: 100%;">

        <form method="post" enctype="multipart/form-data">
          
          <div style="margin-top:10px;">
          <?php if(!empty($_GET["start"])): ?>
            <input style="margin: 5px 0;" value=<?php echo $_GET["start"] ?> type="time" name="time1" required>
          <?php else:?>
            <input style="margin: 5px 0;" type="time" name="time1" required>
          <?php endif ?>
          </div>

          <p style="font-style:bold;font-size: 35px;">~</p>

          <input type="hidden" name="action" value="edit">

          <div style="margin-top:10px;">
            <?php if(!empty($_GET["end"])): ?>
              <input style="margin: 5px 0;" value=<?php echo $_GET["end"] ?> type="time" name="time2" required>
            <?php else:?>
              <input style="margin: 5px 0;" type="time" name="time2" required>
              <?php endif ?>
          </div>

          <div style="display:flex;margin-top: 20px;">
          <button class="button-delete" style="margin-right: 20px;background-color: #0095f6;">保存する</button>
          <div style="display:flex;align-items: center;width: 100px;margin-right: 20px;">
            <a href="shift.php?action=delete&id=<?php echo $_GET['id'];?>&hinichi_id=<?php echo $_GET['year']."-".$_GET['day'];?>" style="width: 100%;">
            <p class="profile-list-button" style="padding: 0 10px;background-color:#ed4956;">削除する</p>
            </a>
          </div>
          
         
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
  <?php elseif(!empty($_GET['action']) && $_GET['action'] == "delete"): ?>
    <?php 
      $id = $_GET['id'];
      $hinichi_id = $_GET['hinichi_id'];
      $query = "select shift from users where id = '$id'";
      $result = mysqli_query($con, $query);
      $row = mysqli_fetch_assoc($result);
      $shift = json_decode($row['shift'], true);
    ?>
    <div style="margin: 0 auto; width: 650px;">
    <h2 style="margin: 30px 0 20px 0;">このシフトを本当に削除しますか？</h2>
      <div style="display:flex;align-items: center;justify-content: space-between;">
        <div style="display:flex;align-items: center;">

          <div>
            <p style="color: #737373;font-size: 15px;"><?php echo $shift[$hinichi_id]["01"]."~".$shift[$hinichi_id]["02"]; ?></p>
          </div>
        </div>

        <div style="display: flex;">
        <form method="post">
          <div style="display:flex;align-items: center;margin-right: 10px;">
              <!-- 👇ここで$_POST['action']にdeleteが追加？される -->
          <input type="hidden" name="action" value="real_delete">
          <input type="hidden" name="hinichi_id" value="<?php echo $hinichi_id;?>">
          <input type="hidden" name="id" value="<?php echo $id;?>">
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
  <?php elseif(!empty($_GET['action']) && $_GET['action'] == "edit_month"): ?>
    <?php 
    $date = $_GET["tukihi"];
    $this_month;
    $this_date;
    $zenhan_kouhan;
    // print_r($_GET["zengo"]);die;
    if($_GET["zengo"] == "before" && $_GET["zenkouhan"] == "zenhan") {
      $this_month =  date('Y-m', strtotime('-1 month ' . $date));
      $this_date = "16";
      $zenhan_kouhan = "kouhan";
    } elseif($_GET["zengo"] == "before" && $_GET["zenkouhan"] == "kouhan") {
      $this_month =  substr($_GET['tukihi'], 0, 7);
      $this_date = "01";
      $zenhan_kouhan = "zenhan";
    } elseif($_GET["zengo"] == "after" && $_GET["zenkouhan"] == "zenhan") {
      $this_month =  substr($_GET['tukihi'], 0, 7);
      $this_date = "16";
      $zenhan_kouhan = "kouhan";
    } else {
      $this_month =  date('Y-m', strtotime('+1 month ' . $date));
      $this_date = "01";
      $zenhan_kouhan = "zenhan";
    }
    ?>
    <div class="main-content" style="margin: 0 auto;">
    <?php require "header.php"; ?>

    <?php
     $query = "select * from users";
     $result = mysqli_query($con, $query);
    ?>
    <div style="width: 80%;margin: auto;">
      <div style="margin: 0 auto;margin-bottom: 20px;">
        <h3 style="text-align: center;"><a style="font-size: 30px;color: #0095f6;" href="shift.php?action=edit_month&month=<?php echo $this_month ?>&tukihi=<?php echo $this_month."-".$this_date ?>&zenkouhan=<?php echo $zenhan_kouhan ?>&zengo=before"><</a>  <?php echo $this_month ?> 
        <?php if($_GET["zenkouhan"] == "zenhan") {?> 
          後半
        <?php } else { ?>
          前半
        <?php } ?>
        <a style="font-size: 30px;color: #0095f6;" href="shift.php?action=edit_month&month=<?php echo $this_month ?>&tukihi=<?php echo $this_month."-".$this_date ?>&zenkouhan=<?php echo $zenhan_kouhan ?>&zengo=after">></a></h3>
      </div>
      <table width="80%" style="margin: auto; height: 500px;border: 1px solid #eee;border-spacing: 0px;">
      <tr>
      <th style="width: 100px;font-size: 12px;border-right: 1px solid #eee;border-bottom: 1px solid #eee;">名前</th>
      <?php
        $count = 1;
        $count0;
        // print_r(date('t', strtotime($this_month."-01")));die;
        if($_GET["zenkouhan"] == "zenhan") {
          $count0 = date('t', strtotime($this_month."-01")) - 15;
        } else {
          $count0 = 15;
        }
        while($count <= $count0){
      ?>
        <th style="font-size: 12px;border-right: 1px solid #eee;width: 35px;"><?php
        if($_GET["zenkouhan"] == "zenhan") {
          echo $count+15;
        } else {
          echo $count;
        }
        ?></th>
      <?php 
        $count++; } 
      ?>
      </tr>
      <?php
      while($table = mysqli_fetch_assoc($result)) {
      ?>
      <tr>
      <td style="width: 100px;padding-left: 5px;font-size: 12px;border-right: 1px solid #eee;border-bottom: 1px solid #eee;"><?php print(htmlspecialchars($table['username'])); ?></td>
      <?php
        $count1 = 1;
        $count2;
        if($_GET["zenkouhan"] == "zenhan") {
          $count2 = date('t', strtotime($this_month."-01")) - 15;
        } else {
          $count2 = 15;
        }
        while($count1 <= $count2){
      ?>
      <!-- 👇date("Y-m-d")を直す必要あり -->
        <td style="width: 50px;border: 1px solid #eee;height: 55px;padding: 2px;text-align:center;">
        <?php 
          $sonohi;
          if($_GET["zenkouhan"] == "zenhan") {
            $sonohi = $this_month."-".($count1 + 15);
          } else {
            if($count1 < 10) {
              $sonohi = $this_month."-"."0".$count1;
            } else {
              $sonohi = $this_month."-".$count1;
            }
          }
          // echo json_decode($table['shift'], true)[$sonohi]["01"];die;
          $json_for_display = json_decode($table['shift'], true);
        ?>
        <?php if($_SESSION['info']['email'] == "ower@mail.com" && !empty($table['shift']) && !empty($json_for_display[$sonohi])) :?>
          <a style="text-align:center;font-size: 12px;width: 100%;height: 100%;display:block;" href="shift.php?action=edit&id=<?php echo $table['id']; ?>&day=<?php
             if($_GET["zenkouhan"] == "zenhan"){
              echo $count1+15;
             } else {
              if($count1 < 10) {echo "0".$count1;} else {echo $count1;} 
             }
          ?>&year=<?php echo $this_month ?>&start=<?php echo $json_for_display[$sonohi]["01"] ?>&end=<?php echo $json_for_display[$sonohi]["02"] ?>">
        <?php else: ?>
          <a style="text-align:center;font-size: 12px;width: 100%;height: 100%;display:block;" href="shift.php?action=edit&id=<?php echo $table['id']; ?>&day=<?php 
            if($_GET["zenkouhan"] == "zenhan"){
              echo $count1+15;
             } else {
              if($count1 < 10) {echo "0".$count1;} else {echo $count1;} 
             }
          ?>&year=<?php echo $this_month ?>">
        <?php endif ?>
          <?php 
            if(!empty($table['shift']) && !empty($json_for_display[$sonohi])) {
              echo $json_for_display[$sonohi]["01"]."<br>"."~"."<br>".$json_for_display[$sonohi]["02"];
            }
          ?>
          </a>
        </td>
      <?php 
        $count1++; } 
      ?>
      </tr>
      <?php }?>
      </table>
    </div>
    
  </div>
  <?php else: ?>
  <div class="main-content" style="margin: 0 auto;">
    <?php require "header.php"; ?>

    <?php
     $query = "select * from users";
     $result = mysqli_query($con, $query);
     $zenhan_kouhan;
     if(date("d") < 16) {
        $zenhan_kouhan = "zenhan";
      } else { 
        $zenhan_kouhan = "kouhan";
      } 
    ?>
    <div style="width: 80%;margin: auto;">
      <div style="margin: 0 auto;margin-bottom: 20px;">
        <h3 style="text-align: center;"><a style="font-size: 30px;color: #0095f6;" href="shift.php?action=edit_month&month=<?php echo date("m") ?>&tukihi=<?php echo date("Y-m-d") ?>&zenkouhan=<?php echo $zenhan_kouhan ?>&zengo=before"><</a>  <?php echo date("Y-m"); ?> 
        <?php if(date("d") < 16) {?> 
          前半
        <?php } else { ?>
          後半
        <?php } ?>
        <a style="font-size: 30px;color: #0095f6;" href="shift.php?action=edit_month&month=<?php echo date("m") ?>&tukihi=<?php echo date("Y-m-d") ?>&zenkouhan=<?php echo $zenhan_kouhan ?>&zengo=after">></a></h3>
      </div>
      <table width="80%" style="margin: auto; height: 500px;border: 1px solid #eee;border-spacing: 0px;">
      <tr>
      <th style="width: 100px;font-size: 12px;border-right: 1px solid #eee;border-bottom: 1px solid #eee;">名前</th>
      <?php
        $count = 1;
        while($count < 16){
      ?>
        <th style="font-size: 12px;border-right: 1px solid #eee;width: 35px;"><?php echo $count ?></th>
      <?php 
        $count++; } 
      ?>
      </tr>
      <?php
      while($table = mysqli_fetch_assoc($result)) {
      ?>
      <tr>
      <td style="width: 100px;padding-left: 5px;font-size: 12px;border-right: 1px solid #eee;border-bottom: 1px solid #eee;"><?php print(htmlspecialchars($table['username'])); ?></td>
      <?php
        $count1 = 1;
        while($count1 < 16){
      ?>
        <td style="width: 50px;border: 1px solid #eee;height: 55px;padding: 2px;text-align:center;">
        <?php 
          $sonohi;
          if($count1 < 10) {
            $sonohi = "2024-03-"."0".$count1;
          } else {
            $sonohi = "2024-03-".$count1;
          }
          // echo json_decode($table['shift'], true)[$sonohi]["01"];die;
          $json_for_display = json_decode($table['shift'], true);
        ?>
        <?php if($_SESSION['info']['email'] == "ower@mail.com" && !empty($table['shift']) && !empty($json_for_display[$sonohi])) :?>
          <a style="text-align:center;font-size: 12px;width: 100%;height: 100%;display:block;" href="shift.php?action=edit&id=<?php echo $table['id']; ?>&day=<?php if($count1 < 10) {echo "0".$count1;} else {echo $count1;} ?>&year=<?php echo date("Y-m") ?>&start=<?php echo $json_for_display[$sonohi]["01"] ?>&end=<?php echo $json_for_display[$sonohi]["02"] ?>">
        <?php else: ?>
          <a style="text-align:center;font-size: 12px;width: 100%;height: 100%;display:block;" href="shift.php?action=edit&id=<?php echo $table['id']; ?>&day=<?php if($count1 < 10) {echo "0".$count1;} else {echo $count1;} ?>&year=<?php echo date("Y-m") ?>">
        <?php endif ?>
          <?php 
            if(!empty($table['shift']) && !empty($json_for_display[$sonohi])) {
              echo $json_for_display[$sonohi]["01"]."<br>"."~"."<br>".$json_for_display[$sonohi]["02"];
            }
          ?>
          </a>
        </td>
      <?php 
        $count1++; } 
      ?>
      </tr>
      <?php }?>
      </table>
    </div>
    
  </div>
  <?php endif ?>
</body>
</html>