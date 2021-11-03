<?php

use Illuminate\Support\Facades\Route;

function stringCutter($length, $string)
{
  if (strlen($string) > $length) {
    return substr($string, 0, $length) . "...";
  } else {
    return $string;
  }
}

function activeLink($routeName)
{
  if (Route::current()->getName() == $routeName) {
    return 'active';
  }
}
