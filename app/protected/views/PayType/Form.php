<div class="panel panel-primary" style="margin: 10px">
  <div class="panel-heading">เพิ่มรายการ : ประเภทรายจ่าย</div>
  <div class="panel-body">
    <?php 
    $f = $this->beginWidget('CActiveForm', array(
      'action' => 'index.php?r=PayType/Form'
    ));
    echo $f->errorSummary($payType); 
    ?>
    <div>
      <label>ชื่อประเภท *</label>
      <?php echo $f->textField($payType, 'name', array(
        'class' => 'form-control',
        'style' => 'width: 250px'
      )); ?>
    </div>
    <div>
      <label>หมายเหตุ</label>
      <?php echo $f->textField($payType, 'remark', array(
        'class' => 'form-control',
        'style' => 'width: 400px'
      )); ?>
    </div>
    <div>
      <label></label>
      <input type="submit" class="btn btn-primary" value="บันทึก">
    </div>
    <?php echo $f->hiddenField($payType, 'id'); ?>
    <?php $this->endWidget(); ?>
  </div>
</div>