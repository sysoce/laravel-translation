<?php

namespace Sysoce\Translation\Clients;

use Sysoce\Translation\Contracts\Client;
use Google\Cloud\Translate\TranslateClient;

class GoogleCloudTranslate implements Client
{
    /** @var TranslateClient */
    protected $client;

    /** @var string source language */
    protected $source;

    /** @var string target language */
    protected $target;

    /**
     * @param Application   $app
     */
    public function __construct(Application $app)
    {
        $this->client = new TranslateClient($app->config['translation.clients.config']);
    }

    /**
     * Returns the source language.
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Returns the target language.
     *
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * {@inheritdoc}
     */
    public function setSource($source = null)
    {
        return $this->source = $source;
    }

    /**
     * {@inheritdoc}
     */
    public function setTarget($target)
    {
        return $this->target = $target;
    }

    /**
     * {@inheritdoc}
     */
    public function translate($text)
    {
        $translation = $this->client->translate($text, [
            'source' => $this->source,
            'target' => $this->target
        ]);
        return $translation['text'];
    }
}