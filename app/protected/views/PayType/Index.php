<div class="panel panel-primary" style="margin: 10px">
  <div class="panel-heading">ประเภทรายจ่าย</div>
  <div class="panel-body">
    <a href="index.php?r=PayType/Form" class="btn btn-primary">
      <i class="glyphicon glyphicon-plus"></i>
      เพิ่มรายการ
    </a>

    <table style="margin-top: 10px" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>ชื่อประเภท</th>
          <th>หมายเหตุ</th>
          <th width="40px">แก้ไข</th>
          <th width="40px">ลบ</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($payTypes as $payType): ?>
        <tr>
          <td><?php echo $payType->name; ?></td>
          <td><?php echo $payType->remark; ?></td>
          <td>
            <a href="index.php?r=PayType/Edit&id=<?php echo $payType->id; ?>" class="btn btn-primary">
              <i class="glyphicon glyphicon-pencil"></i>
            </a>
          </td>
          <td>
            <a href="index.php?r=PayType/Delete&id=<?php echo $payType->id; ?>" onclick="return confirm('ยืนยันการลบรายการ')" class="btn btn-danger">
              <i class="glyphicon glyphicon-remove"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>