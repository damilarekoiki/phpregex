<?php

namespace Expressions;

use function is_array;
use function is_string;

trait Contains {
    public function contains(string|int $chars) {
        $this->patterns[] = (string) $chars;
        return $this;
    }

    public function containsAnyOf(string|array $chars) {
        if(empty($chars)) {
            // return an exception
        }

        if (is_array($chars)) {
            $this->patterns[] = implode("|", $chars) ?? "";
        }
        if(is_string($chars)) {
            $this->patterns[] = "[$chars]";
        }

        return $this;
    }

    public function doesntContainAnyOf(string|array $chars) {
        if(empty($chars)) {
            return $this;
        }

        if (is_array($chars)) {
            // Fails if any of the characters are present
            $this->patterns[] = '^(?!.*(' . implode('|', array_map(fn($char) => preg_quote($char, '/'), $chars)) . ')).*$';
        } elseif (is_string($chars)) {
            $this->patterns[] = "^[^" . preg_quote($chars, '/') . "]*$";
        }

        return $this;
    }

    public function containsDigit() {
        $this->patterns[] = "\d";
        return $this;
    }

    public function doesntContainDigit() {
        // $this->patterns[] = "[^0-9]";
        $this->patterns[] = "^\D*$";
        return $this;
    }

    public function containsOnlyDigits() {
        $this->patterns[] = "^\d+$";
        return $this;
    }

    public function doesntContainOnlyDigits() {
        $this->patterns[] = "^(?!\d+$).+";
        return $this;
    }

    public function containsNonDigit() {
        $this->patterns[] = "\D";
        return $this;
    }

    public function containsAlphaNumeric() {
        $this->patterns[] = "[A-Za-z0-9]";
        return $this;
    }

    public function doesntContainAlphaNumeric() {
        $this->patterns[] = "^(?!.*[A-Za-z0-9]).*$";
        return $this;
    }

    public function containsOnlyAlphaNumeric() {
        $this->patterns[] = "^[A-Za-z0-9]+$";
        return $this;
    }

    public function doesntContainOnlyAlphaNumeric() {
        $this->patterns[] = "[^A-Za-z0-9]";
        return $this;
    }

    public function containsWordsThatBeginWith($subject) {
        $this->patterns[] = "\b$subject";
        return $this;
    }

    public function containsWordsThatEndWith($subject) {
        $this->patterns[] = "$subject\b";
        return $this;
    }
}