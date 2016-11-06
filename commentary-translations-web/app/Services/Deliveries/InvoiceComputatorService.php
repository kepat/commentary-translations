<?php
namespace App\Services\Deliveries;

use App\Contracts\Repositories\DeliveryRepositoryInterface;
use App\Contracts\Repositories\JobDetailRepositoryInterface;
use App\Contracts\Repositories\PostalRepositoryInterface;
use App\Contracts\Repositories\PriceRepositoryInterface;
use App\Contracts\Repositories\UOMRepositoryInterface;
use App\Contracts\Services\Deliveries\InvoiceComputatorService as InvoiceComputatorServiceInterface;

/**
 * Invoice Computator Service
 *
 * @package     App\Services\Deliveries
 * @author      Kevin Tan <tankpst@gmail.com>
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version     Release: 0.1.0
 * @link        http://github.com/vertical-software-asia/scs
 * @since       Class available since Release 0.1.0
 */
class InvoiceComputatorService implements InvoiceComputatorServiceInterface
{

    /**
     * Delivery repository instance
     *
     * @access protected
     * @type   DeliveryRepositoryInterface
     */
    protected $delivery;

    /**
     * Postal repository instance
     *
     * @access protected
     * @type   PostalRepositoryInterface
     */
    protected $postal;

    /**
     * UOM repository instance
     *
     * @access protected
     * @type   UOMRepositoryInterface
     */
    protected $uom;

    /**
     * Price repository instance
     *
     * @access protected
     * @type   PriceRepositoryInterface
     */
    protected $price;

    /**
     * Job Detail repository instance
     *
     * @access protected
     * @type   JobDetailRepositoryInterface
     */
    protected $job_detail;


    /**
     * Create a Invoice Computator Service instance
     *
     * @param  DeliveryRepositoryInterface  $delivery
     * @param  PostalRepositoryInterface    $postal
     * @param  UOMRepositoryInterface       $uom
     * @param  PriceRepositoryInterface     $price
     * @param  JobDetailRepositoryInterface $job_detail
     *
     * @access public
     */
    public function __construct(
        DeliveryRepositoryInterface $delivery,
        PostalRepositoryInterface $postal,
        UOMRepositoryInterface $uom,
        PriceRepositoryInterface $price,
        JobDetailRepositoryInterface $job_detail
    ) {
        $this->delivery   = $delivery;
        $this->postal     = $postal;
        $this->uom        = $uom;
        $this->price      = $price;
        $this->job_detail = $job_detail;
    }


