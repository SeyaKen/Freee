
<div class="side-bar" style="width: 200px;">
  
  <div style="margin: 20px 0;padding: 0 11px;">
    <img src="uploads/kintai.png" style="width: 80%;">
  </div>
  <?php if(empty($_SESSION['info'])):?>
  <?php elseif($_SESSION['info']['email'] == 'owner@mail.com'): ?>
    <div><a href="shift.php"><i class="fa-solid fa-table"></i> シフト</a></div>
    <div><a href="profile_list.php"><i class="fa-solid fa-user fa-lg"></i> プロフィール一覧</a></div>
    <div><a href="kintai.php"><i class="fa-solid fa-user-check fa-lg"></i> 勤怠</a></div>
    <div><a href="logout.php"><i class="fa-solid fa-right-from-bracket fa-lg"></i> ログアウト</a></div>
  <?php else:?>
    <div><a href="shift.php"><i class="fa-solid fa-table"></i> シフト</a></div>
    <div><a href="kintai.php"><i class="fa-solid fa-user-check fa-lg"></i> 勤怠</a></div>
    <div><a href="logout.php"><i class="fa-solid fa-right-from-bracket fa-lg"></i> ログアウト</a></div>
  <?php endif?>
</div>