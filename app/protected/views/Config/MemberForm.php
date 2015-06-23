<div class="panel panel-primary" style="margin: 10px">
    <div class="panel-heading">บันทึกข้อมูลสมาชิกร้าน</div>
    <div class="panel-body">
      
      <?php $form = $this->beginWidget('CActiveForm', array(
				'htmlOptions' => array(
					'name' => 'formMember'
				)
      )); 
			
			echo $form->errorSummary($model);
			?>
			
			<div>
				<?php echo $form->labelEx($model, 'member_code'); ?>
				<?php echo $form->textField($model, 'member_code', array(
				'class' => 'form-control',
				'style' => 'width: 200px'
				)); ?>
			</div>
			
			<div>
				<?php echo $form->labelEx($model, 'member_name'); ?>
				<?php echo $form->textField($model, 'member_name', array(
				'class' => 'form-control',
				'style' => 'width: 400px'
				)); ?>
			</div>
			
			<div>
				<?php echo $form->labelEx($model, 'member_tel'); ?>
				<?php echo $form->textField($model, 'member_tel', array(
				'class' => 'form-control',
				'style' => 'width: 200px'
				)); ?>
			</div>
			
			<div>
				<?php echo $form->labelEx($model, 'member_address'); ?>
				<?php echo $form->textField($model, 'member_address', array(
				'class' => 'form-control',
				'style' => 'width: 700px'
				)); ?>
			</div>
			
			<div>
				<?php echo $form->labelEx($model, 'branch_id'); ?>
				<?php echo $form->dropdownList($model, 'branch_id', Branch::getOptions(true), array(
				'class' => 'form-control',
				'style' => 'width: 300px'
				)); ?>
				
				<font color="red"> * ถ้าไม่เลือกสาขา จะยึดตามรหัสสาขาของพนักงานที่เข้าระบบ</font>
			</div>

			<div>
				<label>รายละเอียดเพิ่มเติม</label>
				<?php echo $form->textField($model, 'remark', array(
					'class' => 'form-control',
					'style' => 'width: 500px'
				)); ?>
			</div>
			
      <div>
				<label></label>
				<a href="#" class="btn btn-primary" onclick="formMember.submit()">
					<b class="glyphicon glyphicon-floppy-disk"></b>
					Save
				</a>
			</div>
			
			<?php echo $form->hiddenField($model, 'member_id'); ?>
			<?php $this->endWidget(); ?>
    </div>
</div>