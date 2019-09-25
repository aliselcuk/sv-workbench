<?php

namespace Tests\Workbench;

use Illuminate\Foundation\Testing\RefreshDatabase;
use SuperV\Platform\Testing\PlatformTestCase;

class TestCase extends PlatformTestCase
{
    use RefreshDatabase;

    protected $installs = ['addons/superv/modules/workbench'];
}