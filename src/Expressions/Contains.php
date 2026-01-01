<?php

declare(strict_types=1);

namespace Ten\Phpregex\Expressions;

use function is_array;
use function is_string;

trait Contains
{
    public function contains(string|int $chars): self
    {
        $subject = preg_quote((string) $chars, '/');

        return $this->addPattern('(?=.*' . $subject . ')', false);
    }

    public function doesntContain(string|int $chars): self
    {
        return $this->addPattern('(?!.*' . preg_quote((string) $chars, '/') . ')', false);
    }

    /**
     * @param string|array<int|string> $chars
     */
    public function containsAnyOf(string|array $chars): self
    {
        if (empty($chars)) {
            return $this;
        }

        if (is_array($chars)) {
            $chars = array_map(fn ($char): string => preg_quote((string) $char, '/'), $chars);
            $this->addPattern('(?=.*(' . implode("|", $chars) . '))', false);
        }
        if (is_string($chars)) {
            $chars = preg_quote($chars, '/');
            $this->addPattern("(?=.*[{$chars}])", false);
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
            $this->addPattern('^(?!.*(' . implode('|', array_map(fn ($char): string => preg_quote((string) $char, '/'), $chars)) . ')).*$');
        } elseif (is_string($chars)) {
            $this->addPattern("^[^" . preg_quote($chars, '/') . "]*$");
        }

        return $this;
    }

    public function containsDigit(): self
    {
        return $this->addPattern('(?=.*\d)', false);
    }

    public function doesntContainDigit(): self
    {
        return $this->addPattern('^(?!.*\d).+$', false);
    }

    public function containsOnlyDigits(): self
    {
        return $this->addPattern("^\d+$");
    }

    public function doesntContainOnlyDigits(): self
    {
        return $this->addPattern("^(?!\d+$).+");
    }

    public function containsNonDigit(): self
    {
        return $this->addPattern('(?=.*\D)', false);
    }

    public function containsAlphaNumeric(): self
    {
        return $this->addPattern('(?=.*[A-Za-z0-9])', false);
    }

    public function doesntContainAlphaNumeric(): self
    {
        return $this->addPattern("^(?!.*[A-Za-z0-9]).*$");
    }

    public function containsOnlyAlphaNumeric(): self
    {
        return $this->addPattern("^[A-Za-z0-9]+$");
    }

    public function doesntContainOnlyAlphaNumeric(): self
    {
        return $this->addPattern("[^A-Za-z0-9]");
    }

    public function containsWordsThatBeginWith(string|int $subject): self
    {
        return $this->addPattern('(?=.*\b' . $subject . ')', false);
    }

    public function containsWordsThatEndWith(string|int $subject): self
    {
        return $this->addPattern('(?=.*' . $subject . '\b)', false);
    }

    public function containsLetter(): self
    {
        return $this->addPattern('(?=.*[a-zA-Z])', false);
    }

    public function containsLowercaseLetter(): self
    {
        return $this->addPattern('(?=.*[a-z])', false);
    }

    public function containsUppercaseLetter(): self
    {
        return $this->addPattern('(?=.*[A-Z])', false);
    }

    public function containsWhitespace(): self
    {
        return $this->addPattern('(?=.*\s)', false);
    }

    public function containsNonWhitespace(): self
    {
        return $this->addPattern('(?=.*\S)', false);
    }

    public function containsWordCharacter(): self
    {
        return $this->addPattern('(?=.*\w)', false);
    }

    public function containsNonWordCharacter(): self
    {
        return $this->addPattern('(?=.*\W)', false);
    }

    public function containsAnything(): self
    {
        return $this->addPattern('(?=.*.)', false);
    }
}
