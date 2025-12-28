<?php

namespace Ten\Phpregex\Expressions;

use Closure;
use Ten\Phpregex\Regex;

/**
 * @property static $or Specifies alternatives
 * @property static $and Specifies conjunctions
 */

trait Booleans
{
    public function or(): self
    {
        return $this->addPattern('|', false);
    }

    public function and(string|Closure|null $subject = null): self
    {
        if ($subject === null) {
            return $this;
        }

        $pattern = $this->resolvePattern($subject);
        return $this->addPattern("(?=.*{$pattern})", false);
    }

    public function not(string|Closure $subject): self
    {
        $pattern = $this->resolvePattern($subject);
        return $this->addPattern("(?!{$pattern})", false);
    }

    public function when(bool $condition, Closure $callback): self
    {
        if ($condition) {
            $callback($this);
        }

        return $this;
    }

    private function resolvePattern(string|Closure $subject): string
    {
        if ($subject instanceof Closure) {
            $regex = (new Regex())->build();
            $subject($regex);
            return $regex->getPattern();
        }

        return preg_quote((string) $subject, '/');
    }
}
