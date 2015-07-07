<script type="text/javascript">
    document.ready = function() {
        $("#from").datepicker({
            dateFormat: 'dd/mm/yy',
            changeYear: true,
            changeMonth: true
        });

        $("#to").datepicker({
            dateFormat: 'dd/mm/yy',
            changeYear: true,
            changeMonth: true
        });
    }
    
    function browseMember() {        
        $.ajax({
            url: 'index.php?r=Dialog/DialogMember',
            success: function(data) {
                $("#modalContent").html(data);
                $(".cmdChooseMember").click(function() {
                    $("#member_code").val($(this).attr("member_code"));
                    $("#member_name").val($(this).attr("member_name"));
                    $("#hidden_member_code").val($(this).attr("member_code"));
                });
            }
        });
    }
    
    function getBillDrop() {
        $.ajax({
            url: "index.php?r=Basic/BillDropGet",
            type: "POST",
            data: $("#formData").serialize(),
            success: function(data) {
                if (data == 'complete') {
                    refreshData();
                }
            }
        });
    }
    
    function printBillDrop() {
        // send data to session
        var uri = "index.php?r=Dialog/DialogBillDrop";
        var options = "dialogWidth=900px; dialogHeight=600px";
           
        $.ajax({
            url: 'index.php?r=Basic/BillDropTemp',
            type: 'POST',
            data: $("#formData").serialize(),
            success: function(data) {
                if (data == 'complete') {
                    window.open(uri, null, options);
                    refreshData();
                }
            },
            error: function(data) {
                alert('ERROR ' + data.responseText);
            }
        });
    }
    
    function refreshData() {
        document.form1.submit();
    }
    
    function cancelBillDrop() {
        $.ajax({
            url: "<?php echo $this->createUrl("Basic/BillDropCancel"); ?>",
            type: "POST",
            data: $("#formData").serialize(),
            success: function(data) {
                if (data != null) {
                    refreshData();
                }
            }
        });
    }
    
    function deleteBillDrop() {
	    $.ajax({
            url: "<?php echo $this->createUrl("Basic/BillDropDelete"); ?>",
            type: "POST",
            data: $("#formData").serialize(),
            success: function(data) {
                if (data != null) {
                    refreshData();
                }
            }
        });
    }

    function showData() {
        var from = $("#from").val();
        var to = $("#to").val();
        var member_code = $("#member_code").val();

        if (from != "" && to != "" && member_code != "") {
            document.form1.submit();
        } else {
            alert("โปรดป้อนข้อมูลด้วย");
        }
    }
</script>

