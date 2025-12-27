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
        return (bool) preg_match($this->resolve(), $word);
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
        return '/' . $this->getPattern() . '/';
        // AND
        // $this->patterns[] = '/^(?=.*[aeijg])(?=.*\b(?:dog|cat|goat)\b).*/i';

        // OR
        // $this->patterns[] = '/[aeijg]|\b(?:dog|cat|goat)\b/i';


        // NOT
        // $this->patterns[] = '/^(?!.*[aeijg])(?!.*\b(?:dog|cat|goat)\b).*$/i';

    }

    public function get(): string
    {
        return $this->resolve();
    }

    public function getPattern(): string
    {
        return implode('', $this->patterns);
    }
}
