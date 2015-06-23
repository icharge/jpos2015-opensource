<div class="panel panel-primary" style="margin: 10px">
  <div class="panel-heading">จำนวนสินค้าใกล้หมดสต็อก</div>
  <div class="panel-body">
    <?php if (!$print): ?>
      <div style="margin-bottom: 5px" class="pull-right">
        <a href="index.php?r=Basic/AlertStock&print=true" target="_blank" class="btn btn-primary">
          <i class="glyphicon glyphicon-print"></i>
          พิมพ์รายงาน
        </a>
      </div>
      <div class="clearfix"></div>
      <table class="table table-striped table-bordered">
    <?php else: ?>
      <meta charset="utf-8" />

      <style>
        * {
          font-size: 12px;
        }
        table tr th {
          padding: 5px;
        }
        table tr td {
          padding: 5px;
        }
      </style>
      <table border="1" style="border-collapse: collapse">
    <?php endif; ?>

      <thead>
        <tr>
          <th width="50px">ลำดับ</th>
          <th width="150px">Barcode</th>
          <th>สินค้า</th>
          <th width="80px">ราคาปลีก</th>
          <th width="120px">จำนวนคงเหลือ</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $product): ?>
        <tr>
          <td style="text-align: right"><?php echo $n++; ?></td>
          <td><?php echo $product->product_code; ?></td>
          <td><?php echo $product->product_name; ?></td>
          <td style="text-align: right"><?php echo number_format($product->product_price); ?></td>
          <td style="text-align: right"><?php echo number_format($product->product_quantity); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>  
</div>