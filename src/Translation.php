<?php

/*
 * This file is part of the sysoce/laravel-translation package.
 *
 * (c) Sysoce <sysoce@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://github.com/sysoce/laravel-translation
 */

namespace Sysoce\Translation;

use Sysoce\Translation\Contracts\Client;
// use Sysoce\Translation\Clients\GoogleCloudTranslate as Client;
use Sysoce\Translation\Models\Translation as Model;

class Translation
{
    /**
     * Holds the translation client.
     *
     * @var ClientInterface
     */
    protected $client;

    /**
     * {@inheritdoc}
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Returns the source language code.
     *
     * @return string
     */
    public function getSource()
    {
        return $this->client->getSource();
    }

    /**
     * Returns the target language code.
     *
     * @return string
     */
    public function getTarget()
    {
        return $this->client->getTarget();
    }

    /**
     * {@inheritdoc}
     */
    public function setSource($source = null)
    {
        return $this->client->setSource($source);
    }
    /**
     * {@inheritdoc}
     */
    public function setTarget($target)
    {
        return $this->client->setTarget($target);
    }

    /**
     * {@inheritdoc}
     */
    public function translate($text)
    {
        $source = Model::firstOrCreate(['locale' => $this->getSource(), 'text' => $text]);
        $translation = $source->translations()->where('locale', $this->getTarget())->first();
        if(!$translation) {
            $translation = $source->source()->where('locale', $this->getTarget())->first();
            if(!$translation) {
                $translation = Model::firstOrCreate(
                    [
                        'locale' => $this->getTarget(),
                        'text' => $this->client->translate($text)
                    ],
                    [
                        'source_id' => $source->id
                    ]
                );
            }
        }

        return $translation;
    }

    /**
     * Set translation by editing or creating the dictionary entries.
     * Note: Creates the source and translation dictionary entries if they don't exist.
     *
     * @param  string            $text              Source text
     * @param  string            $translation_text       Translation
     * @return \Illuminate\Http\Response
     */
    public function setTranslation($text, $translation_text)
    {
        $source = Model::firstOrCreate(['locale' => $this->getSource(), 'text' => $text]);
        $old_translation = $source->translations()->where('locale', $this->getTarget())->first();
        $translation = Model::updateOrCreate(
            [
                'locale' => $this->getTarget(),
                'source_id' => $source->id
            ],
            [
                'text' => $translation_text
            ]
        );
        if($old_translation && $old_translation->id != $translation->id) {
            $old_translation->source_id = null;
            $old_translation->save();
        }
        return $translation;
    }

    /**
     * Get translation dictionary entry if it exists.
     * Note: Creates a source dictionary entry if it doesn't exists.
     *
     * @param  string            $text              Source text
     * @return \Illuminate\Http\Response | null
     */
    public function getTranslation($text)
    {
        $source = Model::firstOrCreate(['locale' => $this->getSource(), 'text' => $text]);
        $translation = $source->translations()->where('locale', $this->getTarget())->first();
        if(!$translation) {
            $translation = $source->source()->where('locale', $this->getTarget())->first();
        }
        return $translation;
    }
}
