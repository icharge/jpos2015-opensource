<?php

class BasicController extends Controller {

  function checkLogin() {
    if (Yii::app()->request->cookies['user_id'] == null) {
      $this->redirect(array("Site/Index"));
    }
  }

  function actionChangeProfile() {
    $this->checkLogin();

    $pk = (int) Yii::app()->request->cookies["user_id"]->value;
    $model = User::model()->findByPk((int) $pk);

    if ($_POST != null) {
      $model->attributes = $_POST["User"];

      if ($model->save()) {
        $this->redirect(array('Site/index'));
      }
    }

    $this->render('//Basic/ChangeProfile', array(
        'model' => $model
    ));
  }

  function actionBillImport($id = null) {
    $this->checkLogin();

    $configSoftware = ConfigSoftware::model()->find();
    $model = new BillImport();

    // SAVE DATA
    if (!empty($_POST)) {
      $pk = Util::input($_POST['BillImport']['bill_import_code']);

      if (!empty($pk)) {
        // FIND BILL
        $model = BillImport::model()->findByPk((int) $pk);

        if (empty($model)) {
          $model = new BillImport();
        }
      }

      // VARIABLE
      $import_pay_date = Util::input($_POST['BillImport']['bill_import_pay_date']);
      $import_created_date = Util::input($_POST['BillImport']['bill_import_created_date']);

      $import_pay_date = Util::thaiToMySQLDate($import_pay_date);
      $import_created_date = Util::thaiToMySQLDate($import_created_date);

      $model->attributes = Util::input($_POST['BillImport']);
      $model->bill_import_pay_date = $import_pay_date;
      $model->bill_import_created_date = $import_created_date;

      // PAY AND SAVE
      $import_pay_status = Util::input($_POST["BillImport"]["bill_import_pay_status"]);

      if ($import_pay_status == "pay" && $import_pay_date == "") {
        $model->bill_import_pay_date = new CDbExpression("NOW()");
      }

      if ($model->save()) {
        $this->redirect(array('BillImport'));
      }
    }

    // BILL IMPORT
    $modelForGrid = new CActiveDataProvider('BillImport', array(
      'criteria' => array(
        'order' => 'bill_import_created_date DESC'
      ),
      'pagination' => array(
        'pageSize' => $configSoftware->items_per_page
      )
    ));

    // DATA FOR EDIT
    if (!empty($id)) {
      $model = BillImport::model()->findByPk((int) $id);

			$created_date = $model->bill_import_created_date;
			$pay_date = $model->bill_import_pay_date;

			$model->bill_import_created_date = Util::mysqlToThaiDate($created_date);
			$model->bill_import_pay_date = Util::mysqlToThaiDate($pay_date);
    }

    // RENDER PAGE
    $this->render('//Basic/BillImport', array(
        'model' => $model,
        'modelForGrid' => $modelForGrid
    ));
  }

  // DELETE BILL IMPORT
  public function actionBillImportDelete($id) {
    $this->checkLogin();

    BillImport::model()->deleteByPk((int) $id);
    $this->redirect(array('BillImport'));
  }

  // BILL IMPORT DETAIL
  public function actionBillImportDetail($bill_import_code = null, $id = null) {
    $this->checkLogin();

    $configSoftware = ConfigSoftware::model()->find();

    // CHECK $bill_import_code
    if (empty($bill_import_code)) {
      $bill_import_code = Util::input($_POST['BillImportDetail']['bill_import_code']);
    }

    // CREATE OBJECT OF BillImport
    $modelBillImport = BillImport::model()->findByPk($bill_import_code);
    $modelBillImportDetail = new BillImportDetail();
    $modelBillImportDetail->bill_import_code = $bill_import_code;

    // SAVE
    if (!empty($_POST)) {
      $pk = (int) Util::input($_POST['BillImportDetail']['bill_import_detail_id']);
      $bill_import_code = (int) Util::input($_POST['BillImportDetail']['bill_import_code']);

      // CREATE OBJECT OF BillImportDetail
      if (!empty($pk)) {
        $model = BillImportDetail::model()->findByPk($pk);
      } else {
        $model = new BillImportDetail();
      }

      // QTY
      $qty = Util::input($_POST['BillImportDetail']['import_bill_detail_product_qty']);
      $qty_before = Util::input($_POST['qty_before']);
      $newQty = 0;

      if (!empty($qty_before)) {
        if ($qty_before > $qty) {
          // -
          $newQty = -($qty_before - $qty);
        } else {
          // +
          $newQty = ($qty - $qty_before);
        }
      }

      // UPDATE STOCK
      $codeProduct = (int) Util::input($_POST['BillImportDetail']['product_id']);
      $attribute = array();
      $attribute['product_code'] = $codeProduct;
      $product = Product::model()->findByAttributes($attribute);

      if (empty($product)) {
        $barcodePrice = BarcodePrice::model()->findByAttributes(array(
          'barcode' => $codeProduct
        ));

        if (!empty($barcodePrice)) {
          $product = $barcodePrice->getProduct();
        }
      }

      if (!empty($pk)) {
        $product->product_quantity += $newQty;
      } else {
        $product->product_quantity += $qty;
      }

      // update by barcode_prices
      if (!empty($_POST['qty_sub_stock'])) {
        $qty_sub_stock = Util::input($_POST['qty_sub_stock']);
        $qty_input = Util::input($_POST['BillImportDetail']['import_bill_detail_product_qty']);
        $qty_total = ($qty_input * $qty_sub_stock);
        $qty_add = $product->product_quantity + $qty_total;
        $qty_add -= $qty_input;

        $product->product_quantity = $qty_add; 
      }

      $product->save();

      // SAVE bill_import_detail
      $model->attributes = Util::input($_POST["BillImportDetail"]);
      $model->import_bill_detail_qty = ($qty * $product->product_total_per_pack);
      $model->product_id = $product->product_id;
      $model->import_bill_detail_code = Util::input($_POST['BillImportDetail']['product_id']);
      $model->import_bill_detail_qty_per_pack = $product->product_total_per_pack;

      // add from barcode_prices
      if (!empty($_POST['qty_sub_stock'])) {
        $model->import_bill_detail_qty = ($qty * Util::input($_POST['qty_sub_stock']));
        $model->import_bill_detail_qty_per_pack = Util::input($_POST['qty_sub_stock']);
      }

      // DEFAULT PRICE
      if (empty($_POST['BillImportDetail']['import_bill_detail_price'])) {
        $model->import_bill_detail_price = $product->product_price;
      }

      // SAVE
      if ($model->save()) {
        $this->redirect(array(
            'BillImportDetail',
            'bill_import_code' => $bill_import_code
        ));
      }
    }

    // DATA FOR EDIT
    if (!empty($id)) {
      $modelBillImportDetail = BillImportDetail::model()->findByPk((int) $id);
    }

    // sum
    $sumQty = 0;
    $sumPrice = 0;

    // RENDER
    $pagination = new CPagination();
    $pagination->setPageSize($configSoftware->items_per_page);

    $dataProvider = $modelBillImportDetail->search($modelBillImport->bill_import_code);
    $dataProvider->setPagination($pagination);

    $this->render('//Basic/BillImportDetail', array(
        'modelBillImport' => $modelBillImport,
        'model' => $modelBillImportDetail,
        'sumQty' => $sumQty,
        'sumPrice' => $sumPrice,
        'dataProvider' => $dataProvider
    ));
  }

