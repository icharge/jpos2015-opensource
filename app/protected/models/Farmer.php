<?php

class Farmer extends CActiveRecord {

  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function tableName() {
    return "tb_farmer";
  }

  public function attributeLabels() {
    return array(
        'farmer_id' => 'id',
        'farmer_name' => 'ชื่อผู้จัดจำหน่าย',
        'farmer_tel' => 'เบอร์โทร',
        'farmer_address' => 'ที่อยู่',
        'farmer_created_date' => 'วันที่บันทึก'
    );
  }

  public function rules() {
    return array(
        array('farmer_name', 'required'),
        array('farmer_id, farmer_tel, farmer_address, farmer_created_date', 'safe')
    );
  }

  public function beforeValidate() {
    if ($this->isNewRecord) {
      $this->farmer_created_date = new CDbExpression("NOW()");
    }
    return parent::beforeValidate();
  }

  public function search() {
    return new CActiveDataProvider($this);
  }

  public static function getOptions() {
    $model = Farmer::model()->findAll();
    $arr = array();

    foreach ($model as $r) {
      $arr[$r->farmer_id] = $r->farmer_name;
    }
    return $arr;
  }

}

