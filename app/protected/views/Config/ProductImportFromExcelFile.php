<script>
  function browseFile() {
    $("#excel_file").trigger("click");
    $("#formUpload").change(function() {
      var f = $("#excelFile");
      
      if (f != null) {
        document.formUpload.submit();
      }
    });
  }
</script>

<form name="formProductExcel" action="" style="display: none">
  <input type="hidden" name="excelFile" id="excelFile" />
</form>

<div class="panel panel-primary" style="margin: 10px">
  <div class="panel-heading">เลือกไฟล์จาก Excel</div>
  <div class="panel-body">
    <div class="alert alert-danger">
      * รูปแบบข้อมูลจะต้องเรียงดังนี้
    </div>

    <h4>ตัวอย่างข้อมูลใน excel (example-product.csv) ไม่ต้องมีหัวด้านบน ให้มีแต่ข้อมูลเท่านั้น</h4>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th width="50px">ลำดับ</th>
          <th width="200px">รหัสสินค้า/บาร์โค้ด</th>
          <th>ชื่อสินค้า</th>
          <th width="100px">ราคาทุน</th>
          <th width="100px">ราคาขาย</th>
          <th width="120px">จำนวนคงเหลือ</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>8850127044003</td>
          <td>ไมโลแอดทิฟ-บี 35กรัม/ซอง</td>
          <td>3</td>
          <td>8</td>
          <td>26</td>
        </tr>
        <tr>
          <td>2</td>
          <td>8850157400916</td>
          <td>เบนโตะ</td>
          <td>4</td>
          <td>50</td>
          <td>37</td>
        </tr>
        <tr>
          <td>3</td>
          <td>8851123212205</td>
          <td>M SPORT/หีบ</td>
          <td>0</td>
          <td>210</td>
          <td>94</td>
        </tr>
      </tbody>
    </table>

    <a href="#" class="btn btn-primary btn-lg" onclick="browseFile()">
      <i class="glyphicon glyphicon-download"></i>
      เลือกไฟล์เพื่อนำเข้า กดที่นี่ (ไฟล์นามสกุล .csv เท่านั้น)
    </a>

    <form id="formUpload" name="formUpload" method="post" action="index.php?r=Config/ProductImportFromExcelFile" enctype="multipart/form-data" style="display: none">
      <input type="file" id="excel_file" name="excel_file" />
    </form>
  </div>
</div>