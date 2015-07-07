<div class="panel panel-info" style="margin: 10px;">
	<div class="panel-heading">รายการในบิล / <?php echo $modelBillSale->bill_sale_id; ?></div>
  <div class="panel-body">
  		<?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $dataProvider,
            'itemsCssClass' => 'table table-bordered table-striped',
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
            'columns' => array(
                array(
                    'name' => 'bill_sale_detail_id',
                    'htmlOptions' => array(
                        'width' => '50px',
                        'align' => 'center'
                    )
                ),
                array(
                    'name' => 'bill_sale_detail_barcode',
                    'htmlOptions' => array(
                        'width' => '150px',
                        'align' => 'center'
                    )
                ),
                array(
                    'header' => 'รายการ',
                    'value' => '@$data->product->product_name'
                ),
                array(
                    'name' => 'bill_sale_detail_price',
                    'htmlOptions' => array(
                        'width' => '80px',
                        'align' => 'right'
                    )
                ),
                array(
                    'name' => 'bill_sale_detail_qty',
                    'htmlOptions' => array(
                        'width' => '50px',
                        'align' => 'right'
                    )
                ),
                array(
                    'header' => 'รวม',
                    'value' => 'number_format($data->bill_sale_detail_price * $data->bill_sale_detail_qty)',
                    'htmlOptions' => array(
                        'width' => '80px',
                        'align' => 'right'
                    )
                ),
                array(
                    'class' => 'CButtonColumn',
                    'template' => '{edit} {del}',
                    'buttons' => array(
                        'edit' => array(
                            'label' => '
															<span class="btn btn-success">
																<b class="glyphicon glyphicon-pencil"></b>
															</span>',
                            'url' => 'Yii::app()->createUrl("Basic/BillSaleDetailEdit", array(
															"bill_sale_detail_id" => $data->bill_sale_detail_id 
														))'
                        ),
                        'del' => array(
                            'label' => '
															<span class="btn btn-danger">
																<b class="glyphicon glyphicon-trash"></b>
															</span>
														',
                            'url' => 'Yii::app()->createUrl("Basic/BillSaleDetailDelete", array(
															"bill_sale_detail_id" => $data->bill_sale_detail_id
														))',
                            'options' => array(
                                'onclick' => 'return confirm("ยืนยันการลบ")'
                            )
                        )
                    ),
                    'htmlOptions' => array(
                        'width' => '110px',
                        'align' => 'center'
                    )
                )
            )
        ));
        ?>
    </div>
</div>