  // BILL IMPORT DETAIL DELETE
  public function actionBillImportDetailDelete($id, $bill_import_code) {
    $this->checkLogin();

    // model
    $model = BillImportDetail::model()->findByPk((int) $id);
    $qty = $model->import_bill_detail_product_qty;

    // update stock
    if (!empty($model->product)) {
      $barcodePrice = BarcodePrice::model()->findByAttributes(array(
        'barcode' => $model->import_bill_detail_code
      ));

      if (!empty($barcodePrice)) {
        $totalQty = ($model->import_bill_detail_qty_per_pack * $model->import_bill_detail_product_qty);
        $model->product->product_quantity -= $totalQty;
      } else {
        $model->product->product_quantity -= $qty;
      }

      $model->product->save();
    }

    // delete
    $model->deleteByPk((int) $id);

    $this->redirect(array('BillImportDetail',
        'bill_import_code' => $bill_import_code
    ));
  }

	// SALE
  public function actionSale() {
    $this->checkLogin();

    $model = new BillSale();

    if (!empty($_POST)) {
      Yii::app()->session['sessionBillSale'] = $_POST;

      // BILL SALE DETAIL
      $arrayBillSaleDetail = Yii::app()->session['billSaleDetail'];

      if (empty($arrayBillSaleDetail)) {
        $arrayBillSaleDetail = array();
      }
      $size = count($arrayBillSaleDetail);

      // ADD bill_sale_detail ITEMS
      $productCode = Util::input($_POST['product_code']);
      $productQty = Util::input($_POST['product_qty']);
      $code = "";
      $price = 0;
      $qty_per_pack = 0;

      $product = Product::model()->findByAttributes(array(
        'product_code' => $productCode
      ));

      $sale_condition = Util::input($_POST['sale_condition']);

      if (empty($product)) {
        $product = Product::model()->findByAttributes(array(
          'product_pack_barcode' => $productCode
        ));

        if (!empty($product)) {
          $code = $product->product_pack_barcode;
          $price = $product->product_price_per_pack;
          $qty_per_pack = $product->product_total_per_pack;
        }
      } else {
        // FIND PRICE OF PRODUCT
        if ($sale_condition == 'many') {    // กรณีขายส่ง
          $price = $product->product_price_send;

          // ค้นหาราคา ที่กำหนดไว้ใน product_prices
          $productPrice = ProductPrice::model()->find(array(
            "condition" => "
              product_barcode = :productCode
              AND (qty <= :productQty AND qty_end >= :productQty)
            ",
            "params" => array(
              "productCode" => $productCode,
              "productQty" => $productQty
            )
          ));

          if (!empty($productPrice)) {
            $price = $productPrice->price_send;
          }
        } else {
          // กรณีขายปลีก
          $price = $product->product_price;

          // ค้นหาราคา ที่กำหนดไว้ใน product_prices
          $productPrice = ProductPrice::model()->find(array(
            "condition" => "
              product_barcode = :productCode
              AND (qty <= :productQty AND qty_end >= :productQty)
            ",
            "params" => array(
              "productCode" => $productCode,
              "productQty" => $productQty
            )
          ));

          if (!empty($productPrice)) {
            $price = $productPrice->price;
          }
        }

        $code = $product->product_code;
        $qty_per_pack = 1;
      }

      // หาราคา ตามตาราง barcode_prices
      $barcodePrice = BarcodePrice::model()->findByAttributes(array(
        'barcode' => $productCode
      ));

      if (!empty($barcodePrice)) {
        $price = $barcodePrice->price;
        $product = $barcodePrice->getProduct();
        $qty_per_pack = $barcodePrice->qty_sub_stock;

        $product->product_name = $product->product_name.' ( '.$barcodePrice->name.' )';
      }

      // FOUND PRODUCT
      if (!empty($product)) {
        if (!empty($_POST['hidden_product_codes'])) {
          // second item
          $hidden_product_codes = Util::input($_POST['hidden_product_codes']);
          $hidden_product_name = Util::input($_POST['hidden_product_name']);
          $hidden_qty_per_pack = Util::input($_POST['hidden_qty_per_pack']);
          $serials = Util::input($_POST['serials']);
          $prices = Util::input($_POST['prices']);
          $qtys = Util::input($_POST['qtys']);

          $arr = array();

          // old item
          for ($i = 0; $i < count($hidden_product_codes); $i++) {
            $arr[] = array(
              'product_qty' => $qtys[$i],
              'product_code' => $hidden_product_codes[$i],
              'product_name' => $hidden_product_name[$i],
              'product_price' => $prices[$i],
              'product_serial_no' => $serials[$i],
              'product_expire_date' => Util::input($_POST['product_expire_date']),
              'product_qty_per_pack' => $hidden_qty_per_pack[$i],
              'sale_status' => Util::input($_POST['sale_status']),
              'sale_condition' => Util::input($_POST['sale_condition']),
              'has_bonus' => 'normal',
              'bill_sale_created_date' => Util::input($_POST['BillSale']['bill_sale_created_date'])
            );
          }

          // add item
          $arr[] = array(
            'product_qty' => Util::input($_POST['product_qty']),
            'product_code' => Util::input($_POST['product_code']),
            'product_name' => $product->product_name,
            'product_price' => $price,
            'product_serial_no' => Util::input($_POST['product_serial_no']),
            'product_expire_date' => Util::input($_POST['product_expire_date']),
            'product_qty_per_pack' => $qty_per_pack,
            'sale_status' => Util::input($_POST['sale_status']),
            'sale_condition' => Util::input($_POST['sale_condition']),
            'has_bonus' => 'normal',
            'bill_sale_created_date' => Util::input($_POST['BillSale']['bill_sale_created_date'])
          );     
        } else { 
          // add item
          $arr[] = array(
            'product_qty' => Util::input($_POST['product_qty']),
            'product_code' => Util::input($_POST['product_code']),
            'product_name' => $product->product_name,
            'product_price' => $price,
            'product_serial_no' => Util::input($_POST['product_serial_no']),
            'product_expire_date' => Util::input($_POST['product_expire_date']),
            'product_qty_per_pack' => $qty_per_pack,
            'sale_status' => Util::input($_POST['sale_status']),
            'sale_condition' => Util::input($_POST['sale_condition']),
            'has_bonus' => 'normal',
            'bill_sale_created_date' => Util::input($_POST['BillSale']['bill_sale_created_date'])
          );
        }
        
        Yii::app()->session['billSaleDetail'] = $arr;
        Yii::app()->session['billSaleCreatedDate'] = Util::input($_POST['BillSale']['bill_sale_created_date']);

        $this->redirect(array('Sale'));
      }
    }

    // RENDER
    $this->render('//Basic/Sale', array(
        'model' => $model
    ));
  }

