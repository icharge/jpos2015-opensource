<?php

class BillImport extends CActiveRecord {

    private $SUM_ALL;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return "tb_bill_import";
    }

    public function attributeLabels() {
        return array(
            'bill_import_code' => 'รหัสบิล',
            'bill_import_created_date' => 'วันที่รับเข้า',
            'farmer_id' => 'ซื้อมาจาก',
            'bill_import_pay' => 'การชำระเงิน',
            'bill_import_remark' => 'หมายเหตุ',
            'bill_import_pay_status' => 'สถานะ',
            'bill_import_pay_date' => 'วันที่ชำระเงิน',
            'branch_id' => 'รับเข้าที่สาขา',
            'from_branch_id' => 'รับเข้าจากสาขา'
        );
    }

    public function rules() {
        return array(
            array('bill_import_code', 'unique'),
            array('
							bill_import_code,
							bill_import_created_date,
							farmer_id,
							bill_import_pay,
							branch_id
						', 'required'),
            array('
							bill_import_pay_date,
							from_branch_id,
							bill_import_pay_status,
							bill_import_remark
						', 'safe')
        );
    }

    public function relations() {
        return array(
            'farmer' => array(self::BELONGS_TO, 'Farmer', 'farmer_id'),
            'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id'),
            'from_branch' => array(self::BELONGS_TO, 'Branch', 'from_branch_id')
        );
    }

    public function buttonImportProduct($data, $row) {
        $text = $data->bill_import_code;
        $url = array(
					"Basic/BillImportDetail",
					"bill_import_code" => $data->bill_import_code
				);

        return CHtml::link($text, $url, array(
            "class" => "btn btn-info"
        ));
    }

		public function getImportPayName($import_pay_name) {
			$arr = BillImport::getImportPay();
			return $arr[$import_pay_name];
		}

		public function getPayStatusName($pay_status) {
			$arr = BillImport::getPayStatus();
			return $arr[$pay_status];
		}

		public static function getImportPay() {
			return array(
				'cash' => 'เงินสด',
				'credit' => 'เงินเชื่อ'
			);
		}

		public static function getPayStatus() {
			return array(
				'wait' => 'รอชำระ',
				'pay' => 'ชำระแล้ว'
			);
		}

		public function getSumReport($data, $row) {

        $criteria = new CDbCriteria();
        $criteria->select = 'SUM(import_bill_detail_price * import_bill_detail_qty) AS SUM_ALL';
        $criteria->join = 'INNER JOIN tb_bill_import_detail AS c ON c.bill_import_code = t.bill_import_code';
        $criteria->condition = 'bill_import_pay = "credit"';
        $criteria->condition = 'bill_import_pay_status = "wait"';
        $criteria->compare("t.farmer_id", $data->farmer_id);
        $criteria->group = 't.farmer_id';

        $result = BillImport::model()->query($criteria);

        return number_format($result->SUM_ALL, 2);

    }

}
