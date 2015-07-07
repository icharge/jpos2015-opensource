<script type="text/javascript">
    $(function() {
        $("#BillImportDetail_product_id").keyup(function(e) {
            if (e.keyCode == 13) {
                showProductName();
            }
        });
        $("#BillImportDetail_product_id").blur(function() {
            showProductNameOfStock();
        });
    });
    
    function showProductNameOfStock() {
        var product_code = $("#product_code").val();
        
        $.ajax({
           url: 'index.php?r=Ajax/getProductInfo',
           dataType: 'json',
           data: {
               product_code: product_code
           },
           success: function(data) {
               $("#lblProductName").val(data.product_name);
               $("#BillImportDetail_import_bill_detail_price").focus();
               $("#qty_sub_stock").val(data.product_total_per_pack);
           }
        });

        return false;
    }

    function browseProduct() {
        $.ajax({
            url: 'index.php?r=Dialog/DialogProduct&find_on_page_quotation=true',
            success: function(data) {
                $("#modalContent").html(data);
                $(".btnChooseProduct").removeAttr("onclick");
                $(".btnChooseProduct").click(function() {
                    var code = $(this).attr('title');
                    
                    $("#BillImportDetail_product_id").val(code);
                    showProductNameOfStock();
                });
            }
        });

        return false;
    }

    function showProductInfo(code) {
        showProductNameOfStock();
    }
</script>

