<?php

namespace Ten\Phpregex\Expressions;

use function is_array;
use function is_string;

trait Contains
{
    public function contains(string|int $chars): self
    {
        $this->patterns[] = '(?=.*' . preg_quote((string) $chars, '/') . ')';
        return $this;
    }

    /**
     * @param string|array<int|string> $chars
     */
    public function containsAnyOf(string|array $chars): self
    {
        if (empty($chars)) {
            // return an exception
        }

        if (is_array($chars)) {
            $this->patterns[] = implode("|", $chars);
        }
        if (is_string($chars)) {
            $this->patterns[] = "[{$chars}]";
        }

        return $this;
    }

    /**
     * @param string|array<int|string> $chars
     */
    public function doesntContainAnyOf(string|array $chars): self
    {
        if (empty($chars)) {
            return $this;
        }

        if (is_array($chars)) {
            // Fails if any of the characters are present
            $this->patterns[] = '^(?!.*(' . implode('|', array_map(fn ($char): string => preg_quote((string) $char, '/'), $chars)) . ')).*$';
        } elseif (is_string($chars)) {
            $this->patterns[] = "^[^" . preg_quote($chars, '/') . "]*$";
        }

        return $this;
    }

    public function containsDigit(): self
    {
        $this->patterns[] = "\d";
        return $this;
    }

    public function doesntContainDigit(): self
    {
        // $this->patterns[] = "[^0-9]";
        $this->patterns[] = "^\D*$";
        return $this;
    }

    public function containsOnlyDigits(): self
    {
        $this->patterns[] = "^\d+$";
        return $this;
    }

    public function doesntContainOnlyDigits(): self
    {
        $this->patterns[] = "^(?!\d+$).+";
        return $this;
    }

    public function containsNonDigit(): self
    {
        $this->patterns[] = "\D";
        return $this;
    }

    public function containsAlphaNumeric(): self
    {
        $this->patterns[] = "[A-Za-z0-9]";
        return $this;
    }

    public function doesntContainAlphaNumeric(): self
    {
        $this->patterns[] = "^(?!.*[A-Za-z0-9]).*$";
        return $this;
    }

    public function containsOnlyAlphaNumeric(): self
    {
        $this->patterns[] = "^[A-Za-z0-9]+$";
        return $this;
    }

    public function doesntContainOnlyAlphaNumeric(): self
    {
        $this->patterns[] = "[^A-Za-z0-9]";
        return $this;
    }

    public function containsWordsThatBeginWith(string|int $subject): self
    {
        $this->patterns[] = "\b" . $subject;
        return $this;
    }

    public function containsWordsThatEndWith(string|int $subject): self
    {
        $this->patterns[] = $subject . "\b";
        return $this;
    }

    public function containsLetter(): self
    {
        $this->patterns[] = "[a-zA-Z]";
        return $this;
    }

    public function containsLowercaseLetter(): self
    {
        $this->patterns[] = "[a-z]";
        return $this;
    }

    public function containsUppercaseLetter(): self
    {
        $this->patterns[] = "[A-Z]";
        return $this;
    }

    public function containsWhitespace(): self
    {
        $this->patterns[] = "\s";
        return $this;
    }

    public function containsNonWhitespace(): self
    {
        $this->patterns[] = "\S";
        return $this;
    }

    public function containsWordCharacter(): self
    {
        $this->patterns[] = "\w";
        return $this;
    }

    public function containsNonWordCharacter(): self
    {
        $this->patterns[] = "\W";
        return $this;
    }

    public function containsAnything(): self
    {
        $this->patterns[] = ".";
        return $this;
    }
}
