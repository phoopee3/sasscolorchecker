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
        form span {
          position: relative;
        }
        span .clear {
          right: 8px;
          position: absolute;
          top: 11px;
        }
        .clear {
          background-image: url(bootstrap/img/glyphicons-halflings.png);
          background-position-x: -313px;
          background-position-y: -1px;
          display: none;
          /*display: block;*/
          filter:alpha(opacity=35);
          height: 12px;
          opacity:0.35;
          width: 12px;
          
        }
    </style>
    <script>
    $(function() {
        $('.tip').tooltip();
        $('form input').wrap("<span></span>").after("<span class='clear'></span>");
        $('form input').focus(function() {
          if ($(this).val() != '') $('form .clear').show();
        });
        $('form input').blur(function() {
          if ($(this).val() == '') $('form .clear').hide();
        });
        $('form input').keyup(function() {
          if ($(this).val() != '') $('form .clear').show();
          else $('form .clear').hide();
        })
        $('form .clear').click(function() {
          $(this).prev().val('');
          $(this).prev().keyup();
        })

        $('#usedColorSearch .search-query').keyup(function() {
          var filter = $(this).val();
          // search colors
          $('#usedColors a').each(function() {
            var dataSearch = $(this).attr('data-search');
            if (dataSearch && dataSearch.search(new RegExp(filter, "i")) < 0) {
              $(this).hide();
            } else {
              $(this).show();
            }
          });
          // search list
          $('#usedColors .accordion-inner .row').each(function() {
            var dataSearch = $(this).attr('data-search');
            if (dataSearch && dataSearch.search(new RegExp(filter, "i")) < 0) {
              $(this).hide();
            } else {
              $(this).show();
            }
          });
        });
        $('#unusedColorSearch .search-query').keyup(function() {
          var filter = $(this).val();
          // search colors
          $('#unusedColors a').each(function() {
            var dataSearch = $(this).attr('data-search');
            if (dataSearch && dataSearch.search(new RegExp(filter, "i")) < 0) {
              $(this).hide();
            } else {
              $(this).show();
            }
          });
          // search list
          $('#unusedColors .accordion-inner .row').each(function() {
            var dataSearch = $(this).attr('data-search');
            if (dataSearch && dataSearch.search(new RegExp(filter, "i")) < 0) {
              $(this).hide();
            } else {
              $(this).show();
            }
          });
        });
    });
    </script>
</head>
<body>
    