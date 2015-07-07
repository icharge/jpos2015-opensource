<div class="panel panel-info" style="margin: 10px">
  <div class="panel-heading">รายงานสินค้า หมดสต้อก</div>
  <div class="panel-body">
    <!--
    <div class="pull-right" style="margin-bottom: 10px">
      <a href="" class="btn btn-primary">
        <i class="glyphicon glyphicon-print"></i>
        พิมพ์รายงาน
      </a>
      <a href="" class="btn btn-primary">
        <i class="glyphicon glyphicon-upload"></i>
        ส่งออกเป็น Excel
      </a>  
    </div>
    <div class="clearfix"></div>
    -->

    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th width="200px">รหัสสินค้า</th>
          <th>ชื่อสินค้า</th>
          <th width="110px">จำนวนคงเหลือ</th>
          <th width="110px">จำนวนต่อแพค</th>
          <th width="150px">จำนวนคงเหลือ (แพค)</th>
          <th width="80px">ราคาปลีก</th>
          <th width="80px">ราคาส่ง</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $product): ?>
        <tr>
          <td><?php echo $product->product_code; ?></td>
          <td><?php echo $product->product_name; ?></td>
          <td><?php echo $product->product_quantity; ?></td>
          <td><?php echo $product->product_total_per_pack; ?></td>
          <td><?php echo $product->product_quantity_of_pack; ?></td>
          <td><?php echo $product->product_price; ?></td>
          <td><?php echo $product->product_price_send; ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>