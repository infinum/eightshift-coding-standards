<?php

class ExampleClass {
  public function methodName(string $key): string {
    return $key;
  }
}

class ExampleClassFail
{
  public function methodName(string $key): string
  {
    return $key;
  }
}

class Wrong_Class {
  public function method_name( string $key ) : string {
    return $key;
  }
}

class WrongSpacing {
  public function methodName( string $key ) : string {
    return $key;
  }
}
