<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $model,
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
            'name' => 'product_code',
            'type' => 'raw',
            'value' => '
              CHtml::link(
                $data->product_code,
                "#",
                array(
                  "onclick" => "chooseProduct(\'$data->product_code\')",
                                    "data-dismiss" => "modal",
                                    "class" => "btnChooseProduct",
                                    "title" => $data->product_code                
                )
              )
            ',
            'htmlOptions' => array(
                'align' => 'center',
                'width' => '100px'
            )
        ),
        'product_name',
        'product_price',
        'product_price_send'
    )
));
?>