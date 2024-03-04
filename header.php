<div class="side-bar">
  <!-- <div><a href="index.php"><i class="fa-solid fa-user-check"></i></a></div>
  <div><a href="profile.php">Profile</a></div> -->
  <div style="margin-top: 30px;"><a href="kintai.php"><i class="fa-solid fa-user-check fa-lg"></i></a></div>

  <?php if(empty($_SESSION['info'])):?>
    <!-- <div><a href="login.php">Login</a></div> -->
    <!-- <div><a href="signup.php">Signup</a></div> -->
  <?php else:?>
    <div><a href="logout.php"><i class="fa-solid fa-right-from-bracket fa-lg"></i></a></div>
  <?php endif?>
</div>