<?php

class SiteController extends Controller {

  public function actionIndex() {
    if (!empty($_POST)) {
      $conditions = array();
      $conditions["user_username"] = $_POST["User"]["user_username"];
      $conditions["user_password"] = $_POST["User"]["user_password"];

      $model = User::model()->findByAttributes($conditions);

      if (!empty($model)) {
      	$expire = time() + (60 * 60 * 24 * 30);
      		
        $user_id = new CHttpCookie("user_id", $model->user_id);
        $user_id->expire = $expire;

        $user_username = new CHttpCookie("user_username", $model->user_username);
        $user_username->expire = $expire;
        
        $user_level = new CHttpCookie("user_level", $model->user_level);
        $user_level->expire = $expire;

        Yii::app()->request->cookies->add("user_id", $user_id);
        Yii::app()->request->cookies->add("user_username", $user_username);
        Yii::app()->request->cookies->add("user_level", $user_level);

        $this->redirect(array("home"));
      } else {
        Yii::app()->user->setFlash('message', 'User Not Found');
      }
    }

    $model = new User();
    $this->render('//site/index', array(
        "model" => $model
    ));
  }

  public function actionHome() {
    $this->render("//site/home");
  }

  public function actionLogout() {
    Yii::app()->request->cookies->remove("user_id");
    Yii::app()->request->cookies->remove("user_username");

    $this->redirect(array("//site/index"));
  }

}

