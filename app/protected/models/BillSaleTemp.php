<?php

class BillSaleTemp extends CActiveRecord {
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function tableName() {
        return 'tb_bill_sale_temp';
    }
    
    public function attributeLabels() {
        return array(
            'product_total' => 'จำนวน',
            'product_name' => 'ชื่อสินค้า',
            'product_price' => 'ราคา',
            'bill_id' => 'รหัสบิล'
        );
    }
}


