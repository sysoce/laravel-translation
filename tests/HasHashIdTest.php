<?php

namespace Sysoce\Translation\Test;

use Sysoce\Translation\Exceptions\AttributeIsNotTranslatable;
use Sysoce\Translation\Models\Translation as Model;

class HasHashIdTest extends TestCase
{
    /** @var \Sysoce\Translation\Models\Translation */
    protected $model;

    /** @var \Sysoce\Translation\Models\Translation */
    protected $savedModel;

    public function setUp()
    {
        parent::setUp();

        $test_data = [
            'locale' => $this->faker->locale,
            'text' => $this->faker->unique()->text
        ];
        $this->model = new Model($test_data);
        $this->savedModel = app(Model::class)->create($test_data);
    }

    /** @test */
    public function it_saves_hash_id_on_creation()
    {
        $this->assertNull($this->model->hash_id);
        $this->assertNotNull($this->savedModel->hash_id);
    }

    /** @test */
    public function it_creates_a_model_with_firstOrCreate_if_the_model_does_not_exist()
    {
        $test_data = [
            'locale' => $this->faker->locale,
            'text' => $this->faker->unique()->text
        ];
        $model1 = app(Model::class)->where('locale', $test_data['locale'])->where('text', $test_data['text'])->first();
        $this->assertNull($model1);
        $model2 = app(Model::class)->firstOrCreate($test_data);
        $this->assertInstanceOf(Model::class, $model2);
    }

    /** @test */
    public function it_finds_a_model_with_firstOrCreate_if_the_model__exist()
    {
        $test_data = [
            'locale' => $this->faker->locale,
            'text' => $this->faker->unique()->text
        ];
        $model1 = app(Model::class)->create($test_data);
        $this->assertInstanceOf(Model::class, $model1);
        $model2 = app(Model::class)->firstOrCreate($test_data);
        $this->assertTrue($model1->is($model2));
    }

    /** @test */
    public function it_gets_hashable_string()
    {
        $data = [
            'locale' => $this->faker->locale,
            'text' => $this->faker->unique()->locale
        ];
        $string = $this->model->getHashableString($data);
        // TODO: remove implementation specific
        $this->assertTrue($string == $data['locale'].$data['text']);
    }

    /** @test */
    public function it_hashes()
    {
        $hash1 = $this->model->hash($this->faker->unique()->text);
        $hash2 = $this->model->hash($this->faker->unique()->text);
        $this->assertTrue($hash1 != $hash2);
    }
}