<?php

namespace App\Console\Commands;

use App\Events\ResultStudentReceiveEvent;
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
        $now = Carbon::now()->format("Y-m-d H:i");
        $result_details = ResultDetail::query()
            ->where("is_finished", 0)
            ->whereRaw("DATE_FORMAT(time_end, '%Y-%m-%d\ %H:%i') = ?", $now)
            ->get();
        if ($result_details->count() == 0) {
            $this->info("$now No student");
        } else {
            try {
                foreach ($result_details as $result_detail) {
                    $result_detail->is_finished = 1;
                    $result_detail->save();
//                    event(new ResultStudentReceiveEvent($result_detail));
                    $this->info("Student $result_detail->student_name stop exam");
                }
            } catch (\Exception $exception) {
                $error = $exception->getMessage();
                $this->info("Error: $error");
            }
        }
    }
}
