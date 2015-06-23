<div class="panel panel-primary" style="margin: 10px">
  <div class="panel-heading">ตั้งค่า เงื่อนไขการขาย</div>
  <div class="panel-body">
    <?php if (Yii::app()->user->hasFlash('message')): ?>
    <div class="alert alert-success">
      <i class="glyphicon glyphicon-ok"></i>
      <?php echo Yii::app()->user->getFlash('message'); ?>
    </div>
    <?php endif; ?>

    <form method="post" class="form-inline">
      <input type="hidden" name="test" value="test" />
      <table class="table table-bordered table-striped">
        <tbody>
          <tr>
            <td width="300px">ให้พนักงานขาย แก้ไขราคาจำหน่ายได้ </td>
            <td><input type="checkbox" name="ConfigSoftware[sale_can_edit_price]" value="yes" 
              <?php 
              if ($configSoftware->sale_can_edit_price == 'yes') {
                echo 'checked';
              }
              ?> 
              />
            </td>
          </tr>
          <tr>
            <td width="300px">ให้พนักงานขาย คิดส่วนลดได้ </td>
            <td><input type="checkbox" name="ConfigSoftware[sale_can_add_sub_price]" value="yes"
              <?php 
              if ($configSoftware->sale_can_add_sub_price == 'yes') {
                echo 'checked';
              }
              ?> 
             /></td>
          </tr>
          <tr>
            <td width="300px">ขายสินค้าหมดสต็อคได้ </td>
            <td><input type="checkbox" name="ConfigSoftware[sale_out_of_stock]" value="yes"
              <?php 
              if ($configSoftware->sale_out_of_stock == 'yes') {
                echo 'checked';
              }
              ?> 
             /></td>
          </tr>
        </tbody>
      </table>
      
      <input type="submit" class="btn btn-primary" value="บันทึก" />
    </form>
  </div>  
</div>