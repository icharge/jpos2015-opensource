<script type="text/javascript">

  function dialogGroupProduct() {
    $.ajax({
      url: 'index.php?r=Dialog/DialogGroupProduct',
      success: function(data) {
        $("#dialogGroupProduct").html(data);
      }
    });

    return false;
  }

  function getGroupProductName() {
    var group_product_code = $("#Product_group_product_id").val();

    $.ajax({
      url: 'index.php?r=Ajax/GetGroupProductInfo',
      dataType: 'json',
      cache: false,
      data: {
        group_product_code: group_product_code
      },
      success: function(data) {
        if (data != null) {
          $("#Product_group_name").val(data.group_product_name);
        }
      }
    });
  }

  function genProductCode() {
    $.ajax({
      url: 'index.php?r=Ajax/genProductCode',
      cache: false,
      success: function(data) {
        $("#Product_product_code").val(data);
      }
    });
  }

  function printBarCode() {
    var barcode = $("#Product_product_code").val();
    var url = 'index.php?r=Ajax/PrintBarCode&barcode=' + barcode;
    var left = window.innerWidth / 2;
    var top = window.innerHeight / 2;
    var opt = 'width=300, height=100, left=' + left + ', top=' + top + ', toolbar=no, location=no, menubar=no, titlebar=no';

    window.open(url, null, opt);
  }

  <?php if (!empty($model)): ?>
  $(function() {
    getGroupProductName();
  });
  <?php endif; ?>

  $(function() {
    $("#tablist").tabs();
    $("#Product_product_code").keydown(function(e) {
      if (e.keyCode == 13 || e.keyCode == 9) {
        isProduct();
      }
    });
  });

  function isProduct(e) {
      var code = $("#Product_product_code").val();

      $.ajax({
        url: 'index.php?r=Ajax/GetProductInfo',
        data: {
          product_code: code
        },
        dataType: 'json',
        success: function(data) {
          if (data != null) {
            if (!confirm('กรอกรายการใหม่')) {
              window.location.href = 'index.php?r=Config/ProductForm&id=' + data.product_id;
            } else {
              $("#Product_product_code").val("");
            }
          }
        }
      });
  }

  function saveProductPriceBarcode() {
    $("#ProductPriceByBarCode_code").val($("#Product_product_code").val());
    var barcode_fk = $("#ProductPriceByBarCode_code").val();

    if (barcode_fk != "") {
      $.ajax({
        url: 'index.php?r=Config/SaveProductPriceBarCode',
        type: 'POST',
        data: $("#formPriceBarCode").serialize(),
        success: function(data) {
          if (data == 'success') {
            alert("บันทึกรายการแล้ว");
            window.location.href = 'index.php?r=Config/ProductForm'
          }
        }
      });
    } else {
      alert("โปรดทำการกำหนด barcode สินค้าก่อน");
    }
  }
</script>

<div class="panel panel-info" style="margin: 10px">
  <div class="panel-heading">สินค้า</div>
  <div class="panel-body">

    <ul class="nav nav-tabs" role="tablist">
      <?php if (!empty($_GET['id'])): ?>
      <li class="active">
        <a href="#productInfo" role="tab" data-toggle="tab">ข้อมูลสินค้า</a>
      </li>
      <li><a href="#profile" role="tab" data-toggle="tab">ราคาจำหน่าย</a></li>
      <li><a href="#priceByBarCode" role="tab" data-toggle="tab">ราคาจำหน่าย แยกตามบาร์โค้ด</a></li>
      <li><a href="#printBarCode" role="tab" data-toggle="tab">พิมพ์บาร์โค้ด</a></li>
      <li><a href="#messages" role="tab" data-toggle="tab">ภาพสินค้า</a></li>
      <?php endif; ?>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane active" id="productInfo">
        <?php $this->renderPartial("//Config/_ProductInfo", array(
          "model" => $model,
          "default_product_expire" => $default_product_expire,
          "default_product_sale_condition" => $default_product_sale_condition,
          "default_product_return" => $default_product_return
        )); ?>
      </div>
      <div class="tab-pane" id="profile">
        <?php $this->renderPartial("//Config/_ProductPrice"); ?>
      </div>
      <div class="tab-pane" id="messages">...</div>
      <div class="tab-pane" id="priceByBarCode">
        <?php $this->renderPartial("//Config/_ProductPriceByBarCode", array(
          'barcodePrices' => $barcodePrices
        )); ?>
      </div>
      <div class="tab-pane" id="printBarCode">

      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 850px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">ประเภทสินค้า</h4>
      </div>
      <div class="modal-body" id="dialogGroupProduct">

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
