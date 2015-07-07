<script type="text/javascript">
  function findProduct() {
    var search = $("input[name=barcode]").val();
    var group_product_id = $('input[name=group_product_id]').val();

    window.location = "index.php?r=Config/ProductIndex&search=" + search + "&group_product_id=" + group_product_id;
  }

  function browseProductFromExcelFile() {
    $("#excelFile").trigger("click");
    $("#formProductExcel").change(function() {
      var excelFile = $("#excelFile").val();

      if (excelFile != null) {
        $("#formProductExcel").submit();
      }
    });
  }
</script>

<div class="panel panel-info" style="margin: 10px">
  <div class="panel-heading">
    ข้อมูลสินค้า
    <?php if (!empty($group_product_id)): ?>
    <?php $groupProduct = GroupProduct::model()->findByPk($group_product_id); ?>
    : ประเภท <span class="label label-danger" style="font-size: 12px"><?php echo $groupProduct->group_product_name; ?></span>
    <?php endif; ?>
  </div>
  <div class="navbar-primary mynav">
    <ul class="nav navbar-nav">
      <li><a href="index.php?r=Config/ProductForm"><i class="glyphicon glyphicon-plus"></i> เพิ่มรายการ</a></li>
      <li><a href="index.php?r=Config/ProductIndex&productTag=true"><i class="glyphicon glyphicon-ok"></i> สินค้าขายบ่อย</a></li>
      <li><a href="index.php?r=Config/ProductIndex"><i class="glyphicon glyphicon-th-list"></i> สินค้าทั้งหมด</a></li>
      <li><a href="index.php?r=Config/ProductImportFromExcelFile"><i class="glyphicon glyphicon-download"></i> นำเข้าจากไฟล์ Excel</a> </li>
    </ul>
  </div>

  <div class="panel-body">
    <div class="pull-left">
      <div class="input-group" style="width: 270px">
        <input type="hidden" name="group_product_id" value="<?php echo $group_product_id; ?>" />
        <input type="text" name="barcode" value="<?php echo $search; ?>" class="form-control" style="width: 200px" />
        <a href="#" style="color: white" class="btn btn-primary input-group-addon" onclick="return findProduct()">
          <i class="glyphicon glyphicon-search"></i>
           ค้นหา
        </a>
      </div>
    </div>
    <div class="pull-right">
      <a onclick="return confirm('ยืนยันการลบรายการทั้งหมด')" href="index.php?r=Config/ProductClear" class="btn btn-danger">
        <b class="glyphicon glyphicon-remove"></b>
        ลบรายการสินค้าทั้งหมด
      </a>
    </div>
    <div class="clearfix"></div>

    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model,
        "pagerCssClass" => "pagination",
        "pager" => array(
          "selectedPageCssClass" => "active",
          "firstPageCssClass" => "previous",
          "lastPageCssClass" => "next",
          "hiddenPageCssClass" => "disabled",
          "header" => "",
          "htmlOptions" => array(
            "class" => "pagination"
          )
        ),
        'itemsCssClass' => 'table table-bordered table-striped',
        'columns' => array(
            'product_code',
            'product_pack_barcode',
            'product_name',
            'product_price',
            'product_price_send',
            'product_price_per_pack',
            'product_price_buy',
            array(
                'class' => 'CButtonColumn',
                'template' => '{edit} {del}',
                'buttons' => array(
                    'edit' => array(
                        'label' => '
                          <span class="btn btn-info">
                            <b class="glyphicon glyphicon-pencil"></b>
                          </span>',
                        'url' => 'Yii::app()->createUrl("Config/ProductForm", array(
                          "id" => $data->product_id
                         ))',
                        'options' => array(
                          'title' => ''
                        )
                    ),
                    'del' => array(
                        'label' => '
                          <span class="btn btn-danger">
                            <b class="glyphicon glyphicon-remove"></b>
                          </span>',
                        'url' => 'Yii::app()->createUrl("Config/ProductDelete", array(
                          "id" => $data->product_id
                        ))',
                        'options' => array(
                            'onclick' => 'return confirm("ยืนยันการลบ")',
                            'title' => ''
                        )
                    )
                ),
                'htmlOptions' => array(
                    'width' => '110px',
                    'align' => 'center'
                )
            )
        )
    ));
    ?>
  </div>
</div>
