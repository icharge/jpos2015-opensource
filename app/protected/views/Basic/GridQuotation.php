<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th width="50px">ลำดับ</th>
      <th width="100px">วันที่</th>
      <th width="50px">เลขที่</th>
      <th>ลูกค้า</th>
      <th>เบอร์โทร</th>
      <th width="80px"></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($quotations as $quotation): ?>
      <tr>
        <td style="text-align: right"><?php echo $n++; ?></td>
        <td><?php echo Util::mysqlToThaiDate($quotation->created_at); ?></td>
        <td style="text-align: right"><?php echo $quotation->id; ?></td>
        <td><?php echo $quotation->customer_name; ?></td>
        <td><?php echo $quotation->customer_tel; ?></td>
        <td>
          <a href="#" onclick="return chooseQuotation(<?php echo $quotation->id; ?>)" class="btn btn-primary" data-dismiss="modal">
            <i class="glyphicon glyphicon-ok"></i>
            แสดงข้อมูล
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
