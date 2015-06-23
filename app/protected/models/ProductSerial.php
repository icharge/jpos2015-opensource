<?php

class ProductSerial extends CActiveRecord {

  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function tableName() {
    return 'tb_product_serial';
  }

  public function relations() {
    return array(
        'product' => array(self::BELONGS_TO, 'Product', 'product_code'),
        'bill_sale' => array(self::BELONGS_TO, 'BillSale', 'bill_sale_id')
    );
  }

  public function attributeLabels() {
    return array(
        'product_start_date' => 'วันที่เริ่มต้นประกัน',
        'product_expire_date' => 'วันหมดประกัน'
    );
  }

  public static function getExpireStatus($product_expire_date) {
    if ($product_expire_date != '0000-00-00 00:00:00') {
      $exp_date = explode('-', $product_expire_date);

      if (count($exp_date) > 3) {
        $exp_y = $exp_date[0];
        $exp_m = $exp_date[1];
        $exp_d = $exp_date[2];

        $y = date("Y");
        $m = date("m");
        $d = date("d");

        // more year
        if ($exp_y > $y) {
          return false;
        }

        // same year
        if ($exp_y == $y) {
          // more month
          if ($exp_m > $m) {
            return false;
          }

          // same month
          if ($exp_m == $m) {
            // more day and same day
            if ($exp_d >= $d) {
              return false;
            }
          }
        }

        return true;    // expire
      }

      return false;
    }

    return false;
  }

}

