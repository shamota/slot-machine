<?php
namespace App\Console\Commands;

use App\Services\SlotService;
use Illuminate\Console\Command;

class RollSlotCommand extends Command
{
    /**
     * @var SlotService
     */
    private $service;

    protected $signature = "slot:roll";
    protected $description = "Try your luck and roll the slot!";

    /**
     * RollSlotCommand constructor.
     * @param SlotService $service
     */
    public function __construct(SlotService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function handle()
    {
        $bet = $this->service->getBet();
        $array = $this->service->generateArray();
        $board = $this->service->transformToBoard($array);
        $paylines = $this->service->processBoard($board);
        $totalWin = $this->service->getTotalWinnings($paylines);

        $paylines = $this->transfromPaylines($paylines);

        $this->line("board:" . collect($board)->toJson());
        $this->line("paylines: {$paylines}");
        $this->line("bet_amount: {$bet}");
        $this->line("total_win: {$totalWin}");
    }

    /**
     * @param $paylines
     *
     * Transforms winning paylines into strings to allow printing in console
     * @return string
     */
    private function transfromPaylines($paylines)
    {
        if (!$paylines) {
            return "N/A";
        }

        return collect($paylines)->map(function ($item) {
            $payline = array_get($item, 'payline');
            $num = array_get($item, 'num');

            return [implode($payline, ' ') => $num];
        })->toJson();
    }
}
