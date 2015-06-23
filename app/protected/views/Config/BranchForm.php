<div class="panel panel-primary" style="margin: 10px">
    <div class="panel-heading">
      <b class="glyphicon glyphicon-home"></b> 
      บันทึกข้อมูล คลังสินค้า/สาขา
    </div>
    <div class="panel-body">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'htmlOptions' => array('name' => 'formBranch')
        ));
        
        echo $form->errorSummary($model);
        ?>
      
      <div>
        <?php echo $form->labelEx($model, 'branch_name'); ?>
        <?php echo $form->textField($model, 'branch_name', array(
            'class' => 'form-control',
            'style' => 'width: 400px'
        )); ?>
      </div>
      
      <div>
        <?php echo $form->labelEx($model, 'branch_tel'); ?>
        <?php echo $form->textField($model, 'branch_tel', array(
            'class' => 'form-control',
            'style' => 'width: 400px'
        )); ?>
      </div>
      
      <div>
        <?php echo $form->labelEx($model, 'branch_email'); ?>
        <?php echo $form->textField($model, 'branch_email', array(
            'class' => 'form-control',
            'style' => 'width: 400px'
        )); ?>
      </div>
      
      <div>
        <?php echo $form->labelEx($model, 'branch_address'); ?>
        <?php echo $form->textField($model, 'branch_address', array(
            'class' => 'form-control',
            'style' => 'width: 400px'
        )); ?>
      </div>
      
      <div>
        <label></label>
        <?php echo $form->hiddenField($model, 'branch_id'); ?>
        <a href="#" onclick="formBranch.submit()" class="btn btn-primary">
          <b class="glyphicon glyphicon-floppy-disk"></b>
          Save
        </a>
      </div>
      
      <?php $this->endWidget(); ?>
    </div>
</div>

