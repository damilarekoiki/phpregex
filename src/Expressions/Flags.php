<?php

declare(strict_types=1);

namespace Ten\Phpregex\Expressions;

use Closure;
use Ten\Phpregex\Regex;

trait Flags
{
    /**
     * @var array<int, string>
     */
    protected array $flags = [];

    public function ignoreCase(): self
    {
        $this->flags[] = 'i';
        return $this;
    }

    public function multiline(): self
    {
        $this->flags[] = 'm';
        return $this;
    }

    public function dotAll(): self
    {
        $this->flags[] = 's';
        return $this;
    }

    public function extended(): self
    {
        $this->flags[] = 'x';
        return $this;
    }

    public function utf8(): self
    {
        $this->flags[] = 'u';
        return $this;
    }

    public function ungreedy(): self
    {
        $this->flags[] = 'U';
        return $this;
    }

    public function ignoreCaseFor(string|Closure $subject): self
    {
        return $this->addLocalFlag('i', $subject);
    }

    public function multilineFor(string|Closure $subject): self
    {
        return $this->addLocalFlag('m', $subject);
    }

    public function dotAllFor(string|Closure $subject): self
    {
        return $this->addLocalFlag('s', $subject);
    }

    public function extendedFor(string|Closure $subject): self
    {
        return $this->addLocalFlag('x', $subject);
    }

    public function utf8For(string|Closure $subject): self
    {
        return $this->addLocalFlag('u', $subject);
    }

    public function ungreedyFor(string|Closure $subject): self
    {
        return $this->addLocalFlag('U', $subject);
    }

    private function addLocalFlag(string $flag, string|Closure $subject): self
    {
        $pattern = '';

        if ($subject instanceof Closure) {
            $regex = (new Regex())->build();
            $subject($regex);
            $pattern = $regex->getPattern();
        } else {
            $pattern = $subject;
        }

        $this->patterns[] = "(?{$flag}:{$pattern})";
        return $this;
    }
}
