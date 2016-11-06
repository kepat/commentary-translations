<?php
namespace App\Services;

use App\Contracts\Repositories\DistanceRepositoryInterface;
use App\Contracts\Services\RouteOptimizerService as RouteOptimizerServiceInterface;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;

/**
 * RouteOptimizer Service
 *
 * @package     App\Services\Deliveries
 * @author      Kevin Tan <tankpst@gmail.com>
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version     Release: 0.1.0
 * @link        http://github.com/vertical-software-asia/scs
 * @since       Class available since Release 0.1.0
 */
class RouteOptimizerService implements RouteOptimizerServiceInterface
{

    /**
     * Distance repository instance
     *
     * @access protected
     * @type   DistanceRepositoryInterface
     */
    protected $distance;


    /**
     * Create a Route Optimizer Service instance
     *
     * @param  DistanceRepositoryInterface $distance
     *
     * @access public
     */
    public function __construct(DistanceRepositoryInterface $distance)
    {
        $this->distance = $distance;
    }


    /**
     * Optimize the route for the provided job deliveries
     *
     * @param string     $start
     * @param Collection $jobs
     *
     * @access public
     * @return Collection
     */
    public function optimizeRoute($start, $jobs)
    {
        // Variables to store the route list
        $active_postal   = [];
        $inactive_postal = [];
        $routes          = [];
        $full_postal     = [];

        // Add the starting point
        $full_postal[] = $start . '?&';

        // Store the postal list
        foreach ($jobs as $job) {
            // Loop through the available details
            foreach ($job->job_details as $job_detail) {
                // Get the needed info
                $from_postal = $job_detail->delivery->from_country . '+' . $job_detail->delivery->from_postal;
                $to_postal   = $job_detail->delivery->to_country . '+' . $job_detail->delivery->to_postal;

                // Store the only needed data for the job detail
                $data = $this->detailedJobDetail($job_detail);

                // Store the variable depending on the status
                if ($job_detail->status == 'new') {
                    // Check if from postal already exist then just add
                    $active_postal = $this->checkAndInsertData($from_postal, $active_postal, $data, ['mode' => 'pickup']);
                    // Store the to postal in the inactive
                    $inactive_postal = $this->checkAndInsertData($from_postal, $inactive_postal, $data, ['mode' => 'delivery']);
                } else if ($job_detail->status == 'in progress') {
                    // Check if to postal already exist then just add
                    $active_postal = $this->checkAndInsertData($to_postal, $active_postal, $data, ['mode' => 'delivery']);
                }

                // Compile the full postal list
                $full_postal[] = $from_postal . '?&' . $job_detail->delivery->address;
                $full_postal[] = $to_postal . '?&' . $job_detail->delivery->ship_to;
            }
        }

        // Loop through all of the postal and create the distance if not existing
        $full_postal = array_unique($full_postal);
        foreach ($full_postal as $postal1) {
            foreach ($full_postal as $postal2) {
                $this->distanceExists($postal1, $postal2)->first();
            }
        }

        // Get the path using the greedy algorithm - this is not the best shortest path
        $total_distance = 0;
        $current_postal = $start . '?&';
        while (! empty($active_postal)) {
            // Get the most nearest postal from the active list
            $distance = 0;
            $postal   = '';
            $address  = '';
            $time     = '';
            foreach ($active_postal as $active => $deliveries) {
                $distance_object  = $this->distanceExists($current_postal, $active . '?&' . $deliveries['address'])->first();
                $current_distance = $distance_object->distance;
                // Check whether to store the current postal and distance in the loop or not
                if (($distance == 0 && $postal == '') || ($distance > ($current_distance))) {
                    $distance = $current_distance;
                    $postal   = $active;
                    $address  = $deliveries['address'];
                    $time     = $distance_object->time;
                }
            }

            // Add the time to the active_postal
            $active_postal[$postal]['time'] = $time;

            // Store the next route for the postal
            array_push($routes, $active_postal[$postal]);
            // Store the total distance
            $total_distance += $distance;

            // Update the details for the next loop
            $current_postal = $postal . '?&' . $address;
            unset($active_postal[$postal]);

            // Check if there is assigned next delivery step for the current new postal
            if (array_key_exists($postal, $inactive_postal)) {
                // Loop through the deliveries
                foreach ($inactive_postal[$postal]['deliveries'] as $deliveries) {
                    // Check if to postal already exist then just add
                    $active_postal = $this->checkAndInsertData($deliveries['to_country'] . '+' . $deliveries['to'], $active_postal, $deliveries);
                }
                // Remove from inactive
                unset($inactive_postal[$postal]);
            }
        }

        // Collection
        $collection = new Collection();
        $collection->put('routes', $routes);
        $collection->put('total_distance', $total_distance);
        $collection->put('start', $start);

        return $collection;
    }

    // Collection

