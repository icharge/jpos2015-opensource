<?php

class DialogController extends CController {

  public $layout = '//layouts/dialog';

  public function actionDialogGroupProduct() {
    $criteria = new CDbCriteria();
    $criteria->order = 'group_product_name';

    $model = GroupProduct::model()->findAll($criteria);

    $this->render('//Dialog/DialogGroupProduct', array(
        'model' => $model
    ));
  }

  public function actionDialogProduct($find_on_page_quotation = false) {
    $this->render('//Dialog/DialogProduct', array(
      'find_on_page_quotation' => $find_on_page_quotation
    ));
  }

  public function actionGridProduct() {
    $search = null;
    $criteria = new CDbCriteria();

    if (!empty($_POST)) {
      $search = Util::input($_POST['search']);

      $criteria->condition = "
        product_code LIKE(:search)
        OR product_name LIKE(:search)
      ";

      $criteria->params = array(
        'search' => '%'.$search.'%'
      );
    }

    $product = new Product();
    $model = new CActiveDataProvider($product, array(
        'sort' => array(
            'defaultOrder' => 'product_id DESC'
        )
    ));

    $pagination = new CPagination();
    $pagination->setPageSize(40);

    $model->setPagination($pagination);
    $model->setCriteria($criteria);

    $this->render('//Dialog/GridProduct', array(
        'model' => $model
    ));
  }

  public function actionDialogMember() {
    $this->render('//Dialog/DialogMember');
  }

  public function actionDialogMemberGrid() {
    if (!empty($_POST)) {
      $model = new CActiveDataProvider('Member', array(
        'pagination' => false
      ));

      if (!empty($_POST)) {
        $search = Util::input($_POST['search']);
        $criteria = new CDbCriteria();
        $criteria->compare('member_code', $search, true, 'OR');
        $criteria->compare('member_name', $search, true, 'OR');
        $criteria->compare('member_tel', $search, true, 'OR');
        $criteria->compare('member_address', $search, true, 'OR');

        $model->setCriteria($criteria);
      }

      $this->render('//Grid/GridMember', array(
          'model' => $model
      ));
    }
  }

  public function actionDialogEndSale() {
    $total = 0;

    $this->renderPartial('//Dialog/DialogEndSale', array(
      'total' => $total
    ));
  }

  public function actionDialogPrintSlip($bill_id, $input, $return_money) {
    // organization data
    $org = Organization::model()->find();

    $billSale = BillSale::model()->find(array(
      'limit' => 1,
      'order' => 'bill_sale_id DESC',
      'condition' => "bill_sale_status = 'pay'"
    ));

    $bill_id = $billSale->bill_sale_id;

    // bill_sale_detail data
    $billSaleDetail = BillSaleDetail::model()->findAll(array(
      'condition' => 'bill_id = :bill_id',
      'params' => array(
        'bill_id' => $bill_id
      ),
      'order' => 'bill_sale_detail_id'
    ));

    // render page
    $this->renderPartial('//Report/Slip', array(
      'org' => $org,
      'billSale' => $billSale,
      'billSaleDetail' => $billSaleDetail,
      'input' => $input,
      'return_money' => $return_money
    ));
  }

  public function actionDialogBillSendProduct($bill_type = "send") {
    // organization data
    $org = Organization::model()->find();

    // bill_sale data
    $billSale = BillSale::model()->find(array(
      'limit' => 1,
      'order' => 'bill_sale_id DESC'
    ));

    // bill_sale_detail data
    $billSaleDetail = BillSaleDetail::model()->findAll(array(
      'condition' => "bill_id = {$billSale->bill_sale_id}"
    ));

    // member
    if ($billSale->member_id > 0) {
      $criteria = new CDbCriteria();
      $criteria->compare('member_code', @$billSale->member->member_code);

      $member = Member::model()->find($criteria);
    } else {
      $member = null;
    }

    $this->renderPartial('//Report/BillSendProduct', array(
        'org' => $org,
        'billSale' => $billSale,
        'billSaleDetail' => $billSaleDetail,
        'member' => $member,
        'billType' => $bill_type
    ));
  }

