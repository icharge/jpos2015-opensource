<script>
  function chooseGroupProduct(code, name) {
    $("#Product_group_product_id").val(code);
    $("#Product_group_name").val(name);

    return false;
  }
</script>

    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th width="50px"></th>
          <th width="100px">รหัสประเภท</th>
          <th width="300px">ชื่อประเภท</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($model as $r): ?>
          <tr>
            <td style="text-align: center">
              <a style="color: white" href="#"
                 class="btn btn-success"
                 data-dismiss="modal"
                 onclick="return chooseGroupProduct(
              '<?php echo $r->group_product_code; ?>',
              '<?php echo $r->group_product_name; ?>'
              )">
                <i class="icon-ok icon-white"></i>
                เลือก
              </a>
            </td>
            <td><?php echo $r->group_product_code; ?></td>
            <td><?php echo $r->group_product_name; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