    /**
     * Calculate the invoices
     *
     * @param array   $deliveries_id
     * @param string  $discount_type
     * @param decimal $invoice_discount
     *
     * @access protected
     * @return mixed
     */
    public function compute($deliveries_id, $discount_type, $invoice_discount)
    {
        // Create new arrays
        $invoices = [];

        // Go through all of the deliveries selected
        foreach ($deliveries_id as $delivery_id) {
            // Get the delivery details
            $delivery = $this->delivery->find($delivery_id);

            // Go through all of the job details of the delivery
            foreach ($delivery->job_details as $job_detail) {
                // If calculation exist then skip
                if (isset($job_detail->invoice_line->id)) {
                    continue;
                }

                // Make sure it's not a failed delivery
                if ($job_detail->status == 'fail trip') {
                    continue;
                }

                // Get the main key and quantity key to store the data to be calculated
                $key          = $job_detail->job->assigned_date . '-' . $job_detail->job->id . '-' . $delivery->customer->code . '-' . $delivery->from_postal . '-' . $delivery->to_postal;
                $quantity_key = $delivery->uom;

                // Check if from value already exist then just add or create
                if (array_key_exists($key, $invoices)) {
                    // Check if quantity already exist
                    if (array_key_exists($quantity_key, $invoices[$key]['items'])) {
                        $invoices[$key]['items'][$quantity_key]['quantity'] += $job_detail->quantity_delivered;
                        // Job details list
                        if (! in_array($job_detail->id, $invoices[$key]['items'][$quantity_key]['job_details'])) {
                            array_push($invoices[$key]['items'][$quantity_key]['job_details'], $job_detail->id);
                        }
                        // Deliveries details list
                        if (! in_array($delivery->id, $invoices[$key]['items'][$quantity_key]['deliveries'])) {
                            array_push($invoices[$key]['items'][$quantity_key]['deliveries'], $delivery->id);
                        }
                        // DO and SO details list
                        if (! in_array($delivery->number, $invoices[$key]['items'][$quantity_key]['do_numbers'])) {
                            array_push($invoices[$key]['items'][$quantity_key]['do_numbers'], $delivery->number);
                        }
                        if (! in_array($delivery->sales_number, $invoices[$key]['items'][$quantity_key]['so_numbers'])) {
                            array_push($invoices[$key]['items'][$quantity_key]['so_numbers'], $delivery->sales_number);
                        }
                    } else {
                        $invoices[$key]['items'] = $invoices[$key]['items'] + [
                                $quantity_key => [
                                    'quantity'    => $job_detail->quantity_delivered,
                                    'job_details' => [$job_detail->id],
                                    'deliveries'  => [$delivery->id],
                                    'do_numbers'  => [$delivery->number],
                                    'so_numbers'  => [$delivery->sales_number]
                                ]
                            ];
                    }
                } else {
                    // Create new one with the needed details
                    $invoices[$key]['customer_id']      = $delivery->customer->id;
                    $invoices[$key]['customer_code']    = $delivery->customer->code;
                    $invoices[$key]['customer_name']    = $delivery->customer->name;
                    $invoices[$key]['to_customer_code'] = $delivery->to_customer_code;
                    $invoices[$key]['to_customer_name'] = $delivery->to_customer;
                    $invoices[$key]['date']             = $job_detail->job->assigned_date;
                    $invoices[$key]['from']             = $delivery->from_postal;
                    $invoices[$key]['to']               = $delivery->to_postal;
                    $invoices[$key]['ship_to']          = $delivery->ship_to;

                    // Store the items per uom
                    $invoices[$key]['items'] = [
                        $quantity_key => [
                            'quantity'    => $job_detail->quantity_delivered,
                            'job_details' => [$job_detail->id],
                            'deliveries'  => [$delivery->id],
                            'do_numbers'  => [$delivery->number],
                            'so_numbers'  => [$delivery->sales_number]
                        ]
                    ];
                }
            }
        }

        // Compute the initial invoices
        foreach ($invoices as $key => $invoice) {
            $total_amount   = 0;
            $total_discount = 0;

            // ** Location cost - from and to ** //
            $location_cost = 0;
            $location_cost += $this->locationPrice($invoice['from']);
            $location_cost += $this->locationPrice($invoice['to']);

            // Go through the item and calculate the pricing
            foreach ($invoice['items'] as $line_uom => $invoice_line) {
                // ** Items cost ** //
                $items_cost = 0;
                // Check and calculate the pricing for the specific uom item line
                $uom = $this->uom->where('code', '=', $line_uom)->first();
                if (isset($uom)) {
                    $prices = $this->price->customerUOMPrice($invoice['customer_id'], $uom->id);
                    if ($prices->count() > 0) {
                        $total_quantity = $invoice_line['quantity'];
                        $first_price    = true;
                        // Loop through the prices
                        foreach ($prices as $price) {
                            if ($total_quantity <= $price->to && $total_quantity >= $price->from) {
                                $quantity = ($total_quantity - $price->from) + 1;
                            } elseif ($total_quantity > $price->to) {
                                $quantity = ($price->to - $price->from) + 1;
                            }
                            // Check if first price if yes then just use the price itself
                            if ($first_price) {
                                $items_cost += $price->price;
                                $first_price = false;
                            } else {
                                $items_cost += ($quantity * $price->price);
                            }
                        }
                    }
                }

                // ** Manpower and Discount ** //
                $manpower_cost   = 0;
                $discount_amount = 0;
                // Compute the discount and manpower per delivery
                foreach ($invoice_line['deliveries'] as $delivery) {
                    $delivery = $this->delivery->find($delivery);
                    // Manpower cost
                    if ($delivery->manpower == true) {
                        $manpower_cost += $delivery->manpower_charge;
                    }
                    // Discount Amount
                    if ($delivery->discount == true) {
                        if ($delivery->discount_type == 'percent') {
                            $discount_amount += ($delivery->discount_amount / 100) * $items_cost;
                        } else {
                            $discount_amount += $delivery->discount_amount;
                        }
                    }
                }

                // ** Waiting ** //
                $waiting_cost = 0;
                // Go through all of the job details for the total waiting time cost per job detail
                foreach ($invoice_line['job_details'] as $job_detail) {
                    // Get the job details
                    $job_detail = $this->job_detail->find($job_detail);
                    // Waiting cost
                    if ($job_detail->delivery->waiting == true) {
                        // Get the waiting time
                        $arrival_time    = strtotime($job_detail->delivery_arrival);
                        $completion_time = strtotime($job_detail->delivery_completed);
                        $interval        = abs($completion_time - $arrival_time);
                        $minutes         = round($interval / 60);

                        $waiting_cost += ceil($minutes / $job_detail->delivery->waiting_time) * $job_detail->delivery->waiting_charge;
                    }
                }

                // Add the data computed to the invoice line
                $invoices[$key]['items'][$line_uom]['items_cost']      = $items_cost;
                $invoices[$key]['items'][$line_uom]['manpower_cost']   = $manpower_cost;
                $invoices[$key]['items'][$line_uom]['waiting_cost']    = $waiting_cost;
                $invoices[$key]['items'][$line_uom]['discount_amount'] = $discount_amount;
                $invoices[$key]['items'][$line_uom]['total_amount']    = $items_cost + $manpower_cost + $waiting_cost - $discount_amount;

                $total_amount += $items_cost + $manpower_cost + $waiting_cost - $discount_amount;
            }

            // Add the total amount with the location price
            $total_amount += $location_cost;

            // ** Group Discount ** //
            // Check if the total group discount must be applied
            if ($invoice_discount != '' && is_numeric($invoice_discount)) {
                // Check type
                if ($discount_type == 'percent') {
                    $total_discount = ($invoice_discount / 100) * $total_amount;
                } else {
                    $total_discount = $invoice_discount;
                }
            }

            // Add the data computed to the invoice
            $invoices[$key]['location_cost']  = $location_cost;
            $invoices[$key]['total_discount'] = $total_discount;
            $invoices[$key]['total_amount']   = $total_amount - $total_discount;
        }

        return $invoices;
    }


    /**
     * Calculate the postal location price based on the available data
     *
     * @param string $value
     *
     * @access protected
     * @return decimal
     */
    public function locationPrice($value)
    {
        // Get the location price if existing for from and to
        $location_cost = 0;
        $postal        = $this->postal->where('value', '=', $value)->first();
        if (isset($postal)) {
            if (isset($postal->route->price)) {
                $location_cost += $postal->route->price;
            }
        } else {
            $district = $this->postal->where('value', '=', substr($value, 0, 2))->first();
            if (isset($district)) {
                if (isset($district->route->price)) {
                    $location_cost += $district->route->price;
                }
            }
        }

        return $location_cost;
    }

}
