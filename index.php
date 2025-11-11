
<?php
    // require_once 'Regex.php';

    // Class(es) and Interface(s)
// Methods that are intuitive enough
// Parser(s) that convert the user's expression to regex and if-else statements

    $regexMatch = Regex::build()
        ->beginsWith('abc')
        ->match('abcdef');

    echo $regexMatch ? 'Match' : 'No Match';