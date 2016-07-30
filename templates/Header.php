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

<div class="col-sm-6 col-lg-3">
  <div class="widget-simple-chart text-right card-box">
    <div class="circliful-chart circliful" data-dimension="90" data-text="35%" data-width="5" data-fontsize="14" data-percent="35" data-fgcolor="#5fbeaa" data-bgcolor="#ebeff2" style="width: 90px;">
		<span class="circle-text" style="line-height: 90px; font-size: 14px;">35%</span><canvas width="90" height="90"></canvas>
	</div>
    <h3 class="text-success counter">12 Games</h3>
    <p class="text-muted">Total Wins Today</p>
  </div>
</div>

</div>

<?php endif; ?>