	// SALE DELETE
  public function actionSaleDelete($index) {
    $this->checkLogin();

    $billSaleDetail = Yii::app()->session['billSaleDetail'];

    // remove product item from array
    for ($i = 0; $i < count($billSaleDetail); $i++) {
      if ($i == (int) $index) {
        $billSaleDetail[$i] = null;
      }
    }

    // clear empty array item
    $newArray = array();
    for ($i = 0; $i < count($billSaleDetail); $i++) {
      if (!empty($billSaleDetail[$i])) {
        $newArray[count($newArray)] = $billSaleDetail[$i];
      }
    }

    // add new array to session
    Yii::app()->session['billSaleDetail'] = $newArray;
    $this->redirect(array('Sale'));
  }

	// END SALE
  public function actionEndSale() {
    $this->checkLogin();

    $saleTemps = SaleTemp::model()->findAllByAttributes(array(
      'user_id' => (int) Yii::app()->request->cookies['user_id']->value
    ));

    if (!empty($saleTemps)) {
      // find member_id
      $member_code = Util::input($_POST['txt_member_code']);
      $member_id = 0;

      if (!empty($member_code)) {
        $member = Member::model()->findByAttributes(array(
          'member_code' => $member_code
        ));

        if (!empty($member)) {
          $member_id = $member->member_id;
        }
      }

      // sale_status
      if ($_POST['sale_status'] == 'cash') {
        $saleStatus = 'pay';
      } else {
        $saleStatus = 'credit';
      }
      
			$created_date = Util::input($_POST['BillSale']['bill_sale_created_date']);
      $created_date = Util::thaiToMySQLDate($created_date);

      // bill sale
      $modelBillSale = new BillSale();
      $modelBillSale->bill_sale_created_date = $created_date;
      $modelBillSale->bill_sale_status = $saleStatus;
      $modelBillSale->member_id = $member_id;
      $modelBillSale->bill_sale_vat = Util::input($_POST['bill_sale_vat']);
      $modelBillSale->user_id = (int) Yii::app()->request->cookies['user_id']->value;
      $modelBillSale->branch_id = Util::input($_POST['BillSale']['branch_id']);
      $modelBillSale->bonus_price = Util::input($_POST['bonus_price']);
      $modelBillSale->out_vat = Util::input($_POST['out_vat']);
      $modelBillSale->vat_type = Util::input($_POST['hidden_vat_type']);
      $modelBillSale->input_money = Util::input($_POST['hidden_input']);
      $modelBillSale->return_money = Util::input($_POST['hidden_return_money']);
      $modelBillSale->total_money = Util::input($_POST['hidden_total']);

      if ($_POST['sale_status'] == 'cash') {
        $_time = date("h:i:s");
        $modelBillSale->bill_sale_pay_date = $created_date." ".$_time;
      }

      if ($modelBillSale->save()) {
        // store data bill_sale_detail from session to database
        $i = 0;
        $qtys = $_POST['qtys'];

        foreach ($saleTemps as $saleTemp) {          
          $qty_for_sub_stock = $qtys[$i];

          $model = new BillSaleDetail();
          $model->bill_id = $modelBillSale->bill_sale_id;
          $model->bill_sale_detail_barcode = $saleTemp->barcode;
          $model->bill_sale_detail_price = $saleTemp->price;
          $model->bill_sale_detail_qty = $saleTemp->qty;
          $model->bill_sale_detail_price_vat = ($saleTemp->price * .07);
          $model->old_price = $saleTemp->old_price;
          $model->save();

          // sub stock
          $product_code = $saleTemp->barcode;

          // find by barcode
          $product = Product::model()->findByAttributes(array(
            'product_code' => $product_code
          ));

          if (empty($product)) {
            // find by pack barcode
            $product = Product::model()->findByAttributes(array(
                'product_pack_barcode' => $product_code
            ));
          }

          if (empty($product)) {
            // find by barcode_price
            $barcodePrice = BarcodePrice::model()->findByAttributes(array(
              'barcode' => $product_code
            ));
            $product = $barcodePrice->getProduct();
          }

          if (!empty($saleTemp->qty_per_pack)) {
            $qty_for_sub_stock = ($saleTemp->qty_per_pack * $qty_for_sub_stock);
          }

					$qty = ($product->product_quantity - $qty_for_sub_stock);
        
          $product->product_quantity = $qty;
          $product->save();

          $i++;
        }

        // save to tb_product_serial
        $serials = $_POST['serials'];
        $hidden_product_codes = $_POST['hidden_product_codes'];

        if (!empty($serials)) {
          $i = 0;

          foreach ($serials as $serial) {
            $product_code = $hidden_product_codes[$i];

            $productSerial = new ProductSerial();
            $productSerial->product_code = $product_code;
            $productSerial->serial_no = $serial;
            $productSerial->product_start_date = new CDbExpression('NOW()');
            $productSerial->bill_sale_id = $modelBillSale->bill_sale_id;

            // expire date
            if (!empty($r['product_expire_date'])) {
              $expire_date = Util::thaiToMySQLDate($r['product_expire_date']);
              $productSerial->product_expire_date = $expire_date;
            }

            $productSerial->save();
          }
        }

        // keep last bill_id
        $output = array(
          'last_bill_id' => $modelBillSale->bill_sale_id,
          'message' => 'success'
        );

        echo CJSON::encode($output);
      } else {
        echo 'can not save modal bill sale';
      }
    } else {
      echo 'saleTemp is a empty';
    }
  }

