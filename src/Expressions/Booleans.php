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
    /**
     * Add an "OR" alternator to the regex.
     */
    public function or(): self
    {
        return $this->addPattern('|', false);
    }

    /**
     * Add an "AND" lookahead to the regex.
     *
     * @param string|Closure|null $subject The pattern to look for.
     */
    public function and(string|Closure|null $subject = null): self
    {
        if ($subject === null) {
            return $this;
        }

        $pattern = $this->resolveSimplePattern($subject);
        return $this->addPattern("(?=.*{$pattern})", false);
    }

    /**
     * Add a negative lookahead to the regex.
     *
     * @param string|Closure $subject The pattern that should not match.
     */
    public function not(string|Closure $subject): self
    {
        $pattern = $this->resolveSimplePattern($subject);
        return $this->addPattern("(?!{$pattern})", false);
    }

    /**
     * Conditionally apply a set of patterns.
     *
     * @param bool $condition The condition to check.
     * @param Closure $callback A closure that defines the patterns to apply.

     */
    public function when(bool $condition, Closure $callback): self
    {
        if ($condition) {
            $callback($this);
        }

        return $this;
    }
}