  public function actionDialogBillDrop() {
    // object
    $org = Organization::model()->find();
    $hidden_member_code = Yii::app()->session['hidden_member_code'];

    // member
    $member = Member::model()->findByAttributes(array(
        'member_code' => $hidden_member_code
    ));

    // update bill_sale
    $bill_sale_ids = Yii::app()->session['bill_sale_ids'];
    foreach ($bill_sale_ids as $id) {
      $model = BillSale::model()->findByPk((int) $id);
      $model->bill_sale_drop_bill_date = new CDbExpression("NOW()");
      $model->save();
    }

    // render
    $this->renderPartial('//Report/BillDrop', array(
        'org' => $org,
        'member' => $member
    ));
  }

  function actionDialogSerial() {
    $productSerials = new CActiveDataProvider('ProductSerial', array(
      'criteria' => array(
        'condition' => 'serial_no != 0',
        'order' => 'id DESC',
        'limit' => 100
      )
    ));

    $this->render('//Dialog/DialogSerial', array(
        'productSerials' => $productSerials
    ));
  }

  function actionDialogUser() {
    $model = new CActiveDataProvider('User', array(
        'pagination' => array(
            'pageSize' => 20
        )
    ));

    if (!empty($_POST)) {
      $search = Util::input($_POST['search']);
      $criteria = new CDbCriteria();
      $criteria->compare('user_name', $search, true, 'OR');
      $criteria->compare('user_tel', $search, true, 'OR');

      $model->setCriteria($criteria);
    }

    $this->render('//Dialog/DialogUser', array(
        'model' => $model
    ));
  }

  function actionDialogBranch() {
    $model = new CActiveDataProvider('Branch');
    $this->render('//Dialog/DialogBranch', array(
        'model' => $model
    ));
  }

  function actionDialogBillAddVat() {
    $org = Organization::model()->find();

    $criteria = new CDbCriteria();
    $criteria->order = 'bill_sale_id DESC';
    $criteria->limit = 1;
    $criteria->compare('bill_sale_status', 'pay');

    $billSale = BillSale::model()->find($criteria);

    $this->render('//Dialog/DialogBillAddVat', array(
        'org' => $org,
        'billSale' => $billSale
    ));
  }

  function actionReportSalePerDayPdf() {
    $params = array();

    $result = TempSalePerDay::model()->findAll();
    $date_find = Yii::app()->session['date_find'];

    if (!empty($result)) {
      $branch_id = Yii::app()->session['branch_id'];

      $params['result'] = $result;
      $params['date_find'] = Util::mysqlToThaiDate($date_find);
      $params['sale_condition_cash'] = Yii::app()->session['sale_condition_cash'];
      $params['sale_condition_credit'] = Yii::app()->session['sale_condition_credit'];
      $params['has_bonus_no'] = Yii::app()->session['has_bonus_no'];
      $params['has_bonus_yes'] = Yii::app()->session['has_bonus_yes'];
      $params['n'] = 1;
      $params['branch'] = Branch::model()->findByPk((int) $branch_id);
    }

    $this->render('//Report/ReportSalePerDayPdf', $params);
  }

  public function actionReportSalePerDayExcel() {
    $tempSalePerDays = TempSalePerDay::model()->findAll();

    $output = "";

    foreach ($tempSalePerDays as $row) {
      $output .= "{$row->no},{$row->sale_date},{$row->bill_id},{$row->barcode},{$row->name},{$row->bill_status},{$row->price},{$row->sale_price},{$row->price_old},{$row->bonus_per_unit},{$row->qty},{$row->total_bonus},{$row->total_income}\n";
    }

    file_put_contents('report-sale-per-day.csv', $output);
  }

  public function actionGetRepairSearch() {
    if (!empty($_POST)) {
      $search = $_POST['search'];

      $repairs = new CActiveDataProvider('Repair', array(
        'criteria' => array(
          'condition' => '
            product_code LIKE(:search)
            OR repair_tel LIKE(:search)
            OR repair_name LIKE(:search)
            OR repair_product_name LIKE(:search)
          ',
          'params' => array(
            'search' => '%'.$search.'%'
          ),
          'order' => 'repair_id DESC'
        )
      ));

      $this->render('//Basic/GetRepairSearch', array(
        'repairs' => $repairs
      ));
    }
  }

  public function actionPayType() {
    $payTypes = PayType::model()->findAll();
    $this->renderPartial('//Dialog/PayType', array(
      'payTypes' => $payTypes
    ));
  }

}