	// SALE RESET
  public function actionSaleReset() {
    $this->checkLogin();

    Yii::app()->session['billSaleDetail'] = null;
    Yii::app()->session['sessionBillSale'] = null;

    $this->redirect(array('Sale'));
  }

	// MANAGE BILL
  public function actionManageBill() {
    $this->checkLogin();

		// BILL SALE OBJECT
    $billSale = new BillSale();

		// CONDITION
    $criteria = new CDbCriteria();
    $criteria->order = 'bill_sale_id DESC';

    $modelForGrid = new CActiveDataProvider('BillSale', array(
        'criteria' => $criteria
    ));

		// RENDER
    $this->render('//Basic/ManageBill', array(
        'model' => $billSale,
        'modelForGrid' => $modelForGrid
    ));
  }

	// BILL SALE DETAIL
  public function actionBillSaleDetail($bill_sale_id) {
    $this->checkLogin();

    // MODEL
    $modelBillSale = BillSale::model()->findByPk((int) $bill_sale_id);

    // dataProvider
    $dataProvider = new CActiveDataProvider('BillSaleDetail', array(
        'criteria' => array(
            'condition' => "bill_id = $bill_sale_id",
            'order' => 'bill_sale_detail_id DESC'
        ),
        'pagination' => false
    ));

    // RENDER
    $this->render('//Basic/BillSaleDetail', array(
        'modelBillSale' => $modelBillSale,
        'dataProvider' => $dataProvider
    ));
  }

	// EDIT BILL SALE DETAIL
  public function actionBillSaleDetailEdit($bill_sale_detail_id = null) {
    $this->checkLogin();

    if (empty($bill_sale_detail_id)) {
      $bill_sale_detail_id = Util::input($_POST['bill_sale_detail_id']);
    }

    $model = BillSaleDetail::model()->findByPk((int) $bill_sale_detail_id);

    // update bill_sale_detail
    if (!empty($_POST)) {
      $old_qty = Util::input($_POST['old_qty']);
      $new_qty = Util::input($_POST['BillSaleDetail']['bill_sale_detail_qty']);
      $model->bill_sale_detail_qty = $new_qty;
      $model->save();

      // update stock
      $product_code = $model->bill_sale_detail_barcode;
      $product = Product::model()->find(array(
          'condition' => "product_code = :product_code",
          'params' => array(
            'product_code' => $product_code
          )
      ));

      if ($new_qty > $old_qty) {
        $update_qty = ($new_qty - $old_qty);
        $product->product_quantity += $update_qty;
      } else {
        $update_qty = ($old_qty - $new_qty);
        $product->product_quantity -= $update_qty;
      }

      $product->save();
      $this->redirect(array('BillSaleDetail', 'bill_sale_id' => $model->bill_id));
    }

    // REDIRECT
    $this->render('BillSaleEdit', array(
      'bill_sale_id' => $model->bill_id,
      'model' => $model
    ));
  }

	// DELETE BILL SALE DETAIL
  public function actionBillSaleDetailDelete($bill_sale_detail_id) {
    $this->checkLogin();

    // OBJECT
    $billSaleDetail = BillSaleDetail::model()->findByPk((int) $bill_sale_detail_id);
    $bill_sale_id = $billSaleDetail->bill_id;

    $criteria = new CDbCriteria();
    $criteria->compare('bill_id', $bill_sale_id);
    $model = BillSaleDetail::model()->findAll($criteria);

    $totalRow = count($model);

    // UPDATE STOCK
    $criteria = new CDbCriteria();
    $criteria->compare('product_code', $billSaleDetail->bill_sale_detail_barcode);
    $product = Product::model()->find($criteria);

    $product->product_quantity = ($product->product_quantity + $billSaleDetail->bill_sale_detail_qty);
    $product->save();

    // DELETE
    $billSaleDetail->delete();

    if ($totalRow == 1) {
      // DELETE BILL_SALE
      BillSale::model()->deleteByPk((int) $bill_sale_id);
      $this->redirect(array('ManageBill'));
    }

    // REDIRECT FOR MANAGE BILL_SALE_DETAIL
    $this->redirect(array('BillSaleDetail', 'bill_sale_id' => $bill_sale_id));
  }

