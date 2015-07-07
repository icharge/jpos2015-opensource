<div class="panel panel-info" style="margin: 10px">
  <div class="panel-heading">รายงานกำไร - ขาดทุน</div>
  <div class="panel-body">
    <div class="pull-left">
      <!-- form -->
      <form style="margin-bottom: 10px" method="post" name="formReportIncome">
        <label style="width: 50px">จากวันที่</label>
        <input type="text" name="from" value="<?php echo $from; ?>" class="form-control datepicker" style="width: 100px" />

        <label style="width: 50px">ถึงวันที่</label>
        <input type="text" name="to" value="<?php echo $to; ?>" class="form-control datepicker" style="width: 100px" />

        <a href="#" onclick="document.formReportIncome.submit()" class="btn btn-info">
          แสดงรายงาน
        </a>
      </form>
    </div>
    <div class="pull-right">
      <strong style="font-size: 16px">รายได้</strong>
      <span class="label label-primary" id="lblIncome" style="font-size: 16px">0.00</span>

      <strong style="margin-left: 20px; font-size: 16px">ค่าใช้จ่าย</strong>
      <span class="label label-danger" id="lblPay" style="font-size: 16px">0.00</span>

      <strong style="margin-left: 20px; font-size: 16px" id="lblBonusText">กำไร</strong>
      <span class="label label-success" id="lblBonus" style="font-size: 16px">0.00</span>
    </div>
    <div class="clearfix"></div>

    <!-- รายได้ -->
    <div class="row">
      <div class="col-md-6">
        <div class="panel panel-success">
          <div class="panel-heading">
            <i class="glyphicon glyphicon-plus"></i>
            รายได้
          </div>
          <div class="">
            <?php if (!empty($billSaleDetails)): ?>
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th width="40px" style="text-align: right">#</th>
                  <th>รายการ</th>
                  <th width="90px" style="text-align: center">วันที่</th>
                  <th width="70px" style="text-align: right">ราคา</th>
                  <th width="80px" style="text-align: right">รวม</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($billSaleDetails as $billSaleDetail): ?>
                <?php
                $price = $billSaleDetail['bill_sale_detail_price'];
                $qty = $billSaleDetail['bill_sale_detail_qty'];
                $total_price_in_row = ($price * $qty);
                $sumInput += $total_price_in_row;
                ?>
                <tr>
                  <td style="text-align: right">
                    <?php echo $n++; ?>
                  </td>
                  <td>
                    <?php 
                    $barcodePrice = BarcodePrice::model()->findByAttributes(array(
                      'barcode' => $billSaleDetail['bill_sale_detail_barcode']
                    ));

                    if (!empty($barcodePrice)) {
                      $product_name = $barcodePrice->product->product_name.' - '.$barcodePrice->name.' ('.$barcodePrice->qty_sub_stock.')';
                    } else {
                      $product_name = $billSaleDetail['product_name'];
                    }
                    ?>
                    <?php echo $product_name; ?>
                  </td>
                  <td style="text-align: center">
                    <?php echo Util::mysqlToThaiDate($billSaleDetail['bill_sale_pay_date']); ?>
                  </td>
                  <td style="text-align: right">
                    <?php echo $price; ?>
                    x
                    <?php echo $qty; ?>
                  </td>
                  <td style="text-align: right">
                    <?php echo number_format($total_price_in_row, 2); ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="4"><strong>รวม</strong></td>
                  <td style="text-align: right">
                    <?php echo number_format($sumInput, 2); ?>
                  </td>
                </tr>
              </tfoot>
            </table>
            <?php else: ?>
            <div style="padding: 20px">
              <h4>ยังไม่มีข้อมูล</h4>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- ค่าใช้จ่าย -->
      <div class="col-md-6">
        <div class="panel panel-danger">
          <div class="panel-heading">
            <i class="glyphicon glyphicon-minus"></i>
            ค่าใช้จ่าย
          </div>
          <div class="">
            <?php if (!empty($pays)): ?>
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th width="40px" style="text-align: right">#</th>
                  <th>รายการ</th>
                  <th width="100px" style="text-align: center">วันที่</th>
                  <th width="80px" style="text-align: right">จำนวนเงิน</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($pays as $pay): ?>
                <tr>
                  <td style="text-align: right"><?php echo $nPay++; ?></td>
                  <td><?php echo $pay['name']; ?></td>
                  <td style="text-align: center">
                    <?php echo Util::mysqlToThaiDate($pay['created_at']); ?>
                  </td>
                  <td style="text-align: right">
                    <?php echo number_format($pay['price'], 2); ?>
                  </td>
                </tr>
                <?php $sumPay += $pay['price']; ?>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="3"><strong>รวม</strong></td>
                  <td style="text-align: right">
                    <?php echo number_format($sumPay, 2); ?>
                  </td>
                </tr>
              </tfoot>
            </table>
            <?php else: ?>
            <div style="padding: 20px">
              <h4>ยังไม่มีข้อมูล</h4>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $bonus = ($sumInput - $sumPay); ?>

<script>
  $(function() {
    $('#lblIncome').text('<?php echo number_format($sumInput, 2); ?>');
    $('#lblPay').text('<?php echo number_format($sumPay, 2); ?>');
    $('#lblBonus').text('<?php echo number_format($bonus, 2); ?>');

    <?php if ($bonus < 0): ?>
    $('#lblBonusText').text('ขาดทุน');
    $('#lblBonus').removeClass('label-success').addClass('label-danger');
    <?php else: ?>
    $('#lblBonusText').text('กำไร');
    $('#lblBonus').removeClass('label-danger').addClass('label-success');
    <?php endif; ?>
  });
</script>