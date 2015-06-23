<!DOCTYPE html>
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
    echo CHtml::scriptFile('js/jquery-2.0.3.js');
    echo CHtml::scriptFile('js/bootstrap.js');
    echo CHtml::scriptFile('js/jquery-ui-1.10.3.custom.js');
    ?>

  </head>
  <body style="margin: 0px;">
    <div class="content" style="padding: 10px;">
      <?php echo $content; ?>
    </div>
  </body>
</html>

