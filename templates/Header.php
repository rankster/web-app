<?php if (isset($_SESSION['user_name'])): ?>
<div class="row">
  <div class="col-sm-6 col-lg-3">
    <div class="card-box widget-user">
      <div>
        <img src="http://<?php echo $_SESSION['user_picture']; ?>" class="img-responsive img-circle" alt="user">
        <div class="wid-u-info">
          <h4 class="m-t-0 m-b-5"><?php echo $_SESSION['user_name']; ?></h4>
          <p class="text-muted m-b-5 font-13"><?php echo $_SESSION['user_email']; ?></p>
          <small class="text-success"><b>Rookie</b></small>
        </div>
      </div>
    </div>
  </div>
</div>

<?php endif; ?>
