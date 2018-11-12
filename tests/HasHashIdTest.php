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
        $model = app(Model::class)->firstOrCreate($test_data);
        $this->assertInstanceOf(Model::class, $model);
    }

    /** @test */
    public function it_finds_a_model_with_firstOrCreate_if_the_model__exist()
    {
        $test_data = [
            'locale' => $this->faker->locale,
            'text' => $this->faker->unique()->text
        ];
        $model1 = app(Model::class)->create($test_data);
        $model2 = app(Model::class)->firstOrCreate($test_data);
        $this->assertTrue($model1->is($model2));
    }

    /** @test */
    public function it_gets_hashable_string()
    {
        $data = [
            'locale' => $this->faker->locale,
            'text' => $this->faker->unique()->text
        ];
        $string = $this->model->getHashableString($data);
        // TODO: remove implementation specific
        $this->assertTrue($string == $data['locale'].$data['text']);
    }

    /** @test */
    public function it_hashes_with_md5()
    {
        $string = 'testesthelloworld_md5hash';
        $expected = 'a5df194176bd9d4d50c8c0067d7d89ed';

        $hash1 = $this->model->hash($string);
        $this->assertTrue($hash1 == $expected);

        $hash2 = $this->model->hash($this->faker->unique()->text);
        $this->assertTrue($hash2 != $expected);
    }

    /** @test */
    public function it_generates_hash_id()
    {
        $data1 = [
            'locale' => 'en',
            'text' => 'kebab'
        ];
        $expected = '59079dac7bf569beabc50d8ab8e3efcf';

        $hash_id1 = $this->model->generateHashId($data1);
        $this->assertTrue($hash_id1 == $expected);

        $data2 = [
            'locale' => $this->faker->locale,
            'text' => $this->faker->unique()->text
        ];
        $hash_id2 = $this->model->generateHashId($data2);
        $this->assertTrue($hash_id1 != $hash_id2);
    }

    /** @test */
    public function it_replaces_hashable_attributes()
    {
        $data = [
            'locale' => 'en',
            'text' => 'kebab',
            'source_id' => 123
        ];
        $expected = '59079dac7bf569beabc50d8ab8e3efcf';

        $output = $this->model->replaceHashableAttributes($data);
        $this->assertNotNull($output['hash_id']);
        $this->assertTrue($output['hash_id'] == $expected);
        $this->assertTrue($output['source_id'] == $data['source_id']);
        $this->assertTrue(count($output) == 2);
    }
}