	// CHECK STOCK
  public function actionCheckStock() {
    $this->checkLogin();

    $model = new Product();
    $param = array();
    $param['model'] = $model;
    $param['product_code'] = "";

    // find product
    if (!empty($_POST)) {
      $product = Product::model()->findByAttributes(array(
          'product_code' => Util::input($_POST['Product']['product_code'])
      ));

      // find by pack_code
      if (empty($product)) {
        $product = Product::model()->findByAttributes(array(
            'product_pack_barcode' => Util::input($_POST['Product']['product_code'])
        ));
      }

      $param['product'] = $product;
      $param['product_code'] = Util::input($_POST['Product']['product_code']);
    }

    // render
    $this->render('//Basic/CheckStock', $param);
  }

	// BILL DROP
  public function actionBillDrop() {
    $this->checkLogin();

    $model = new BillSale();
    $params = array();

    if (!empty($_POST)) {
      // get value
      $from = Util::thaiToMySQLDate(Util::input($_POST['from']));
      $to = Util::thaiToMySQLDate(Util::input($_POST['to']));
      $bill_status = Util::input($_POST['bill_status']);

      // find member id
      $member = Member::model()->findByAttributes(array(
          'member_code' => Util::input($_POST['member_code'])
      ));

      // criteria
      $criteria = new CDbCriteria();
      $criteria->order = 'bill_sale_created_date DESC ';
      $criteria->condition = '
        member_id = :member_id
        AND DATE(bill_sale_created_date) BETWEEN :from AND :to
      ';
      
      // filter bill status
      switch ($bill_status) {
        case 'no':
          $criteria->condition .= ' AND bill_sale_pay_date IS NULL ';
          $criteria->condition .= ' AND bill_sale_drop_bill_date IS NULL ';
          break;
        case 'drop_no':
          $criteria->condition .= ' AND bill_sale_pay_date IS NULL ';
          $criteria->condition .= ' AND bill_sale_drop_bill_date IS NOT NULL ';
          break;
        case 'drop_pay':
          $criteria->condition .= ' AND bill_sale_pay_date IS NOT NULL ';
          $criteria->condition .= ' AND bill_sale_drop_bill_date IS NOT NULL ';
          break;
      }

      // params
      $criteria->params = array(
          'member_id' => $member->member_id,
          'from' => $from,
          'to' => $to
      );

      // data provider
      $dataProvider = new CActiveDataProvider('BillSale', array(
          'criteria' => $criteria,
          'pagination' => false
      ));

      // have data
      $params['dataProvider'] = $dataProvider;
    } else {
      $from = "";
      $to = "";
    }

    $params['from'] = $from;
    $params['to'] = $to;
    $params['model'] = $model;
    $params['member_code'] = @Util::input($_POST['member_code']);
    $params['member_name'] = @Util::input($_POST['member_name']);
    $params['bill_status'] = @Util::input($_POST['bill_status']);

    $this->render('//Basic/BillDrop', $params);
  }

	// BILL DROP TEMP
  public function actionBillDropTemp() {
    $this->checkLogin();

    Yii::app()->session['hidden_member_code'] = Util::input($_POST['hidden_member_code']);
    Yii::app()->session['bill_sale_ids'] = (int) Util::input($_POST['bill_sale_id']);

    echo 'complete';
  }

	// BILL DROP GET
  public function actionBillDropGet() {
    $this->checkLogin();

    $bill_sale_ids = (int) Util::input($_POST['bill_sale_id']);

    foreach ($bill_sale_ids as $id) {
      $model = BillSale::model()->findByPk((int) $id);
      $model->bill_sale_pay_date = new CDbExpression("NOW()");
      $model->bill_sale_status = 'pay';
      $model->save();
    }

    echo 'complete';
  }

	// BILL DROP CANCEL
  public function actionBillDropCancel() {
    $this->checkLogin();

    $bill_sale_ids = (int) Util::input($_POST['bill_sale_id']);

    foreach ($bill_sale_ids as $id) {
      $model = BillSale::model()->findByPk((int) $id);
      $model->bill_sale_pay_date = null;
      $model->bill_sale_drop_bill_date = null;
      $model->bill_sale_status = 'credit';
      $model->save();
    }

    echo true;
  }

  // BILL DROP DELETE
  public function actionBillDropDelete() {
    $this->checkLogin();

    $bill_sale_ids = (int) Util::input($_POST['bill_sale_id']);

    foreach ($bill_sale_ids as $id) {
      $billSaleDetails = BillSaleDetail::model()->findAllByAttributes(array(
      		'bill_id' => $id
      ));

      foreach ($billSaleDetails as $billSaleDetail) {
	     	$billSaleDetail->delete();
      }

      BillSale::model()->deleteByPk((int) $id);
    }

    echo true;
  }

	// GET SALE
  public function actionGetSale() {
    $this->checkLogin();

    $model = new BillSaleDetail();
    $product = null;

    // search
    if (!empty($_POST)) {
			$barcode = Util::input($_POST['BillSaleDetail']['bill_sale_detail_barcode']);

      if (empty($_POST['product_id'])) {
        // find data
        $billSaleDetail = BillSaleDetail::model()->findByAttributes(array(
            'bill_sale_detail_barcode' => $barcode,
            'bill_id' => (int) Util::input($_POST['BillSaleDetail']['bill_id'])
        ));
        $model->_attributes = $_POST['BillSaleDetail'];

        // find by product_id
        if (!empty($billSaleDetail)) {
          $product = Product::model()->findByAttributes(array(
              'product_code' => $billSaleDetail->bill_sale_detail_barcode
          ));
          if (!empty($product)) {
            $model->bill_sale_detail_barcode = $product->product_code;
          }
        }
      } else {
        // get product
        $product = Product::model()->findByPk($_POST['product_id']);

        // remove from bill
        BillSaleDetail::model()->deleteAllByAttributes(array(
            'bill_sale_detail_barcode' => $barcode,
            'bill_id' => (int) Util::input($_POST['BillSaleDetail']['bill_id'])
        ));

        // update stock and redirect
        $product->product_quantity += 1;
        $product->save();

        $this->redirect(array('GetSale'));
      }
    }

    // render
    $this->render('//Basic/GetSale', array(
        'model' => $model,
        'product' => $product
    ));
  }

