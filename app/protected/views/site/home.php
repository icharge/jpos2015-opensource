<style>
  body {
    background-image: url(images/abstract-ubuntu-wallpaper.jpg);
    background-repeat: no-repeat;
    background-size: 100%;
  }
</style>

<script>
  function browseFile() {
    $("#background").trigger("click");
    $("#background").change(function() {
      document.formBackground.submit();
    });
  }
</script>

<div class="pull-right">
  <div style="margin-right: 15px; margin-top: 5px">
    <a href="#" onclick="browseFile()" class="btn btn-primary" style="margin-bottom: 10px">
      <i class="glyphicon glyphicon-plus"></i>
      เพิ่มภาพใหม่
    </a>

    <form method="post" action="index.php?r=Basic/BackgroundSave" enctype="multipart/form-data" name="formBackground">
      <input style="display: none" type="file" name="background" id="background" />
    </form>

    <div><img src="images/incredible-ubuntu-wallpaper.jpg" width="180px" height="140px" /></div>
    <div style="margin-top: 4px"><img src="images/lucid_lynx.jpg" width="180px" height="140px" /></div>
    <div style="margin-top: 4px"><img src="images/abstract-ubuntu-wallpaper.jpg" width="180px" height="140px" /></div>
    <div style="margin-top: 4px"><img src="images/ubuntu-wallpapers-13.jpg" width="180px" height="140px" /></div>
  </div>
</div>
<div class="clearfix"></div>