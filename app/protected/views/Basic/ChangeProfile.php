<div class="panel panel-primary" style="margin: 10px;">
  <div class="panel-heading">แก้ไขข้อมูลส่วนตัว</div>
  <div class="panel-body">

    <?php if (Yii::app()->user->hasFlash('message') != null): ?>
      <div class="alert alert-danger">
        <?php echo Yii::app()->user->getFlash('message'); ?>
      </div>
    <?php endif; ?>


    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'htmlOptions' => array('name' => 'formChangeProfile')
    ));
    echo $form->errorSummary($model);
    ?>
    
    <div>
      <?php echo $form->labelEx($model, 'user_name'); ?>
      <?php
      echo $form->textField($model, 'user_name', array(
          'class' => 'form-control',
          'style' => 'width: 300px'
      ));
      ?>
    </div>
    
    <div>
      <?php echo $form->labelEx($model, 'user_tel'); ?>
      <?php
      echo $form->textField($model, 'user_tel', array(
          'class' => 'form-control',
          'style' => 'width: 300px'
      ));
      ?>
    </div>
    
    <div>
      <?php echo $form->labelEx($model, 'user_username'); ?>
      <?php
      echo $form->textField($model, 'user_username', array(
          'class' => 'form-control',
          'style' => 'width: 300px'
      ));
      ?>
    </div>
    
    <div>
      <?php echo $form->labelEx($model, 'user_password'); ?>
      <?php
      echo $form->passwordField($model, 'user_password', array(
          'class' => 'form-control',
          'style' => 'width: 300px'
      ));
      ?>
    </div>
    
    <div>
      <label></label>
      <a href="#" class="btn btn-primary" onclick="formChangeProfile.submit()">
        Save
      </a>
    </div>
    <?php $this->endWidget(); ?>
  </div>
</div>

