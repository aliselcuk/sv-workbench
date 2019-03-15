<?php

namespace Tests\Workbench;

class WorkbenchTest extends TestCase
{
    /** @test */
    function module_is_installed()
    {
        $this->assertNotNull(superv('addons')->get('superv.modules.workbench'));
    }
}