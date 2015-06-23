<?php

class ReportController extends Controller {

    function actionSalePerDay() {
      $params = array();

      // checked
      $checked_cash = 'checked="checked"';
      $checked_credit = 'checked="checked"';

      $checked_bonus_no = 'checked="checked"';
      $checked_bonus_yes = 'checked="checked"';

      // default date
      $date_end = date("d/m/Y");

        $branch_id = null;

        if (!empty($_POST)) {
          // data
          $sale_condition_cash = $_POST['sale_condition_cash'];
          $sale_condition_credit = $_POST['sale_condition_credit'];

          $has_bonus_yes = "";

            if (!empty($_POST['has_bonus_yes'])) {
                $has_bonus_yes = $_POST['has_bonus_yes'];
            }

            $has_bonus_no = "";

            if (!empty($_POST['has_bonus_no'])) {
             $has_bonus_no = $_POST['has_bonus_no'];
            }

          $branch_id = $_POST['branch_id'];

          // checked
          if (!empty($sale_condition_cash)) {
            $checked_cash = 'checked="checked"';
          } else {
            $checked_cash = '';
          }

          if (!empty($sale_condition_credit)) {
            $checked_credit = 'checked="checked"';
          } else {
            $checked_credit = '';
          }

          if (!empty($has_bonus_yes)) {
            $checked_bonus_yes = 'checked="checked"';
          } else {
            $checked_bonus_yes = '';
          }

          if (!empty($has_bonus_no)) {
            $checked_bonus_no = 'checked="checked"';
          } else {
            $checked_bonus_no = '';
          }

          // SQL Condition
          if (!empty($sale_condition_cash)) {
            $condition_sale = "AND bill_sale_status = 'pay'";
          }
          if (!empty($sale_condition_credit)) {
            $condition_sale = "AND bill_sale_status = 'credit'";
          }
          if (!empty($sale_condition_cash) && !empty($sale_condition_credit)) {
            $condition_sale = "AND bill_sale_status IN('credit', 'pay')";
          }

          if (!empty($has_bonus_no)) {
            $condition_has_bonus = "AND bill_sale_detail_has_bonus = 'no'";
          }
          if (!empty($has_bonus_yes)) {
            $condition_has_bonus = "AND bill_sale_detail_has_bonus = 'yes'";
          }
          if (!empty($has_bonus_no) && !empty($has_bonus_yes)) {
            $condition_has_bonus = "AND bill_sale_detail_has_bonus IS NOT NULL";
          }

          // Date
            $date_find = Util::thaiToMySQLDate($_REQUEST['date_find']);
            $date_end_sql = Util::thaiToMySQLDate($_POST['date_end']);
            $date_end = $_POST['date_end'];

            $date = explode('-', $date_find);
            $y = $date[0];
            $m = $date[1];
            $d = $date[2];

            $date_end_arr = explode("-", $date_end_sql);
            $y_end = $date_end_arr[0];
            $m_end = $date_end_arr[1];
            $d_end = $date_end_arr[2];

            $d = ($d * 1);
            $d_end = ($d_end * 1);

            // SQL Command
            $sql = "
              SELECT * FROM tb_bill_sale_detail
              LEFT JOIN tb_bill_sale ON tb_bill_sale.bill_sale_id = tb_bill_sale_detail.bill_id
              LEFT JOIN tb_product ON tb_product.product_code = tb_bill_sale_detail.bill_sale_detail_barcode
              WHERE
                bill_sale_detail_barcode != ''
                AND
                (
                  YEAR(tb_bill_sale.bill_sale_created_date) >= $y
                  AND MONTH(bill_sale_created_date) >= $m
                  AND DAY(bill_sale_created_date) >= $d
                )
                AND 
                (
                  YEAR(tb_bill_sale.bill_sale_created_date) <= $y_end
                  AND MONTH(tb_bill_sale.bill_sale_created_date) <= $m_end
                  AND DAY(tb_bill_sale.bill_sale_created_date) <= $d_end
                )
                $condition_sale
                $condition_has_bonus
                AND tb_bill_sale.branch_id = $branch_id
                ORDER BY tb_bill_sale_detail.bill_id ASC
            ";

            // query all
            $result = Yii::app()->db->createCommand($sql)->queryAll();

            $params['date'] = $date_find;
            $params['result'] = $result;

            // save result to session
            $session = new CHttpSession();
            $session->open();
            $session['result'] = $result;
            $session['date_find'] = $date_find;
            $session['sale_condition_cash'] = $sale_condition_cash;
            $session['sale_condition_credit'] = $sale_condition_credit;
            $session['has_bonus_no'] = $has_bonus_no;
            $session['has_bonus_yes'] = $has_bonus_yes;
            $session['branch_id'] = $branch_id;
        }

        $params['checked_cash'] = $checked_cash;
        $params['checked_credit'] = $checked_credit;
        $params['checked_bonus_yes'] = $checked_bonus_yes;
        $params['checked_bonus_no'] = $checked_bonus_no;
        $params['branch_id'] = $branch_id;
        $params['date_end'] = $date_end;

        $drawcash = DrawCash::model()->find(array(
          'condition' => '
            YEAR(draw_date) = YEAR(NOW())
            AND MONTH(draw_date) = MONTH(NOW())
            AND DAY(draw_date) = DAY(NOW())
          '
        ));
        $params['drawcash'] = $drawcash;

        $this->render('//Report/ReportSalePerDay', $params);
    }

