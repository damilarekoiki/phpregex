<?php

namespace Ten\Phpregex;

use Ten\Phpregex\Expressions\Contains;
use Ten\Phpregex\Expressions\Positional;
use Ten\Phpregex\Expressions\Quantifiers;
use Ten\Phpregex\Expressions\Sequential;

class Regex
{
    use Contains;
    use Positional;
    use Quantifiers;
    use Sequential;

    private array $patterns = [];

    private string $word = "";

    public static function build(): self
    {
        $instance = new self();
        return $instance;
    }

    public function match(string $word): bool
    {
        // $this->resolve();
        // foreach ($this->patterns as $pattern) {
        //     if (preg_match($pattern, $this->word)) {
        //         return true;
        //     }
        // }
        // return false;
        return preg_match($this->resolve(), $word);
    }

    private function resolve(): string
    {
        return '/'.$this->patterns[0].'/';
        // AND
        // $this->patterns[] = '/^(?=.*[aeijg])(?=.*\b(?:dog|cat|goat)\b).*/i';

        // OR
        // $this->patterns[] = '/[aeijg]|\b(?:dog|cat|goat)\b/i';


        // NOT
        // $this->patterns[] = '/^(?!.*[aeijg])(?!.*\b(?:dog|cat|goat)\b).*$/i';

    }

}