<div class="panel panel-info" style="margin: 10px">
    <div class="panel-heading">
			รับเข้าสินค้าในบิล : 
			<?php echo $modelBillImport->bill_import_code; ?>
		</div>
    <div class="panel-body">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'focus' => array($model, 'product_id'),
            'htmlOptions' => array(
                'name' => 'form1',
                'class' => 'form-inline'
                )));
        echo $form->errorSummary($model);
        ?>
				
        <div>
            <?php echo $form->labelEx($model, 'product_id'); ?>
            <input id="product_code" name="BillImportDetail[product_id]" class="form-control" style="width: 200px" />
            <a href="#" class="btn btn-info" onclick="return showProductName()">
                <i class="glyphicon glyphicon-ok"></i>
                แสดงข้อมูล
            </a>
            <a href="#" class="btn btn-info" onclick="return browseProduct()" data-toggle="modal" data-target="#myModal">
                <i class="glyphicon glyphicon-search"></i>
                ค้นหา
            </a>
        </div>
				
        <div>
            <label></label>
            <?php echo $form->textField($model, 'product_id', array(
                'disabled' => 'disabled',
                'id' => 'lblProductName',
                'class' => 'form-control',
				'style' => 'width: 550px',
                'value' => @$model->product->product_name == "" ? "" 
													: $model->product->product_name
            )); ?>
        </div>
				
        <div>
            <?php echo $form->labelEx($model, 'import_bill_detail_product_qty'); ?>
            <?php
            echo $form->textField($model, 'import_bill_detail_product_qty', array(
                'class' => 'form-control',
								'style' => 'width: 100px',
                'value' => @$model->import_bill_detail_product_qty == "" ? 1 
													: $model->import_bill_detail_product_qty
            ));
            echo CHtml::hiddenField('qty_before', @$model->import_bill_detail_product_qty);
            ?>
        </div>
				
        <div>
            <label>ราคาซื้อ</label>
            <?php echo $form->textField($model, 'import_bill_detail_price', array(
							'class' => 'form-control',
							'style' => 'width: 100px'
						)); ?>
        </div>
				
        <div class="buttons">
            <?php echo $form->hiddenField($model, 'bill_import_detail_id'); ?>
            <?php echo $form->hiddenField($model, 'bill_import_code'); ?>
						
						<label></label>
            <a href="#" onclick="document.form1.submit()" class="btn btn-info">
							<b class="glyphicon glyphicon-floppy-disk"></b>
							บันทึกรายการ
						</a>
        </div>
        <input type="hidden" name="qty_sub_stock" id="qty_sub_stock" />
				
        <?php $this->endWidget(); ?>

        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $dataProvider,
            "pagerCssClass" => "pagination",
            "pager" => array(
              "selectedPageCssClass" => "active",
              "firstPageCssClass" => "previous",
              "lastPageCssClass" => "next",
              "hiddenPageCssClass" => "disabled",
              "header" => "",
              "htmlOptions" => array(
                "class" => "pagination"
              )
            ),
            'itemsCssClass' => 'table table-bordered table-striped',
            'columns' => array(
                'bill_import_detail_id',
                'product.product_code',
                'product.product_name',
                array(
                    'name' => 'import_bill_detail_product_qty',
                    'value' => 'number_format($data->import_bill_detail_product_qty)',
                    'htmlOptions' => array(
                        'align' => 'right'
                    )
                ),
                array(
                    'name' => 'import_bill_detail_price',
                    'value' => 'number_format($data->import_bill_detail_price, 2)',
                    'htmlOptions' => array(
                        'align' => 'right'
                    )
                ),
                array(
                    'name' => 'product.product_total_per_pack',
                    'value' => 'number_format($data->import_bill_detail_qty_per_pack)',
                    'htmlOptions' => array(
                        'align' => 'right'
                    )
                ),
                array(
                    'name' => 'import_bill_detail_qty',
                    'value' => 'number_format($data->import_bill_detail_qty)',
                    'htmlOptions' => array(
                        'align' => 'right'
                    )
                ),
                array(
                    'value' => 'number_format($data->import_bill_detail_product_qty * $data->import_bill_detail_price, 2)',
                    'header' => 'จำนวนเงิน',
                    'htmlOptions' => array(
                        'align' => 'right'
                    )
                ),
                array(
                    'name' => 'product.product_quantity',
                    'value' => 'number_format($data->product->product_quantity)',
                    'htmlOptions' => array(
                        'align' => 'right'
                    )
                ),
                array(
                    'class' => 'CButtonColumn',
                    'template' => '{del}',
                    'buttons' => array(
                        /*'edit' => array(
                            'label' => '
							 <span class="btn btn-success">
							     <b class="glyphicon glyphicon-pencil"></b>
							 </span>',
                            'url' => 'Yii::app()->createUrl("Basic/BillImportDetail", array(
							     "id" => $data->bill_import_detail_id, 
								 "bill_import_code" => $data->bill_import_code
							))',
                            'options' => array(
                                'title' => ''
                            )
                        ),*/
                        'del' => array(
                            'label' => '
															<span class="btn btn-danger">
																<b class="glyphicon glyphicon-trash"></b>
															</span>
														',
                            'url' => 'Yii::app()->createUrl("Basic/BillImportDetailDelete", array(
															"id" => $data->bill_import_detail_id, 
															"bill_import_code" => $data->bill_import_code
														))',
                            'options' => array(
                                'onclick' => 'return confirm("ยืนยันการลบ")',
                                'title' => ''
                            )
                        )
                    ),
                    'htmlOptions' => array(
                        'width' => '50px',
                        'align' => 'center'
                    )
                )
            )
        ));
        ?>

        <?php
        $data = $dataProvider->getData();
        
        foreach ($data as $billImportDetail) {
            $sumQty += $billImportDetail->import_bill_detail_product_qty;
            $sumPrice += ($billImportDetail->import_bill_detail_product_qty * $billImportDetail->import_bill_detail_price);
        }
        ?>
        <form class="form-inline">
            <div>
                <label style="width: 100px">รับเข้าทั้งหมด: </label>
                <input type="text" value="<?php echo @number_format($sumQty); ?>" disabled="disabled" class="form-control" style="width: 100px; text-align: right" />

                <label>จำนวนเงินรวม: </label>
                <input type="text" value="<?php echo @number_format($sumPrice, 2); ?>" disabled="disabled" class="form-control" style="width: 100px; text-align: right" />
            </div>
        </form>
    </div>
</div>

<!-- Modal -->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 850px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">เลือกรายการสินค้า</h4>
      </div>
      <div class="modal-body" id="modalContent">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">
          <i class="glyphicon glyphicon-remove"></i>
          Close
        </button>
      </div>
    </div>
  </div>
</div>

