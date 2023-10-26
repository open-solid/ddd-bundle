<?php

namespace Yceruto\DddBundle;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class DddBundle extends AbstractBundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
