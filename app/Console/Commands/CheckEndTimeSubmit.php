<?php

namespace App\Console\Commands;

use App\Events\ResultStudentReceiveEvent;
use App\Events\RoomOnlineEvent;
use App\Events\StudentFinishExamEvent;
use App\Models\ResultDetail;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckEndTimeSubmit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check-student-end';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check time to submit answer from student';

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
     */
    public function handle()
    {
        $now = Carbon::now()->second(0)->timestamp;
        $result_details = ResultDetail::query()
            ->where("is_finished", 0)
            ->where("timestamp_out", $now)
            ->get();
        if ($result_details->count() == 0) {
            $this->info(Carbon::now()->format("Y-m-d H:i") . " - $now : no student");
        } else {
            try {
                $result_details->each(function ($result_detail) use ($now) {
                    $result_detail->is_finished = 1;
                    $result_detail->save();
                    $this->info(Carbon::now()->format("Y-m-d H:i") . " - $now : student $result_detail->student_name stop exam");
                });
                event(new StudentFinishExamEvent($now));
            } catch (\Exception $exception) {
                $error = $exception->getMessage();
                $this->info("Error: $error");
            }
        }
    }
}
