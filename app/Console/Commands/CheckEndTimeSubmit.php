<?php

namespace App\Console\Commands;

use App\Events\RoomOnlineEvent;
use App\Models\ResultDetail;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckEndTimeSubmit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check-room-online';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change status room to 0';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $result_details = ResultDetail::query()->where("is_finished", 0)->get();
        foreach ($result_details as $result_detail) {
            $time_end = Carbon::parse($result_detail->time_end)->format("Y-m-d H:i");
            if ($time_end == Carbon::now()->format("Y-m-d H:i")) {
                $result_detail->status = 1;
                $result_detail->save();
            }
        }
    }
}
