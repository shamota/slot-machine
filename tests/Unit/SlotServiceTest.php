<?php

namespace tests\Unit;

use App\Services\SlotService;
use TestCase;

class SlotServiceTest extends TestCase
{
    /**
     * @covers SlotService::generateBoard()
     */
    public function testGeneratedBoard()
    {
        $array = $this->service->generateArray();
        $this->assertTrue(is_array($array));

        $board = $this->service->transformToBoard($array);
        $this->assertTrue(is_array($board));
    }

    /**
     * @covers SlotService::processBoard()
     */
    public function testPaylinesFound()
    {
        $board = $this->service->transformToBoard($this->getTestArray());
        $actualPaylines = $this->service->processBoard($board);

        $this->assertEquals($this->getTestPaylines(), $actualPaylines);
    }

    /**
     * @covers SlotService::getTotalWinnings()
     */
    public function testPaylineWinnings()
    {
        $board = $this->service->transformToBoard($this->getTestArray());
        $paylines = $this->service->processBoard($board);
        $winnings = $this->service->getTotalWinnings($paylines);

        $this->assertEquals(40, $winnings);
    }
}