    function actionSaleSumPerDay() {
        $month = date("m");
        $year = date("Y");

        if (!empty($_POST)) {
          $month = $_POST['month'];
          $year = $_POST['year'];
        }

        $total_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $billSale = new BillSale();

        $this->render('//Report/ReportSaleSumPerDay', array(
          "month" => $month,
          "year" => $year,
          "sum" => 0,
          'n' => 1,
          'total_day' => $total_day,
          'billSale' => $billSale
        ));
    }

    function actionSaleSumPerMonth() {
      $year = date('Y');
      $yearList = array();
      $yearStart = date('Y');

      for ($i = ($yearStart - 5); $i <= $yearStart; $i++) {
        $yearList[$i] = $i;
      }

      if ($_POST) {
        $year = $_POST['year_find'];
      }

      $billSale = new BillSale();
      $monthRange = Util::monthRange();

      $this->render('//Report/ReportSaleSumPerMonth', array(
        "year" => $year,
        "result_sum" => 0,
        'yearList' => $yearList,
        'billSale' => $billSale,
        'monthRange' => $monthRange
      ));
    }

    function actionSaleSumPerType() {
      $month = date('m');
      $year = date('Y');
      $yearList = array();
      $yearStart = date('Y');

      for ($i = ($yearStart - 5); $i <= $yearStart; $i++) {
        $yearList[$i] = $i;
      }

      if ($_POST) {
        $year = $_POST['year'];
        $month = $_POST['month'];
      }

      $billSale = new BillSale();
      $monthRange = Util::monthRange();
      $groupProducts = GroupProduct::model()->findAll(array(
        'order' => 'group_product_name'
      ));

      $this->render('//Report/ReportSaleSumPerType', array(
        'sum' => 0,
        'n' => 1,
        'yearList' => $yearList,
        'groupProducts' => $groupProducts,
        'monthRange' => $monthRange,
        'year' => $year,
        'month' => $month * 1,
        'billSale' => $billSale
      ));
    }

    function actionSaleSumPerMember() {
      $y = date('Y');

      if (!empty($_POST)) {
        $y = $_POST['y'];
      }

      $yearList = array();
      $yearStart = date('Y');

      for ($i = ($yearStart - 5); $i <= $yearStart; $i++) {
        $yearList[$i] = $i;
      }

      $members = Member::model()->findAll(array(
        'order' => 'member_id DESC'
      ));

      $billSale = new BillSale();
      $configSoftware = ConfigSoftware::model()->find();

      $this->render('//Report/ReportSaleSumPerMember', array(
        'members' => $members,
        'n' => 1,
        'billSale' => $billSale,
        'configSoftware' => $configSoftware,
        'y' => $y,
        'yearList' => $yearList
      ));        
    }

    function actionSaleSumPerEmployee() {
      $m = date("m");
      $y = date("Y");

      if (!empty($_POST)) {
        $m = $_POST['m'];
        $y = $_POST['y'];
      }

      $users = User::model()->findAll(array(
        'order' => 'user_name'
      ));
      $billSale = new BillSale();

      $yearList = array();
      $yearStart = date('Y');

      for ($i = ($yearStart - 5); $i <= $yearStart; $i++) {
        $yearList[$i] = $i;
      }

      $this->render('//Report/ReportSaleSumPerEmployee', array(
        "n" => 1,
        "sum" => 0,
        'users' => $users,
        'm' => $m * 1,
        'y' => $y,
        'billSale' => $billSale,
        'yearList' => $yearList,
        'monthList' => Util::monthRange()
      ));
    }

    function actionProductStock() {
        $this->render('//Report/ReportProductStock');
    }

    function actionProductInStock() {
      $products = Product::model()->findAll(array(
        'condition' => 'product_quantity > 0',
        'order' => 'product_quantity ASC'
      ));

      $this->render('//Report/ProductInStock', array(
        'products' => $products
      ));
    }

    function actionProductOutStock() {
      $products = Product::model()->findAll(array(
        'condition' => 'product_quantity <= 0',
        'order' => 'product_quantity ASC'
      ));

      $this->render('//Report/ProductOutStock', array(
        'products' => $products
      ));
    }

    function actionReportAR() {
        $billSales = new CActiveDataProvider('BillSale', array(
            "criteria" => array(
                "condition" => "
                  bill_sale_pay_date IS NULL 
                  OR bill_sale_pay_date = '0000-00-00 00:00:00'",
                "order" => "bill_sale_id DESC"
            ),
            "pagination" => array(
              "pageSize" => 50
            )
        ));

        $this->render('//Report/ReportAR', array(
            "billSales" => $billSales
        ));
    }

