
<?php
    require_once 'Regex.php';

    $regexMatch = Regex::build()
        ->containsWordsThatEndWith('cat')
        ->match('rcat fcat');

    echo $regexMatch ? 'Match' : 'No Match';