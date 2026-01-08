<?php

declare(strict_types=1);

namespace DamilareKoiki\PhpRegex\Expressions;

use Closure;
use DamilareKoiki\PhpRegex\Regex;

trait Flags
{
    /**
     * @var array<int, string>
     */
    protected array $flags = [];

    /**
     * Ignore case when matching.
     */
    public function ignoreCase(): self
    {
        $this->flags[] = 'i';
        return $this;
    }

    /**
     * Match across multiple lines.
     */
    public function multiline(): self
    {
        $this->flags[] = 'm';
        return $this;
    }

    /**
     * Allow the dot (.) to match newlines.
     */
    public function dotAll(): self
    {
        $this->flags[] = 's';
        return $this;
    }

    /**
     * Ignore whitespace in the pattern.
     */
    public function extended(): self
    {
        $this->flags[] = 'x';
        return $this;
    }

    /**
     * Enable UTF-8 support.
     */
    public function utf8(): self
    {
        $this->flags[] = 'u';
        return $this;
    }

    /**
     * Make quantifiers match as little as possible.
     */
    public function ungreedy(): self
    {
        $this->flags[] = 'U';
        return $this;
    }

    /**
     * Ignore case for a specific part of the pattern.
     *
     * @param string|Closure(Regex $regex): mixed $subject
     */
    public function ignoreCaseFor(string|Closure $subject): self
    {
        return $this->addLocalFlag('i', $subject);
    }

    /**
     * Match across multiple lines for a specific part of the pattern.
     *
     * @param string|Closure(Regex $regex): mixed $subject
     */
    public function multilineFor(string|Closure $subject): self
    {
        return $this->addLocalFlag('m', $subject);
    }

    /**
     * Allow the dot to match newlines for a specific part of the pattern.
     *
     * @param string|Closure(Regex $regex): mixed $subject
     */
    public function dotAllFor(string|Closure $subject): self
    {
        return $this->addLocalFlag('s', $subject);
    }

    /**
     * Ignore whitespace for a specific part of the pattern.
     *
     * @param string|Closure(Regex $regex): mixed $subject
     */
    public function extendedFor(string|Closure $subject): self
    {
        return $this->addLocalFlag('x', $subject);
    }

    /**
     * Enable UTF-8 support for a specific part of the pattern.
     *
     * @param string|Closure(Regex $regex): mixed $subject
     */
    public function utf8For(string|Closure $subject): self
    {
        return $this->addLocalFlag('u', $subject);
    }

    /**
     * Make quantifiers ungreedy for a specific part of the pattern.
     *
     * @param string|Closure(Regex $regex): mixed $subject
     */
    public function ungreedyFor(string|Closure $subject): self
    {
        return $this->addLocalFlag('U', $subject);
    }

    /**
     * Add a local flag group around a pattern.
     *
     * @param string|Closure(Regex $regex): mixed $subject
     */
    private function addLocalFlag(string $flag, string|Closure $subject): self
    {
        $pattern = '';

        if ($subject instanceof Closure) {
            $regex = Regex::build();
            $subject($regex);
            $pattern = $regex->getPattern();
        } else {
            $pattern = $subject;
        }

        $this->patterns[] = "(?{$flag}:{$pattern})";
        return $this;
    }
}
