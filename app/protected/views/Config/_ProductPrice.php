<script type="text/javascript">
  function formProductPriceSave() {
    $("#ProductPrice_product_code").val($("#Product_product_code").val());

    $.ajax({
      url: 'index.php?r=Config/ProductPriceSave',
      data: $("#formProductPrice").serialize(),
      type: 'POST',
      success: function(data) {
        if (data == 'success') {
          alert("บันทึกราคาขาย เรียบร้อยแล้ว");
        }
      }
    });

    return false;
  }
</script>

<div style="padding-top: 10px">
  <form id="formProductPrice">
    <input type="hidden" id="ProductPrice_product_code" name="product_code" />
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th width="40px">ลำดับ</th>
          <th width="120px">จำนวนชิ้น (ตั้งแต่)</th>
          <th width="120px">จำนวนชิ้น (ถึง)</th>
          <th width="170px">ราคาต่อหน่วย (ราคาปลีก)</th>
          <th>ราคาต่อหน่วย (ราคาส่ง)</th>
        </tr>
      </thead>
      <tbody>

      <?php if (!empty($_GET['id'])): ?>

      <?php
        $id = $_GET['id'];
        $product = Product::model()->findByPk($id);
        $productPrices = ProductPrice::model()->findAll(array(
          'condition' => 'product_barcode = :product_barcode',
          'params' => array(
            'product_barcode' => $product->product_code
          ),
          'order' => 'order_field ASC'
        ));
        $n = 1;
      ?>
        <!-- Update Record -->
        <?php if (!empty($productPrices)): ?>
        <?php foreach ($productPrices as $productPrice): ?>
        <tr>
          <td><?php echo $n++; ?></td>
          <td><input type="text" name="qty[]" class="form-control" style="width: 100px; text-align: right" value="<?php echo number_format($productPrice->qty, 0); ?>" /></td>
          <td><input type="text" name="qty_end[]" class="form-control" style="width: 100px; text-align: right" value="<?php echo number_format($productPrice->qty_end, 0); ?>" /></td>
          <td><input type="text" name="product_price[]" class="form-control" style="width: 100px; text-align: right" value="<?php echo number_format($productPrice->price, 2); ?>" /></td>
          <td><input type="text" name="product_price_send[]" class="form-control" style="width: 100px; text-align: right" value="<?php echo number_format($productPrice->price_send, 2); ?>" /></td>
        </tr>
        <?php endforeach; ?>
          <!-- Add Empty Row -->
          <?php if ($n < 10): ?>
            <?php for ($i = $n; $i < 10; $i++): ?>
              <tr>
                <td><?php echo $n++; ?></td>
                <td><input type="text" name="qty[]" class="form-control" style="width: 100px; text-align: right" /></td>
                <td><input type="text" name="qty_end[]" class="form-control" style="width: 100px; text-align: right" /></td>
                <td><input type="text" name="product_price[]" class="form-control" style="width: 100px; text-align: right" /></td>
                <td><input type="text" name="product_price_send[]" class="form-control" style="width: 100px; text-align: right" /></td>
              </tr>
            <?php endfor; ?>
          <?php endif; ?>
        <?php else: ?>
          <?php for ($i = 0; $i < 10; $i++): ?>
          <tr>
            <td><?php echo ($i + 1); ?>
            <td><input type="text" name="qty[]" class="form-control" style="width: 100px; text-align: right" /></td>
            <td><input type="text" name="qty_end[]" class="form-control" style="width: 100px; text-align: right" /></td>
            <td><input type="text" name="product_price[]" class="form-control" style="width: 100px; text-align: right" /></td>
            <td><input type="text" name="product_price_send[]" class="form-control" style="width: 100px; text-align: right" /></td>
          </tr>
          <?php endfor; ?>
        <?php endif; ?>
      <?php else: ?>
        <?php for ($i = 0; $i < 5; $i++): ?>
          <tr>
            <td><?php echo ($i + 1); ?>
            <td><input type="text" name="qty[]" class="form-control" style="width: 100px; text-align: right" /></td>
            <td><input type="text" name="qty_end[]" class="form-control" style="width: 100px; text-align: right" /></td>
            <td><input type="text" name="product_price[]" class="form-control" style="width: 100px; text-align: right" /></td>
            <td><input type="text" name="product_price_send[]" class="form-control" style="width: 100px; text-align: right" /></td>
          </tr>
        <?php endfor; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </form>
  <a href="#" class="btn btn-info" onclick="return formProductPriceSave()">
    <b class="glyphicon glyphicon-floppy-disk"></b>
    บันทึก
  </a>
</div>