	// REPAIR
  public function actionRepair() {
    $this->checkLogin();

    $params = @$_POST;

    if (!empty($_POST)) {
      // search
      $search = Util::input($_POST['search_code']);

      if (empty($search)) {
        $search = Util::input($_GET['serial_code']);
      }

      // productSerial
      $productSerial = ProductSerial::model()->findByAttributes(array(
          'serial_no' => $search
      ));

      if (!empty($productSerial)) {
        $product = Product::model()->findByAttributes(array(
            'product_code' => $productSerial->product_code
        ));

        $params['product'] = $product;
        $params['productSerial'] = $productSerial;
      }

      // repair history
      $criteria = new CDbCriteria();
      $criteria->compare('serial_no', Util::input($_POST['search_code']));
      $criteria->order = 'repair_id DESC';

      $repairs = new CActiveDataProvider('Repair');
      $repairs->setCriteria($criteria);
      $params['repairs'] = $repairs;
    }

    $this->render('//Basic/Repair', $params);
  }

  function actionStartRepair() {
    $this->checkLogin();

    $serial_code = $_GET['serial_code'];

    // product serial
    $productSerial = Yii::app()->db->createCommand()
            ->select('tb_product_serial.*, tb_product.product_name, tb_bill_sale.bill_sale_created_date')
            ->from('tb_product_serial')
            ->join('tb_product', 'tb_product.product_code = tb_product_serial.product_code')
            ->join('tb_bill_sale', 'tb_bill_sale.bill_sale_id = tb_product_serial.bill_sale_id')
            ->where('tb_product_serial.serial_no = ' . $serial_code)
            ->queryRow();

    // repair
    if (!empty($_GET['repair_id'])) {
      $repair = Repair::model()->findByPk((int) $_GET['repair_id']);
    } else {
      $repair = new Repair();
    }

    // render
    $this->render('//Basic/StartRepair', array(
        'productSerial' => $productSerial,
        'repair' => $repair
    ));
  }

  function actionStartRepairSave() {
    $this->checkLogin();

    if (!empty($_POST)) {
      // serail_code
      $serial_code = Util::input($_POST['Repair']['serial_no']);

      // save
      $state_new = true;

      if (empty($_POST['Repair']['repair_id'])) {
        $repair = new Repair();
      } else {
        $state_new = false;
        $repair = Repair::model()->findByPk((int) Util::input($_POST['Repair']['repair_id']));
      }

      $repair->_attributes = $_POST['Repair'];
      $repair->user_id = (int) Util::input($_POST['user_id']);
      $repair->repair_created_date = Util::thaiToMySQLDate(Util::input($_POST['repair_created_date']));
      $repair->branch_id = Util::input($_POST['hidden_branch_id']);
      $repair->repair_date = Util::thaiToMySQLDate(Util::input($_POST['Repair']['repair_date']));

      if ($repair->save()) {
        if ($state_new) {
          $this->redirect(array('Basic/StartRepair', 'serial_code' => $serial_code));
        } else {
          $this->redirect(array('Basic/Repair', 'serial_code' => $serial_code));
        }
      }
    }
  }

  function actionRepairView($repair_id) {
    $this->checkLogin();

    $serial_code = Util::input($_GET['serial_code']);

    // product serial
    $productSerial = Yii::app()->db->createCommand()
            ->select('tb_product_serial.*, tb_product.product_name, tb_bill_sale.bill_sale_created_date')
            ->from('tb_product_serial')
            ->join('tb_product', 'tb_product.product_code = tb_product_serial.product_code')
            ->join('tb_bill_sale', 'tb_bill_sale.bill_sale_id = tb_product_serial.bill_sale_id')
            ->where('tb_product_serial.serial_no = ' . $serial_code)
            ->queryRow();

    // repair
    if (!empty($_GET['repair_id'])) {
      $repair = Repair::model()->findByPk((int) $_GET['repair_id']);
    } else {
      $repair = new Repair();
    }

    // render
    $this->render('//Basic/RepairView', array(
        'productSerial' => $productSerial,
        'repair' => $repair
    ));
  }

  function actionBillQuotation() {
    $this->checkLogin();
    $this->render("//Basic/BillQuotation");
  }

  function actionQuotationSave($quotation_id = null) {
    $this->checkLogin();

    if (!empty($_POST)) {
      if (empty($quotation_id)) {
        // INSERT DATA TO TABLE
        $quotation = new Quotation();
        $quotation->created_at = new CDbExpression("NOW()");
      } else {
        $quotation = Quotation::model()->findByPk((int) $quotation_id);
      }

      $quotation->customer_name = Util::input($_POST['customer_name']);
      $quotation->customer_address = Util::input($_POST['customer_address']);
      $quotation->customer_tel = Util::input($_POST['customer_tel']);
      $quotation->customer_fax = Util::input($_POST['customer_fax']);
      $quotation->customer_tax = Util::input($_POST['customer_tax']);
      $quotation->quotation_day = Util::input($_POST['quotation_day']);
      $quotation->quotation_send_day = Util::input($_POST['quotation_send_day']);
      $quotation->quotation_pay = Util::input($_POST['quotation_pay']);
        
      $quotation->user_id = Yii::app()->request->cookies["user_id"]->value;
      $quotation->vat = Util::input($_POST['vat']);

      if ($quotation->save()) {
        // INSERT TO quotation_details
        $barcodes = Util::input($_POST['barcode_hidden']);

        // clear quotation detail
        if (!empty($quotation_id)) {
          QuotationDetail::model()->deleteAllByAttributes(array(
            'quotation_id' => $quotation_id
          ));
        }

        // insert quotation data
        for ($i = 0; $i < count($barcodes); $i++) {
          $quotationDetail = new QuotationDetail();
          $quotationDetail->quotation_id = $quotation->id;
          $quotationDetail->barcode = Util::input($_POST['barcode_hidden'][$i]);
          $quotationDetail->old_price = str_replace(",", "", Util::input($_POST['old_price'][$i]));
          $quotationDetail->qty = str_replace(",", "", Util::input($_POST['qty'][$i]));
          $quotationDetail->sub = str_replace(",", "", Util::input($_POST['sub'][$i]));
          $quotationDetail->sale_price = str_replace(",", "", Util::input($_POST['sale_price'][$i]));
          $quotationDetail->save();
        }

        Yii::app()->session['current_quotation_id'] = $quotation->id;
      }
    } else {
      Yii::app()->session['current_quotation_id'] = $quotation_id;
    }

    echo "success";
  }

