<?php

namespace Tests\Workbench\Context;

use SuperV\Modules\Workbench\Domains\Context\Context;
use Tests\Workbench\TestCase;

require_once(__DIR__.'/fixtures.php');

class ContextTest  extends TestCase
{
    function test__apply()
    {
        $provider = new SolidProvider;
        $provider->plane = 'Boink';
        $provider->money = null;

        $acceptor = new SolidAcceptor;
        $acceptor->plane = null;
        $acceptor->money = 123;


        $context = new Context;
        $context->add($provider);
        $context->add($acceptor);

        $context->apply();

        $this->assertEquals('Boink', $acceptor->plane);
        $this->assertEquals('123', $provider->money);
    }
}