<div class="panel panel-info" style="margin: 10px">
    <div class="panel-heading">พิมพ์ใบวางบิล</div>
    <div class="panel-body">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'htmlOptions' => array(
                'name' => 'form1',
                'class' => 'form-inline'
            )
        ));
        ?>
        <div>
            <label>จากวันที่: </label>
            <input type="text" name="from" id="from" class="form-control" style="width: 200px" value="<?php echo Util::mysqlToThaiDate($from); ?>" />

            <label>ถึงวันที่: </label>
            <input type="text" name="to" id="to" class="form-control" style="width: 200px" value="<?php echo Util::mysqlToThaiDate($to); ?>" />
        </div>
        <div>
            <div class="">
            <label>สมาชิก</label>
            <input type="text" name="member_code" id="member_code" class="form-control" style="width: 150px" value="<?php echo $member_code; ?>" />
            <input type="text" name="member_name" id="member_name" value="<?php echo $member_name; ?>" class="form-control disabled" style="width: 362px" readonly="readonly" />
            <a href="#" class="btn btn-info" onclick="browseMember()" data-toggle="modal" data-target="#myModal">
                <i class="glyphicon glyphicon-search"></i>
            </a>
            </div>
        </div>
        <div>
            <label>สถานะบิล</label>
            <?php echo CHtml::radioButton('bill_status', $bill_status == 'all' || empty($bill_status), array('value' => 'all')); ?> ทุกสถานะ
            <?php echo CHtml::radioButton('bill_status', $bill_status == 'no', array('value' => 'no')); ?> ยังไม่วางบิล
            <?php echo CHtml::radioButton('bill_status', $bill_status == 'drop_no', array('value' => 'drop_no')); ?> วางบิลแล้ว รอชำระ
            <?php echo CHtml::radioButton('bill_status', $bill_status == 'drop_pay', array('value' => 'drop_pay')); ?> วางบิลแล้ว ชำระแล้ว
        </div>
        <div>
            <label></label>
            <a href="#" class="btn btn-info" onclick="showData();">
                <i class="glyphicon glyphicon-ok"></i>
                แสดงรายการ
            </a>
        </div>
        <?php $this->endWidget(); ?>
        <hr />

        <form id="formData">
            <div>
                <a href="#" class="btn btn-info" onclick="getBillDrop()">
                  <i class="glyphicon glyphicon-list-alt"></i>
                  รับบิล
                </a>
                <a href="#" class="btn btn-info" onclick="printBillDrop()">
                  <i class="glyphicon glyphicon-print"></i>
                  พิมพ์ใบวางบิล
                </a>
                <a href="#" class="btn btn-warning" onclick="cancelBillDrop()">
                  <i class="glyphicon glyphicon-remove"></i>
                  ยกเลิกบิล
                </a>
                |
                <a href="#" class="btn btn-danger" onclick="deleteBillDrop()">
                    <b class="glyphicon glyphicon-trash"></b>
                	ลบบิลออกจากระบบ
                </a>
            </div>
            <?php
            if (!empty($dataProvider)) {
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider' => $dataProvider,
                    'selectableRows' => 2,
                    'itemsCssClass' => 'table table-bordered table-striped',
                    'columns' => array(
                        array(
                            'class' => 'CCheckBoxColumn',
                            'id' => 'bill_sale_id',
                            'htmlOptions' => array(
                                'width' => '30px',
                                'align' => 'center'
                            )
                        ),
                        array(
                            'name' => 'bill_sale_id',
                            'htmlOptions' => array(
                                'width' => '80px',
                                'align' => 'center'
                            )
                        ),
                        array(
                            'header' => 'จำนวนยอด',
                            'type' => 'raw',
                            'value' => '$data->getSum()',
                            'htmlOptions' => array(
                                'width' => '100px',
                                'align' => 'right'
                            )
                        ),
                        array(
                            'name' => 'bill_sale_created_date',
                            'htmlOptions' => array(
                                'width' => '150px',
                                'align' => 'center'
                            )
                        ),
                        array(
                            'name' => 'bill_sale_drop_bill_date',
                            'htmlOptions' => array(
                                'width' => '150px',
                                'align' => 'center'
                            )
                        ),
                        array(
                            'name' => 'bill_sale_status',
                            'htmlOptions' => array(
                                'width' => '80px',
                                'align' => 'center'
                            ),
                            'value' => '$data->getStatus()'
                        ),
                        array(
                            'name' => 'user_id',
                            'value' => '@$data->user->user_name'
                        ),
                        array(
                            'name' => 'bill_sale_pay_date',
                            'htmlOptions' => array(
                                'width' => '150px',
                                'align' => 'center'
                            )
                        )
                    )
                ));
            }
            ?>
            <input type="hidden" name="hidden_member_code" id="hidden_member_code" value="<?php echo $member_code; ?>" />
        </form>
    </div>
</div>

<!-- Modal -->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 850px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">เลือกสมาชิกร้าน</h4>
      </div>
      <div class="modal-body" id="modalContent">

      </div>
      <div class="modal-footer">
        <button id="btnCloseModal" type="button" class="btn btn-danger" data-dismiss="modal">
          <i class="glyphicon glyphicon-remove"></i>
          Close
        </button>
      </div>
    </div>
  </div>
</div>