<?php

class Quotation extends CActiveRecord {

  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function tableName() {
    return "quotations";
  }

  public function relations() {
    return array(
      "user" => array(self::BELONGS_TO, "User", "user_id")
    );
  }
  
}
