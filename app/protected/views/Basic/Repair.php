<script type="text/javascript">
    function startRepair() {
        var url = "index.php?r=Basic/startRepair&serial_code=<?php echo @$search_code; ?>";
        window.location = url;
    }
    
    function browseSerial() {        
        $.ajax({
            url: 'index.php?r=Dialog/DialogSerial',
            success: function(data) {
                $("#modalContent").html(data);
            }
        });
    }
    function chooseSerial(serial) {
        $("#search_code").val(serial);
    }

    $(function() {
        <?php if (!empty($_POST)): ?>
        $("#cmdStartRepair").removeClass('disabled');
        <?php endif; ?>
    });
</script>

<div class="panel panel-primary" style="margin: 10px">
    <div class="panel-heading">ซ่อมแซมสินค้า</div>
    <div class="panel-body">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'htmlOptions' => array(
                'name' => 'form1',
                'class' => 'form-inline',
                'id' => 'form1'
            )
        ));
        ?>
        <div>
            <div class="form-search">
                <label>serial code</label>
                <?php echo CHtml::textField('search_code', @$search_code, array(
                		'class' => 'form-control',
                		'style' => 'width: 200px'
                )); ?>
                <a href="#" class="btn btn-primary" onclick="return browseSerial()" data-toggle="modal" data-target="#myModal">
                    <i class="glyphicon glyphicon-search"></i>
                    ...
                </a>
                <a href="#" class="btn btn-primary" onclick="document.form1.submit()">
                    <i class="glyphicon glyphicon-list-alt"></i>
                    แสดงรายการ
                </a>
                <a href="#" id="cmdStartRepair" class="btn btn-success disabled" onclick="startRepair()">
                    <i class="glyphicon glyphicon-cog"></i>
                    รับซ่อม
                </a>
            </div>
        </div>

        <?php if (!empty($product)): ?> 

            <!-- expire product -->
            <?php if (ProductSerial::getExpireStatus($productSerial['product_expire_date'])): ?>
                <div class="alert alert-danger">
                    <i class="glyphicon glyphicon-question-sign"></i>
                    <strong>สินค้านี้หมดประกันแล้ว</strong>
                </div>
            <?php else: ?>
                <div class="alert alert-success">
                    <i class="glyphicon glyphicon-ok-sign"></i>
                    <strong>สินค้ายังอยู่ในประกัน</strong>
                </div>
            <?php endif; ?>

            <div><strong>ข้อมูลทั่วไป</strong></div>
            <form>
                <div class="well well-small">
                    <div>
                        <?php echo $form->labelEx($product, 'product_code'); ?>
                        <?php
                        echo $form->textField($product, 'product_code', array(
                            'disabled' => 'disabled',
                            'class' => 'form-control',
                            'style' => 'width: 200px'
                        ));
                        ?>

                        <?php echo $form->labelEx($product, 'product_pack_barcode'); ?>
                        <?php
                        echo $form->textField($product, 'product_pack_barcode', array(
                            'disabled' => 'disabled',
                            'class' => 'form-control',
                            'style' => 'width: 200px'
                        ));
                        ?>
                    </div>
                    <div>
                        <?php echo $form->labelEx($product, 'product_name'); ?>
                        <?php
                        echo $form->textField($product, 'product_name', array(
                            'disabled' => 'disabled',
                            'class' => 'form-control',
                            'style' => 'width: 560px'
                        ));
                        ?>
                    </div>

                    <div>
                        <?php echo $form->labelEx($product, 'group_product_id'); ?>
                        <?php
                        if (!empty($product->group_product)) {
                            echo $form->textField($product, 'group_product_id', array(
                                'disabled' => 'disabled',
                                'class' => 'form-control',
                                'style' => 'width: 560px',
                                'value' => @$product->group_product->group_product_name
                            ));
                        } else {
                            echo $form->textField($product, 'group_product_id', array(
                                'disabled' => 'disabled',
                                'class' => 'form-control',
                                'style' => 'width: 560px',
                                'value' => 'ยังไม่ได้จัดกลุ่มให้สินค้านี้ / ข้อมูลหมวดสินค้านี้ถูกลบ'
                            ));
                        }
                        ?>
                    </div>
                    <div>
                        <?php echo $form->labelEx($product, 'product_detail'); ?>
                        <?php
                        echo $form->textField($product, 'product_detail', array(
                            'disabled' => 'disabled',
                            'class' => 'form-control',
                            'style' => 'width: 560px',
                        ));
                        ?>
                    </div>
                    <div>
                        <?php echo $form->labelEx($product, 'product_expire'); ?>
                        <?php
                        echo $form->textField($product, 'product_expire', array(
                            'disabled' => 'disabled',
                            'class' => 'form-control',
                            'style' => 'width: 200px',
                            'value' => $product->getProductExpire()
                        ));
                        ?>

                        <?php echo $form->labelEx($productSerial, 'product_start_date'); ?>
                        <?php
                        echo $form->textField($productSerial, 'product_start_date', array(
                            'disabled' => 'disabled',
                            'class' => 'form-control',
                            'style' => 'width: 200px',
                            'value' => Util::mysqlToThaiDate($productSerial->product_start_date)
                        ));
                        ?>

                        <?php echo $form->labelEx($productSerial, 'product_expire_date'); ?>
                        <?php
                        echo $form->textField($productSerial, 'product_expire_date', array(
                            'disabled' => 'disabled',
                            'class' => 'form-control',
                            'style' => 'width: 200px',
                            'value' => Util::mysqlToThaiDate($productSerial->product_expire_date)
                        ));
                        ?>
                    </div>
                    <div>
                        <?php echo $form->labelEx($product, 'product_return'); ?>
                        <?php
                        echo $form->textField($product, 'product_return', array(
                            'disabled' => 'disabled',
                            'class' => 'form-control',
                            'style' => 'width: 200px',
                            'value' => $product->getProductReturn()
                        ));
                        ?>
                        
                        <?php echo $form->labelEx($product, 'product_price'); ?>
                        <?php
                        echo $form->textField($product, 'product_price', array(
                            'disabled' => 'disabled',
                            'class' => 'form-control',
                            'style' => 'width: 200px',
                            'value' => number_format($product->product_price, 2)
                        ));
                        ?>

                        <?php echo $form->labelEx($product, 'product_price_send'); ?>
                        <?php
                        echo $form->textField($product, 'product_price_send', array(
                            'disabled' => 'disabled',
                            'class' => 'form-control',
                            'style' => 'width: 200px',
                            'value' => number_format($product->product_price_send)
                        ));
                        ?>
                    </div>
                    <div>
                        <?php echo $form->labelEx($product, 'product_expire_date'); ?>
                        <?php
                        echo $form->textField($product, 'product_expire_date', array(
                            'disabled' => 'disabled',
                            'class' => 'form-control',
                            'style' => 'width: 200px',
                        ));
                        ?>
                    </div>
                </div>
            </form>

            <div><strong>ประวัติการซ่อม</strong></div>
            <div class="well well-small">
                <?php
                if (!empty($repairs)) {
                    $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $repairs,
                        'id' => 'gridRepair',
                        'columns' => array(
                            'user.user_name',
                            'repair_get_name',
                            'branch.branch_name',
                            array(
                                'name' => 'repair_date',
                                'value' => 'Util::mysqlToThaiDate($data->repair_date)'
                            ),
                            'repair_problem',
                            array(
                                'name' => 'repair_complete_date',
                                'value' => 'Util::mysqlToThaiDate($data->repair_complete_date)'
                            ),
                            array(
                                'name' => 'repair_status',
                                'value' => '$data->getStatus()'
                            ),
                            array(
                                'class' => 'CButtonColumn',
                                'template' => '{btn_view} {btn_edit} {btn_delete}',
                                'buttons' => array(
                                    'btn_view' => array(
                                        'label' => '<i class="icon icon-white icon-folder-open"></i> เปิดดู',
                                        'url' => 'Yii::app()->createUrl("Basic/RepairView", array(
                                            "repair_id" => $data->repair_id,
                                            "serial_code" => $data->serial_no
                                        ))',
                                        'options' => array(
                                            'class' => 'btn btn-success',
                                            'title' => 'เปิดดู'
                                        )
                                    ),
                                    'btn_edit' => array(
                                        'label' => '<i class="icon icon-white icon-edit"></i> แก้ไข',
                                        'url' => 'Yii::app()->createUrl("Basic/StartRepair", array(
                                            "serial_code" => $data->serial_no,
                                            "repair_id" => $data->repair_id
                                        ))',
                                        'options' => array(
                                            'class' => 'btn btn-primary',
                                            'title' => 'แก้ไข'
                                        )
                                    ),
                                    'btn_delete' => array(
                                        'label' => '<i class="icon icon-white icon-trash"></i> ลบ',
                                        'options' => array(
                                            'class' => 'btn btn-danger',
                                            'title' => 'ลบ'
                                        )
                                    )
                                ),
                                'htmlOptions' => array(
                                    'style' => 'text-align: center',
                                    'width' => '240px'
                                )
                            )
                        )
                    ));
                }
                ?>
            </div>
        <?php endif; ?>

        <?php $this->endWidget(); ?>
    </div>
</div>

<!-- Modal -->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 850px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">เลือกสินค้า</h4>
      </div>
      <div class="modal-body" id="modalContent">

      </div>
      <div class="modal-footer">
        <button id="btnCloseModal" type="button" class="btn btn-danger" data-dismiss="modal">
          <i class="glyphicon glyphicon-remove"></i>
          Close
        </button>
      </div>
    </div>
  </div>
</div>