<?php
namespace App\Services\Jobs;

use App\Contracts\Repositories\DriverRepositoryInterface;
use App\Contracts\Repositories\JobRepositoryInterface;
use App\Contracts\Services\Jobs\CommissionComputatorService as CommissionComputatorServiceInterface;

/**
 * Commission Computator Service
 *
 * @package     App\Services\Deliveries
 * @author      Kevin Tan <tankpst@gmail.com>
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version     Release: 0.1.0
 * @link        http://github.com/vertical-software-asia/scs
 * @since       Class available since Release 0.1.0
 */
class CommissionComputatorService implements CommissionComputatorServiceInterface
{

    /**
     * Job repository instance
     *
     * @access protected
     * @type   JobRepositoryInterface
     */
    protected $job;

    /**
     * Driver repository instance
     *
     * @access protected
     * @type   DriverRepositoryInterface
     */
    protected $driver;


    /**
     * Create a Commission Computator Service instance
     *
     * @param  JobRepositoryInterface    $job
     * @param  DriverRepositoryInterface $driver
     *
     * @access public
     */
    public function __construct(JobRepositoryInterface $job, DriverRepositoryInterface $driver)
    {
        $this->job    = $job;
        $this->driver = $driver;
    }


    /**
     * Calculate the commissions of the jobs
     *
     * @param array $jobs_id
     *
     * @access protected
     * @return mixed
     */
    public function compute($jobs_id)
    {
        // Create new arrays
        $commissions = [];

        // Go through all of the jobs selected
        foreach ($jobs_id as $job_id) {
            // Get the job details
            $job = $this->job->find($job_id);

            // Get the driver detail of the job
            $driver = $this->driver->find($job->driver_id);

            // Variable
            $commission_amount = 0;
            $waiting_cost      = 0;
            $total_amount      = 0;

            // Set the initial values for the commission data
            $commissions[$job_id]['driver_code']   = $driver->code;
            $commissions[$job_id]['driver_name']   = $driver->last_name . ', ' . $driver->first_name;
            $commissions[$job_id]['assigned_date'] = $job->assigned_date;
            $commissions[$job_id]['job_details']   = [];

            // Go through all of the job details of the job
            foreach ($job->job_details as $job_detail) {
                // Make sure it's not a failed delivery
                if ($job_detail->status == 'fail trip') {
                    continue;
                }

                // ** Commission cost ** //
                if ($job_detail->delivery->commission == true) {
                    $commission_amount += $job_detail->delivery->commission_amount;
                }

                // ** Commission cost ** //
                if ($driver->waiting == true) {
                    // Get the waiting time
                    $arrival_time    = strtotime($job_detail->delivery_arrival);
                    $completion_time = strtotime($job_detail->delivery_completed);
                    $interval        = abs($completion_time - $arrival_time);
                    $minutes         = round($interval / 60);

                    $waiting_cost += ceil($minutes / $driver->waiting_time) * $driver->waiting_charge;
                }

                // Store the job detail id
                if (! in_array($job_detail->id, $commissions[$job_id]['job_details'])) {
                    array_push($commissions[$job_id]['job_details'], $job_detail->id);
                }
            }

            // ** Fixed and Overtime cost ** //
            $fixed_cost = $driver->fixed_cost;

            // Check if the driver can have overtime pay
            $overtime_cost = 0;
            if ($job->driver->overtime == true) {
                $overtime_cost = $driver->overtime_cost;
            }

            // Check the day if matched with the driver's working day
            $working_day = strtolower(date('l', strtotime($job_detail->delivery_arrival)));
            if ($driver->work_schedule->$working_day == 1) {
                $total_amount += $fixed_cost;
            } else {
                if ($job->driver->overtime == true) {
                    $total_amount += $overtime_cost;
                } else {
                    $total_amount += $fixed_cost;
                }
            }

            // Add the commission and waiting
            $total_amount += $commission_amount + $waiting_cost;

            // Store the value to commission
            $commissions[$job_id]['fixed_cost']        = $fixed_cost;
            $commissions[$job_id]['overtime_cost']     = $overtime_cost;
            $commissions[$job_id]['waiting_cost']      = $waiting_cost;
            $commissions[$job_id]['commission_amount'] = $commission_amount;
            $commissions[$job_id]['total_amount']      = $total_amount;
            $commissions[$job_id]['deliveries_number'] = $job->job_details->count();
        }

        return $commissions;
    }
}
