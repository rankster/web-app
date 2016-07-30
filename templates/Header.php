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
      <div class="circliful-chart circliful" data-dimension="90" data-text="35%" data-width="5" data-fontsize="14" data-percent="35" data-fgcolor="#5fbeaa" data-bgcolor="#ebeff2" style="width: 90px;">
      </div>
      <h3 class="text-success counter">19</h3>
      <p class="text-muted">Total Wins Today</p>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3">
  <div class="widget-simple-chart text-right card-box" style="max-height: 123px;text-align: center;">


    <button class="btn btn-lg btn-danger waves-effect waves-light m-b-5" style="width:177px">
      <a href="#game-req" rel="modal:open">
        <i class="glyphicon glyphicon-new-window m-r-5" style="color: #fff;"></i>
        <span style="color: #fff;">New Match</span>
      </a>
    </button>

    <button class="btn btn-lg btn-success waves-effect waves-light m-b-5">
      <a href="#game-req" rel="modal:open">
        <i class="glyphicon glyphicon-refresh m-r-5" style="color: #fff;"></i>
        <span style="color: #fff;">Replay Previous</span>
      </a>
      </button>

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
