<div class="panel panel-info" style="margin: 10px">
  <div class="panel-heading">ตั้งค่าบิล และสินค้าขั้นต่ำ</div>
  <div class="panel-body">
    <?php if (Yii::app()->user->hasFlash('message')): ?>
    <div class="alert alert-success">
      <?php echo Yii::app()->user->getFlash('message'); ?>
    </div>
    <?php endif; ?>

    <form class="form-inline" method="post">
      <div>
        <label>สต้อกขั้นต่ำ</label>
        <input type="text" name="alert_min_stock" value="<?php echo @$configSoftware->alert_min_stock; ?>" class="form-control" style="width: 100px" />
      </div>
      <div>
        <label>หัวบิล สลิป</label>
        <input type="text" name="bill_slip_title" value="<?php echo @$configSoftware->bill_slip_title; ?>" class="form-control" style="width: 300px" />

        <label style="width: 200px">หมายเหตุท้ายบิล สลิป</label>
        <input type="text" name="bill_slip_footer" value="<?php echo @$configSoftware->bill_slip_footer; ?>" class="form-control" style="width: 400px" />
      </div>
      <div>
        <label>หัวบิล ใบส่งสินค้า</label>
        <input type="text" name="bill_send_title" value="<?php echo @$configSoftware->bill_send_title; ?>" class="form-control" style="width: 300px" />

        <label style="width: 200px">หมายเหตุท้ายบิล ใบส่งสินค้า</label>
        <input type="text" name="bill_send_footer" value="<?php echo @$configSoftware->bill_send_footer; ?>" class="form-control" style="width: 400px" />
      </div>
      <div>
        <label>หัวบิล ใบกำกับภาษี</label>
        <input type="text" name="bill_vat_title" value="<?php echo @$configSoftware->bill_vat_title; ?>" class="form-control" style="width: 300px" />
      
        <label style="width: 200px">หมายเหตุท้ายบิล ใบกำกับภาษี</label>
        <input type="text" name="bill_vat_footer" value="<?php echo @$configSoftware->bill_vat_footer; ?>" class="form-control" style="width: 400px" />
      </div>
      <div>
        <label>หัวบิล ใบเสร็จรับเงิน</label>
        <input type="text" name="bill_sale_title" value="<?php echo @$configSoftware->bill_sale_title; ?>" class="form-control" style="width: 300px" />

        <label style="width: 200px">หมายเหตุท้ายบิล ใบเสร็จรับเงิน</label>
        <input type="text" name="bill_sale_footer" value="<?php echo @$configSoftware->bill_sale_footer; ?>" class="form-control" style="width: 400px" />
      </div>
      <div>
        <label>หัวบิล ใบวางบิล</label>
        <input type="text" name="bill_drop_title" value="<?php echo @$configSoftware->bill_drop_title; ?>" class="form-control" style="width: 300px" />
      
        <label style="width: 200px">หมายเหตุท้ายบิล ใบวางบิล</label>
        <input type="text" name="bill_drop_footer" value="<?php echo @$configSoftware->bill_drop_footer; ?>" class="form-control" style="width: 400px" />
      </div>
      <div>
        <label>จำนวนรายการต่อหน้า</label>
        <input type="text" name="items_per_page" value="<?php echo @$configSoftware->items_per_page; ?>" class="form-control" style="width: 100px" />
      </div>
      <div>
        <label></label>
        <input type="submit" class="btn btn-info" value="บันทึก" />
      </div>
    </form>
  </div>
</div>