<div class="panel panel-primary" style="margin: 10px">
  <div class="panel-heading">รายงานยอดขาย</div>
  <div class="panel-body">
    <form class="form-inline">
      <div>
        <label>รหัสสมาชิก</label>
        <input type="text" value="<?php echo $member->member_code; ?>" class="form-control disabled" disabled="disabled" style="width: 100px" />
      
        <label style="width: 50px">ชื่อ</label>
        <input type="text" value="<?php echo $member->member_name; ?>" class="form-control disabled" disabled="disabled" style="width: 300px" />

        <label style="width: 50px">ปี</label>
        <input type="text" value="<?php echo $year ?>" class="form-control disabled" disabled="disabled" style="width: 100px" />
      </div>
    </form>

    <table style="margin-top: 10px" class="table table-striped table-bordered">
      <thead>
        <tr>
          <th width="40px" style="text-align: right">#</th>
          <th width="200px">รหัสสินค้า</th>
          <th>รายการ</th>
          <th width="90px" style="text-align: right">ราคา</th>
          <th width="80px" style="text-align: right">จำนวน</th>
          <th width="100px" style="text-align: center">วันที่</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($billSaleDetails as $billSaleDetail): ?>
        <tr>
          <td style="text-align: right"><?php echo $n++; ?></td>
          <td><?php echo $billSaleDetail['bill_sale_detail_barcode']; ?></td>
          <td><?php echo $billSaleDetail['product_name']; ?></td>
          <td style="text-align: right"><?php echo number_format($billSaleDetail['bill_sale_detail_price'], 2); ?></td>
          <td style="text-align: right"><?php echo number_format($billSaleDetail['bill_sale_detail_qty']); ?></td>
          <td style="text-align: center"><?php echo Util::mysqlToThaiDate($billSaleDetail['bill_sale_created_date']); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>