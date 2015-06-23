<?php

class GroupProduct extends CActiveRecord {

  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function tableName() {
    return "tb_group_product";
  }

  public function attributeLabels() {
    return array(
        "group_product_id" => "id",
        "group_product_code" => "รหัสประเภทสินค้า",
        "group_product_name" => "ชื่อประเภทสินค้า",
        "group_product_detail" => "รายละเอียดเพิ่มเติม",
        "group_product_created_date" => "วันที่บันทึกรายการ",
        "group_product_last_update" => "วันที่แก้ไขล่าสุด"
    );
  }

  public function rules() {
    return array(
        array('group_product_code, group_product_name', 'required'),
        array('group_product_id, group_product_detail', 'safe')
    );
  }

  public function beforeValidate() {
    if ($this->isNewRecord) {
      $this->group_product_created_date = new CDbExpression("NOW()");
      $this->group_product_last_update = new CDbExpression("NOW()");
    }
    return parent::beforeValidate();
  }

  public function search() {
    $dataProvider = new CActiveDataProvider($this);
    return $dataProvider;
  }

}
