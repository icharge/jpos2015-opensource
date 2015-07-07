
<div class="alert alert-danger" style="margin-top: 10px">
  <div><i class="glyphicon glyphicon-question-sign"></i> ใช้ในกรณีสินค้าขายได้หลายระดับ เช่น เป็นชิ้น เป็นแพก เป็นลัง เป็นห่อ โดยแยกตามรหัสบาร์โค้ด</div>
  <div><i class="glyphicon glyphicon-question-sign"></i> เริ่มต้นจากรหัส แพค เป็นต้นไป</div>
</div>

<form id="formPriceBarCode">
  <input type="hidden" id="ProductPriceByBarCode_code" name="product_code" />

  <table style="margin-top: 10px" class="table table-bordered table-striped">
    <thead>
      <tr>
        <th width="50px">ลำดับ</th>
        <th width="150px">บาร์โค้ด</th>
        <th width="120px">ราคาทุน</th>
        <th width="120px">ราคาจำหน่าย</th>
        <th width="180px">ชื่อเรียก (ลัง, แพค, โหล)</th>
        <th>จำนวนที่จะตัดสต้อก</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($barcodePrices)): ?>
        <?php for ($i = 0; $i < 20; $i++): ?>
        <tr>
          <td><?php echo $i + 1; ?></td>
          <td><input type="text" name="barcode[]" class="form-control" /></td>
          <td><input type="text" name="price_before[]" class="form-control" /></td>
          <td><input type="text" name="price[]" class="form-control" style="width: 100px; text-align: right" /></td>
          <td><input type="text" name="name[]" class="form-control" style="width: 180px" /></td>
          <td><input type="text" name="qty[]" class="form-control" style="width: 100px; text-align: right" /></td>
        </tr>
        <?php endfor; ?>
      <?php else: ?>
        <?php $i = 1; ?>
        <?php foreach ($barcodePrices as $barcodePrice): ?>
        <tr>
          <td><?php echo $i++; ?></td>
          <td><input type="text" name="barcode[]" value="<?php echo $barcodePrice->barcode; ?>" class="form-control" /></td>
          <td><input type="text" name="price_before[]" value="<?php echo $barcodePrice->price_before; ?>" class="form-control" />
          <td><input type="text" name="price[]" value="<?php echo $barcodePrice->price; ?>" class="form-control" style="width: 100px; text-align: right" /></td>
          <td><input type="text" name="name[]" value="<?php echo $barcodePrice->name; ?>" class="form-control" style="width: 180px" /></td>
          <td><input type="text" name="qty[]" value="<?php echo $barcodePrice->qty_sub_stock; ?>" class="form-control" style="width: 100px; text-align: right" /></td>
        </tr>
        <?php endforeach; ?>

        <!-- Add empty row -->
        <?php $size = count($barcodePrices); ?>
        <?php for ($i = $size + 1; $i <= 20; $i++): ?>
        <tr>
          <td><?php echo $i; ?></td>
          <td><input type="text" name="barcode[]" class="form-control" /></td>
          <td><input type="text" name="price_before[]" class="form-control" style="width: 100px" /></td>
          <td><input type="text" name="price[]" class="form-control" style="width: 100px; text-align: right" /></td>
          <td><input type="text" name="name[]" class="form-control" style="width: 180px" /></td>
          <td><input type="text" name="qty[]" class="form-control" style="width: 100px; text-align: right" /></td>
        </tr>
        <?php endfor; ?>
      <?php endif; ?>
    </tbody>
  </table>
</form>

<a href="#" onclick="saveProductPriceBarcode()" class="btn btn-info">
  <b class="glyphicon glyphicon-floppy-disk"></b>
  บันทึก
</a>