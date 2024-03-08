<?php  
  require "functions.php";

  // 👇エラーを表示してくれる関数
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  
  date_default_timezone_set ('Asia/Tokyo');

  check_login();

  // 👇出勤を入力する処理
  if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['action']) && $_POST['action'] == 'shukkin') {
         
      $user_id = $_SESSION['info']['id'];
      $name = $_SESSION['info']['username'];
      $time = $_POST['time'];

      $query = "select * from kintai where user_id = '$user_id'";
      $result = mysqli_query($con, $query);
      // echo print_R($result);die;
      if(mysqli_num_rows($result) == 0) {
        // echo $result;die;
        $query = "insert into kintai (user_id, kintai, name, time) value ('$user_id', 1, '$name', '$time')";
        $result = mysqli_query($con, $query);
      }

      $query = "update kintai set kintai = 1, time = '$time' where user_id = '$user_id' limit 1";
      mysqli_query($con, $query);
      $query = "select * from kintai where user_id = '$user_id'";
      $result = mysqli_query($con, $query);
      // echo print_R($result);die;

      if(mysqli_num_rows($result) > 0) {
        $_SESSION['kintai']  = mysqli_fetch_assoc($result);
      }

      if (!$result) {
        die("エラー: " . mysqli_error($con));
      }

      header("Location: kintai.php");
      die;
    }
    // 👇退勤を入力する処理
    elseif($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['action']) && $_POST['action'] == 'taikin') {
    
    $_SESSION['kintai']['kintai'] = 0;
    $id = $_SESSION['kintai']['id'];
    $user_id = $_SESSION['kintai']['user_id'];
    $name = $_SESSION['kintai']['name'];
    $nyuukintime = $_SESSION['kintai']['time'];
    $time = $_POST['time'];
    // $time = "2024-03-06 11:13:20";

    $roudouhun;
    $kyuuryou;
    $Date;
    // 日付が変わっているかどうか
    if(substr($time, 8, 2) == substr($_SESSION['kintai']['time'], 8, 2)) {
      
      $Date = date("Y-m-d");
      // $InTime = $Date."01:00:00";
      $InTime = $Date.substr($_SESSION['kintai']['time'], 11, 8);
      $OutTime = $Date.substr($time, 11, 8);
      $roudouhun = floor((strtotime($OutTime) - strtotime($InTime))/60);
      $kyuuryou = floor($roudouhun * 1113/60);
    } else {
      $Date = date('Y-m-d', strtotime('-1 day'));
      $Date2 = date("Y-m-d");
      // $InTime = $Date1."21:00:00";
      $InTime = $Date.substr($_SESSION['kintai']['time'], 11, 8);
      $OutTime = $Date2.substr($time, 11, 8);
      $roudouhun = floor((strtotime($OutTime) - strtotime($InTime))/60);
      $kyuuryou = floor($roudouhun * 1113/60);
    }

    // 給料とその月の合計労働時間をkyuuroujikanテーブルに入れる処理
    $query = "insert into kyuuryoujikan (user_id, time, kyuuryou, name, date) value ('$user_id', '$roudouhun', '$kyuuryou', '$name', '$Date') ";
    $result = mysqli_query($con, $query);
    
    
    
    //👇退勤の最終的な処理 
    $query = "update kintai set user_id = '$user_id', kintai = 0, name = '$name', time = '$time' where id = '$id' && user_id = '$user_id' && time = '$nyuukintime' limit 1";
    $result = mysqli_query($con, $query);
    $query = "SELECT * FROM kintai WHERE id = '$id' && user_id = '$user_id' && time = '$nyuukintime' LIMIT 1";
    $result = mysqli_query($con, $query);
    // 👇kintaiのセッションを切る
    if(mysqli_num_rows($result) > 0) {
      $_SESSION['kintai']  = mysqli_fetch_assoc($result);
    }

    if (!$result) {
      die("エラー: " . mysqli_error($con));
    }

    header("Location: kintai.php");
    die;
    
  } else {
    $user_id = $_SESSION['info']['id'];
      $name = $_SESSION['info']['username'];

      $query = "select * from kintai where user_id = '$user_id'";
      $result = mysqli_query($con, $query);
      // echo print_R($result);die;
      if(mysqli_num_rows($result) != 0) {
        $query = "select * from kintai where user_id = '$user_id'";
        $result = mysqli_query($con, $query);
        // echo print_R($result);die;

        if(mysqli_num_rows($result) > 0) {
          $_SESSION['kintai']  = mysqli_fetch_assoc($result);
        }
      }
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
</head>
<body>
  
  <div style="display:flex;height: 100vh;">
  <?php require "header.php"; ?>
    <div style="padding: 30px 20px 0; margin: auto;border: 1px solid #dbdbdb;">
      
      <!-- 👇勤怠を記録する画面 -->
      <?php if(!empty($_GET['action']) && $_GET['action'] == 'kintaigamen'):?>
        <h2 style="text-align: center;margin-bottom: 30px;">
        <?php if(!empty($_SESSION['kintai']['kintai']) && $_SESSION['kintai']['kintai']){
            echo "退勤する";
          } else {
            echo "出勤する";
          }
        ?>
        </h2>
          <form method="post" enctype="multipart/form-data" style="margin: auto; pading: 10px;">

            <input value="<?= $today = date("Y-m-d H:i:s");?>" type="text" name="time" placeholder="勤怠時間" required><br>
            <input type="hidden" 
                   name="action" 
                   value=<?php if(!empty($_SESSION['kintai']['kintai']) && $_SESSION['kintai']['kintai']){
                      echo "taikin";
                    } else {
                      echo "shukkin";
                    }
                  ?>>

            <div class="kintai-bottom-button" style="margin: 30px auto; width: 250px;">
              <div>
                <button>
                  <?php if(!empty($_SESSION['kintai']['kintai']) && $_SESSION['kintai']['kintai']){
                      echo "退勤する";
                    } else {
                      echo "出勤する";
                    }
                  ?>
                </button>
              </div>

              <div>
                <a class="button" href="kintai.php" style="padding: 10px 15px;width: 100px;">
                  戻る
                </a>
              </div>
            </div>
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

              <a href="kintai.php">
                <button type="button">Cancel</button>
              </a>
            </form>
          </div>
      <?php else:?>
      
        <div style="height: 300px;display: flex;align-items: center;margin: auto; width: 600px;text-align: center;">
          <div style="border-radius: 50%;">
          <?php if(!empty($_SESSION['info']['image'])): ?>
            <img src="<?php echo $_SESSION['info']['image'] ?>" style="border-radius: 50%;height: 150px;object-fit: cover;">
          <?php else: ?>
            <img src="uploads/tokumei.jpeg" style="border-radius: 50%;height: 150px;object-fit: cover;">
          <?php endif; ?>
          </div>
          <div style="margin-left: 50px;">
            <p style="font-weight: 400; font-size: 25px;"><?php echo $_SESSION['info']['username'] ?></p>
            <a href="kintai.php?action=kintaigamen" style="padding: 10px 15px;">
              <div class="button">
                <p>勤怠</p>
              </div>
            </a>
          </div>

        </div>

        <?php endif;?>
      </div>
  </div>

  <?php require "footer.php"; ?>
  
</body>
</html>