  function actionQuotationBill() {
    $this->checkLogin();

    $quotation_id = Yii::app()->session['current_quotation_id'];

    $quotation = Quotation::model()->findByPk((int) $quotation_id);
    
    $quotationDetails = QuotationDetail::model()->findAllByAttributes(array(
      "quotation_id" => $quotation_id
    ));

    $org = Organization::model()->find();
    $user_id = (int) Yii::app()->request->cookies["user_id"]->value;

    $user = User::model()->findByPk((int) $user_id);

    $this->renderPartial("//Basic/QuotationBill", array(
      "quotation" => $quotation,
      "quotationDetails" => $quotationDetails,
      "org" => $org,
      "user" => $user
    ));
  }

  public function actionGridQuotation() {
    $this->checkLogin();

    $quotations = Quotation::model()->findAll(array(
      "order" => "id DESC"
    ));

    $this->renderPartial("//Basic/GridQuotation", array(
      "quotations" => $quotations,
      "n" => 1
    ));
  }

  public function actionFindQuotationById($id) {    
    $quotation = Quotation::model()->findByPk((int) $id);
    echo CJSON::encode($quotation);
  }

  public function actionQuotationDetail($quotation_id) {    
    $quotationDetails = QuotationDetail::model()->findAllByAttributes(array(
      "quotation_id" => $quotation_id
    ));

    $arr = array();
    $i = 0;

    foreach ($quotationDetails as $quotationDetail) {
      $arr[$i]['id'] = $quotationDetail->id;
      $arr[$i]['barcode'] = $quotationDetail->barcode;
      $arr[$i]['product_name'] = $quotationDetail->getProduct()->product_name;
      $arr[$i]['qty'] = number_format($quotationDetail->qty);
      $arr[$i]['old_price'] = number_format($quotationDetail->old_price);
      $arr[$i]['sub'] = number_format($quotationDetail->sub);
      $arr[$i]['sale_price'] = number_format($quotationDetail->sale_price);

      $i++;
    }

    echo CJSON::encode($arr);
  }

  public function actionQuotationDetailAdd() {
    $this->checkLogin();

    if (!empty($_POST)) {
      $quotationDetail = new QuotationDetail();
      $quotationDetail->quotation_id = (int) Util::input($_POST['quotation_id']);
      $quotationDetail->barcode = Util::input($_POST['barcode']);
      $quotationDetail->old_price = str_replace(",", "", Util::input($_POST['old_price']));
      $quotationDetail->qty = str_replace(",", "", Util::input($_POST['qty']));
      $quotationDetail->sub = str_replace(",", "", Util::input($_POST['sub']));
      $quotationDetail->sale_price = str_replace(",", "", Util::input($_POST['sale_price']));
      $quotationDetail->save();

      echo $quotationDetail->id;
    }
  }

  public function actionQuotationDetailDelete($id) {
    $this->checkLogin();
    QuotationDetail::model()->deleteByPk((int) $id);
  }

  public function actionClearBillSale() {
    $this->checkLogin();
    BillSale::model()->deleteAll();
    BillSaleDetail::model()->deleteAll();

    $this->redirect(array("Basic/ManageBill"));
  }

  public function actionBackgroundSave() {
    $this->checkLogin();

    if ($_FILES['background']['name'] != null) {
      $name = $_FILES['background']['name'];
      $tmp = $_FILES['background']['tmp_name'];
      $size = $_FILES['background']['size'];

      if ($size > 0) {
        $ext = explode(".", $name);
        $ext = $ext[count($ext) - 1];
        $ext = strtolower($ext);

        $name = microtime();
        $name = str_replace(" ", "", $name);
        $name = str_replace(".", "", $name);

        if ($ext == "jpg" || $ext == "png") {
          if (move_uploaded_file($tmp, "upload/$name.$ext")) {
            $background = new Background();
            $background->name = $name.".".$ext;
            
            if ($background->save()) {
              $this->redirect(array("Site/Home"));
            }
          }
        }
      }
    }
  }

  public function actionBackgroundDelete($id) {
    $this->checkLogin();
  }

  public function actionGetRepair() {
    $this->checkLogin();
    $this->render('//Basic/GetRepair');
  }

  public function actionGetRepairSave() {
    $this->checkLogin();

    if (!empty($_POST)) {
      $repair_id = (int) Util::input($_POST['repair_id']);

      if (empty($repair_id)) {
        $repair = new Repair();
      } else {
        $repair = Repair::model()->findByPk((int) $repair_id);
      }

      $repair->user_id = (int) Util::input($_POST['user_id']);
      $repair->branch_id = (int) Util::input($_POST['hidden_branch_id']);
      $repair->product_code = Util::input($_POST['product_code']);
      $repair->repair_date = Util::thaiToMySQLDate(Util::input($_POST['repair_date']));
      $repair->repair_problem = Util::input($_POST['repair_problem']);
      $repair->repair_price = Util::input($_POST['repair_price']);
      $repair->repair_type = Util::input($_POST['repair_type']);
      $repair->repair_original = Util::input($_POST['repair_original']);
      $repair->repair_detail = Util::input($_POST['repair_detail']);
      $repair->repair_created_date = Util::thaiToMySQLDate(Util::input($_POST['repair_created_date']));
      $repair->repair_status = Util::input($_POST['repair_status']);
      $repair->repair_group = 'external';
      $repair->repair_tel = Util::input($_POST['repair_tel']);
      $repair->repair_name = Util::input($_POST['repair_name']);
      $repair->repair_product_name = Util::input($_POST['repair_product_name']);

      if ($repair->save()) {
        echo CJSON::encode($repair);
      }
    }
  }

