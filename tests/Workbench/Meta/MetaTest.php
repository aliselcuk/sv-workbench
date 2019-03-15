<?php

namespace Tests\Workbench\Support\Meta;

use ArrayAccess;
use SuperV\Platform\Domains\Resource\Testing\ResourceTestHelpers;
use SuperV\Modules\Workbench\Support\Meta\Meta;
use Tests\Workbench\TestCase;

class MetaTest extends TestCase
{
    use ResourceTestHelpers;

    /** @var array */
    protected $metadata;

    /** @var \SuperV\Modules\Workbench\Support\Meta\Meta */
    protected $meta;

    protected function setUp()
    {
        parent::setUp();

        $this->metadata = [
            'type'      => 'string',
            'length'    => 255,
            'abc'       => 'cba',
            'array'     => ['d', 'e', 'f'],
            'associate' => ['g' => 'G', 'h' => 'H', 'i' => 'I'],
        ];
        $this->meta = new Meta($this->metadata);
    }

    function test__construct()
    {
        $meta = new Meta();
        $this->assertEquals([], $meta->data());

        $meta = new Meta([]);
        $this->assertEquals([], $meta->data());

        $meta = new Meta(['noor']);
        $this->assertEquals(['noor'], $meta->data());

        $meta = new Meta($this->metadata);
        $this->assertEquals($this->metadata, $meta->data());
    }

    function test__array_access()
    {
        $this->assertInstanceOf(ArrayAccess::class, $this->meta);
    }

    function test__data()
    {
        $this->assertEquals($this->metadata, $this->meta->compose());
    }

    function test__has()
    {
        $this->assertTrue($this->meta->has('type'));
    }

    function test__get()
    {
        $meta = new Meta($this->metadata);
        $this->assertEquals('string', $meta->get('type'));
        $this->assertEquals('H', $meta->get('associate.h'));

        $meta = new Meta(['parent' => $meta]);
        $this->assertEquals('string', $meta->get('parent.type'));
        $this->assertEquals('H', $meta->get('parent.associate.h'));
    }

    function test__get_with_dot_notation()
    {
        $meta = new Meta(['abc' => ['def' => true]]);
        $this->assertTrue($meta->get('abc.def'));

        $meta = new Meta(['abc' => ['def' => ['ghi' => true]]]);
        $this->assertTrue($meta->get('abc.def.ghi'));

        $meta = new Meta(['abc' => ['def' => ['ghi' => ['jkl' => true]]]]);
        $this->assertTrue($meta->get('abc.def.ghi.jkl'));

        $this->assertNull($meta->get('abc.xyz'));
        $this->assertNull($meta->get('abc.def.xyz'));
    }

    function test__get_with_default()
    {
        $this->assertEquals('default', $this->meta->get('nobody', 'default'));
    }

    function test__set_returns_self()
    {
        $this->assertInstanceOf(Meta::class, Meta::make()->set('key', 'valye'));
    }

    function test__set()
    {
        $this->assertEquals('ABC', Meta::make()->set('abc', 'ABC')->get('abc'));
    }

    function test__set_with_dot_notation()
    {
        $meta = new Meta();
        $meta->set('rules.nullable', true);

        $this->assertEquals(['rules' => ['nullable' => true]], $meta->compose());
    }

    function test__set_null()
    {
        $meta = new Meta(['key' => null]);
        $meta->set('foo', null);
        $this->assertFalse($meta->has('key'));
        $this->assertFalse($meta->has('foo'));
    }

    function test_set_converts_array_values()
    {
        $meta = Meta::make(['config' => ['rules' => ['unique', 'required']]]);

        $this->assertEquals(['rules' => ['unique', 'required']], $meta->get('config'));
        $this->assertEquals(['unique', 'required'], $meta->get('config.rules'));
        $this->assertEquals('unique', $meta->get('config.rules.0'));
    }

    function test__zip()
    {
        $meta = Meta::make()->set('abc.def', 'DEF');

        $this->assertEquals(['abc' => ['def' => 'DEF']], $meta->zip()->data());
    }

    function test__zip_returns_new_instance()
    {
        $meta = Meta::make();

        $this->assertNotEquals($meta, $meta->zip());
    }

    function test__offset_exists()
    {
        $this->assertTrue($this->meta->offsetExists('type'));
    }

    function test__offset_get()
    {
        $this->assertEquals('string', $this->meta->offsetGet('type'));
    }

    function test__offset_set()
    {
        $this->meta->offsetSet('type', 'number');
        $this->assertEquals('number', $this->meta->offsetGet('type'));
    }

    function test__offset_unset()
    {
        $this->meta->offsetUnset('type');
        $this->assertNull($this->meta->offsetGet('type'));
    }
}

