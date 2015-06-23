<div class="panel panel-primary" style="margin: 10px">
    <div class="panel-heading">รายงานยอดขายวัน</div>
    <div class="panel-body">
        <form name="form1" action="index.php?r=Report/SaleSumPerDay" method="post">
        <div>
            <label>เลือกเดือน</label>
            <?php echo CHtml::dropDownList("month", $month, Util::monthRange(), array(
            		'class' => 'form-control',
            		'style' => 'width: 200px'
            )); ?>
            <label>เลือกปี</label>
            <?php echo CHtml::dropDownList("year", $year, Util::yearRange(), array(
            		'class' => 'form-control',
            		'style' => 'width: 200px'
            )); ?>
        </div>
        <div>
            <label></label>
            <a href="#" class="btn btn-primary" onclick="document.form1.submit();">
                <i class="glyphicon glyphicon-ok"></i>
                แสดงรายงาน
            </a>
        </div>
        <form>

        <table style="margin-top: 20px" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50px">วันที่</th>
                    <th style="text-align: right">จำนวนเงิน</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 1; $i <= $total_day; $i++): ?>
                <?php 
                $total = $billSale->getSumPriceByDayMonthYear($i, $month, $year);
                $sum += $total;
                ?>
                    <tr>
                        <td style="text-align: right;"><?php echo $i; ?></td>
                        <td style="text-align: right;">
                            <?php echo number_format($total, 2); ?>
                        </td>
                    </tr>
                <?php endfor; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td style="text-align: right"><strong>รวม</strong></td>
                    <td style="text-align: right">
                        <?php echo number_format($sum, 2); ?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>