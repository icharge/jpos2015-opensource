<!DOCTYPE html>

<?php 
ini_set("memory_limit", "15000M");
$version = "2015.06.22";
Yii::app()->timeZone = 'Asia/Bangkok'; 

date_default_timezone_set('Asia/Bangkok');
@ini_alter('date.timezone','Asia/Bangkok');
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />
    <meta charset="utf-8" />

    <?php
    // css
    echo CHtml::cssFile('css/bootstrap.css');
    echo CHtml::cssFile('css/bootstrap-theme.css');
    echo CHtml::cssFile('css/ui-lightness/jquery-ui-1.10.3.custom.css');

    // js
    Yii::app()->clientScript->registerScriptFile('js/jquery-2.0.3.js');
    Yii::app()->clientScript->registerScriptFile('js/jquery-ui-1.10.3.custom.js');
    Yii::app()->clientScript->registerScriptFile('js/bootstrap.js');
		Yii::app()->clientScript->registerScriptFile('js/numeral/numeral.js');
    ?>

    <style>
      label {
        display: inline-block;
        width: 150px;
        text-align: right;
      }
      .form-control {
        display: inline-block;
      }
      form div {
        padding: 2px;
      }

      /* dropdown */
      .dropdown-submenu {
        position:relative;
      }
      .dropdown-submenu > .dropdown-menu {
        top:0;
        left:100%;
        margin-top:-6px;
        margin-left:-1px;
        -webkit-border-radius:0 6px 6px 6px;
        -moz-border-radius:0 6px 6px 6px;
        border-radius:0 6px 6px 6px;
      }
      .dropdown-submenu:hover > .dropdown-menu {
        display:block;
      }
      .dropdown-submenu > a:after {
        display:block;
        content:" ";
        float:right;
        width:0;
        height:0;
        border-color:transparent;
        border-style:solid;
        border-width:5px 0 5px 5px;
        border-left-color:#cccccc;
        margin-top:5px;
        margin-right:-10px;
      }
      .dropdown-submenu:hover > a:after {
        border-left-color:#ffffff;
      }
      .dropdown-submenu .pull-left{
        float:none;
      }
      .dropdown-submenu.pull-left > .dropdown-menu {
        left:-100%;
        margin-left:10px;
        -webkit-border-radius:6px 0 6px 6px;
        -moz-border-radius:6px 0 6px 6px;
        border-radius:6px 0 6px 6px;
      }

      /* Error */
      .errorSummary {
        padding: 10px;
        border: red 1px solid;
        background: #ffe1e1;
        margin-bottom: 10px;
        -webkit-border-radius:6px 6px 6px 6px;
        -moz-border-radius:6px 6px 6px 6px;
        border-radius:6px 6px 6px 6px;
        color: red;
      }

      /* Table */
      .grid-view .items thead tr th {
        padding: 5px;
        font-size: 14px;
        font-weight: normal;
      }
      .grid-view .items tbody tr td {
        padding: 5px;
        font-size: 14px;
      }

      /* toolbar */
      .mynav {
        border-bottom: #cccccc 1px solid;
        padding: 0px;
        display: inline-block;
        width: 100%;
        background: #f2f5f6;
      }

      .mynav ul li a {
        padding: 10px;
      }

      .mynav ul li {
        padding: 0px;
      }
    </style>

    <script type="text/javascript">
      var dateBefore=null;

      function initCalendar() {
        $(".calendar").datepicker({
          dateFormat: 'dd/mm/yy',
          changeMonth: true,
          changeYear: true,
          dayNamesMin: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
          monthNamesShort: [
            'มกราคม',
            'กุมภาพันธ์',
            'มีนาคม',
            'เมษายน',
            'พฤษภาคม',
            'มิถุนายน',
            'กรกฎาคม',
            'สิงหาคม',
            'กันยายน',
            'ตุลาคม',
            'พฤศจิกายน',
            'ธันวาคม'
          ]
        });

        checkMinProductStock();
      }

      function checkMinProductStock() {
        $.ajax({
          url: 'index.php?r=Ajax/MinProductStock',
          dataType: 'json',
          type: 'get',
          success: function(data) {
            if (data != '') {
              $("#totalAlertStock").text(data);
              $("#divAlertStock").show();
            }
          }
        });
      }

      function upToNewVersion() {
        $.ajax({
          url: 'index.php?r=Help/UpToNewVersion',
          success: function(data) {
            if (data == 'success') {
              alert('update version เรียบร้อย');
              location.reload();
            }
          }
        });
      }
    </script>

    <title>jPOS <?php echo $version; ?> ระบบบริหารงานร้านค้าปลีก ส่ง ทุกรูปแบบ</title>
  </head>

  <body onload="initCalendar()">
    <?php
    $current_version = $version;

    try {
      $_url = 'http://www.pingpongsoft.com/pos-current-version.json?rand='.rand(1, 100000);
      $data = @file_get_contents($_url);
      $json = @json_decode($data);

      if (!empty($json)) {
        $current_version = $json->current_version;
      }
    } catch (Exception $e) {
    }
    ?>

    <?php if ($current_version != $version): ?>
    <div id="my-alert" style="margin: 10px" class="alert alert-success">
      ขณะนี้มี version ใหม่มาแล้ว อัพเป็นรุ่น <font color="red"><?php echo $current_version; ?></font>
      <a href="#" onclick="return upToNewVersion()" class="btn btn-primary btn-lg">
        <i class="glyphicon glyphicon-open"></i>
        กดที่นี่เพื่ออัพเดต
      </a>
      <a href="http://www.pingpongsoft.com/history.php" target="_blank" class="btn btn-info btn-lg">
        <i class="glyphicon glyphicon-list"></i>
        รายละเอียด
      </a>
    </div>
    <?php endif; ?>

    <div class="nav navbar-inverse"
				style="padding: 10px; color: #f9f8f7">
      <div class="pull-left" style="font-size: 20px">
        <?php $org = Organization::model()->find(); ?>

        <?php
        $bg = "";

        if (!empty($org->logo_show_on_header_bg)) {
          if ($org->logo_show_on_header_bg == "yes") {
            $bg = "background-color: #f9f8f7";
          }
        }
        ?>

        <?php if (!empty($org->logo_show_on_header)): ?>
        <?php if ($org->logo_show_on_header == "yes"): ?>
        <img src="<?php echo Yii::app()->baseUrl; ?>/upload/<?php echo $org->org_logo; ?>" style="width: 50px; <?php echo $bg; ?>" />
        <?php endif; ?>
        <?php endif; ?>

        <?php echo $org->org_name; ?>
        <font size="2" color="#AAB2BD">(v. <?php echo $version; ?>)</font>
      </div>

      <?php if (Yii::app()->request->cookies['user_id'] != null): ?>
        <div class="pull-right">
          <?php
          $id = Yii::app()->request->cookies['user_id']->value;
          $user = User::model()->findByPk($id);
          ?>
          <label><?php echo @$user->user_name; ?> (<?php echo @$user->user_level; ?>)</label>
          <a href="index.php?r=Site/Logout" class="btn btn-danger"
             onclick="return confirm('Logout Now')">
						 <b class="glyphicon glyphicon-off"></b>
            Logout
          </a>
        </div>
      <?php endif; ?>
      <div class="clearfix"></div>
    </div>

    <?php
    if (Yii::app()->request->cookies['user_id'] != null) {
      $this->renderPartial('//site/menu');
    }
    ?>

		<?php if (Yii::app()->request->cookies['user_id'] != null): ?>
    
    <?php echo $content; ?>
    <?php else: ?>
    <?php $this->renderPartial("//site/index"); ?>
    <?php endif; ?>
  </body>
</html>
