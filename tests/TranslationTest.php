<?php

namespace Sysoce\Translation\Test;

use Sysoce\Translation\Translation;
use Sysoce\Translation\Models\Translation as Model;

class TranslationTest extends TestCase
{
    /** @test */
    public function it_sets_and_gets_source()
    {
        $source = 'ja';
        app(Translation::class)->setSource('ja');
        $this->assertEquals($source, app(Translation::class)->getSource());
        $source = 'nl';
        app(Translation::class)->setSource('nl');
        $this->assertEquals($source, app(Translation::class)->getSource());
    }

    /** @test */
    public function it_sets_and_gets_target()
    {
        $target = 'ja';
        app(Translation::class)->setTarget('ja');
        $this->assertEquals($target, app(Translation::class)->getTarget());
        $target = 'nl';
        app(Translation::class)->setTarget('nl');
        $this->assertEquals($target, app(Translation::class)->getTarget());
    }

    /** @test */
    public function it_has_attribute_client()
    {
        $this->assertObjectHasAttribute('client', app(Translation::class));
    }

    /** @test */
    public function class_has_attribute_client()
    {
        $this->assertClassHasAttribute('client', Translation::class);
    }

    /** @test
     * Comment out to test default Client (Google Translate):
     * CAUTION: costs money! ご注意ください。お金がかかります！
    **/
    // public function it_translates_text()
    // {
    //     app(Translation::class)->setSource('en');
    //     app(Translation::class)->setTarget('ja');
    //     $source = 'Hello';
    //     $expected = 'こんにちは';
    //     $translation = app(Translation::class)->translate($source);
    //     $this->assertEquals($expected, $translation->text);
    // }

    /** @test
     * CAUTION: costs money if fail! ご注意ください。バッツとお金がかかります！
    **/
    public function it_uses_dictionary()
    {
        $text = 'Kebab';
        $expected = 'ケバブ';
        $source_locale = 'en';
        $target_locale = 'ja';

        $source_translation = app(Model::class)->create(['locale' => $source_locale, 'text' => $text]);
        $translation1 = $source_translation->translations()->create([
            'locale' => $target_locale,
            'text' => $expected,
        ]);

        app(Translation::class)->setSource($source_locale);
        app(Translation::class)->setTarget($target_locale);

        $translation2 = app(Translation::class)->translate($text);
        $this->assertTrue($translation1->is($translation2));
    }

    /** @test */
    public function it_serializes()
    {
        Model::unguard();
        $test_data = [
            "id" => 1,
            "locale" => $this->faker->locale,
            "text" => $this->faker->unique()->text,
            "updated_at" => "2018-07-27 17:14:08",
            "created_at" => "2018-07-27 17:14:08",
        ];
        $model = app(Model::class)->create($test_data);
        Model::reguard();
        $this->assertJsonStringEqualsJsonString(
            json_encode($test_data),
            $model->__toString()
        );
    }

}