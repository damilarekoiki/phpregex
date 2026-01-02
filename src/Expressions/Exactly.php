<?php

declare(strict_types=1);

namespace Ten\Phpregex\Expressions;

use function is_array;

trait Exactly
{
    /**
     * @param string|int|array<int|string> $chars
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

    public function digit(): self
    {
        return $this->addPattern('\d');
    }

    public function onlyDigits(): self
    {
        return $this->addPattern("^\d+$");
    }

    public function nonDigit(): self
    {
        return $this->addPattern('\D');
    }

    public function onlyAlphaNumeric(): self
    {
        return $this->addPattern("^[A-Za-z0-9]+$");
    }

    public function alphanumeric(): self
    {
        return $this->addPattern("[a-zA-Z0-9]");
    }

    public function wordsThatBeginWith(string|int $subject): self
    {
        return $this->addPattern('\b' . preg_quote((string) $subject, '/'));
    }

    public function wordsThatEndWith(string|int $subject): self
    {
        return $this->addPattern(preg_quote((string) $subject, '/') . '\b');
    }

    public function letter(): self
    {
        return $this->addPattern('[a-zA-Z]');
    }

    public function lowercaseLetter(): self
    {
        return $this->addPattern('[a-z]');
    }

    public function uppercaseLetter(): self
    {
        return $this->addPattern('[A-Z]');
    }

    public function whitespace(): self
    {
        return $this->addPattern('\s');
    }

    public function nonWhitespace(): self
    {
        return $this->addPattern('\S');
    }

    public function wordCharacter(): self
    {
        return $this->addPattern('\w');
    }

    public function nonWordCharacter(): self
    {
        return $this->addPattern('\W');
    }

    public function anything(): self
    {
        return $this->addPattern('.');
    }
}
