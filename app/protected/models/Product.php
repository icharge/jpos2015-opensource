<?php

class Product extends CActiveRecord {

  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function tableName() {
    return "tb_product";
  }

  public function relations() {
    return array(
      'group_product' => array(self::BELONGS_TO, 'GroupProduct', 'group_product_id')
    );
  }

  public function attributeLabels() {
    return array(
      'product_id' => 'id',
      'group_product_id' => 'ประเภทสินค้า',
      'product_code' => 'รหัสสินค้า',
      'product_name' => 'ชื่อสินค้า',
      'product_detail' => 'รายละเอียดเพิ่มเติม',
      'product_created_date' => 'วันที่บันทึก',
      'product_last_update' => 'วันที่แก้ไขล่าสุด',
      'product_quantity' => 'จำนวนคงเหลือ',
      'product_pack_barcode' => 'รหัสแพค',
      'product_total_per_pack' => 'จำนวนต่อแพค',
      'product_expire' => 'ชนิดสินค้า',
      'product_return' => 'สินค้าฝากขาย',
      'product_expire_date' => 'วันหมดอายุสินค้า',
      'product_sale_condition' => 'เงื่อนไขเวลาขาย',
      'product_serial_no' => 'serial สินค้า',
      'product_price' => 'ราคาปลีก',
      'product_price_send' => 'ราคาส่ง',
      'product_price_per_pack' => 'ราคาต่อแพค',
      'product_quantity_of_pack' => 'จำนวนคงเหลือ(แพค)',
      'product_price_buy' => 'ราคาทุน (เฉลี่ย)'
    );
  }

  public function rules() {
    return array(
      array('group_product_id, product_code, product_name, product_price', 'required'),
      array(
        'product_detail,
              product_quantity,
              product_pack_barcode,
              product_total_per_pack,
              product_expire,
              product_return,
              product_expire_date,
              product_sale_condition,
              product_serial_no,
              product_price_send,
              product_price_per_pack,
              product_quantity_of_pack,
              product_price_buy,
              product_tag', 'safe'
      )
    );
  }

  public function beforeValidate() {
    if ($this->isNewRecord) {
      $this->product_last_update = new CDbExpression('NOW()');
    }
    return parent::beforeValidate();
  }

  public function search() {
    return new CActiveDataProvider($this, array(
      'sort' => array('defaultOrder' => 'product_id DESC')
    ));
  }

  public function getProductExpire() {
    if ($this->product_expire == 'expire') {
      return 'สินค้าสด';
    } else {
      return 'ไม่ใช่สินค้าสด';
    }
  }

  public function getProductReturn() {
    if ($this->product_return == 'in') {
      return 'สินค้าของร้าน';
    }
    return 'สินค้าฝากขาย';
  }

  public function getGroupProductByGroupProductId() {
    $group_product_code = $this->group_product_id;

    $criteria = new CDbCriteria();
    $criteria->condition = "group_product_code = '$group_product_code'";

    return GroupProduct::model()->find($criteria);
  }

  public function getGroupProduct() {
    return GroupProduct::model()->findByAttributes(array(
      "group_product_code" => $this->group_product_id
    ));
  }

  public static function getInfoByBarcode($barcode) {
    $product = Product::model()->findByAttributes(array(
      'product_code' => $barcode
    ));

    $info = array();

    if (!empty($product)) {
      $info['price'] = $product->product_price;
      $info['qty_per_pack'] = $product->product_total_per_pack;
      $info['old_price'] = $product->product_price_buy;
      $info['name'] = $product->product_name;
    } else {
      $info['price'] = 0;
      $info['qty_per_pack'] = 0;
      $info['old_price'] = 0;
      $info['name'] = '';
    }

    return $info;
  }

}
