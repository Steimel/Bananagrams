<?php

$word = $_GET['word'];

if(isset($word))
{
    $d_file = file_get_contents('dictionary.txt');
    
    $dictionary = explode(PHP_EOL, $d_file);
    
    $min = 0;
    $max = count($dictionary);
    $is_word = false;
    
    while($min < $max && !$is_word)
    {
        $mid = floor(($max + $min) / 2);
        $c = strcasecmp(preg_replace("/[^a-z]+/", "", $dictionary[$mid]), $word);
        if($c < 0)
        {
            $min = $mid + 1;
        }
        elseif($c > 0)
        {
            $max = $mid;
        }
        else
        {
            $is_word = true;
        }
    }
    
    $close_words = array();
    $num_words = 9;
    if($mid < $num_words / 2)
    {
        $mid = ceil($num_words / 2);
    }
    if($mid + ceil($num_words / 2) > count($dictionary))
    {
        $mid = count($dictionary) - ceil($num_words / 2);
    }
    for($i = $mid - floor($num_words / 2); $i < $mid + ceil($num_words / 2); $i++)
    {
        $close_words[] = preg_replace("/[^a-z]+/", "", $dictionary[$i]);
    }
}

?>
<head>
<link rel="stylesheet" type="text/css" href="css/bootstrap/css/bootstrap.css">
</head>
<body>
<div class='container'>
<div class='bs-example'>
<form class="form" role="form" action="index.php" method="GET">
  <div class="form-group">
    <label for="inputWord">Word</label>
    <input type="text" class="form-control" id="inputWord" name="word" placeholder="Enter word">
  </div>
  <button type="submit" class="btn btn-default">Check Validity</button>
</form>
</div>
<br>
<?php
    if(isset($is_word))
    {
        if($is_word)
        {
            echo '<div class="alert alert-success">"' . $word . '" is a word</div><br>';
            echo '<p class="lead">Here are some close words:</p><br>';
            echo '<table class="table table-bordered table-striped table-hover table-condensed"><tbody>';
            for($i = 0; $i < count($close_words); $i++)
            {
                $c = strcasecmp($close_words[$i], $word);
                echo '<tr';
                if($c == 0)
                {
                    echo ' class="success"';
                }
                echo '><td>';
                echo $close_words[$i];
                echo '</td></tr>';
            }
            echo '</tbody></table><br><br>';
        }
        else
        {
            echo '<div class="alert alert-danger">"' . $word . '" is not a word</div><br>';
            echo '<p class="lead">Here are some close words:</p><br>';
            echo '<table class="table table-bordered table-striped table-hover table-condensed"><tbody>';
            for($i = 0; $i < count($close_words); $i++)
            {
                echo '<tr><td>';
                echo $close_words[$i];
                echo '</td></tr>';
            }
            echo '</tbody></table><br><br>';
        }
    }
?>
</div>
</body>

