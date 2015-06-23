<?php

class Pay extends CActiveRecord
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
    return 'pays';
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
      'PayType' => array(self::BELONGS_TO, 'PayType', 'pay_type_id')
    );
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