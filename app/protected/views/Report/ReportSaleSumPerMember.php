
<div class="panel panel-primary" style="margin: 10px">
    <div class="panel-heading">รายงานยอดขายตามสมาชิก</div>
    <div class="panel-body">
        <?php echo CHtml::form(Yii::app()->controller->createUrl('//Report/SaleSumPerMember'), 'post', array('name' => 'form1')); ?>
        
        <div>
            <label style="width: 80px">เลือกปี</label>
            <?php echo CHtml::dropDownList("y", $y, $yearList, array(
                    'class' => 'form-control',
                    'style' => 'width: 100px'
            )); ?>

            <a href="#" class="btn btn-primary" onclick="document.form1.submit();">
                <i class="glyphicon glyphicon-ok"></i>
                แสดงรายงาน
            </a>
        </div>
        <?php echo CHtml::endForm(); ?>

            <table style="margin-top: 5px;" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="50px" style="text-align: right">ลำดับ</th>
                        <th>สมาชิก</th>
                        <th width="150px">ยอดขาย</th>
                        <th width="150px">คะแนน</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $member): ?>
                    <?php $price = $billSale->getSumPriceByYearAndMemberId($y, $member->member_id); ?>
                    <tr>
                        <td style="text-align: right"><?php echo $n++; ?></td>
                        <td><?php echo $member->member_name; ?></td>
                        <td style="text-align: right">
                            <a href="index.php?r=Report/SaleSumPerMemberDetail&member_id=<?php echo $member->member_id; ?>&year=<?php echo $y; ?>">
                                <?php echo number_format($price, 2); ?></td>
                            </a>
                        <td style="text-align: right"><?php echo number_format($price / $configSoftware->score); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
    </div>
</div>