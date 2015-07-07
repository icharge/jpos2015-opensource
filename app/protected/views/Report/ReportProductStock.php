<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<div class="panel panel-info" style="margin: 10px">
    <div class="panel-heading">รายงานสินค้า</div>
    <div class="panel-body">
        <?php
        $model = new Product();
        
        $dataProvider = new CActiveDataProvider($model, array(
            'pagination' => array('pageSize' => 50)
        ));
        
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
            'summaryText' => "แสดงข้อมูลตั้งแต่ {start} ถึง {end} จากข้อมูล {count}",
            'pager' => array('header' => ''),
            'columns' => array(
                array(
                    'name' => 'product_code',
                    'htmlOptions' => array(
                        'width' => '100',
                        'style' => 'text-align: center;',
                    ),
                ),
                array(
                    'name' => 'product_name',
                    'htmlOptions' => array(
                        'width' => '450',
                        'style' => 'text-align: left; padding-left: 30px;',
                    ),
                ),
                array(
                    'name' => 'product_quantity',
                    'htmlOptions' => array(
                        'width' => '100',
                        'style' => 'text-align: center;',
                    ),
                ),
                array(
                    'name' => 'product_total_per_pack',
                    'htmlOptions' => array(
                        'width' => '100',
                        'style' => 'text-align: center;',
                    ),
                ),
                array(
                    'name' => 'product_quantity_of_pack',
                    'htmlOptions' => array(
                        'width' => '140',
                        'style' => 'text-align: center;',
                    ),
                ),
                array(
                    'name' => 'product_price',
                    'htmlOptions' => array(
                        'width' => '80',
                        'style' => 'text-align: center; background-color: #ffffcc;',
                    ),
                ),
                array(
                    'name' => 'product_price_send',
                    'htmlOptions' => array(
                        'width' => '80',
                        'style' => 'text-align: center;',
                    ),
                ),
            ),
        ));
        ?>
    </div>
</div>