<?php

namespace Ten\Phpregex;

use Stringable;
use Closure;
use Ten\Phpregex\Expressions\Contains;
use Ten\Phpregex\Expressions\Positional;
use Ten\Phpregex\Expressions\Quantifiers;
use Ten\Phpregex\Expressions\Sequential;

class Regex implements Stringable
{
    use Contains;
    use Positional;
    use Quantifiers;
    use Sequential;

    /**
     * @var array<string>
     */
    private array $patterns = [];

    public static function build(): self
    {
        return new self();
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
        return (bool) preg_match($this->resolve(), $word);
    }

    public function sequence(Closure $callback): self
    {
        $callback(new Sequence($this));
        return $this;
    }

    public function addPattern(string $pattern): self
    {
        $this->patterns[] = $pattern;
        return $this;
    }

    public function setPattern(string $pattern): void
    {
        $this->patterns = [$pattern];
    }

    private function resolve(): string
    {
        return '/' . implode('', $this->patterns) . '/';
        // AND
        // $this->patterns[] = '/^(?=.*[aeijg])(?=.*\b(?:dog|cat|goat)\b).*/i';

        // OR
        // $this->patterns[] = '/[aeijg]|\b(?:dog|cat|goat)\b/i';


        // NOT
        // $this->patterns[] = '/^(?!.*[aeijg])(?!.*\b(?:dog|cat|goat)\b).*$/i';

    }

    public function __toString(): string
    {
        return $this->resolve();
    }

}
