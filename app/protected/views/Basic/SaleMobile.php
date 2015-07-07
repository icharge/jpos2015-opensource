<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="language" content="en" />
  <meta charset="utf-8" />
  
  <title>jpos</title>
  <?php 
    echo CHtml::cssFile('css/bootstrap.css');
  
    Yii::app()->clientScript->registerScriptFile('js/jquery-2.0.3.js');
    Yii::app()->clientScript->registerScriptFile('js/bootstrap.js');
    //Yii::app()->clientScript->registerScriptFile('js/numeral/numeral.js');
  ?>
  
  <script src="js/jquery-2.0.3.js"></script>
  <script src="js/bootstrap.js"></script>
  <script>
    var pk_temp;

    function clickRow(id_target) {
      pk_temp = id_target;

      $.ajax({
        url: 'index.php?r=Ajax/SaleTempInfo',
        data: {
          pk_temp: id_target
        },
        dataType: 'json',
        type: 'post',
        success: function(data) {
          if (data != null) {
            $('#barcode').text(data.barcode);
            $('#serial').text(data.serial);
            $('#name').text(data.name);
            $('#price').text(data.price);
          }
        }
      });
    }

    function removeItem() {
      $.ajax({
        url: 'index.php?r=Ajax/SaleTempMobileDelete',
        data: {
          pk_temp: pk_temp
        },
        type: 'post',
        success: function(data) {
          if (data == 'success') {
            location.reload();
          }
        }
      }); 
    }

    function endSale() {
      $.ajax({
        url: 'index.php?r=Ajax/EndSaleMobile',
        success: function(data) {
          if (data == 'success') {
            location.reload();
          }
        }
      });
    }
  </script>
</head>
<body>
  <div class="nav navbar-inverse" style="padding: 5px">
    <div class="pull-left">
      <a href="#" class="btn btn-primary" onclick="endSale()">
        <i class="glyphicon glyphicon-ok-sign"></i>
        จบการขาย
      </a>

      <a href="#" class="btn btn-primary" onclick="document.form1.submit()">
        <i class="glyphicon glyphicon-plus"></i>
        บันทึก
      </a>
      
      <!--
      <a href="" class="btn btn-primary btn-lg">
        <i class="glyphicon glyphicon-user"></i>
      </a>
      <a href="" class="btn btn-primary btn-lg">
        <i class="glyphicon glyphicon-plus"></i>
      </a>
      -->
    </div>
    <div class="pull-right">
      <span style="font-size: 25px; color: #f9f8f7" id="sum">0.00</span>
    </div>
    <div class="clearfix"></div>
  </div>

  <form name="form1" action="" method="post" class="form-inline">
    <div class="pull-left">
      <div class="input-group">
        <label style="width: 80px" class="input-group-addon">Barcode</label>
        <input type="text" name="barcode" class="form-control" />
      </div>
      <div class="input-group">
        <label style="width: 80px" class="input-group-addon">Serial</label>
        <input type="text" name="serial" class="form-control" />
      </div>
    </div>
    <div class="clearfix"></div>
  </form>

  <table class="table table-striped table-bordered">
    <tbody>
      <tbody>
        <?php foreach ($saleTemps as $saleTemp): ?>
        <tr>
          <td>
            <a href="#" data-toggle="modal" data-target="#myModal" onclick="return clickRow(<?php echo $saleTemp->pk_temp; ?>)">
              <div><?php echo $saleTemp->barcode; ?> : <?php echo $saleTemp->serial; ?></div>
              <div><?php echo $saleTemp->name; ?></div>
            </a>
          </td>
          <td width="80px" style="text-align: right">
            <?php echo number_format($saleTemp->price, 2); ?>
          </td>
        </tr>
        <?php $sum += $saleTemp->price; ?>
        <?php endforeach; ?>
      </tbody>
    </tbody>
  </table>

  <script>
    $(function() {
      $('#sum').text('<?php echo number_format($sum, 2); ?>');
    });
  </script>

  <!-- Modal -->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 250px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">รายการ</h4>
      </div>
      <div class="modal-body" id="modalContent">
        <div><h5>Barcode: <span id="barcode"></span></h5></div>
        <div><h5>Serial: <span id="serial"></span></h5></div>
        <div><h5 id="name"></h5></div>
        <div><h5>ราคา: <span id="price">0.00</span></h5></div>
      </div>
      <div class="modal-footer" style="text-align: center">
        <a href="#" onclick="removeItem()" class="btn btn-danger btn-lg">
          <i class="glyphicon glyphicon-remove"></i>
          ลบรายการ
        </a>
      </div>
    </div>
  </div>
</div>
</body>
</html>
