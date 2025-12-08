
<?php
require_once 'vendor/autoload.php';

use Ten\Phpregex\Regex;

$regexMatch = Regex::build()
    ->doesntContainOnlyAlphaNumeric()
    ->match('r bcat fcat2_');

echo $regexMatch ? 'Match' : 'No Match';
