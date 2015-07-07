<div class="panel panel-info" style="margin: 10px">
    <div class="panel-heading">รายงานยอดขายตามประเภท</div>
    <div class="panel-body">
        <?php echo CHtml::form(Yii::app()->controller->createUrl('//Report/SaleSumPerType'), 'post', array('name' => 'form1')); ?>
        <div>
            <label style="width: 80px">เดือน</label>
            <?php
                echo CHtml::dropDownList("month", $month, $monthRange, array(
                    'style' => 'width: 150px',
                    'class' => 'form-control'
                ));
            ?>

            <label style="width: 80px">เลือกปี</label>
            <?php
                echo CHtml::dropDownList("year", $year, $yearList, array(
                    'style' => 'width: 100px',
                    'class' => 'form-control'
                ));
            ?>
  
            <a href="#" class="btn btn-info" onclick="document.form1.submit();">
                <i class="glyphicon glyphicon-ok"></i>
                แสดงรายงาน
            </a>
        </div>
        <?php echo CHtml::endForm(); ?>

        <table style="margin-top: 20px" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50px" style="text-align: right">NO</th>
                    <th width="300px">ประเภท</th>
                    <th style="text-align: right">จำนวนเงิน</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($groupProducts as $groupProduct): ?>
                <?php
                $total = $billSale->getSumPriceByMonthYearGroupProductId($month, $year, $groupProduct->group_product_id);
                $sum += $total;
                ?>
                <tr>
                    <td style="text-align: right;"><?php echo $n++; ?></td>
                    <td style="text-align: left;">
                        <?php echo $groupProduct->group_product_name; ?>
                    </td>
                    <td style="text-align: right">
                        <?php echo number_format($total, 2); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">
                        <strong>สรุปยอดเงินทั้งหมด : </strong>
                    </td>
                    <td style="text-align: right">
                        <?php echo number_format($sum, 2); ?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>