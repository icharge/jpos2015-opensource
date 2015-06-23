<?php $this->widget('zii.widgets.grid.CGridView', array(
  'dataProvider' => $repairs,
  'columns' => array(
    array(
      'header' => 'เลือกรายการ',
      'type' => 'raw',
      'value' => 'CHtml::link("<i class=\"glyphicon glyphicon-ok\"></i> เลือก", "#", array(
        "class" => "btn btn-primary cmdChooseRepair",
        "repair_id" => $data->repair_id
      ))',
      'htmlOptions' => array(
        'width' => '100px'
      )
    ),
    array(
      'name' => 'product_code',
      'header' => 'รหัสสินค้า'
    ),
    array(
      'name' => 'repair_product_name',
      'header' => 'สินค้า'
    ),
    array(
      'name' => 'repair_name',
      'header' => 'ลูกค้า'
    ),
    array(
      'name' => 'repair_tel',
      'header' => 'เบอร์โทร'
    )
  )
)); ?>