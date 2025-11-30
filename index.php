
<?php
    require_once 'Regex.php';

    $regexMatch = Regex::build()
        ->doesntContainOnlyAlphaNumeric()
        ->match('r bcat fcat2_');

    echo $regexMatch ? 'Match' : 'No Match';