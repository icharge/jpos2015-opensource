<div class="panel panel-primary" style="margin: 10px">
    <div class="panel-heading">บันทึกรายการ ตัวแทนจำหน่าย</div>
    <div class="panel-body">
      <?php $form = $this->beginWidget('CActiveForm', array(
          'htmlOptions' => array(
              'name' => 'formFarmer'
          )
      )); 
      
      echo $form->errorSummary($model);
      ?>
      
      <div>
        <?php echo $form->labelEx($model, 'farmer_name'); ?>
        <?php echo $form->textField($model, 'farmer_name', array(
            'class' => 'form-control',
            'style' => 'width: 400px'
        )); ?>
      </div>
      
      <div>
        <?php echo $form->labelEx($model, 'farmer_tel'); ?>
        <?php echo $form->textField($model, 'farmer_tel', array(
            'class' => 'form-control',
            'style' => 'width: 200px'
        )); ?>
      </div>
      
      <div>
        <?php echo $form->labelEx($model, 'farmer_address'); ?>
        <?php echo $form->textField($model, 'farmer_address', array(
            'class' => 'form-control',
            'style' => 'width: 600px'
        )); ?>
      </div>
      
      <div>
        <label></label>
        <?php echo $form->hiddenField($model, 'farmer_id'); ?>
        <a href="#" onclick="formFarmer.submit()" class="btn btn-primary">
          <b class="glyphicon glyphicon-floppy-disk"></b>
          Save
        </a>
      </div>
    
      <?php $this->endWidget(); ?>
    </div>
</div>

