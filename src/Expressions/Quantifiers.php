<?php

namespace Ten\Phpregex\Expressions;

trait Quantifiers {

    public function containsAtleastOne($subject) {
        $this->patterns[] = "$subject+";
        return $this;
    }

    public function containsZeroOrMore($subject) {
        $this->patterns[] = "$subject*";
        return $this;
    }

    public function containsZeroOrOne($subject) {
        $this->patterns[] = "$subject?";
        return $this;
    }
}
