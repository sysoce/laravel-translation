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
}