  public function actionGetRepairInfo() {
    $this->checkLogin();

    if (!empty($_POST)) {
      $repair_id = Util::input($_POST['repair_id']);
      $repair = Repair::model()->findByPk((int) $repair_id);

      if (!empty($repair)) {
        $arr = array();
        $arr['repair_id'] = $repair->repair_id;
        $arr['product_code'] = $repair->product_code;
        $arr['user_id'] = $repair->user_id;
        $arr['user_name'] = $repair->user->user_name;
        $arr['branch_id'] = $repair->branch_id;
        $arr['branch_name'] = $repair->branch->branch_name;
        $arr['repair_date'] = Util::mysqlToThaiDate($repair->repair_date);
        $arr['repair_problem'] = $repair->repair_problem;
        $arr['repair_price'] = $repair->repair_price;
        $arr['repair_type'] = $repair->repair_type;
        $arr['repair_original'] = $repair->repair_original;
        $arr['repair_detail'] = $repair->repair_detail;
        $arr['repair_created_date'] = Util::mysqlToThaiDate($repair->repair_created_date);
        $arr['repair_status'] = $repair->repair_status;
        $arr['serial_no'] = $repair->serial_no;
        $arr['repair_group'] = $repair->repair_group;
        $arr['repair_tel'] = $repair->repair_tel;
        $arr['repair_name'] = $repair->repair_name;
        $arr['repair_product_name'] = $repair->repair_product_name;
        $arr['repair_end_date'] = $repair->repair_end_date;
      }

      echo CJSON::encode($arr);
    }
  }

  public function actionGetRepairDelete() {
    $this->checkLogin();

    if (!empty($_POST)) {
      $repair_id = Util::input($_POST['repair_id']);

      Repair::model()->deleteByPk((int) $repair_id);
      echo 'success';
    }
  }

  public function actionGetRepairEnd() {
    $this->checkLogin();

    if (!empty($_POST)) {
      $repair_id = Util::input($_POST['repair_id']);
      $repair = Repair::model()->findByPk((int) $repair_id);

      $repair->user_id = (int) Util::input($_POST['user_id']);
      $repair->branch_id = (int) Util::input($_POST['hidden_branch_id']);
      $repair->product_code = Util::input($_POST['product_code']);
      $repair->repair_date = Util::thaiToMySQLDate(Util::input($_POST['repair_date']));
      $repair->repair_problem = Util::input($_POST['repair_problem']);
      $repair->repair_price = Util::input($_POST['repair_price']);
      $repair->repair_type = Util::input($_POST['repair_type']);
      $repair->repair_original = Util::input($_POST['repair_original']);
      $repair->repair_detail = Util::input($_POST['repair_detail']);
      $repair->repair_created_date = Util::thaiToMySQLDate(Util::input($_POST['repair_created_date']));
      $repair->repair_status = Util::input($_POST['repair_status']);
      $repair->repair_group = 'external';
      $repair->repair_tel = Util::input($_POST['repair_tel']);
      $repair->repair_name = Util::input($_POST['repair_name']);
      $repair->repair_product_name = Util::input($_POST['repair_product_name']);
      $repair->repair_end_date = new CDbExpression('NOW()');

      if ($repair->save()) {
        echo 'success';
      }
    } 
  }

  public function actionAlertStock($print = false) {
    $configSoftware = ConfigSoftware::model()->find();

    $products = Product::model()->findAll(array(
      'condition' => 'product_quantity <= :qty',
      'order' => 'product_quantity',
      'params' => array(
        'qty' => $configSoftware->alert_min_stock
      )
    ));

    if (!$print) {
      $this->render('//Basic/AlertStock', array(
        'products' => $products,
        'n' => 1,
        'print' => $print
      ));
    } else {
      $this->renderPartial('//Basic/AlertStock', array(
        'products' => $products,
        'n' => 1,
        'print' => $print
      ));
    }
  }

  public function actionSaleMobile() {
    $this->checkLogin();

    $user_id = Yii::app()->request->cookies['user_id']->value;

    if (!empty($_POST)) {
      $barcode = Util::input($_POST['barcode']);

      $info = Product::getInfoByBarcode($barcode);
      $user = User::model()->findByPk((int) $user_id);

      $saleTemp = new SaleTemp();
      $saleTemp->barcode = $barcode;
      $saleTemp->serial = Util::input($_POST['serial']);
      $saleTemp->qty = 1;
      $saleTemp->qty_per_pack = $info['qty_per_pack'];
      $saleTemp->price = $info['price'];
      $saleTemp->user_id = $user_id;
      $saleTemp->branch_id = $user->branch_id;
      $saleTemp->pk_temp = rand(1000, 10000);
      $saleTemp->created_at = new CDbExpression('NOW()');
      $saleTemp->old_price = $info['old_price'];
      $saleTemp->sale_type = 'mobile';
      $saleTemp->name = $info['name'];

      if ($saleTemp->save()) {
        $this->redirect(array('SaleMobile'));
      }
    }

    $saleTemps = SaleTemp::model()->findAll(array(
      'condition' => 'user_id = :user_id AND sale_type = :sale_type',
      'params' => array(
        'user_id' => $user_id,
        'sale_type' => 'mobile'
      ),
      'order' => 'created_at DESC'
    ));

    $this->renderPartial('//Basic/SaleMobile', array(
      'saleTemps' => $saleTemps,
      'sum' => 0
    ));
  }

}
