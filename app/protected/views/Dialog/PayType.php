<table style="margin-top: 10px" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th width="130px">&nbsp;</th>
      <th width="80px">รหัส</th>
      <th>ชื่อประเภท</th>
      <th>หมายเหตุ</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($payTypes as $payType): ?>
    <tr>
      <td align="center">
        <a href="#" class="btn btn-success btnChoosePayType" 
          id="<?php echo $payType->id; ?>"
          name="<?php echo $payType->name; ?>">
          <i class="glyphicon glyphicon-ok"></i>
          เลือกรายการ
        </a>
      </td>
      <td><?php echo $payType->id; ?></td>
      <td><?php echo $payType->name; ?></td>
      <td><?php echo $payType->remark; ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>