<?php

class PayTypeController extends CController {

  function checkLogin() {
    if (Yii::app()->request->cookies['user_id'] == null) {
      $this->redirect(array("Site/Index"));
    }
  }

  public function actionIndex() {
    $this->checkLogin();

    $payTypes = PayType::model()->findAll();

    $this->render('//PayType/Index', array(
      'payTypes' => $payTypes
    ));        
  }

  public function actionForm() {
    $this->checkLogin();

    $payType = new PayType();

    if (!empty($_POST)) {
      $id = Util::input($_POST['PayType']['id']);

      if (!empty($id)) {
        $payType = PayType::model()->findByPk((int) $id);
      }
      
      $payType->name = Util::input($_POST['PayType']['name']);
      $payType->remark = Util::input($_POST['PayType']['remark']);

      if ($payType->save()) {
        $this->redirect(array('Index'));
      }
    }

    $this->render('//PayType/Form', array(
      'payType' => $payType
    ));
  }

  public function actionDelete($id) {
    $this->checkLogin();

    $payType = PayType::model()->findByPk((int) $id);

    if ($payType) {
      if ($payType->delete()) {
        $this->redirect(array('Index'));
      }
    }
  }

  public function actionEdit($id) {
    $this->checkLogin();

    $payType = PayType::model()->findByPk((int) $id);

    if ($payType) {
      $this->render('//PayType/Form', array(
        'payType' => $payType
      ));
    }
  }

  public function actionInfo() {
    $this->checkLogin();
    $id = $_POST['id'];

    $payType = PayType::model()->findByPk((int) $id);

    if ($payType) {
      echo CJSON::encode($payType);
    } else {
      echo 'id = '.$id;
    }
  }

}