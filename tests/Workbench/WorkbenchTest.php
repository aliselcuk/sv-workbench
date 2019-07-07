<?php

namespace Tests\Workbench;

class WorkbenchTest extends TestCase
{
    function test__module_is_installed()
    {
        $this->assertNotNull(superv('addons')->get('superv.modules.workbench'));
    }
}