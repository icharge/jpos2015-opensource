<script type="text/javascript">
  $.ajax({
    url: 'index.php?r=Help/UpdateSoftwareNow',
    success: function() {
      
    }
  });
</script>

<div class="panel panel-primary" style="margin: 100px">
  <div class="panel-heading">Login เข้าระบบ</div>
  <div class="panel-body">
    
    <?php if (Yii::app()->user->hasFlash('message')): ?>
    <div class="alert alert-danger">
      <?php echo Yii::app()->user->getFlash('message'); ?>
    </div>
    <?php endif; ?>
    
    <form method="post" name="formLogin">
    <div>
      <label>Username:</label>
      <input type="text" name="User[user_username]" class="form-control"
             style="width: 200px" />
    </div>
    <div>
      <label>Password</label>
      <input type="password" name="User[user_password]" class="form-control"
             style="width: 200px" />
    </div>
    <div>
      <label></label>
      <input type="submit" class="btn btn-primary" value="Login" />
    </div>
  </div>
</div>

