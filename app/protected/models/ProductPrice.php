<?php

class ProductPrice extends CActiveRecord {

  public static function model($name = __CLASS__) {
    return parent::model($name);
  }

  public function tableName() {
    return "product_prices";
  }
  
}