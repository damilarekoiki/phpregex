<?php
namespace Expressions;

trait Positional {

    public function between($subject1, $subject2, $caseSensitive) {
        $this->patterns[] = "[$subject1-$subject2]";
        return $this;
    }

    public function notBetween($subject1, $subject2) {
        $this->patterns[] = "[^$subject1-$subject2]";
        return $this;
    }

    public function beginsWith($subject) {
        $this->patterns[] = "^$subject";
        return $this;
    }

    public function endsWith($subject) {
        $this->patterns[] = "$subject$";
        return $this;
    }
}