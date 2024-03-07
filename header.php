<div class="side-bar" style="width: 200px;">
  <div style="margin: 20px 0;">
    <img src="uploads/kintai.png" style="width: 52%;">
  </div>
  <?php if(empty($_SESSION['info'])):?>
    <div><a href="login.php"><i class="fa-solid fa-right-to-bracket"></i> ログイン</a></div>
  <?php elseif($_SESSION['info']['email'] == 'owner@mail.com'): ?>
    <div><a href="index.php"><i class="fa-solid fa-house fa-lg"></i> ホーム</a></div>
    <div><a href="profile_list.php"><i class="fa-solid fa-user fa-lg"></i> プロフィール一覧</a></div>
    <div><a href="kintai.php"><i class="fa-solid fa-user-check fa-lg"></i> 勤怠</a></div>
    <div><a href="logout.php"><i class="fa-solid fa-right-from-bracket fa-lg"></i> ログアウト</a></div>
  <?php else:?>
    <div style="margin-top: 30px;"><a href="kintai.php"><i class="fa-solid fa-user-check fa-lg"></i> 勤怠</a></div>
    <div><a href="logout.php"><i class="fa-solid fa-right-from-bracket fa-lg"></i> ログアウト</a></div>
  <?php endif?>
</div>