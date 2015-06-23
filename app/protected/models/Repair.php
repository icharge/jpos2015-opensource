<?php

class Repair extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'tb_repair';
    }

    public function attributeLabels() {
        return array(
            'repair_id' => 'id',
            'user_id' => 'พนักงานที่รับซ่อม',
            'branch_id' => 'สาขา',
            'repair_date' => 'วันที่เริ่มซ่อม',
            'repair_problem' => 'ปัญหา/อาการ',
            'repair_price' => 'ค่าบริการ',
            'repair_type' => 'ประเภทการซ่อม',
            'repair_original' => 'สาเหตุอาการเสีย',
            'repair_detail' => 'การดำเนินการ',
            'repair_created_date' => 'วันที่รับซ่อม',
            'repair_status' => 'สถานะ',
            'repair_get_date' => 'วันที่รับคืน',
            'repair_complete_date' => 'วันที่ซ่อมเสร็จ',
            'repair_clame_date' => 'วันที่ส่งเคลม',
            'repair_get_name' => 'ผู้มารับสินค้า'
        );
    }

    public function relations() {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id')
        );
    }

    public static function getSearchType() {
        return array(
            'product_code' => 'Barcode สินค้า',
            'product_serial_no' => 'Serial NO'
        );
    }

    public function getRepairType() {
        $arr = array(
            'internal' => 'ซ่อมเอง',
            'center' => 'ส่งศูนย์',
            'external' => 'ส่งซ่อมภายนอก'
        );
        return $arr[$this->repair_type];
    }

    public function getStatus() {
        $arr = array(
            'wait' => 'รอการซ่อม',
            'do' => 'กำลังดำเนินการ',
            'complete' => 'ซ่อมเสร็จแล้ว'
        );
        return $arr[$this->repair_status];
    }
}
