<?php

class BarcodePrice extends CActiveRecord
{
  /**
   * Returns the static model of the specified AR class.
   * @return CActiveRecord the static model class
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName()
  {
    return 'barcode_prices';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    return array(
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations()
  {
    return array(

    );
  }

  public function getProduct() {
    return Product::model()->findByAttributes(array(
      'product_code' => $this->barcode_fk
    ));
  }

  /**
   * @return array customized attribute labels (name=&gt;label)
   */
  public function attributeLabels()
  {
    return array(
    );
  }
}