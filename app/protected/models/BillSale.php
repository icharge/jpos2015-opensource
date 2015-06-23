<?php

class BillSale extends CActiveRecord {

    private $SUM_ALL;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'tb_bill_sale';
    }

    public function attributeLabels() {
        return array(
            'bill_sale_id' => 'รหัสบิล',
            'bill_sale_created_date' => 'วันที่ทำรายการ',
            'bill_sale_status' => 'สถานะบิล',
            'member_id' => 'สมาชิก',
            'bill_sale_vat' => 'VAT',
            'user_id' => 'พนักงานขาย',
            'branch_id' => 'สาขา',
            'bill_sale_pay_date' => 'วันที่ชำระเงิน/รับบิล',
            'bill_sale_drop_bill_date' => 'วันที่วางบิล'
        );
    }

    public function relations() {
        return array(
            'member' => array(self::BELONGS_TO, 'Member', 'member_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id')
        );
    }

    public static function sumTotalPrice() {
        $billSaleDetail = Yii::app()->session['billSaleDetail'];
        $sum = 0;

        if (!empty($billSaleDetail)) {
            for ($i = 0; $i < count($billSaleDetail); $i++) {
                $row = $billSaleDetail[$i];
                $sum += ($row['product_price'] * $row['product_qty']);
            }
        }
        return $sum;
    }

    public function buttonManageBill($data, $row) {
        $text = $data->bill_sale_id;
        $url = array(
					"Basic/BillSaleDetail",
					"bill_sale_id" => $data->bill_sale_id
				);

        return CHtml::link($text, $url, array(
        	"class" => "btn btn-info"
        ));
    }

    public function getStatus() {
        if ($this->bill_sale_status == 'pay') {
            return 'ชำระแล้ว';
        }
        return 'รอชำระ';
    }

    public function buttonBillDropDetail($data, $row) {
        $text = $data->bill_sale_id;
        $url = array(
					"Basic/BillDropDetail",
					"bill_sale_id" => $data->bill_sale_id
				);

        return CHtml::link($text, $url, array(
        	"class" => "btn btn-info"
        ));
    }

    public function getSum($show_comma = true) {
        $sum = Yii::app()->db->createCommand()
		    ->select('SUM(bill_sale_detail_qty * bill_sale_detail_price) AS my_sum')
            ->from('tb_bill_sale_detail')
            ->where('bill_id = ' . $this->bill_sale_id)
            ->queryRow();

		if ($show_comma) {
      		return number_format($sum['my_sum']);
        }

      return $sum['my_sum'];
    }

    public function getSumReport($data, $row) {
        $criteria = new CDbCriteria();
        $criteria->select = 'SUM(bill_sale_detail_price * bill_sale_detail_qty) AS SUM_ALL';
        $criteria->join = 'INNER JOIN tb_bill_sale_detail AS c ON c.bill_id = t.bill_sale_id';
        $criteria->condition = 'bill_sale_pay_date IS NULL';
        $criteria->condition = 'bill_sale_drop_bill_date IS NULL';
        $criteria->compare("t.member_id", $data->member_id);
        $criteria->group = 't.member_id';

        $result = BillSale::model()->find($criteria);

        return number_format($result->SUM_ALL, 2);
    }

    public function getSumPrice($member_id, $from, $to) {
        $sql = "
            select sum(bill_sale_detail_price) AS total_price
            from tb_bill_sale_detail
            inner join tb_bill_sale on tb_bill_sale.bill_sale_id = tb_bill_sale_detail.bill_id
            where member_id = $member_id
            -- AND YEAR(bill_sale_created_date) >= YEAR('$from')
            -- AND MONTH(bill_sale_created_date) >= MONTH('$from')
            -- AND DAY(bill_sale_created_date) >= DAY('$from')
            -- AND YEAR(bill_sale_created_date) <= YEAR('$to')
            -- AND MONTH(bill_sale_created_date) <= MONTH('$to')
            -- AND DAY(bill_sale_created_date) <= DAY('$to')
        ";
        $sum = Yii::app()->db->createCommand($sql)->queryRow();

        return $sum['total_price'];
    }

    public function getSumPriceByDayMonthYear($d, $m, $y) {
        $sql = "
          SELECT SUM(bill_sale_detail_price * bill_sale_detail_qty) AS money
          FROM tb_bill_sale_detail
          LEFT JOIN tb_bill_sale ON tb_bill_sale_detail.bill_id = tb_bill_sale.bill_sale_id
          WHERE
            MONTH(bill_sale_created_date) = $m
            AND YEAR(bill_sale_created_date) = $y
            AND DAY(bill_sale_created_date) = $d
            AND bill_sale_status IN('pay', 'credit')
        ";
        $rs = Yii::app()->db->createCommand($sql)->queryRow();
        
        return $rs['money'];
    }

    public function getSumPriceByMonthYear($m, $y) {
        $sql = "
          SELECT SUM(bill_sale_detail_price * bill_sale_detail_qty) AS money
          FROM tb_bill_sale_detail
          LEFT JOIN tb_bill_sale ON tb_bill_sale_detail.bill_id = tb_bill_sale.bill_sale_id
          WHERE
            MONTH(bill_sale_created_date) = $m
            AND YEAR(bill_sale_created_date) = $y
            AND bill_sale_status IN('pay', 'credit')
        ";
        $rs = Yii::app()->db->createCommand($sql)->queryRow();
        
        return $rs['money'];
    }

    public function getSumPriceByMonthYearGroupProductId($m, $y, $group_product_id) {
        $sql = "
          SELECT SUM(bill_sale_detail_price * bill_sale_detail_qty) AS money
          FROM tb_bill_sale_detail
          LEFT JOIN tb_bill_sale ON tb_bill_sale_detail.bill_id = tb_bill_sale.bill_sale_id
          LEFT JOIN tb_product ON tb_product.product_code = tb_bill_sale_detail.bill_sale_detail_barcode
          WHERE
            MONTH(bill_sale_created_date) = $m
            AND YEAR(bill_sale_created_date) = $y
            AND bill_sale_status IN('pay', 'credit')
            AND group_product_id = $group_product_id
        ";
        $rs = Yii::app()->db->createCommand($sql)->queryRow();
        
        return $rs['money'];
    }

    public function getSumPriceByMonthYearUserId($m, $y, $user_id) {
        $sql = "
          SELECT SUM(bill_sale_detail_price * bill_sale_detail_qty) AS money
          FROM tb_bill_sale_detail
          LEFT JOIN tb_bill_sale ON tb_bill_sale_detail.bill_id = tb_bill_sale.bill_sale_id
          WHERE
            MONTH(bill_sale_created_date) = $m
            AND YEAR(bill_sale_created_date) = $y
            AND bill_sale_status IN('pay', 'credit')
            AND user_id = $user_id
        ";
        $rs = Yii::app()->db->createCommand($sql)->queryRow();
        
        return $rs['money'];
    }

    public function getSumPriceByYearAndMemberId($y, $member_id) {
        $sql = "
          SELECT SUM(bill_sale_detail_price * bill_sale_detail_qty) AS money
          FROM tb_bill_sale_detail
          LEFT JOIN tb_bill_sale ON tb_bill_sale_detail.bill_id = tb_bill_sale.bill_sale_id
          WHERE
            YEAR(bill_sale_created_date) = $y
            AND bill_sale_status IN('pay', 'credit')
            AND member_id = $member_id
        ";
        $rs = Yii::app()->db->createCommand($sql)->queryRow();
        
        return $rs['money'];
    }

}
