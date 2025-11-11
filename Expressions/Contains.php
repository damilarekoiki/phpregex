<?php

namespace Expressions;

trait Contains {
    public function contains($chars) {
        $this->patterns[] = "$chars";
        return $this;
    }

    public function containsAny(string|array $chars) {
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

    public function containsNone() {

    }

    public function containsDigit() {
        $this->patterns[] = "\d";
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

    public function containsNonAlphaNumeric() {
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