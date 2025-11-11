<?php

// require_once 'Expressions/Contains.php';
// require_once 'Expressions/Positional.php';
// require_once 'Expressions/Quantifiers.php';
// require_once 'Expressions/Sequential.php';

use Expressions\Contains;
use Expressions\Positional;
use Expressions\Quantifiers;
use Expressions\Sequential;

class Regex
{
    use Contains, Positional, Quantifiers, Sequential;

    private array $patterns = [];

    private string $word = "";

    public static function build(): self {
        $instance = new self();
        return $instance;
    }

    public function match(string $word): bool {
        // $this->resolve();
        // foreach ($this->patterns as $pattern) {
        //     if (preg_match($pattern, $this->word)) {
        //         return true;
        //     }
        // }
        // return false;
        return preg_match($this->resolve(), $word);
    }

    private function resolve(): string {
        return $this->patterns[0];
        // AND
        $this->patterns[] = '/^(?=.*[aeijg])(?=.*\b(?:dog|cat|goat)\b).*/i';

        // OR
        $this->patterns[] = '/[aeijg]|\b(?:dog|cat|goat)\b/i';


        // NOT
        $this->patterns[] = '/^(?!.*[aeijg])(?!.*\b(?:dog|cat|goat)\b).*$/i';

    }
    
}