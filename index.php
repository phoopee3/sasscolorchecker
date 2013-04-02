<?php
/**
 * SASS Color Checker
 * Scan a directory of sass files and compile 
 * a cheat sheet of colors and where they are used
 */

// load config
include('config.php');

$variables = $colors = $unknown = array();

// open default each file and look for lines that start with a $
$files = glob($directoryToScan . "*.scss");

foreach($files as $file) {
    $handle = fopen($file, 'r');
    if ($handle) {
        while (($buffer = fgets($handle, 4096)) !== false) {
            $buffer = preg_replace('/\s+/', '', $buffer);
            parseBuffer($buffer);
        }
        if (!feof($handle)) {
            echo "Error: unexpected fgets() fail\n";
        }
        fclose($handle);
    } else {
        echo "handle failed";
    }
}

uasort($colors, 'sortByColor');
// ksort($colors);
// asort($colors);
// combine arrays - not being used
$variables = array_merge($variables, $colors);

// search through the generated css file and match the colors
$css = parseCSS($cssFile);

// loop through the variables and find the colors that are used
ob_start();
foreach($colors as $key => $value) {
    $styles = searchArrayForValue($css,$value);
    if ($styles) {
        $modalValue = 'modal' . substr($value, 1);
        echo "<div id='$modalValue' class='modal hide fade'>" . array2Css($styles, "$key: $value", $value) . "</div>";
    } else {
        // put these styles in an 'notfound' array
        $notFound[$key] = $value;
        unset($colors[$key]);
    }
}
$colorModals = ob_get_clean();
// it would be better to search through the scss files and parse those

include('styles.php');
echo "<div class='container'>";
// m(count($colors));
$colorsLength = count($colors);
$span = 'span1';
?>
<div class='row'>
  <div class='span3'>
    <h3>Colors in use</h3>
  </div>
  <div class='span9 text-right'>
    <h3><form id="usedColorSearch" action="" class="form-inline form-search"><input type="text" class="search-query" placeholder="Search"></form></h3>
  </div>
</div>
<div id="usedColors">
  <div class='row show-grid'>
  <?php
  $i = 0;
  foreach($colors as $key => $value) {
      if ($value[0] == '#') {
          $modalValue = 'modal' . substr($value, 1);
          echo "<a data-search='$key,$value' href='#$modalValue' data-toggle='modal' title='$key: $value' class='tip $span' style='background-color:$value;outline:1px solid #000000;outline-offset:-1px;'></a>";
      }
      $i++;
  }
  ?>
  </div> <!-- end row -->

  <div class="accordion" id="accordion1">
    <div class="accordion-group">
      <div class="accordion-heading">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne">
          List of just the colors
        </a>
      </div>
      <div id="collapseOne" class="accordion-body collapse">
        <div class="accordion-inner">
          <?php display(array_values(array_unique($colors)), 1); ?>
        </div>
      </div>
    </div>
    <div class="accordion-group">
      <div class="accordion-heading">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseTwo">
          List of the names and colors
        </a>
      </div>
      <div id="collapseTwo" class="accordion-body collapse">
        <div class="accordion-inner">
          <?php display($colors, 0); ?>
        </div>
      </div>
    </div>
  </div>
</div> <!-- end usedColors -->

<div class='row'>
  <div class='span3'>
    <h3>Colors not in use</h3>
  </div>
  <div class='span9 text-right'>
    <h3><form id="unusedColorSearch" action="" class="form-inline form-search"><input type="text" class="search-query" placeholder="Search"></form></h3>
  </div>
</div>
<div class='alert alert-info'>Some of these colors might be used as function parameters</div>
<div id="unusedColors">
  <div class='row show-grid'>
  <?php
  // m($notFound);
  $i = 0;
  foreach($notFound as $key => $value) {
      if ($value[0] == '#') {
          $modalValue = 'modal' . substr($value, 1);
          echo "<a title='$key: $value' data-search='$key,$value' href='#$modalValue' data-toggle='modal' class='tip $span' style='background-color:$value;outline:1px solid #000000;outline-offset:-1px;'></a>";
      }
      $i++;
  }
  ?>
  </div> <!-- end row -->

  <div class="accordion" id="accordion2">
    <div class="accordion-group">
      <div class="accordion-heading">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
          List of the unused colors
        </a>
      </div>
      <div id="collapseThree" class="accordion-body collapse">
        <div class="accordion-inner">
          <?php display(array_values(array_unique($notFound)), 1); ?>
        </div>
      </div>
    </div>
    <div class="accordion-group">
      <div class="accordion-heading">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseFour">
          List of the unused names and colors
        </a>
      </div>
      <div id="collapseFour" class="accordion-body collapse">
        <div class="accordion-inner">
          <?php display($notFound, 0); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
echo $colorModals;

echo "</div>"; // end container

?>
<script>
// filter code for used colors
$().ready(function() {
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
