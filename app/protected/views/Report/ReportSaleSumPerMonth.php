<div class="panel panel-primary" style="margin: 10px">
    <div class="panel-heading">รายงานยอดขายตามเดือน</div>
    <div class="panel-body">
        <?php echo CHtml::form(Yii::app()->controller->createUrl('//Report/SaleSumPerMonth'), 'post', array('name' => 'form1')); ?>
        <div>
            <label style="width: 80px">เลือกปี</label>
            <?php
                echo CHtml::dropDownList("year_find", $year, $yearList, array(
                	'style' => 'width: 200px',
                	'class' => 'form-control'
                ));
            ?>
  
            <a href="#" class="btn btn-primary" onclick="document.form1.submit();">
                <i class="glyphicon glyphicon-ok"></i>
                แสดงรายงาน
            </a>
        </div>
        <?php echo CHtml::endForm(); ?>

    <!-- report show -->
    <table style="margin-top: 20px" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="200px">เดือน</th>
                <th style="text-align: right">จำนวนเงิน</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 1; $i <= 12; $i++): ?>
            <?php $total = $billSale->getSumPriceByMonthYear($i, $year); ?>
            <tr>
                <td><?php echo $monthRange[$i]; ?></td>
                <td style="text-align: right">
                    <?php echo number_format($total, 2); ?>
                </td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>
</div>