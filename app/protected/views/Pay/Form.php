<script>
  $(function() {
    var id = $('#Pay_pay_type_id').val();

    if (id != null) {
      payTypeInfo(id);
    }
  });

  function payTypeInfo(id) {
    $.ajax({
      url: 'index.php?r=PayType/Info',
      dataType: 'json',
      type: 'post',
      data: {
        id: id
      },
      success: function(data) {
        if (data != null) {
          $('#Pay_pay_type_name').val(data.name);
        }
      }
    });
  }

  function browsePayType() {
    $('#modalLabel').text('เลือกประเภทค่าใช้จ่าย');
    $.ajax({
      url: 'index.php?r=Dialog/PayType',
      success: function(data) {
        $('#modalContent').html(data);
        $('.btnChoosePayType').click(function() {
          $('#btnCloseModal').trigger('click');

          var id = $(this).attr('id');
          var name = $(this).attr('name');

          $('#Pay_pay_type_id').val(id);
          $('#Pay_pay_type_name').val(name);
        });
      }
    });
  }
</script>

<div class="panel panel-primary" style="margin: 10px">
  <div class="panel-heading">บันทึก รายจ่าย</div>
  <div class="panel-body">
    <?php
    $f = $this->beginWidget('CActiveForm', array(
      'action' => 'index.php?r=Pay/Form'
    )); 
    echo $f->errorSummary($pay);
    ?>
  
    <div>
      <label>ประเภทค่าใช้จ่าย *</label>
      <?php echo $f->textField($pay, 'pay_type_id', array(
        'class' => 'form-control',
        'style' => 'width: 80px'
      )); ?>
      <input type="text" id="Pay_pay_type_name" class="form-control disabled" disabled="disabled" style="width: 300px" />
      <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal" onclick="browsePayType()">
        <i class="glyphicon glyphicon-search"></i>
      </a>
    </div>
    <div>
      <label>ชื่อรายการ *</label>
      <?php echo $f->textField($pay, 'name', array(
        'class' => 'form-control',
        'style' => 'width: 300px'
      )); ?>
    </div>
    <div>
      <label>หมายเหตุ</label>
      <?php echo $f->textField($pay, 'remark', array(
        'class' => 'form-control',
        'style' => 'width: 500px'
      )); ?>
    </div>
    <div>
      <label>จำนวนเงิน *</label>
      <?php echo $f->textField($pay, 'price', array(
        'class' => 'form-control',
        'style' => 'width: 80px'
      )); ?>
    </div>
    <div>
      <label></label>
      <input type="submit" value="บันทึก" class="btn btn-primary">
    </div>
    <?php echo $f->hiddenField($pay, 'id'); ?>
    <?php $this->endWidget(); ?>
  </div>
</div>

<!-- modal -->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 850px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" id="btnCloseModal" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="modalLabel"></h4>
      </div>
      <div class="modal-body" id="modalContent">

      </div>
    </div>
  </div>
</div>