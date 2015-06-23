<div class="panel panel-primary" style="margin: 10px">
  <div class="panel-heading">
    <b class="glyphicon glyphicon-list-alt"></b>
    บันทึกรายการ ประเภทสินค้า
  </div>
  <div class="panel-body">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'htmlOptions' => array(
            'name' => 'formGroupProduct'
        )
    ));
    
    echo $form->errorSummary($model);
    ?>

    <div>
      <?php echo $form->labelEx($model, 'group_product_code'); ?>
      <?php
      echo $form->textField($model, 'group_product_code', array(
          'class' => 'form-control',
          'style' => 'width: 100px'
      ));
      ?>
    </div>
    
    <div>
      <?php echo $form->labelEx($model, 'group_product_name'); ?>
      <?php
      echo $form->textField($model, 'group_product_name', array(
          'class' => 'form-control',
          'style' => 'width: 300px'
      ));
      ?>
    </div>
    
    <div>
      <?php echo $form->labelEx($model, 'group_product_detail'); ?>
      <?php
      echo $form->textField($model, 'group_product_detail', array(
          'class' => 'form-control',
          'style' => 'width: 500px'
      ));
      ?>
    </div>
    
    <div>
      <label></label>
      <?php echo $form->hiddenField($model, 'group_product_id'); ?>
      <a href="#" onclick="formGroupProduct.submit()" class="btn btn-primary">
        <b class="glyphicon glyphicon-floppy-disk"></b>
        Save
      </a>
    </div>
    
    <?php $this->endWidget(); ?>
  </div>
</div>


