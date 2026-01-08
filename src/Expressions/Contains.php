<?php

declare(strict_types=1);

namespace DamilareKoiki\PhpRegex\Expressions;

use DamilareKoiki\PhpRegex\Resolvers\RangePattern;

use function is_array;
use function is_string;

trait Contains
{
    /**
     * Checks if the subject contains the given characters.
     *
     * @param string|int $chars The characters to check for.
     */
    public function contains(string|int $chars): self
    {
        $subject = preg_quote((string) $chars, '/');

        return $this->addPattern('(?=.*' . $subject . ')', false);
    }

    /**
     * Checks if the subject does not contain the given characters.
     *
     * @param string|int $chars The characters to check for.
     */
    public function doesntContain(string|int $chars): self
    {
        return $this->addPattern('(?!.*' . preg_quote((string) $chars, '/') . ')', false);
    }

    /**
     * Checks if the subject contains any of the given characters.
     *
     * @param string|array<int|string> $chars The characters to check for.
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
     * Checks if the subject does not contain any of the given characters.
     *
     * @param string|array<int|string> $chars The characters to check for.
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

    /**
     * Checks if the subject contains a digit.
     */
    public function containsDigit(): self
    {
        return $this->addPattern('(?=.*\d)', false);
    }

    /**
     * Checks if the subject does not contain a digit.
     */
    public function doesntContainDigit(): self
    {
        return $this->addPattern('^(?!.*\d).+$', false);
    }

    /**
     * Checks if the subject contains only digits.
     */
    public function containsOnlyDigits(): self
    {
        return $this->addPattern("^\d+$");
    }

    /**
     * Checks if the subject contains characters within the specified ranges.
     *
     * @param array<string|int, string|int> $ranges Array where key is range start, value is range end.
     * @param bool $caseSensitive Whether the range check should be case sensitive.
     */
    public function containsBetween(array $ranges, bool $caseSensitive = true): self
    {
        return $this->addPattern('(?=.*' . new RangePattern($ranges, negated: false, caseSensitive: $caseSensitive) . ')', false);
    }

    /**
     * Checks if the subject does not contain characters within the specified ranges.
     *
     * @param array<string|int, string|int> $ranges Array where key is range start, value is range end.
     * @param bool $caseSensitive Whether the range check should be case sensitive.
     */
    public function doesntContainBetween(array $ranges, bool $caseSensitive = true): self
    {
        return $this->addPattern('^(?!.*' . new RangePattern($ranges, negated: false, caseSensitive: $caseSensitive) . ').+$', false);
    }

    /**
     * Checks if the subject does not contain only digits.
     */
    public function doesntContainOnlyDigits(): self
    {
        return $this->addPattern("^(?!\d+$).+");
    }

    /**
     * Checks if the subject contains a non-digit character.
     */
    public function containsNonDigit(): self
    {
        return $this->addPattern('(?=.*\D)', false);
    }

    /**
     * Checks if the subject contains an alpha-numeric character.
     */
    public function containsAlphaNumeric(): self
    {
        return $this->addPattern('(?=.*[A-Za-z0-9])', false);
    }

    /**
     * Checks if the subject does not contain an alpha-numeric character.
     */
    public function doesntContainAlphaNumeric(): self
    {
        return $this->addPattern("^(?!.*[A-Za-z0-9]).*$");
    }

    /**
     * Checks if the subject contains only alpha-numeric characters.
     */
    public function containsOnlyAlphaNumeric(): self
    {
        return $this->addPattern("^[A-Za-z0-9]+$");
    }

    /**
     * Checks if the subject does not contain only alpha-numeric characters.
     */
    public function doesntContainOnlyAlphaNumeric(): self
    {
        return $this->addPattern("[^A-Za-z0-9]");
    }

    /**
     * Checks if the subject contains words that begin with the given characters.
     *
     * @param string|int $subject The characters to check for.
     */
    public function containsWordsThatBeginWith(string|int $subject): self
    {
        return $this->addPattern('(?=.*\b' . $subject . ')', false);
    }

    /**
     * Checks if the subject contains words that end with the given characters.
     *
     * @param string|int $subject The characters to check for.
     */
    public function containsWordsThatEndWith(string|int $subject): self
    {
        return $this->addPattern('(?=.*' . $subject . '\b)', false);
    }

    /**
     * Checks if the subject contains a letter.
     */
    public function containsLetter(): self
    {
        return $this->addPattern('(?=.*[a-zA-Z])', false);
    }

    /**
     * Checks if the subject contains a lowercase letter.
     */
    public function containsLowercaseLetter(): self
    {
        return $this->addPattern('(?=.*[a-z])', false);
    }

    /**
     * Checks if the subject contains an uppercase letter.
     */
    public function containsUppercaseLetter(): self
    {
        return $this->addPattern('(?=.*[A-Z])', false);
    }

    /**
     * Checks if the subject contains whitespace.
     */
    public function containsWhitespace(): self
    {
        return $this->addPattern('(?=.*\s)', false);
    }

    /**
     * Checks if the subject contains non-whitespace characters.
     */
    public function containsNonWhitespace(): self
    {
        return $this->addPattern('(?=.*\S)', false);
    }

    /**
     * Checks if the subject contains a word character.
     */
    public function containsWordCharacter(): self
    {
        return $this->addPattern('(?=.*\w)', false);
    }

    /**
     * Checks if the subject contains a non-word character.
     */
    public function containsNonWordCharacter(): self
    {
        return $this->addPattern('(?=.*\W)', false);
    }

    /**
     * Checks if the subject contains anything (any single character).
     */
    public function containsAnything(): self
    {
        return $this->addPattern('(?=.*.)', false);
    }
}
