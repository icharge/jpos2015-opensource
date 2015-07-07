<?php

class SupportController extends Controller {

  public function actionSubPrice() {
    if (!empty($_POST)) {
      $sub_price = Util::input($_POST['sub_price']);
      $sub_type = Util::input($_POST['sub_type']);
      $sub_price_position = Util::input($_POST['sub_price_position']);
      
      if ($sub_type == "baht") {
        // ส่วนลดแบบ บาท
        $field = "";

        if ($sub_price_position == "all") {
          $field = "
            product_price = (product_price - $sub_price),
            product_price_send = (product_price_send - $sub_price)
          ";
        } else if ($sub_price_position == "price") {
          $field = "product_price - $sub_price";
        } else if ($sub_price_position == "send") {
          $field = "product_price_send - $sub_price";
        }

        $sql = "
          UPDATE tb_product SET $field 
          WHERE product_price > $sub_price
        ";

        Yii::app()->db->createCommand($sql)->execute();
      } else {
        // ส่วนลดแบบ %
        $field = "";

        if ($sub_price_position == "all") {
          $field = "
            product_price = (product_price - ((product_price * $sub_price) / 100)),
            product_price_send = (product_price_send - ((product_price_send * $sub_price) / 100))
          ";
        } else if ($sub_price_position == "price") {
          $field = "product_price = (product_price - ((product_price * $sub_price) / 100))";
        } else if ($sub_price_position == "send") {
          $field = "product_price_send = (product_price_send - ((product_price_send * $sub_price) / 100))";
        }

        $sql = "
          UPDATE tb_product SET $field 
          WHERE product_price > $sub_price
        ";

        Yii::app()->db->createCommand($sql)->execute();
      }
      
      Yii::app()->user->setFlash("message", "บันทึกส่วนลดเรียบร้อยแล้ว");
      $this->redirect(array("SubPrice"));
    }

    $products = new CActiveDataProvider("Product", array(
      "criteria" => array(
        "order" => "product_id DESC"
      )
    ));

    $this->render("//Support/SubPrice", array(
      "products" => $products
    ));
  }

  public function actionScore() {
    $configSoftware = ConfigSoftware::model()->find();

    if (!empty($_POST)) {
      $configSoftware->score = Util::input($_POST['score']);
      $configSoftware->save();
    }

    $this->render('//Support/Score', array(
      'configSoftware' => $configSoftware
    ));
  }

}