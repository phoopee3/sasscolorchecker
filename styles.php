<!DOCTYPE html>
<html lang="en">
<head>
    <title>SASS Color Checker</title>
    <script type="text/javascript" src="<?php echo $pathTojQuery; ?>"></script>
    <script type="text/javascript" src="<?php echo $pathToBootstrapJS; ?>"></script>
    <link rel="stylesheet" href="<?php echo $pathToBootstrapCSS; ?>" />
    <link href="<?php echo $pathToPrismCSS; ?>" rel="stylesheet" />
    <script src="<?php echo $pathToPrismJS; ?>"></script>
    <style type="text/css">
        .colorBox {
            border: 1px solid #000000;
            float: left;
            height: 30px;
            margin-right: 10px;
            width: 30px;
        }
        .form-inline {
          /* display:; */
        }
        .form-right {
          float:right;
        }
        .tooltip.in {
          opacity: 100;
          filter: alpha(opacity=100);
        }
        .show-grid {
          margin-top: 10px;
          margin-bottom: 20px;
        }
        .show-grid [class*="span"] {
          background-color: #eee;
          text-align: center;
          -webkit-border-radius: 3px;
             -moz-border-radius: 3px;
                  border-radius: 3px;
          min-height: 40px;
          line-height: 40px;
          margin-bottom: 20px;
        }
        .show-grid [class*="span"]:hover {
          background-color: #ddd;
        }
        .show-grid .show-grid {
          margin-top: 0;
          margin-bottom: 0;
        }
        .show-grid .show-grid [class*="span"] {
          margin-top: 5px;
        }
        .show-grid [class*="span"] [class*="span"] {
          background-color: #ccc;
        }
        .show-grid [class*="span"] [class*="span"] [class*="span"] {
          background-color: #999;
        }
    </style>
    <script>
    $(function() {
        $('.tip').tooltip();
    });
    </script>
</head>
<body>
    