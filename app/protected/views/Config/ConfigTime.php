<div class="panel panel-info" style="margin: 10px">
  <div class="panel-heading">ตั้งค่าเวลา</div>
  <div class="panel-body">
    <div class="alert alert-danger">
      <i class="glyphicon glyphicon-remove"></i> 
      ควรตั้งเฉพาะกรณีที่ เวลาในการออกบิล ไม่ตรงกับเวลาเครื่องเท่านั้น
    </div>
    
    <form class="form-inline" name="formConfigTime" method="post" action="index.php?r=Config/ConfigTime">
      <div>
        <label>ตั้งค่า ชั่วโมง</label>
        +
        <input type="text" name="hour" value="<?php echo $configSoftware->count_hour; ?>" class="form-control" style="width: 50px; text-align: right" placeholder="0" />
        <a href="javascript:void(0)" onclick="document.formConfigTime.submit()" class="btn btn-info">
          บันทึก
        </a>
      </div>
    </form>
  </div>
</div>