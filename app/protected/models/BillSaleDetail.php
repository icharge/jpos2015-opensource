<?php

class BillSaleDetail extends CActiveRecord {
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function tableName() {
        return 'tb_bill_sale_detail';
    }
    
    public function attributeLabels() {
        return array(
            'bill_sale_detail_id' => 'id',
            'bill_id' => 'รหัสบิล',
            'bill_sale_detail_barcode' => 'รหัสสินค้า',
            'bill_sale_detail_price' => 'ราคา',
            'bill_sale_detail_price_vat' => 'ราคา vat',
            'bill_sale_detail_qty' => 'จำนวน'
        );
    }
    
    public function getProduct() {
        return Product::model()->find(array(
            'condition' => "product_code = '{$this->bill_sale_detail_barcode}'"
        ));
    }
}


