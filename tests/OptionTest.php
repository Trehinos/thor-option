<?php

use PHPUnit\Framework\TestCase;
use Thor\Maybe\Maybe;
use Thor\Maybe\Option;

final class OptionTest extends TestCase
{

    public function testNoneCreation() {
        $none = Option::none();
        $none2 = Option::from(null);
        $this->assertEquals($none, $none2);
    }

    public function testUnwrapRuntimeException() {
        $none = Option::none();
        $this->expectException(RuntimeException::class);
        $_ = $none->unwrap();
    }

    public function testUnwrapCustomException() {
        $none = Option::none();
        $this->expectException(Throwable::class);
        $_ = $none->unwrapOrThrow(new Exception('custom exception'));
    }

    public function testDefaultValue() {
        $some = Option::some(1);
        $none = Option::none();
        $this->assertEquals($some->unwrapOr(0), $none->unwrapOr(1));
    }

    public function testMaybe() {
        $some = Option::some(1);
        $none = Option::none();
        $this->assertEquals(Maybe::SOME, $some->is());
        $this->assertEquals(Maybe::NONE, $none->is());
    }

}