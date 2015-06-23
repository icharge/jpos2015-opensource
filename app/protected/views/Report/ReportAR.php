<script>
  function showBillDetail(bill_sale_id) {
    $.ajax({
      url: 'index.php?r=Ajax/BillSaleDetail',
      data: {
        bill_sale_id: bill_sale_id
      },
      success: function(data) {
        $("#gridBillSaleDetail").html(data);
        $("#bill_sale_id").text(bill_sale_id);
      }
    });

    return false;
  }
</script>

<div class="panel panel-primary" style="margin: 10px">
    <div class="panel-heading">รายงานลูกหนี้</div>
    <div class="panel-body">
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $billSales,
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
                  'header' => 'รหัสบิล',
                  'type' => 'raw',
                  'value' => 'CHtml::link($data->bill_sale_id, "#", array(
                    "data-toggle" => "modal",
                    "data-target" => "#myModal",
                    "onclick" => "return showBillDetail($data->bill_sale_id)"
                  ))',
                  'htmlOptions' => array(
                    'width' => '80px',
                    'style' => 'text-align: center'
                  )
                ),
                array(
                  'header' => 'วันที่ทำรายการ',
                  'value' => 'Util::mysqlToThaiDate($data->bill_sale_created_date)',
                  'htmlOptions' => array(
                    'width' => '130px',
                    'style' => 'text-align: center'
                  )
                ),
                'member.member_name',
                array(
                  'header' => 'พนักงานขาย',
                  'value' => '@$data->user->user_name',
                  'htmlOptions' => array(
                    'width' => '250px'
                  )
                ),
                array(
                  'header' => 'จำนวนเงิน',
                  'value' => '$data->getSum()',
                  'htmlOptions' => array(
                    'width' => '100px',
                    'style' => 'text-align: right'
                  )
                )
            )
        ));
        ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 850px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">ข้อมูลการสั่งซื้อของลูกหนี้ Bill : <span style="color: red" id="bill_sale_id"></span></h4>
      </div>
      <div class="modal-body" id="gridBillSaleDetail">

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