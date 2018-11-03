<?php
namespace App\Services;

class SlotService
{
    const MIN_CONSECUTIVE = 3;
    const SYMBOLS = ['9', '10', 'J', 'Q', 'K', 'A', 'CAT', 'DOG', 'MONKEY', 'BIRD'];
    const DISTRIBUTIONS = [
        3   => 0.2,
        4   => 2,
        5   => 10
    ];
    const PAYLINES = [
        [0, 3, 6, 9, 12],
        [1, 4, 7, 10, 13],
        [2, 5, 8, 11, 14],
        [0, 4, 8, 10, 12],
        [2, 4, 6, 10, 14]
    ];

    /**
     * @var int $bet
     */
    private $bet = 100;

    /**
     * @return int
     */
    public function getBet(): int
    {
        return $this->bet;
    }

    /**
     * @param $paylines
     *
     * Distributes winnings according to paylines
     *
     * @return int
     */
    public function getTotalWinnings($paylines): int
    {
        $totalWin = 0;
        if (!$paylines) {
            return $totalWin;
        }

        foreach ($paylines as $winning) {
            $numConsecutive = array_get($winning, 'num');
            $totalWin += $this->getWinning($numConsecutive);
        }

        return $totalWin;
    }

    /**
     * @return array
     *
     * Generates array 3x5 with random chars from the specified list
     *
     */
    public function generateArray(): array
    {
        $board = [];
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 5; $j++) {
                $board[$i][$j] = array_random(self::SYMBOLS);
            }
        }

        return $board;
    }

    /**
     * @param array $board
     * @return array|null
     *
     * Handles the board, define number of consecutive chars
     *
     */
    public function processBoard(array $board):? array
    {
        $result = collect();
        foreach (self::PAYLINES as $payline) {
            $consecutive = 1;
            for ($i = 1; $i < count($payline); $i++) {
                if ($board[$payline[$i]] === $board[$payline[$i - 1]]) {
                    $consecutive++;
                }
            }

            if ($consecutive >= self::MIN_CONSECUTIVE) {
                $result->push([
                    'payline'   => $payline,
                    'num'       => $consecutive
                ]);
            }
        }

        return $result->isNotEmpty() ? $result->toArray() : null;
    }

    /**
     * @param $array
     * @return array
     *
     * Transforms the given array 3x5 to the "slotish" indexed form
     *
     */
    public function transformToBoard($array): array
    {
        $board = [];
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 5; $j++) {
                $index = ($j * 3) + $i;
                $board[$index] = $array[$i][$j];
            }
        }

        return $board;
    }

    /**
     * @param $numConsecutive
     * @return int
     *
     * Calculates winning based on number of consecutive chars
     *
     */
    private function getWinning($numConsecutive): int
    {
        return $this->bet * array_get(self::DISTRIBUTIONS, $numConsecutive, 0);
    }
}