<?php

use App\Services\SlotService;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    protected $service;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function setUp()
    {
        parent::setUp();
        $this->service = app(SlotService::class);

    }

    /**
     * @return array
     *
     * mock to get test paylines
     */
    public function getTestPaylines()
    {
        return [
            [
                "payline"   => [0, 3, 6, 9, 12],
                "num"       => 3
            ],
            [
                "payline"   => [0, 4, 8, 10, 12],
                "num"       => 3
            ]
        ];
    }

    /**
     * @return array
     *
     * mock to get test array
     */
    public function getTestArray()
    {
        return [
            ["J", "J", "J", "Q", "K"],
            ["CAT", "J", "Q", "MONKEY", "BIRD"],
            ["BIRD", "BIRD", "J", "Q", "A"]
        ];
    }
}
