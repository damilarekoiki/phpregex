<?php

declare(strict_types=1);

namespace Ten\Phpregex\Expressions;

use Closure;

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

        $pattern = $this->resolveSimplePattern($subject);
        return $this->addPattern("(?=.*{$pattern})", false);
    }

    public function not(string|Closure $subject): self
    {
        $pattern = $this->resolveSimplePattern($subject);
        return $this->addPattern("(?!{$pattern})", false);
    }

    public function when(bool $condition, Closure $callback): self
    {
        if ($condition) {
            $callback($this);
        }

        return $this;
    }
}
