<?php
namespace App\Services\Deliveries;

use App\Contracts\Repositories\DriverRepositoryInterface;
use App\Contracts\Repositories\PostalRepositoryInterface;
use App\Contracts\Services\Deliveries\DriverExtractorService as DriverExtractorServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Driver Extractor Service
 *
 * @package     App\Services\Deliveries
 * @author      Kevin Tan <tankpst@gmail.com>
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version     Release: 0.1.0
 * @link        http://github.com/vertical-software-asia/scs
 * @since       Class available since Release 0.1.0
 */
class DriverExtractorService implements DriverExtractorServiceInterface
{

    /**
     * Postal repository instance
     *
     * @access protected
     * @type   PostalRepositoryInterface
     */
    protected $postal;

    /**
     * Driver repository instance
     *
     * @access protected
     * @type   DriverRepositoryInterface
     */
    protected $driver;


    /**
     * Create a Driver Extractor Service instance
     *
     * @param  PostalRepositoryInterface $postal
     * @param  DriverRepositoryInterface $driver
     *
     * @access public
     */
    public function __construct(PostalRepositoryInterface $postal, DriverRepositoryInterface $driver)
    {
        $this->postal = $postal;
        $this->driver = $driver;
    }


    /**
     * Extract the specific drivers for the delivery
     *
     * @param DeliveryRepositoryInterface $delivery
     *
     * @access public
     * @return Collection
     */
    public function extractDrivers($delivery)
    {
        // Get the delivery to postal
        $value = $delivery->to_postal;

        // Get the postal whether it's district or postal code
        $postal = $this->postal->where('value', '=', substr($value, 0, 2))->first();
        if (! isset($postal)) {
            $postal = $this->postal->where('value', '=', $value)->first();
            if (! isset($postal)) {
                return $this->driver->allActive();
            }
        }

        // Get the assigned driver
        if (! is_null($postal->route)) {
            if (! is_null($postal->route->drivers)) {
                return $postal->route->drivers()->where('active', true)->get();
            }
        }

        return $this->driver->allActive();
    }


    /**
     * Extract the default driver from a collection of driver
     *
     * @param DeliveryRepositoryInterface $delivery
     *
     * @access public
     * @return DriverRepositoryInterface
     */
    public function extractDefault($delivery)
    {
        // Get the delivery to postal
        $value = $delivery->to_postal;

        // Set the return value
        $driver = null;

        // Get the postal whether it's district or postal code
        $postal = $this->postal->where('value', '=', substr($value, 0, 2))->first();
        if (! isset($postal)) {
            $postal = $this->postal->where('value', '=', $value)->first();
            if (! isset($postal)) {
                return $driver;
            }
        }

        // Get the assigned driver
        if (! is_null($postal->route)) {
            if (! is_null($postal->route->driver_routes)) {
                foreach ($postal->route->driver_routes as $driver_route) {
                    if ($driver_route->default == true && $driver_route->driver->active == true) {
                        return $driver_route->driver;
                    }
                }
            }
        }

        return $driver;
    }

}
