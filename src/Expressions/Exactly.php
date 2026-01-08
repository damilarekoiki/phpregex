<?php

declare(strict_types=1);

namespace DamilareKoiki\PhpRegex\Expressions;

use Closure;
use DamilareKoiki\PhpRegex\Regex;

use function is_array;

trait Exactly
{
    /**
     * Match any of the given characters or strings.
     *
     * @param string|int|array<int|string> $chars The characters or strings to match.
     */
    public function anyOf(string|int|array $chars): self
    {
        if (empty($chars)) {
            return $this;
        }

        if (is_array($chars)) {
            $chars = array_map(fn ($char): string => preg_quote((string) $char, '/'), $chars);
            return $this->addPattern('(' . implode("|", $chars) . ')');
        }

        $chars = preg_quote((string) $chars, '/');
        return $this->addPattern("[{$chars}]");
    }

    /**
     * Match a single digit.
     */
    public function digit(): self
    {
        return $this->addPattern('\d');
    }

    /**
     * Match only digits (entire string).
     */
    public function onlyDigits(): self
    {
        return $this->addPattern("^\d+$");
    }

    /**
     * Match a single non-digit character.
     */
    public function nonDigit(): self
    {
        return $this->addPattern('\D');
    }

    /**
     * Match only alpha-numeric characters (entire string).
     */
    public function onlyAlphaNumeric(): self
    {
        return $this->addPattern("^[A-Za-z0-9]+$");
    }

    /**
     * Match a single alpha-numeric character.
     */
    public function alphanumeric(): self
    {
        return $this->addPattern("[a-zA-Z0-9]");
    }

    /**
     * Match words that begin with the given characters.
     *
     * @param string|int $subject The characters to check for.
     */
    public function wordsThatBeginWith(string|int $subject): self
    {
        return $this->addPattern('\b' . preg_quote((string) $subject, '/'));
    }

    /**
     * Match words that end with the given characters.
     *
     * @param string|int $subject The characters to check for.
     */
    public function wordsThatEndWith(string|int $subject): self
    {
        return $this->addPattern(preg_quote((string) $subject, '/') . '\b');
    }

    /**
     * Match a single letter.
     */
    public function letter(): self
    {
        return $this->addPattern('[a-zA-Z]');
    }

    /**
     * Match a single lowercase letter.
     */
    public function lowercaseLetter(): self
    {
        return $this->addPattern('[a-z]');
    }

    /**
     * Match a single uppercase letter.
     */
    public function uppercaseLetter(): self
    {
        return $this->addPattern('[A-Z]');
    }

    /**
     * Match a single whitespace character.
     */
    public function whitespace(): self
    {
        return $this->addPattern('\s');
    }

    /**
     * Match a single non-whitespace character.
     */
    public function nonWhitespace(): self
    {
        return $this->addPattern('\S');
    }

    /**
     * Match a single word character.
     */
    public function wordCharacter(): self
    {
        return $this->addPattern('\w');
    }

    /**
     * Match a single non-word character.
     */
    public function nonWordCharacter(): self
    {
        return $this->addPattern('\W');
    }

    /**
     * Match anything (any single character).
     */
    public function anything(): self
    {
        return $this->addPattern('.');
    }

    /**
     * Add a pattern that follows the current patterns.
     *
     * @param string|int|Closure(Regex $regex): mixed $subject The pattern to add.
     */
    public function then(string|int|Closure $subject): self
    {
        return $this->addPattern($this->resolveSimplePattern($subject));
    }
}
