<?php

class BillImportDetail extends CActiveRecord {
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function tableName() {
        return "tb_bill_import_detail";
    }
    
    public function attributeLabels() {
        return array(
            'bill_import_detail_id' => 'id',
            'bill_import_code' => 'รหัสบิล',
            'product_id' => 'รหัสสินค้า',
            'box_id' => 'รหัสกล่อง',
            'import_bill_detail_product_qty' => 'จำนวนที่รับเข้า',
            'import_bill_detail_price' => 'ราคาต่อหน่วย',
            'import_bill_detail_qty' => 'จำนวนที่รับเข้าจริง'
        );
    }
    
    public function rules() {
        return array(
            array('product_id, import_bill_detail_product_qty', 'required'),
            array('
							bill_import_detail_id, 
							bill_import_code, 
							import_bill_detail_price, 
							box_id, 
							import_bill_detail_qty
						', 'safe')
        );
    }
    
    public function relations() {
        return array(
            'product' => array(self::BELONGS_TO, 'Product', 'product_id')
        );
    }
    
    public function search($bill_import_id) {
        $criteria = new CDbCriteria();
        $criteria->condition = "bill_import_code = '$bill_import_id'";
        $criteria->order = 'bill_import_detail_id DESC';
        
        return new CActiveDataProvider('BillImportDetail', array(
            'criteria' => $criteria
        ));
    }
    
}
