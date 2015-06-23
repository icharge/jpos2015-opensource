<div class="panel panel-primary" style="margin: 10px;">
  <div class="panel-heading">ข้อมูลร้าน / บริษัท</div>
  <div class="panel-body">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'htmlOptions' => array(
            'name' => 'myForm',
            'enctype' => 'multipart/form-data'
        )
    ));

    echo $form->errorSummary($model);
    ?>

    <?php if (!empty($model->org_logo)): ?>
      <div>
        <label></label>
        <?php
        echo CHtml::image('upload/' . $model->org_logo, null, array(
            'width' => '200px'
        ));
        ?>
      </div>
    <?php endif; ?>

    <div>
      <?php echo $form->labelEx($model, 'org_name'); ?>
      <?php
      echo $form->textField($model, 'org_name', array(
          'class' => 'form-control',
          'style' => 'width: 500px'
      ));
      ?>
    </div>

    <div>
      <?php echo $form->labelEx($model, 'org_name_eng'); ?>
      <?php
      echo $form->textField($model, 'org_name_eng', array(
          'class' => 'form-control',
          'style' => 'width: 500px'
      ));
      ?>
    </div>

    <div>
      <?php echo $form->labelEx($model, 'org_logo'); ?>
      <?php
      echo $form->fileField($model, 'org_logo', array(
          'class' => 'form-control',
          'style' => 'width: 500px; display: inline-block;'
      ));
      ?>
    </div>

    <div>
      <?php
      $checked = 'checked';

      if ($model->org_logo_show_on_bill == 'no') {
        $checked = '';
      }
      ?>

      <label></label>
      <input type="checkbox" value="1" <?php echo $checked; ?> name="org_logo_show_on_bill" />
      แสดงโลโก้บนบิล		
    </div>

    <div>
      <?php
      $checked = 'checked';

      if (!empty($model->logo_show_on_header)) {
        if ($model->logo_show_on_header == 'no') {
          $checked = '';
        }
      }
      ?>
      <label></label>
      <input type="checkbox" value="yes" <?php echo $checked; ?> name="logo_show_on_header" />
      แสดงโลโก้บนส่วนหัว

      <?php
      $checked = 'checked';

      if (!empty($model->logo_show_on_header_bg)) {
        if ($model->logo_show_on_header_bg == 'no') {
          $checked = '';
        }
      }
      ?>
      <label style="width: 80px"></label>
      <input type="checkbox" value="yes" <?php echo $checked; ?> name="logo_show_on_header_bg" />
      แสดงสีพื้นหลัง (บนหัว)
    </div>

    <div>
      <?php echo $form->labelEx($model, 'org_address_1'); ?>
      <?php
      echo $form->textField($model, 'org_address_1', array(
          'class' => 'form-control',
          'style' => 'width: 500px'
      ));
      ?>
    </div>

    <div>
      <?php echo $form->labelEx($model, 'org_address_2'); ?>
      <?php
      echo $form->textField($model, 'org_address_2', array(
          'class' => 'form-control',
          'style' => 'width: 500px'
      ));
      ?>
    </div>

    <div>
      <?php echo $form->labelEx($model, 'org_address_3'); ?>
      <?php
      echo $form->textField($model, 'org_address_3', array(
          'class' => 'form-control',
          'style' => 'width: 500px'
      ));
      ?>
    </div>

    <div>
      <?php echo $form->labelEx($model, 'org_address_4'); ?>
      <?php
      echo $form->textField($model, 'org_address_4', array(
          'class' => 'form-control',
          'style' => 'width: 500px'
      ));
      ?>
    </div>

    <div>
      <?php echo $form->labelEx($model, 'org_tel'); ?>
      <?php
      echo $form->textField($model, 'org_tel', array(
          'class' => 'form-control',
          'style' => 'width: 300px'
      ));
      ?>
    </div>

    <div>
      <?php echo $form->labelEx($model, 'org_fax'); ?>
      <?php
      echo $form->textField($model, 'org_fax', array(
          'class' => 'form-control',
          'style' => 'width: 300px'
      ));
      ?>
    </div>

    <div>
      <?php echo $form->labelEx($model, 'org_tax_code'); ?>
      <?php
      echo $form->textField($model, 'org_tax_code', array(
          'class' => 'form-control',
          'style' => 'width: 300px'
      ));
      ?>
    </div>

    <div>
      <label></label>
      <a href="#" class="btn btn-primary" onclick="document.myForm.submit()">
        บันทึกรายการ
      </a>
    </div>

    <?php $this->endWidget(); ?>
  </div>

</div>

