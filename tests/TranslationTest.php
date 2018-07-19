<?php

namespace Sysoce\Translation\Test;

use Sysoce\Translation\Exceptions\AttributeIsNotTranslatable;

class TranslationTest extends TestCase
{
    /** @var \Sysoce\Translation\Test\TestModel */
    protected $testModel;

    public function setUp()
    {
        parent::setUp();

        $this->testModel = new TestModel();
    }

    /** @test */
    public function it_will_work()
    {
        //
    }
}