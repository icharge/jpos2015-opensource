
      <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $dataProvider,
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
                    'value' => '$data->product->product_name'
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
                )
            )
        ));
        ?>

