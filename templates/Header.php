<?php if (isset($_SESSION['user_name'])) { ?>
<div class="row">
  <div class="col-sm-6 col-lg-3" style="max-height:123px;">
    <div class="card-box widget-user" style="min-height:123px;">
      <div>
        <img src="http://<?php echo $_SESSION['user_picture']; ?>" class="img-responsive img-circle" alt="user">
        <div class="wid-u-info">
          <h4 class="m-t-0 m-b-5"><?php echo $_SESSION['user_name']; ?></h4>
          <p class="text-muted m-b-5 font-13"><?php echo $_SESSION['user_email']; ?></p>
          <small class="text-success"><b>Rookie</b></small>
          <a href="/logout" class="btn btn-default btn-xs" style="float:right;margin-top:15px">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3">
  <div class="widget-simple-chart text-right card-box">
    <p><a href="#game-req" rel="modal:open">Game Submit result</a></p>
    </div>
  </div>
</div>

<?php } else { ?>
<div class="row">
  <div class="col-sm-6 col-lg-3">
    <div class="card-box">
      <div>
        <a href="/login" class="btn btn-block btn-social btn-facebook">
          <span class="fa fa-facebook"></span> Sign in with Facebook
        </a>
      </div>
    </div>
  </div>
</div>
<?php } ?>
