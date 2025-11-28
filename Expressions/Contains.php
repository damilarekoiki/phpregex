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

    public function doesntContainAny(string|array $chars) {
        if(empty($chars)) {
            // return an exception
        }

        if (is_array($chars)) {
            $this->patterns[] = "[^".implode("|", $chars)."]";
        }
        if(is_string($chars)) {
            $this->patterns[] = "[^$chars]";
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
        // $this->patterns[] = "^\D+$";
        $this->patterns[] = "^(?!\d+$).+/";
        return $this;
    }

    public function containsNonDigit() {
        $this->patterns[] = "\D";
        return $this;
    }

    public function containsAlphaNumeric() {
        $this->patterns[] = "\w";
        return $this;
    }

    public function doesntContainAlphaNumeric() {
        $this->patterns[] = "\W";
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