<div class="panel panel-primary" style="margin: 10px">
  <div class="panel-heading">ตัวแทนจำหน่าย</div>
  <div class="panel-body">
    <a href="index.php?r=Config/FarmerForm" class="btn btn-primary">
      <b class="glyphicon glyphicon-plus"></b>
      เพิ่มรายการ
    </a>
    
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model,
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
            'farmer_name',
            'farmer_tel',
            'farmer_address',
            'farmer_created_date',
            array(
                'class' => 'CButtonColumn',
                'template' => '{edit} {del}',
                'buttons' => array(
                    'edit' => array(
                        'label' => '
                          <span class="btn btn-success">
                            <b class="glyphicon glyphicon-align-justify"></b>
                            แก้ไข
                          </span>
                        ',
                        'url' => 'Yii::app()->createUrl("Config/FarmerForm", array("id" => $data->farmer_id))'
                    ),
                    'del' => array(
                        'label' => '
                          <span class="btn btn-danger">
                            <b class="glyphicon glyphicon-minus-sign"></b>
                            ลบ
                          </span>
                        ',
                        'url' => 'Yii::app()->createUrl("Config/FarmerDelete", array("id" => $data->farmer_id))',
                        'options' => array(
                            'onclick' => 'return confirm("ยืนยันการลบ")'
                        )
                    )
                ),
                'htmlOptions' => array(
                    'width' => '170px',
                    'align' => 'center'
                )
            )
        )
    ));
    ?>
  </div>
</div>

