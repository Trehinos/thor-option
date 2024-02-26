# Option type in PHP 8.1+

This package let you use an option (`Thor\Common\Maybe\Option`) type in PHP to handle cases when a value can or can not take a value.

The API of this module is intensively inspired by Rust's [Option type](https://doc.rust-lang.org/std/option/).

## Say goodbye to `null` values

With `Thor\Common\Maybe\Option`, you can wrap any value (including `null`) and will never take back a `null`.

## Examples

### Playing with some data
```php
use Thor\Maybe\Option;
use Thor\Maybe\Maybe;

$myOption = Option::some("data...");

if ($myOption->is_none()) {
    // Never
}
if ($myOption->is_a(Maybe::None)) {
    // Never
}
if ($myOption->is() === Maybe::Some) {
    // Here we know we can unwrap().
    $myString = $myOption->unwrap();
}

// Echoes the string if it is not none, or an empty string if it is :
$myOption->matches(
    fn(string $str) => print($str),
    fn() => '',
);
```

### Handling `NONE` values
```php
use Thor\Maybe\Option;

$myOption = Option::from(null);
$myOption = Option::none();

$value = $myOption->unwrap(); // Throws a RuntimeException
$value = $myOption->unwrap_or_throw(new Exception("Custom Exception"));
$value = $myOption->unwrap_or_else(fn() => 'default value from callable');
$value = $myOption->unwrap_or('default value');
```

## Reference

### Maybe enumeration

- Case `SOME` to represent the case when an option contains some value,
- Case `NONE` to represent the absence of value in an option.

### Option

#### Constructors

- `Option::from(mixed $value)` : create a new option with some value or none if `$value` is null,
- `Option::some(mixed $value)` : create a new option with some value,
- `Option::none()` : create a new option with none.

#### Informational methods

- `$myOption->is()` : returns a `Maybe::SOME` or a `Maybe::NONE`,
- `$myOption->is_none()` : returns `true` if the option is none, 
- `$myOption->is_some()` : returns `true` if the option is some, 
- `$myOption->is_a(Maybe $maybe)` : returns `true` if the option is corresponding the $maybe case.

#### Match

> Do something with the value if the Option contains a value,
> or do something else if the value is none.

```php
use Thor\Common\Option;
use Thor\Common\Maybe;

$myOption = Option::some("data...");

$myOption->matches(
    fn(string $str) => "My Option is Some($str)",
    fn() => 'My Option is None...',
);
```

#### Unwrap methods

- `$value = $myOption->unwrap()` : throws a RuntimeException if the value of the option is none,
- `$value = $myOption->unwrap_or_throw(new Exception("Custom Exception"))` : throws the specified `Throwable` if the value of the option is none,
- `$value = $myOption->unwrap_or_else(fn() => 'default value from callable')` : executes the callable in parameter if the value of the option is none and returns its returned value,
- `$value = $myOption->unwrap_or('default value')` : returns the specified value if the value of the option is none.

## License

Copyright 2024 Sébastien GELDREICH

License MIT