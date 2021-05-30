<?php

namespace App\Console\Commands;

use App\Events\RoomOnlineEvent;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckRoomOnline extends Command
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
        $rooms = Room::query()->where('status', 1)->whereNotNull('time_offline')->get();
        foreach ($rooms as $room) {
            if (Carbon::now()->gte(Carbon::parse($room->time_offline))) {
                $room->status = 0;
                $room->shuffle_answer = 0;
                $room->shuffle_question = 0;
                $room->time_offline = null;
                $room->save();
                event(new RoomOnlineEvent($room->id, false));
            }
        }
    }
}
