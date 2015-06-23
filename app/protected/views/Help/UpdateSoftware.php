<div class="panel panel-primary" style="margin: 10px">
  <div class="panel-heading">ยืนยันการ Update Software</div>
  <div class="panel-body">
    <?php if (Yii::app()->user->hasFlash("message") != null): ?>
    <div class="alert alert-success">
      <i class="glyphicon glyphicon-ok"></i>
      <?php echo Yii::app()->user->getFlash("message"); ?>
    </div>
    <?php endif; ?>

    <a href="index.php?r=Help/UpdateSoftwareNow" class="btn btn-primary btn-lg">
      <i class="glyphicon glyphicon-ok"></i>
      ยันยันการ Update Software
    </a>
    <br />
    <br />

  </div>
</div>