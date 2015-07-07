<script type="text/javascript">
    function showData() {
        document.form1.submit();
    }
    
    function browseProduct() {        
        $.ajax({
            url: "index.php?r=Dialog/DialogProduct",
            success: function(data) {
                $("#modalContent").html(data);
                $(".btnChooseProduct").click(function() {
                    var code = $(this).attr("title");
                    chooseProductForGet(code);
                });
            }
        })
        
        return false;
    }

    function chooseProductForGet(product_code) {
        $("#BillSaleDetail_bill_sale_detail_barcode").val(product_code);
    }
</script>

<div class="panel panel-info" style="margin: 10px">
    <div class="panel-heading">รับคืนสินค้า</div>
    <div class="panel-body">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'form1',
            'htmlOptions' => array(
                'name' => 'form1',
                'class' => 'form-inline'
            )
        ));
        ?>
        <div>
            <?php echo $form->labelEx($model, 'bill_id'); ?>
            <?php echo $form->textField($model, 'bill_id', array(
							'class' => 'form-control',
							'style' => 'width: 200px'
            )); ?>
        </div>
        <div>
            <div class="">
                <?php echo $form->labelEx($model, 'bill_sale_detail_barcode'); ?>
                <?php echo $form->textField($model, 'bill_sale_detail_barcode', array(
									'class' => 'form-control',
									'style' => 'width: 200px'
                )); ?>
								
                <a href="#" class="btn btn-info" data-toggle="modal" data-target="#myModal" onclick="return browseProduct()">
                    <i class="glyphicon glyphicon-search"></i>
                </a>
                <a href="#" class="btn btn-info" onclick="showData()">
                    <i class="glyphicon glyphicon-import"></i>
                    แสดงรายการ
                </a>
            </div>
        </div>
        <hr />

        <?php if (!empty($product)): ?>
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
										'style' => 'width: 558px'
                ));
                ?>
            </div>
            <div>
                <?php echo $form->labelEx($product, 'group_product_id'); ?>
                <?php
								$group_product = $product->getGroupProductByGroupProductId();
								$group_product_name = '';
								
								if (!empty($group_product)) {
									$group_product_name = $group_product->group_product_name;
								}
								
                echo $form->textField($product, 'group_product_id', array(
                    'disabled' => 'disabled',
                    'class' => 'form-control',
                    'value' => $group_product_name,
										'style' => 'width: 558px'
                ));
                ?>
            </div>
            <div>
                <?php echo $form->labelEx($product, 'product_detail'); ?>
                <?php
                echo $form->textField($product, 'product_detail', array(
                    'disabled' => 'disabled',
                    'class' => 'form-control',
										'style' => 'width: 558px'
                ));
                ?>
            </div>
            <div>
                <?php echo $form->labelEx($product, 'product_created_date'); ?>
                <?php
                echo $form->textField($product, 'product_created_date', array(
                    'disabled' => 'disabled',
                    'class' => 'form-control',
										'style' => 'width: 200px'
                ));
                ?>
            
                <?php echo $form->labelEx($product, 'product_quantity'); ?>
                <?php
                echo $form->textField($product, 'product_quantity', array(
                    'disabled' => 'disabled',
                    'class' => 'form-control',
                    'style' => 'background-color: #ffffcc; color: black; width: 80px'
                ));
                ?>
           
                <?php echo $form->labelEx($product, 'product_total_per_pack'); ?>
                <?php
                echo $form->textField($product, 'product_total_per_pack', array(
                    'disabled' => 'disabled',
                    'class' => 'form-control',
										'style' => 'width: 80px'
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
            
                <?php echo $form->labelEx($product, 'product_return'); ?>
                <?php
                echo $form->textField($product, 'product_return', array(
                    'disabled' => 'disabled',
                    'class' => 'form-control',
										'style' => 'width: 200px',
                    'value' => $product->getProductReturn()
                ));
                ?>
            </div>
            <div>
                <?php echo $form->labelEx($product, 'product_expire_date'); ?>
                <?php
                echo $form->textField($product, 'product_expire_date', array(
                    'disabled' => 'disabled',
                    'class' => 'form-control',
										'style' => 'width: 200px'
                ));
                ?>
            
                <?php echo $form->labelEx($product, 'product_sale_condition'); ?>
                <?php
                echo $form->textField($product, 'product_sale_condition', array(
                    'disabled' => 'disabled',
                    'class' => 'form-control',
										'style' => 'width: 200px'
                ));
                ?>
            </div>
            <div>
                <?php echo $form->labelEx($product, 'product_price'); ?>
                <?php
                echo $form->textField($product, 'product_price', array(
                    'disabled' => 'disabled',
                    'class' => 'form-control',
										'style' => 'width: 200px'
                ));
                ?>
            
                <?php echo $form->labelEx($product, 'product_price_send'); ?>
                <?php
                echo $form->textField($product, 'product_price_send', array(
                    'disabled' => 'disabled',
                    'class' => 'form-control',
										'style' => 'width: 200px'
                ));
                ?>
            </div>
            <div>
                <label></label>
                <a href="#" class="btn btn-success btn-large" 
									onclick="document.form1.submit()">
                    <i class="glyphicon glyphicon-check"></i>
                    บันทึกรับคืนสินค้า
                </a>
            </div>
            <?php echo CHtml::hiddenField('product_id', $product->product_id); ?>
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