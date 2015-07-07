<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<div class="panel panel-info" style="margin: 10px">
    <div class="panel-heading">รายงานเจ้าหนี้</div>

    <div class="panel-body">
        <?php
        $model = new BillImport();
        
        $criteria = new CDbCriteria();        
        $criteria->select = 't.bill_import_code, t.farmer_id, farmer_name, farmer_tel, farmer_address';
        $criteria->join = 'INNER JOIN tb_farmer AS b ON b.farmer_id = t.farmer_id';
        $criteria->condition = 'bill_import_pay = "credit"';
        $criteria->condition = 'bill_import_pay_status = "wait"';
        $criteria->group = 'b.farmer_id';
        $criteria->order = 'b.farmer_id ASC';
        
        $dataProvider = new CActiveDataProvider($model, array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => 25)
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
                    'name' => 'farmer.farmer_name',
                    'htmlOptions' => array(
                        'width' => '280'
                    ),
                ),
                array(
                    'name' => 'farmer.farmer_tel',
                    'htmlOptions' => array(
                        'width' => '150',
                        'style' => 'text-align: center;',
                    ),
                ),
                array(
                    'name' => 'farmer.farmer_address',
                    'htmlOptions' => array(
                        'width' => '',
                        'style' => 'text-align: left; padding-left: 30px;',
                    ),
                ),
                array(
                    'header' => 'หนี้รวม',
                    'value' => array($model, "getSumReport"),
                    'htmlOptions' => array(
                        'width' => '150',
                        'style' => 'text-align: right; padding-right: 1%;',
                    ),
                ),
            ),
        ));
        ?>
    </div>

</div>