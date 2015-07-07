<div class="panel panel-info" style="margin: 10px">
    <div class="panel-heading">จัดการบิล [แก้ไข, ยกเลิก]</div>
    <div class="panel-body">
        <div style="text-align: right">
            <a href="index.php?r=Basic/ClearBillSale" onclick="return confirm('ยืนยันการลบบิลทั้งหมด')" class="btn btn-danger">
                <i class="glyphicon glyphicon-remove"></i>
                ลบบิลทั้งหมด
            </a>
        </div>
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $modelForGrid,
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
                array(
                    'name' => 'bill_sale_id',
                    'type' => 'html',
                    'value' => array($model, 'buttonManageBill'),
                    'htmlOptions' => array(
                        'align' => 'center',
                        'width' => '70px'
                    )
                ),
                array(
                    'name' => 'bill_sale_created_date',
                    'htmlOptions' => array(
                        'width' => '180px',
                        'align' => 'center'
                    ),
					'value' => 'Util::mysqlToThaiDate($data->bill_sale_created_date)'
                ),
                'member.member_name',
                array(
                    'name' => 'user_id',
                    'value' => '@$data->user->user_name',
                ),
                array(
                    'name' => 'bill_sale_status',
                    'value' => '$data->getStatus()',
                    'htmlOptions' => array(
                        'align' => 'center',
                        'width' => '100px'
                    )
                )
            )
        ));
        ?>
    </div>
</div>