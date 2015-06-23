<script type="text/javascript">
    function browseUser() {
        var url = "<?php echo Yii::app()->createUrl("Dialog/DialogUser"); ?>";
        var options = "dialogWidth=950px; dialogHeight=600px";
        var w = window.showModalDialog(url, null, options);
        
        if (w != null) {
            $("input[name=user_id]").val(w.user_id);
            $("input[name=user_name]").val(w.user_name);
        }
    }
    function browseBranch() {
        var uri = "<?php echo Yii::app()->createUrl("Dialog/DialogBranch"); ?>";
        var options = "dialogWidth=950px; dialogHeight=600px";
        var w = window.showModalDialog(uri, null, options);
        $("#hidden_branch_id").val(w.branch_id);
        $("input[name=branch_name]").val(w.branch_name);
    }
    $(function() {
        $("input[name=repair_created_date]").datepicker({
            dateFormat: 'dd/mm/yy',
            changeYear: true,
            changeMonth: true
        });
        $("#Repair_repair_date").datepicker({
            dateFormat: 'dd/mm/yy',
            changeYear: true,
            changeMonth: true
        });
    });
</script>

<div class="panel panel-primary" style="margin: 10px">
    <div class="panel-heading">
        บันทึกการรับซ่อมสินค้าหมายเลข Serial: 
        <?php echo $productSerial["serial_no"]; ?>
    </div>

    <div class="panel-body">
        <!-- expire product -->
        <?php if (ProductSerial::getExpireStatus($productSerial['product_expire_date'])): ?>
            <div class="alert alert-danger">
                <i class="glyphicon glyphicon-remove"></i>
                <strong>สินค้านี้หมดประกันแล้ว</strong>
            </div>
        <?php else: ?>
            <div class="alert alert-success">
                <i class="glyphicon glyphicon-ok"></i>
                <strong>สินค้ายังอยู่ในประกัน</strong>
            </div>
        <?php endif; ?>

        <div>
            <i class="glyphicon glyphicon-list-alt"></i>
            <b>ข้อมูลสินค้า</b>
        </div>
        <form>
            <div class="well well-small">  
                <table>
                    <tr>
                        <td><label>รหัสสินค้า</label></td>
                        <td><input type="text" value="<?php echo $productSerial['product_code']; ?>" disabled class="disabled form-control" /></td>
                        <td><label>ชื่อสินค้า</label></td>
                        <td colspan="4"><input type="text" value="<?php echo $productSerial['product_name']; ?>" disabled class="disabled form-control" /></td>
                    </tr>
                    <tr>
                        <td><label>วันที่จำหน่าย</label></td>
                        <td><input type="text" value="<?php echo Util::mysqlToThaiDate($productSerial['bill_sale_created_date']); ?>" disabled class="disabled form-control" /></td>
                        <td><label>วันที่เริ่มประกัน</label></td>
                        <td><input type="text" value="<?php echo Util::mysqlToThaiDate($productSerial['product_start_date']); ?>" disabled class="disabled form-control" /></td>
                        <td align="right"><label>วันหมดประกัน</label></td>
                        <td><input type="text" value="<?php echo Util::mysqlToThaiDate($productSerial['product_expire_date']); ?>" disabled class="disabled form-control" /></td>
                    </tr>
                </table>
            </div>
        </form>
        
        <div>
            <i class="glyphicon glyphicon-cog"></i>
            <b>ข้อมูลการซ่อม</b>
        </div>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'action' => array('Basic/StartRepairSave'),
            'htmlOptions' => array(
                'name' => 'formRepair'
        )));
        ?>
        <table>
            <tr>
                <td><?php echo $form->labelEx($repair, 'user_id'); ?></td>
                <td>
                    <div class="input-append">
                        <input type="text" name="user_id" class="form-control" value="<?php echo @$repair->user_id; ?>" style="width: 100px" />
                        <input type="text" name="user_name" value="<?php echo @$repair->user->user_name; ?>" disabled class="disabled form-control" style="width: 200px" />
                        <a href="#" class="btn btn-primary" onclick="return browseUser()">
                            <i class="glyphicon glyphicon-search"></i>
                            ...
                        </a>
                    </div>
                </td>

                <td><?php echo $form->labelEx($repair, 'repair_created_date'); ?></td>
                <td><input type="text" name="repair_created_date" class="form-control" value="<?php echo Util::nowThai(); ?>" style="width: 100px" /></td>

                <td><?php echo $form->labelEx($repair, 'branch_id', array(
                    'style' => 'width: 50px'
                )); ?></td>
                <td>
                    <div class="input-append">
                        <input type="hidden" id="hidden_branch_id" name="hidden_branch_id" value="<?php echo @$repair->branch_id; ?>" />
                        <input type="text" name="branch_name" value="<?php echo @$repair->branch->branch_name; ?>" disabled class="disabled form-control" style="width: 200px" />
                        <a href="#" class="btn btn-primary" onclick="return browseBranch()">
                            <i class="glyphicon glyphicon-search"></i>
                            ...
                        </a>
                    </div>
                </td>
            </tr>
            <tr>
                <?php
                $repair_date = Util::nowThai();
                
                if ($repair->repair_date != '0000-00-00') {
                    $repair_date = Util::mysqlToThaiDate($repair->repair_date);
                }
                ?>
                <td><?php echo $form->labelEx($repair, 'repair_date'); ?></td>
                <td><?php echo $form->textField($repair, 'repair_date', array(
                    'class' => 'form-control',
                    'style' => 'width: 200px',
                    'value' => $repair_date));
                ?>
                </td>
                <td><?php echo $form->labelEx($repair, 'repair_problem'); ?></td>
                <td colspan="4"><?php echo $form->textField($repair, 'repair_problem', array('class' => 'form-control')); ?></td>
            </tr>
            <tr>
                <?php
                $repair_price = 0;
                
                if (!empty($repair->repair_price)) {
                    $repair_price = $repair->repair_price;
                }
                ?>
                <td><?php echo $form->labelEx($repair, 'repair_price'); ?></td>
                <td><?php echo $form->textField($repair, 'repair_price', array(
                		'value' => $repair_price, 
                		'class' => 'form-control',
                		'style' => 'width: 200px'
                	)); ?></td>
                <td><?php echo $form->labelEx($repair, 'repair_detail'); ?></td>
                <td colspan="3"><?php echo $form->textField($repair, 'repair_detail', array('class' => 'form-control')); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($repair, 'repair_original'); ?></td>
                <td colspan="5">
                    <?php echo $form->textField($repair, 'repair_original', array(
                    		'style' => 'width: 100%',
                    		'class' => 'form-control'
                    	)); ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($repair, 'repair_type'); ?></td>
                <td>
                    <span class="alert alert-danger" style="display: inline-block; padding-top: 8px; padding-bottom: 8px">
                        <?php echo CHtml::radioButton('Repair[repair_type]', true, array('value' => 'internal')); ?> ซ่อมเอง
                        <?php echo CHtml::radioButton('Repair[repair_type]', false, array('value' => 'center')); ?> ส่งศูนย์
                        <?php echo CHtml::radioButton('Repair[repair_type]', false, array('value' => 'external')); ?> ส่งซ่อมภายนอก
                    </span>
                </td>

                <td><?php echo $form->labelEx($repair, 'repair_status'); ?></td>
                <td colspan="4">
                    <span class="alert alert-danger" style="display: inline-block; padding-top: 8px; padding-bottom: 8px">
                        <?php echo CHtml::radioButton('Repair[repair_status]', false, array('value' => 'wait')); ?> รอการซ่อม
                        <?php echo CHtml::radioButton('Repair[repair_status]', true, array('value' => 'do')); ?> กำลังดำเนินการ
                        <?php echo CHtml::radioButton('Repair[repair_status]', false, array('value' => 'complete')); ?> ซ่อมเสร็จแล้ว
                    </span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <a class="btn btn-primary">
                        <i class="glyphicon glyphicon-ok"></i>
                        บันทึกรายการ
                    </a>
                    <a class="btn btn-success">
                        <i class="glyphicon glyphicon-print"></i>
                        พิมพ์ใบรับซ่อม
                    </a>
                </td>
            </tr>
        </table>
        
        <?php echo $form->hiddenField($repair, 'repair_id'); ?>
        <input type="hidden" name="Repair[serial_no]" value="<?php echo $productSerial["serial_no"]; ?>" />
        <?php $this->endWidget(); ?>
    </div>
</div>