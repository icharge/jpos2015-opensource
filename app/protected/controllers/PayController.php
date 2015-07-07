<?php

class PayController extends Controller {

  function checkLogin() {
    if (Yii::app()->request->cookies['user_id'] == null) {
      $this->redirect(array("Site/Index"));
    }
  }

  public function actionIndex() {
    $this->checkLogin();

    $pays = Pay::model()->findAll();

    $this->render('//Pay/Index', array(
      'pays' => $pays,
      'n' => 1,
      'sum' => 0
    ));
  }

  public function actionForm() {
    $this->checkLogin();

    $pay = new Pay();
    $pay->created_at = new CDbExpression('NOW()');

    if (!empty($_POST)) {
      $id = Util::input((int) $_POST['Pay']['id']);

      if (!empty($id)) {
        $pay = Pay::model()->findByPk((int) $id);
      }

      $pay->pay_type_id = Util::input($_POST['Pay']['pay_type_id']);
      $pay->name = Util::input($_POST['Pay']['name']);
      $pay->remark = Util::input($_POST['Pay']['remark']);
      $pay->price = Util::input($_POST['Pay']['price']);

      if ($pay->save()) {
        $this->redirect(array('Index'));
      }
    }

    $this->render('//Pay/Form', array(
      'pay' => $pay
    ));
  }

  public function actionEdit($id) {
    $this->checkLogin();

    $pay = Pay::model()->findByPk((int) $id);

    if ($pay) {
      $this->render('//Pay/Form', array(
        'pay' => $pay
      ));
    }
  }

  public function actionDelete($id) {
    $this->checkLogin();

    $pay = Pay::model()->findByPk((int) $id);

    if ($pay) {
      if ($pay->delete()) {
        $this->redirect(array('Index'));
      }
    }
  }

}