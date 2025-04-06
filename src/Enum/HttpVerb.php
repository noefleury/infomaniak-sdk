<?php

namespace NoeFleury\InfomaniakSdk\Enum;

enum HttpVerb: string
{

    case Get = 'GET';
    case Post = 'POST';
    case Patch = 'PATCH';
    case Put = 'PUT';
    case Delete = 'DELETE';

}
