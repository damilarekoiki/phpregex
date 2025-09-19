<?php

class Regex
{

    
    public function __construct()
    {
        
    }

    /*
        Contains
    */

    public function contains($chars) {
        $pattern = "$chars";
    }

    public function containsAny(string|array $chars) {
        if(empty($chars)) {
            // return an exception
        }

        if (is_array($chars)) {
            $chars = implode("|", $chars) ?? "";
        }
        if(is_string($chars)) {
            $chars = "[$chars]";
        }

        $pattern = "/$chars/";

    }

    public function containsNone() {

    }

    public function containsDigit() {
        $pattern = "/\d/";
    }

    public function containsNonDigit() {
        $pattern = "/\D/";
    }

    public function containsAlphaNumeric() {
        $pattern = "/\w/";
    }

    public function containsNonAlphaNumeric() {
        $pattern = "/\W/";
    }

    /*
        Quantifiers
    */

    public function containsAtleastOne($subject) {
        $pattern = "/$subject+/";
    }

    public function containsZeroOrMore($subject) {
        $pattern = "/$subject*/";
    }

    public function containsZeroOrOne($subject) {
        $pattern = "/$subject?/";
    }

    /*
        Sequences
    */
    public function containsExactSequencesOf($subject, $occurences) {
        $pattern = "/$subject{$occurences}/";
    }

    public function containsSequencesOf($subject, $minOcurrences, $maxOccurrences) {
        $pattern = "/$subject{ $minOcurrences,$maxOccurrences }/";
    }

    public function containsAtleastSequencesOf($subject, $minOcurrences) {
        // $pattern = "/$subject{$minOcurrences , }/";
    }

    /*
        Position
    */

    public function between($subject1, $subject2, $caseSensitive) {
        $pattern = "/[$subject1-$subject2]/";
    }

    public function notBetween($subject1, $subject2) {
        $pattern = "/[^$subject1-$subject2]/";
    }

    public function beginsWith($subject) {
        $pattern = "/^$subject/";
    }

    public function endsWith($subject) {
        $pattern = "/$subject$/";
    }

    public function containsWordsThatBeginWith($subject) {
        $pattern = "/\b$subject/";
    }

    public function containsWordsThatEndWith($subject) {
        $pattern = "/$subject\b/";
    }

    private function resolve() {
        // AND
        $pattern = '/^(?=.*[aeijg])(?=.*\b(?:dog|cat|goat)\b).*/i';

        // OR
        $pattern = '/[aeijg]|\b(?:dog|cat|goat)\b/i';


        // NOT
        $pattern = '/^(?!.*[aeijg])(?!.*\b(?:dog|cat|goat)\b).*$/i';
    }
    
}