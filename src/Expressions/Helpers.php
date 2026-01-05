<?php

declare(strict_types=1);

namespace Ten\Phpregex\Expressions;

trait Helpers
{
    /**
     * Match a standard email address.
     */
    public function email(): self
    {
        return $this->addPattern("[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}");
    }

    /**
     * Match an IPv4 address.
     */
    public function ipv4(): self
    {
        return $this->addPattern("(?:(?:\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])");
    }

    /**
     * Match an IPv6 address.
     */
    public function ipv6(): self
    {
        return $this->addPattern("(?:[a-fA-F0-9]{1,4}:){7}[a-fA-F0-9]{1,4}");
    }

    /**
     * Match an IP address (v4 or v6).
     */
    public function ip(): self
    {
        return $this->addPattern("(?:(?:(?:\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])|(?:[a-fA-F0-9]{1,4}:){7}[a-fA-F0-9]{1,4})");
    }

    /**
     * Match a UUID.
     */
    public function uuid(): self
    {
        return $this->addPattern("[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}");
    }

    /**
     * Match a URL.
     */
    public function url(): self
    {
        return $this->addPattern("https?:\/\/(?:www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()@:%_\+.~#?&\/=]*)");
    }

    /**
     * Match only alpha characters (letters).
     */
    public function alpha(): self
    {
        return $this->addPattern("[a-zA-Z]+");
    }

    /**
     * Match a string of digits.
     */
    public function digits(): self
    {
        return $this->addPattern("[0-9]+");
    }

    /**
     * Match a hex color (e.g., #fff or #ffffff).
     */
    public function hexColor(): self
    {
        return $this->addPattern("#(?:[0-9a-fA-F]{3}){1,2}");
    }

    /**
     * Match a slug (e.g., my-awesome-post).
     */
    public function slug(): self
    {
        return $this->addPattern("[a-z0-9]+(?:-[a-z0-9]+)*");
    }

    /**
     * Match a credit card number.
     */
    public function creditCard(): self
    {
        return $this->addPattern("(?:(?:\d{4}[ -]?){3}\d{4})");
    }

    /**
     * Match a Social Security Number (SSN).
     */
    public function ssn(): self
    {
        return $this->addPattern("\d{3}-\d{2}-\d{4}");
    }

    /**
     * Match a US ZIP code.
     */
    public function zipCode(): self
    {
        return $this->addPattern("\d{5}(?:-\d{4})?");
    }

    /**
     * Match a MAC address.
     */
    public function macAddress(): self
    {
        return $this->addPattern("(?:[0-9a-fA-F]{2}[:-]){5}[0-9a-fA-F]{2}");
    }

    /**
     * Match a date (YYYY-MM-DD).
     */
    public function date(): self
    {
        return $this->addPattern("\d{4}-\d{2}-\d{2}");
    }

    /**
     * Match a time (HH:MM:SS).
     */
    public function time(): self
    {
        return $this->addPattern("(0[0-9]|1[0-9]|2[0-3]):[0-5]\\d:[0-5]\\d");
    }

    /**
     * Match a social media handle (e.g., @username).
     */
    public function socialHandle(): self
    {
        return $this->addPattern("@[a-zA-Z0-9_]{1,15}");
    }

    /**
     * Match a hex string.
     */
    public function hex(): self
    {
        return $this->addPattern("[a-fA-F0-9]+");
    }
}
