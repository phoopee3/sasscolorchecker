<?php
function array2Css($array, $header='', $colorBox='') {
    ob_start();
    if ($header) { ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3 id="myModalLabel"><?php echo $header; ?> <?php if ($colorBox) { echo '<span style="background-color:' . $colorBox . '" class="colorBox"></span>'; } ?></h3>
        </div>
    <?php } ?>
    <div class="modal-body">
        <p>
            <pre><code class="language-css"><?php
                foreach($array as $entry) {
                    foreach($entry as $selector => $declarations) {
                        echo "$selector {\n";
                        foreach($declarations as $declaration) {
                            foreach($declaration as $property => $value) {
                                echo "\t$property: $value;\n";
                            }
                        }
                        echo "}\n";
                    }
                }
                ?></code></pre>
        </p>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
    <?php
    return ob_get_clean();
}

function parseBuffer($buffer) {
    global $colors, $variables, $unknown;

    if (parseThisLine($buffer)) {
        // echo "buffer = $buffer<br/>";
        // break apart the "$key:[$|#]?value;"
        list($key, $value) = explode(':', $buffer);
        // turn "$key" into "key"
        $key = substr($key, 1);
        // turn "[$|#]?value;" into "[$|#]?value"
        $value = substr($value, 0, -1);
        // see if the value is a variable reference
        if ($value[0] == '$') {
            $value = substr($value, 1);
            // echo "looking for $value reference in <br/>";
            // m($colors);
            // m($variables);
            $keyReference = checkArraysForKey($value);
            if ($keyReference) {
                $value = $keyReference;
            } else {
                // put it in unknown[]
                $unknown[$key] = '$' . $value;
                $value = '-'; // for skipping in the next section
            }
        }
        if ($value[0] == '#') {
            $colors[$key] = $value;
            // since we just found a new value we should look in the unknowns
            checkUnknowns();
        } else if ($value[0] != '-') {
            $variables[$key] = $value;
            // since we just found a new value we should look in the unknowns
            checkUnknowns();
        }
    }
}

function checkUnknowns() {
    global $colors, $variables, $unknown;
    foreach($unknown as $key => $value) {
        // m("looking at $key => $value");
        // look in colors and variables KEYS
        $keyReference = checkArraysForKey($value);
        if ($keyReference) {
            if ($keyReference[0] == '#') {
                $colors[$key] = $keyReference;
            } else if ($keyReference[0] != '-') {
                $variables[$key] = $keyReference;
            }
            // remove reference from unknown[]
            unset($unknown[$key]);
        }
    }
}

/**
 * check a list of arrays for a key
 */
function checkArraysForKey($value, $arrays=Array('colors','variables')) {
    global $colors, $variables, $unknown;
    if ($value[0] == '$') $value = substr($value, 1);
    foreach($arrays as $array) {
        // m("looking in $array for $value");
        // m($$array);
        if (array_key_exists($value, ${$array})) {
            // m("found in $array, returning " . ${$array}[$value]);
            return ${$array}[$value];
        }
    }
    return false;
}

function parseCSS($cssFile) {
    $css = file_get_contents($cssFile);

    preg_match_all( '/(?ims)([a-z0-9\s\.\:#_\-\(\)@]+)\{([^\}]*)\}/', $css, $arr);
    // m($css);
    // m($arr);
    $result = array();
    foreach ($arr[0] as $i => $x) {
        $selector = trim($arr[1][$i]);
        // m($selector);
        // ignore code comments like
        // @var {string}
        if ($selector && $selector[0] != '@') {
            $declarations = explode(';', trim($arr[2][$i]));
            $result[$selector] = array();
            foreach ($declarations as $declaration) {
                if (!empty($declaration) && strpos($declaration, ':')) {
                    // m($declaration);
                    list($property, $value) = explode(":", $declaration);
                    $result[$selector][][trim($property)] = trim($value);
                }
            }
        }
    }
    return $result;
}

function parseThisLine($buffer) {
    return ($buffer && $buffer[0] == '$' && strpos($buffer, ':'));
}

function searchArrayForValue($haystack, $needle) {
    $results = array();
    // m($haystack);exit;
    // div -> array
    //      0 -> array
    //          property -> value
    //      1 -> array
    //          property -> value
    // li -> array
    //      0 -> array
    //          property -> value
    //      1 -> array
    //          property -> value
    //          
    foreach($haystack as $selector => $declarations) {
        // m("$selector => $declarations");
        foreach($declarations as $i => $declaration) {
            // m("$i => $declaration");
            foreach($declaration as $property => $value) {
                // m("$property => $value");
                if (strpos($value, $needle) !== false) {
                    // check if the selector is already in the array
                    $found = 0;
                    foreach($results as $results_key => $results_value) {
                        $results_value_key = array_keys($results_value);
                        $results_value_key = $results_value_key[0];
                        if ($selector == $results_value_key) {
                            $found = 1;
                        }
                    }
                    if (!$found) {
                        $results[] = array($selector => $declarations);
                    }
                }
            }
        }
    }
    if ($results) {
        return $results;
    } else {
        return false;
    }
}

function m($str, $hr=1) {
    if ($hr) echo "<hr/>";
    echo "<pre>";
    print_r($str);
    echo "</pre>";
    if ($hr) echo "<hr/>";
}

function display($array, $style = 0) {
    if ($style == 0) {
        foreach ($array as $key => $value) {
            echo "<div data-search='$key,$value' class='row'><div class='span3'>$key</div><div class='span8'>$value</div></div>";
        }
    } else if ($style == 1) {
        foreach ($array as $value) {
            echo "<div data-search='$value' class='row'><div class='span11'>$value</div></div>";
        }
    }
}


function sortByColor($color1, $color2) {
    // convert colors from hex to rgb
    $c = new Color();
    $color1 = checkColorLength($color1);
    $color2 = checkColorLength($color2);
    $c1 = calculateLuminance($c->hex2rgb($color1));
    $c2 = calculateLuminance($c->hex2rgb($color2));
    if ($c1 < $c2) return -1;
    if ($c1 == $c2) return 0;
    if ($c1 > $c2) return 1;
}

function checkColorLength($color) {
    if ($color[0] == '#') {
        $color = substr($color, 1);
        if (strlen($color) == 3) {
            // double each character
            $color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
        } else if (strlen($color) > 6) {
            $color = substr($color, 0, 6);
        }
        $color = '#' . $color;
    }
    return $color;
}

function calculateLuminance($colorObj) {
    // return (0.2126*$colorObj['r']) + (0.7152*$colorObj['g']) + (0.0722*$colorObj['b']);
    return (0.299*$colorObj['r'] + 0.587*$colorObj['g'] + 0.114*$colorObj['b']);
    // return sqrt( 0.241 * $colorObj['r']^2 + 0.691 * $colorObj['g']^2 + 0.068 * $colorObj['b']^2 );
}