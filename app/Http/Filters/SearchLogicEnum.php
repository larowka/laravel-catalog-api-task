<?php

namespace App\Http\Filters;

interface SearchLogicEnum
{
    const NONE = 0;
    const CIRCLE = 1;
    const RECTANGLE = 2;
    const RECTANGLE_BY_POINT = 2;
}
