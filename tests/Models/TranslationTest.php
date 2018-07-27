<?php

namespace Sysoce\Translation\Test\Models;

use Sysoce\Translation\Test\TestCase;
use Sysoce\Translation\Models\Translation as Model;

class TranslationTest extends TestCase
{

    public function it_creates_at_least_hundred_fake_models() {
        $models = [];

        Model::unguard();
        for ($i = 0; $i < mt_rand(100, 1000); $i++) {
            $text = $this->faker->unique()->text;
            $models[] = Model::create([
                'source_id' => null,
                'locale' => $this->faker->locale,
                'text' => $text
            ]);
        }
        Model::reguard();

        $this->assertTrue(count($models) >= 100);
    }

    /** @test */
    public function it_creates_a_model_with_firstOrCreate_if_the_model_does_not_exist()
    {
        $test_data = [
            'locale' => $this->faker->locale,
            'text' => $this->faker->unique()->text
        ];
        // fwrite(STDERR, print_r($test_data, TRUE));
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
        $model1 = Model::create($test_data);
        $this->assertInstanceOf(Model::class, $model1);
        $model2 = app(Model::class)->firstOrCreate($test_data);
        $this->assertTrue($model1->is($model2));
    }
}