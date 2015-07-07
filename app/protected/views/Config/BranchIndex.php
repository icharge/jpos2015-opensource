<div class="panel panel-info" style="margin: 10px">
  <div class="panel-heading">
    <b class="glyphicon glyphicon-home"></b> คลังสินค้า/สาขา
  </div>
  <div class="panel-body">
    <a href="index.php?r=Config/BranchForm" class="btn btn-info">
      <b class="glyphicon glyphicon-plus"></b>
      เพิ่มรายการ
    </a>
    
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model->search(),
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
        'summaryText' => 'แสดงผล {start} ถึง {end} จากทั้งหมด {count} รายการ',
        'columns' => array(
            'branch_id',
            'branch_name',
            'branch_tel',
            'branch_email',
            'branch_address',
            'branch_created_date',
            array(
                'class' => 'CButtonColumn',
                'template' => '{edit} {del}',
                'buttons' => array(
                    'edit' => array(
                        'label' => '
                          <span class="btn btn-info">
                            <b class="glyphicon glyphicon-pencil"></b> 
                          </span>',
                        'url' => 'Yii::app()->createUrl("Config/BranchForm", array(
                          "id" => $data->branch_id
                        ))'
                    ),
                    'del' => array(
                        'label' => '
                          <span class="btn btn-danger">
                            <b class="glyphicon glyphicon-remove"></b> 
                          </span>',
                        'url' => 'Yii::app()->createUrl("Config/BranchDelete", array(
                          "id" => $data->branch_id
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