    function actionReportIR() {
        if($_POST) {

            // query all
            $sql = "
              SELECT * FROM tb_farmer AS a
              ORDER BY a.farmer_id ASC
            ";
            $result = Yii::app()->db->createCommand($sql)->queryAll();

            $this->render('//Report/ReportIR', array(
                "result" => $result,
            ));
        } else {
            $this->render('//Report/ReportIR');
        }
    }

  public function actionPrintBillGetRepair($repair_id) {
    $repair = Repair::model()->findByPk($repair_id);
    $org = Organization::model()->find();

    $repair->repair_date = Util::mysqlToThaiDate($repair->repair_date);
    $repair->repair_created_date = Util::mysqlToThaiDate($repair->repair_created_date);
    $repair->repair_price = number_format($repair->repair_price, 2);

    $this->renderPartial('//Report/PrintBillGetRepair', array(
      'repair' => $repair,
      'org' => $org
    ));
  }

  public function actionPrintBillPayGetRepair($repair_id) {
    $repair = Repair::model()->findByPk($repair_id);
    $org = Organization::model()->find();

    $repair->repair_date = Util::mysqlToThaiDate($repair->repair_date);
    $repair->repair_created_date = Util::mysqlToThaiDate($repair->repair_created_date);
    $repair->repair_price = number_format($repair->repair_price, 2);

    $this->renderPartial('//Report/PrintBillPayGetRepair', array(
      'repair' => $repair,
      'org' => $org
    ));
  }

  public function actionSaleSumPerMemberDetail($year, $member_id) {
      $sql = "
        SELECT 
          tb_bill_sale_detail.*, 
          tb_product.product_name, 
          tb_bill_sale.bill_sale_created_date
        FROM tb_bill_sale_detail
        LEFT JOIN tb_bill_sale ON tb_bill_sale.bill_sale_id = tb_bill_sale_detail.bill_id
        INNER JOIN tb_product ON tb_product.product_code = tb_bill_sale_detail.bill_sale_detail_barcode
        WHERE
          YEAR(tb_bill_sale.bill_sale_created_date) = $year
          AND member_id = $member_id
        ORDER BY bill_sale_detail_id DESC
      ";

      $billSaleDetails = Yii::app()->db->createCommand($sql)->queryAll();
      $member = Member::model()->findByPk($member_id);

      $this->render('//Report/SumSalePerMemberDetail', array(
        'n' => 1,
        'billSaleDetails' => $billSaleDetails,
        'member' => $member,
        'year' => $year
      ));
  }

  public function actionIncome() {
    $billSaleDetails = null;
    $from = date('d/m/Y');
    $to = date('d/m/Y');
    $pays = null;

    if (!empty($_POST)) {
      $from = $_POST['from'];
      $to = $_POST['to'];

      $arr_from = explode('/', $from);
      $arr_to = explode('/', $to);

      $from_y = $arr_from[2];
      $from_m = $arr_from[1];
      $from_d = $arr_from[0];

      $to_y = $arr_to[2];
      $to_m = $arr_to[1];
      $to_d = $arr_to[0];

      //
      // income
      //
      $sql = "
        SELECT * FROM tb_bill_sale_detail
        LEFT JOIN tb_bill_sale ON tb_bill_sale.bill_sale_id = tb_bill_sale_detail.bill_id
        LEFT JOIN tb_product ON tb_product.product_code = tb_bill_sale_detail.bill_sale_detail_barcode
        WHERE 
        (
          YEAR(bill_sale_pay_date) >= $from_y
          AND MONTH(bill_sale_pay_date) >= $from_m
          AND DAY(bill_sale_pay_date) >= $from_d
        )
        AND
        (
          YEAR(bill_sale_pay_date) <= $to_y
          AND MONTH(bill_sale_pay_date) <= $to_m
          AND DAY(bill_sale_pay_date) <= $to_d
        )
        ORDER BY bill_id
      ";

      $billSaleDetails = Yii::app()->db->createCommand($sql)->queryAll();

      //
      // pay
      //
      $sql = "
        SELECT * FROM pays
        WHERE 
        (
          YEAR(created_at) >= $from_y
          AND MONTH(created_at) >= $from_m
          AND DAY(created_at) >= $from_d
        )
        AND
        (
          YEAR(created_at) <= $to_y
          AND MONTH(created_at) <= $to_m
          AND DAY(created_at) <= $to_d
        )
      ";
      $pays = Yii::app()->db->createCommand($sql)->queryAll();
    }

    $this->render('//Report/Income', array(
      'n' => 1,
      'billSaleDetails' => $billSaleDetails,
      'from' => $from,
      'to' => $to,
      'sumInput' => 0,
      'nPay' => 1,
      'pays' => $pays,
      'sumPay' => 0
    ));
  }

}
