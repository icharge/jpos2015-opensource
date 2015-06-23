<?php

class QuotationDetail extends CActiveRecord {

  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function tableName() {
    return "quotation_details";
  }

  public function getProduct() {
    return Product::model()->findByAttributes(array(
      "product_code" => $this->barcode
    ));
  }

}
