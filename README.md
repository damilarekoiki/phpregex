# phpregex

[![Tests](https://github.com/damilarekoiki/phpregex/actions/workflows/ci.yml/badge.svg)](https://github.com/damilarekoiki/phpregex/actions/workflows/ci.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%5E8.2-777bb4.svg)](https://www.php.net/)

An intuitive, readable, and powerful fluent interface for working with Regular Expressions in PHP.

---

## Features

- **Fluent API**: Chain methods to build complex patterns naturally.
- **Readable Syntax**: Methods like `beginsWith`, `contains`, `digit`, and `or` make your intent clear.
- **Lookahead Support**: Easily implement complex "must contain" logic without the headache.
- **Atomic & Reusable**: Build patterns using closures and reuse them across your project.
- **Built-in Helpers**: One-call solutions for common tasks like `email`, `url`, `uuid`, and more.
- **Safe by Default**: Automatically handles `preg_quote` where necessary.

---

## Installation

You can install the package via composer:

```bash
composer require ten/phpregex
```

---

## Usage

### Basic Example

Checking if a string is a valid invoice number (starts with `INV-`, followed by digits, and ends with `.pdf`):

```php
use Ten\Phpregex\Regex;

$regex = Regex::build()
    ->beginsWith('INV')
    ->then('-')
    ->digits()
    ->endsWith('.pdf');

if ($regex->matches('INV-12345.pdf')) {
    // It's a match!
}
```

### Powerful Lookaheads

Validate a password: must contain at least one digit, one uppercase letter, and be at least 8 characters long:

```php
$regex = Regex::build()
    ->containsDigit()
    ->containsUppercaseLetter()
    ->containsAtLeastSequencesOf(fn($r) => $r->anything(), 8);

$regex->matches('Pass1234'); // true
```

### Data Extraction & Replacement

```php
$regex = Regex::build()->digits(); // A consecutive sequence of digits

// Count matches
$count = $regex->count('There are 12 apples and 34 oranges.'); // 2

// Replace matches
$clean = $regex->replace('Order #12345', 'XXXXX'); // "Order #XXXXX"

// Using callbacks for replacement
$result = $regex->replace('Score: 10', function($match) {
    return (int)$match[0] * 2;
}); // "Score: 20"
```

---

## API Reference

### Foundation

| Method | Description |
| :--- | :--- |
| `Regex::build(bool $fullStringMatch = false)` | Start building a new regex. |
| `matches(string $subject)` | Check if the pattern matches. |
| `count(string $subject)` | Count occurrences. |
| `replace(string $subject, string\|callable $replacement)` | Replace matches. |
| `get()` | Get the full regex string (e.g., `/^abc/i`). |
| `getPattern()` | Get the raw regex pattern (e.g., `^abc`). |
| `group(Closure $closure)` | Wrap patterns in a capturing group (parentheses). |
| `overridePattern(string\|Closure $pattern)` | Overwrite all current patterns to a new one. |
| `isEmpty()` | Check if no patterns have been added. |

### Positional & Boundaries

| Method | Description |
| :--- | :--- |
| `beginsWith(string\|int $subject)` | Match if the string begins with the given characters. |
| `endsWith(string\|int $subject)` | Match if the string ends with the given characters. |
| `between(array $ranges, bool $caseSensitive = true)` | Match characters within specified ranges. |
| `notBetween(array $ranges, bool $caseSensitive = true)` | Match characters not within specified ranges. |

### Presence (Lookaheads)

| Method | Description |
| :--- | :--- |
| `contains(string\|int $chars)` | Check if the pattern exists anywhere. |
| `doesntContain(string\|int $chars)` | Check if the pattern does NOT exist. |
| `containsAnyOf(string\|array $chars)` | Check if any of the given characters exist. |
| `doesntContainAnyOf(string\|array $chars)` | Check if none of the given characters exist. |
| `containsDigit()` | Check if a digit exists. |
| `doesntContainDigit()` | Check if no digits exist. |
| `containsOnlyDigits()` | Check if the string contains only digits. |
| `doesntContainOnlyDigits()` | Check if the string does not consist solely of digits. |
| `containsNonDigit()` | Check if a non-digit character exists. |
| `containsAlphaNumeric()` | Check if an alpha-numeric character exists. |
| `doesntContainAlphaNumeric()` | Check if no alpha-numeric characters exist. |
| `containsOnlyAlphaNumeric()` | Check if the string contains only alpha-numeric characters. |
| `doesntContainOnlyAlphaNumeric()` | Check if the string does not consist solely of alpha-numeric characters. |
| `containsBetween(array $ranges, bool $caseSensitive = true)` | Check if characters within specified ranges exist `e.g. containsBetween(['a-z', '0-9'], caseSensitive: false) matches letters between a-z or A-Z or digits between 0-9 anywhere in the string`. |
| `doesntContainBetween(array $ranges, bool $caseSensitive = true)` | Check if characters within specified ranges do NOT exist. |
| `containsWordsThatBeginWith(string\|int $subject)` | Check if words starting with specific characters exist. |
| `containsWordsThatEndWith(string\|int $subject)` | Check if words ending with specific characters exist. |
| `containsLetter()` | Check if a letter exists. |
| `containsLowercaseLetter()` | Check if a lowercase letter exists. |
| `containsUppercaseLetter()` | Check if an uppercase letter exists. |
| `containsWhitespace()` | Check if whitespace exists. |
| `containsNonWhitespace()` | Check if a non-whitespace character exists. |
| `containsWordCharacter()` | Check if a word character ([a-zA-Z0-9_]) exists. |
| `containsNonWordCharacter()` | Check if a non-word character exists. |
| `containsAnything()` | Check if any character exists. |

### Exact Matches (Consuming)

| Method | Description |
| :--- | :--- |
| `digit()` | Match a single digit. |
| `onlyDigits()` | Match only digits (entire string). |
| `nonDigit()` | Match a single non-digit character. |
| `letter()` | Match a single letter. |
| `lowercaseLetter()` | Match a single lowercase letter. |
| `uppercaseLetter()` | Match a single uppercase letter. |
| `alphanumeric()` | Match a single alpha-numeric character. |
| `onlyAlphaNumeric()` | Match only alpha-numeric characters (entire string). |
| `whitespace()` | Match a single whitespace character. |
| `nonWhitespace()` | Match a single non-whitespace character. |
| `wordCharacter()` | Match a single word character. |
| `nonWordCharacter()` | Match a single non-word character. |
| `anyOf(string\|int\|array $chars)` | Match one of the given characters/strings. |
| `wordsThatBeginWith(string\|int $subject)` | Match words that begin with the given characters. |
| `wordsThatEndWith(string\|int $subject)` | Match words that end with the given characters. |
| `anything()` | Match any single character. |
| `then(string\|int\|Closure $subject)` | Add the next part of the pattern. |

### Quantifiers

| Method | Description |
| :--- | :--- |
| `atLeastOne(string\|int $subject)` | Match 1 or more occurrences. |
| `zeroOrMore(string\|int $subject)` | Match 0 or more occurrences. |
| `zeroOrOne(string\|int $subject)` | Match 0 or 1 occurrence. |
| `exactSequencesOf(string\|int\|Closure $subject, int $count)` | Match exactly N times. |
| `sequencesOf(string\|int\|Closure $subject, int $min, int $max)` | Match between N and M times. |
| `atLeastSequencesOf(string\|int\|Closure $subject, int $min)` | Match at least N times. |
| `containsAtleastOne(string\|int $subject)` | Check if at least one occurrence exists (lookahead). |
| `containsZeroOrMore(string\|int $subject)` | Check if zero or more occurrences exist (lookahead). |
| `containsZeroOrOne(string\|int $subject)` | Check if zero or one occurrence exists (lookahead). |
| `containsExactSequencesOf(string\|int\|Closure $subject, int $count)` | Check if an exact number of sequences exists (lookahead). |
| `containsSequencesOf(string\|int\|Closure $subject, int $min, int $max)` | Check if a range of sequences exists (lookahead). |
| `containsAtleastSequencesOf(string\|int\|Closure $subject, int $min)` | Check if at least N sequences exist (lookahead). |

### Logic & Conditionals

| Method | Description |
| :--- | :--- |
| `or()` | Alternative `\|`. |
| `and(string\|null\|Closure $subject = null)` | Conjunction (lookahead). |
| `not(string\|Closure $subject)` | Negative lookahead. |
| `when(bool $condition, Closure $callback)` | Conditionally add patterns. |

### Flags

| Method | Description |
| :--- | :--- |
| `ignoreCase()` | Ignore case when matching. |
| `multiline()` | Match across multiple lines. |
| `dotAll()` | Allow the dot (.) to match newlines. |
| `extended()` | Ignore whitespace in the pattern. |
| `utf8()` | Enable UTF-8 support. |
| `ungreedy()` | Match as little as possible. |
| `ignoreCaseFor(string\|Closure $subject)` | Ignore case for a specific part of the pattern. |
| `multilineFor(string\|Closure $subject)` | Match across multiple lines for a specific part. |
| `dotAllFor(string\|Closure $subject)` | Allow the dot to match newlines for a specific part. |
| `extendedFor(string\|Closure $subject)` | Ignore whitespace for a specific part. |
| `utf8For(string\|Closure $subject)` | Enable UTF-8 support for a specific part. |
| `ungreedyFor(string\|Closure $subject)` | Match as little as possible for a specific part. |

### Helpers (Presets)

Highly optimized patterns for common use cases:

| Method | Description |
| :--- | :--- |
| `email()` | Matches a valid email address. |
| `url()` | Matches a valid URL. |
| `ipv4()` | Matches an IPv4 address. |
| `ipv6()` | Matches an IPv6 address. |
| `ip()` | Matches either an IPv4 or IPv6 address. |
| `uuid()` | Matches a UUID. |
| `slug()` | Matches a URL-friendly slug. |
| `creditCard()` | Matches a generic credit card number. |
| `ssn()` | Matches a US Social Security Number. |
| `zipCode()` | Matches a US Zip Code. |
| `macAddress()` | Matches a MAC address. |
| `date()` | Matches a date string. |
| `time()` | Matches a time string. |
| `hexColor()` | Matches a hex color code (e.g., #fff or #ffffff). |
| `socialHandle()` | Matches common social media handles (e.g., @username). |
| `hex()` | Matches a hexadecimal string. |
| `digits()` | Matches a consecutive sequence of digits. |

---

## Complex Sequences

Use `containsSequence` to match a sequence of patterns anywhere in the string:

```php
$regex = Regex::build()->containsSequence(function (Sequence $sequence) {
    $sequence->then('Step 1')
      ->then(fn($r) => $r->digits())
      ->then('Finished');
});
```

Matches `Step 123Finished`, `abc Step 1562890Finished`, `Step 123Finished def`.

---

## Consuming vs. Non-Consuming Patterns

Patterns in this library generally fall into two categories:

- **Consuming Patterns**: Methods like `then()`, `digit()`, or `anyOf()` match *and* consume characters. They move the regex "cursor" forward.
- **Non-Consuming Patterns (Lookaheads)**: Methods in the **Presence** category (e.g., `contains*() methods`) and `and()` are zero-width assertions. They check if a pattern exists without actually matching or "eating" the characters.

### Important: `count()` and `replace()`

Because non-consuming patterns (Lookaheads) match zero characters, they **do not work as expected** with `count()` and `replace()`:

- **`count($subject)`**: A lookahead might match once at a specific position but won't "progress" through the string.
- **`replace($subject, $replacement)`**: Since no characters are consumed, `replace()` will insert the replacement text at the matched position instead of replacing existing text.

**Alternative**: Use **Exact Matches (Consuming)** if you need to count or replace specific substrings.

```php
// ❌ This will insert 'bar' at the start of the string if 'foo' exists anywhere.
Regex::build()->contains('foo')->replace('I have foo', 'bar');

// ✅ This will replace 'foo' with 'bar'.
Regex::build()->then('foo')->replace('I have foo', 'bar');
```

## Testing

The package is thoroughly tested with [Pest PHP](https://pestphp.com/).

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