    /**
     * Create the key with the data or just add the data to the existing array
     *
     * @param string $key
     * @param array  $array
     * @param array  $value
     * @param array  $options
     *
     * @access protected
     * @return array
     */
    protected function checkAndInsertData($key, $array, $value, $options = [])
    {
        // Check if there are additional attributes to be added in the value
        foreach ($options as $key_add => $value_add) {
            $value[$key_add] = $value_add;
        }

        // Check if from value already exist then just add or create
        if (array_key_exists($key, $array)) {
            array_push($array[$key]['deliveries'], $value);
        } else {
            $array[$key]['deliveries'] = [$value];
            $array[$key]['pickup']     = 0;
            $array[$key]['delivery']   = 0;
        }

        // Add the needed data
        if ($value['mode'] == 'pickup') {
            $array[$key]['address'] = $value['address'];
            $array[$key]['postal']  = $value['from'];
            $array[$key]['pickup'] += 1;
        } else {
            $array[$key]['address'] = $value['ship_to'];
            $array[$key]['postal']  = $value['to'];
            $array[$key]['delivery'] += 1;
        }

        return $array;
    }


    /**
     * Return a collection with the needed data from a job detail
     *
     * @param JobDetail $job_detail
     *
     * @access protected
     * @return array
     */
    protected function detailedJobDetail($job_detail)
    {
        $data                       = [];
        $data['id']                 = $job_detail->id;
        $data['quantity']           = $job_detail->quantity;
        $data['quantity_delivered'] = $job_detail->quantity_delivered;
        $data['quantity_pickup']    = $job_detail->quantity_pickup;
        $data['delivery_arrival']   = $job_detail->delivery_arrival;
        $data['delivery_completed'] = $job_detail->delivery_completed;
        $data['status']             = $job_detail->status;
        $data['remarks']            = $job_detail->remarks;
        $data['signature']          = $job_detail->signature;
        $data['stamp']              = $job_detail->stamp;
        $data['received_by']        = $job_detail->received_by;
        $data['do_number']          = $job_detail->delivery->number;
        $data['request_date']       = $job_detail->delivery->request_date;
        $data['ship_to']            = $job_detail->delivery->ship_to;
        $data['address']            = $job_detail->delivery->address;
        $data['from_country']       = $job_detail->delivery->from_country;
        $data['from']               = $job_detail->delivery->from_postal;
        $data['to_country']         = $job_detail->delivery->to_country;
        $data['to']                 = $job_detail->delivery->to_postal;
        $data['uom']                = $job_detail->delivery->uom;
        $data['customer']           = $job_detail->delivery->customer->code;
        $data['customer_name']      = $job_detail->delivery->customer->name;
        $data['to_customer_code']   = $job_detail->delivery->to_customer_code;
        $data['to_customer']        = $job_detail->delivery->to_customer;

        return $data;
    }


    /**
     * Check if distance data is already existing
     * if not then create
     *
     * @param string $from
     * @param string $to
     *
     * @access protected
     * @return Collection
     */
    protected function distanceExists($from, $to)
    {
        // Seperate the address first
        $from_data = explode('?&', $from);
        $to_data   = explode('?&', $to);

        // Check if distance already exist if not then create
        $distance = $this->distance->distanceExists($from_data[0], $to_data[0]);
        if ($distance->count() == 0) {
            // Create the guzzle client to connect to google map and get the distance
            $client     = new Client(['base_uri' => 'https://maps.googleapis.com/']);
            $parameters = ['origins' => $from_data[0], 'destinations' => $to_data[0]];

            // Send the request and get the response
            $request  = $client->get('maps/api/distancematrix/json', ['query' => $parameters]);
            $result   = json_decode($request->getBody()->getContents());
            $distance = 0;
            $time     = '';

            // Status of the result
            $status = $result->rows[0]->elements[0]->status;

            // Check if result exists
            if ($status != 'NOT_FOUND' && $status != 'ZERO_RESULTS') {
                $distance = $result->rows[0]->elements[0]->distance->value;
                $time     = $result->rows[0]->elements[0]->duration->text;
            } else {
                // Search with address
                $parameters = ['origins' => $from_data[0] . '+' . $from_data[1], 'destinations' => $to_data[0] . '+' . $to_data[1]];
                $request  = $client->get('maps/api/distancematrix/json', ['query' => $parameters]);
                $result   = json_decode($request->getBody()->getContents());

                // Status of the result
                $status = $result->rows[0]->elements[0]->status;

                // Check if result exists
                if ($status != 'NOT_FOUND' && $status != 'ZERO_RESULTS') {
                    $distance = $result->rows[0]->elements[0]->distance->value;
                    $time     = $result->rows[0]->elements[0]->duration->text;
                }
            }

            $attributes = [];

            $attributes['from']     = $from_data[0];
            $attributes['to']       = $to_data[0];
            $attributes['distance'] = $distance;
            $attributes['time']     = $time;

            $distance = $this->distance->create($attributes);
        }

        return $distance;
    }

}
