<div class="panel panel-primary" style="margin: 10px">
    <div class="panel-heading">บันทึกข้อมูล ผู้ใช้งานระบบ</div>
    <div class="panel-body">
			<?php $form = $this->beginWidget('CActiveForm', array(
				'htmlOptions' => array(
					'name' => 'formUser'
				)
			));
			
			echo $form->errorSummary($model);
			?>
			
			<div>
				<?php echo $form->labelEx($model, 'user_name'); ?>
				<?php echo $form->textField($model, 'user_name', array(
				'class' => 'form-control',
				'style' => 'width: 300px'
				)); ?>
			</div>
			
			<div>
				<?php echo $form->labelEx($model, 'user_tel'); ?>
				<?php echo $form->textField($model, 'user_tel', array(
				'class' => 'form-control',
				'style' => 'width: 200px'
				)); ?>
			</div>
			
			<div>
				<?php echo $form->labelEx($model, 'user_level'); ?>
				<?php 
				$items = array(
					'cacheer' => 'Cacheer', 
					'admin' => 'Admin'
				); 
				echo $form->dropdownList($model, 'user_level', $items, array(
					'class' => 'form-control',
					'style' => 'width: 150px'
				)); 
				?>
			</div>
			
			<div>
				<?php echo $form->labelEx($model, 'user_username'); ?>
				<?php echo $form->textField($model, 'user_username', array(
				'class' => 'form-control',
				'style' => 'width: 200px'
				)); ?>
			</div>
			
			<div>
				<?php echo $form->labelEx($model, 'user_password'); ?>
				<?php echo $form->textField($model, 'user_password', array(
				'class' => 'form-control',
				'style' => 'width: 200px'
				)); ?>
			</div>
			
			<div>
				<?php echo $form->labelEx($model, 'branch_id'); ?>
				<?php echo $form->dropdownList($model, 'branch_id', Branch::getOptions(), array(
				'class' => 'form-control',
				'style' => 'width: 300px'
				))?>
			</div>
			
			<div>
				<label></label>
				<a href="#" onclick="formUser.submit()" class="btn btn-primary">
					<b class="glyphicon glyphicon-floppy-disk"></b>
					Save
				</a>
			</div>
			
			<?php echo $form->hiddenField($model, 'user_id'); ?>
			<?php $this->endWidget(); ?>
    </div>
</div>