<?php
/*
Plugin Name: Deliverea Shipping
Plugin URI:  http://deliverea.com
Description: Multi carrier plugin
Version:     1.0.1
Author:      Deliverea Shipping Solutions S.L
Author URI:  http://deliverea.com
License:     GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
*/

require_once(__DIR__ . '/vendor/autoload.php');

if(!class_exists('DelivereaAjaxHandler')) {
    require_once(__DIR__ . '/integration/DelivereaAjaxHandler.php');
}

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Check if WooCommerce is active
 **/
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    exit;
}

final class DelivereaShipping
{
    private $version = '1.0.1';

    private static $instance = null;

    private $orders = [];

    private $currentPage = 1;

    private $totalPages = 1;

    const ITEMS_PER_PAGE = 20;

    public function __construct()
    {
        add_action('admin_init', array($this, 'registerConfig'));
        add_action('wp_ajax_deliverea', array($this, 'ajaxHandler'));
        add_action('admin_menu', array($this, 'addPage'));
    }

    function registerConfig()
    {
        add_settings_section('deliverea-config', 'Ajustes', null, 'deliverea-config');

        $renderField = function ($options) {
            $id = $options['id'];
            echo '<input id="' . $id . '" name="' . $id . '" type="text" value="' . get_option($id, '') . '"/>';
        };

        add_settings_field('wcdeliverea_api_user', 'API User', $renderField, 'deliverea-config', 'deliverea-config',
            ['id' => 'wcdeliverea_api_user']);
        add_settings_field('wcdeliverea_api_key', 'API Key', $renderField, 'deliverea-config', 'deliverea-config',
            ['id' => 'wcdeliverea_api_key']);

        register_setting('deliverea-config', 'wcdeliverea_api_user');
        register_setting('deliverea-config', 'wcdeliverea_api_key');
    }

    function ajaxHandler()
    {
        $ajaxHandler = new DelivereaAjaxHandler();
        $method = isset($_REQUEST['method']) ? $_REQUEST['method'] : null;
        $data = $_REQUEST; // Passed directly to API

        try {
            switch ($method) {
                case 'new-shipment':
                    $result = $ajaxHandler->newShipment($data, function ($result) use ($data) {
                        $orderId = (int)$_REQUEST['shipping_client_ref'];
                        $status = $data['uses_collection'] ? 'shipped' : 'completed';

                        update_post_meta($orderId, '_deliverea_shipping_dlvr_ref', $result['shipping_dlvr_ref']);
                        update_post_meta($orderId, '_deliverea_shipping_date', $data['shipping_date']);
                        update_post_meta($orderId, '_deliverea_shipping_carrier_ref', $result['shipping_carrier_ref']);
                        update_post_meta($orderId, '_deliverea_status', $status);
                    });
                    echo json_encode($result);
                    break;
                case 'new-collection':
                    $result = $ajaxHandler->newCollection($data, function ($result) use ($data) {
                        $orderId = (int)$_REQUEST['collection_client_ref'];
                        update_post_meta($orderId, '_deliverea_collection_dlvr_ref', $result['collection_dlvr_ref']);
                        update_post_meta($orderId, '_deliverea_collection_carrier_ref',
                            $result['collection_carrier_ref']);
                        update_post_meta($orderId, '_deliverea_status', 'completed');
                    });
                    echo json_encode($result);
                    break;
                case 'get-shipment-label':
                    $shippingDlvrRef = $_REQUEST['shipping_dlvr_ref'];
                    $result = $ajaxHandler->generateLabel($shippingDlvrRef);
                    echo json_encode($result);
                    break;
                case 'get-addresses':
                    $result = $ajaxHandler->getAddress();
                    echo json_encode($result);
                    break;
                case 'get-service-info':
                    $result = $ajaxHandler->getServiceInfo($data);
                    echo json_encode($result);
                    break;
                case 'get-collection-cutoff-hour':
                    $result = $ajaxHandler->getCutoffHours($data);
                    echo json_encode($result);
                    break;
                case 'get-client-carriers':
                    $result = $ajaxHandler->getClientCarriers();
                    echo json_encode($result);
                    break;
                case 'get-client-services':
                    $result = $ajaxHandler->getClientServices($data['carrier_code']);
                    echo json_encode($result);
                    break;
                case 'new-pickup-point':
                    $result = $this->newPickupPoint($data);
                    echo json_encode($result);
                    break;
                case 'remove-pickup-point':
                    $result = $this->removePickupPoint($data);
                    echo json_encode($result);
                    break;
                default:
                    status_header(404);
                    break;
            }
        } catch (\Exception $e) {
            status_header(400, "Bad Request");
            echo json_encode($e->__toString());
        }

        wp_die();
    }

    function addPage()
    {
        add_menu_page('Deliverea Shipping', 'Deliverea Shipping', 'manage_options', 'deliverea-shipping', '',
            plugins_url('/img/icon.png', __FILE__));

        add_submenu_page('deliverea-shipping', 'Envíos', 'Envíos', 'manage_options', 'deliverea-shipping',
            array($this, 'listPage'));

        add_submenu_page('deliverea-shipping', 'Puntos de Recogida', 'Puntos de Recogida',
            'manage_options', 'deliverea-shipping-pickup-points', array($this, 'pickupPointsPage'));

        add_submenu_page('deliverea-shipping', 'Ajustes', 'Ajustes', 'manage_options', 'deliverea-shipping-config',
            array($this, 'configPage'));
    }

    /**
     * Export Multiple Labels
     */
    function exportPage()
    {
        $ajaxHandler = new DelivereaAjaxHandler();
        $data = $_REQUEST;
        $references = explode(',', $data['filter_references']);
        $ajaxHandler->exportLabels($references);
        wp_die();
    }

    function loadWooCommerceData()
    {
        $totalPosts = count(get_posts(['post_type' => 'shop_order']));
        $this->totalPages = (int)ceil($totalPosts / self::ITEMS_PER_PAGE);
        $page = (int)$_GET['currentPage'];

        if (is_int($page) && $page > $this->getTotalPages()) {
            $page = $this->getTotalPages();
        }

        if (!$page || !is_int($page) || $page <= 1) {
            $page = 1;
        }

        $this->currentPage = $page;

        $this->orders = get_posts([
            'post_type' => 'shop_order',
            'posts_per_page' => self::ITEMS_PER_PAGE,
            'paged' => $this->currentPage
        ]);
    }

    /**
     * Configuration page
     */
    function configPage()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_REQUEST['wcdeliverea_api_user'])) {
                update_option('wcdeliverea_api_user', $_REQUEST['wcdeliverea_api_user']);
            }

            if (isset($_REQUEST['wcdeliverea_api_key'])) {
                update_option('wcdeliverea_api_key', $_REQUEST['wcdeliverea_api_key']);
            }

            try {
                $ajaxHandler = new DelivereaAjaxHandler();
                $result = $ajaxHandler->getClientServices(null);
                $this->synchronizeServices($result['services']);
            } catch (\Exception $e) {

            }
        }

        include_once('templates/config.php');
    }

    /**
     * Render list of orders
     */
    function listPage()
    {
        if (!empty($_GET['export'])) {
            $this->exportPage();

            return;
        }

        $this->loadWoocommerceData();

        wp_register_style('deliverea', plugins_url('css/styles.css', __FILE__));
        wp_enqueue_style('deliverea');

        wp_register_style('bootstrap', plugins_url('css/plugins/bootstrap.min.css', __FILE__));
        wp_enqueue_style('bootstrap');

        wp_deregister_style('jquery-ui');
        wp_register_style('jquery-ui', plugins_url('css/plugins/jquery-ui.min.css', __FILE__));
        wp_enqueue_style('jquery-ui');

        wp_deregister_style('jquery-ui-structure');
        wp_register_style('jquery-ui-structure',
            plugins_url('css/plugins/jquery-ui.structure.min.css', __FILE__));
        wp_enqueue_style('jquery-ui-structure');

        wp_deregister_style('jquery-ui-theme');
        wp_register_style('jquery-ui-theme',
            plugins_url('css/plugins/jquery-ui.theme.min.css', __FILE__));
        wp_enqueue_style('jquery-ui-theme');

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-datepicker');

        wp_register_script('bootstrap', plugins_url('js/plugins/bootstrap.min.js', __FILE__));
        wp_enqueue_script('bootstrap');

        wp_register_script('moment', plugins_url('js/plugins/moment.min.js', __FILE__));
        wp_enqueue_script('moment');

        wp_register_script('deliverea-api-mappings',
            plugins_url('js/deliverea-api-mappings.js', __FILE__));
        wp_register_script('deliverea-helpers', plugins_url('js/deliverea-helpers.js', __FILE__));
        wp_register_script('deliverea', plugins_url('js/deliverea.js', __FILE__));
        wp_register_script('deliverea-list', plugins_url('js/deliverea-list.js', __FILE__));
        wp_register_script('deliverea-cutoff-hour', plugins_url('js/cutoff-hour.js', __FILE__));

        wp_enqueue_script('deliverea-api-mappings');
        wp_enqueue_script('deliverea-helpers');
        wp_enqueue_script('deliverea-list');
        wp_enqueue_script('deliverea');
        wp_enqueue_script('deliverea-cutoff-hour');

        wp_enqueue_style('font-awesome', plugins_url('css/font-awesome.min.css', __FILE__));

        include_once('templates/list.php');
    }

    public function pickupPointsPage()
    {
        wp_register_style('deliverea', plugins_url('css/styles.css', __FILE__));
        wp_enqueue_style('deliverea');

        wp_register_script('deliverea-api-mappings',
            plugins_url('js/deliverea-api-mappings.js', __FILE__));
        wp_register_script('deliverea', plugins_url('js/deliverea.js', __FILE__));
        wp_register_script('deliverea-pickup-points',
            plugins_url('js/deliverea-pickup-points.js', __FILE__));

        wp_register_style('bootstrap', plugins_url('css/plugins/bootstrap.min.css', __FILE__));
        wp_enqueue_style('bootstrap');

        wp_register_script('bootstrap', plugins_url('js/plugins/bootstrap.min.js', __FILE__));
        wp_enqueue_script('bootstrap');

        wp_enqueue_script('jquery');
        wp_enqueue_script('deliverea-api-mappings');
        wp_enqueue_script('deliverea-pickup-points');
        wp_enqueue_script('deliverea');

        wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');

        include_once('templates/pickup-points.php');
    }

    /**
     * Create a region + services for the ecommerce
     * @param $services
     * @return array
     * @throws Exception
     */
    function synchronizeServices($services)
    {
        global $wpdb;

        $wcShippingZone = new WC_Shipping_Zone();
        $wcShippingZone->set_zone_name('Deliverea - España Peninsular');
        $wcShippingZone->add_location('ES', 'country');
        $wcShippingZone->create();
        $wcShippingZone->save();

        foreach ($services as $service) {
            $instanceId = $wcShippingZone->add_shipping_method('flat_rate');
            $title = $service['carrier']['carrier_name'] . ' - ' . $service['service_name'];

            $wpdb->update("{$wpdb->prefix}woocommerce_shipping_zone_methods", array('is_enabled' => 0),
                array('instance_id' => absint($instanceId)));

            $wcShippingMethodOptions = new WC_Shipping_Flat_Rate($instanceId);
            $wcShippingMethodOptions->set_post_data([
                'woocommerce_flat_rate_enabled' => 'no',
                'woocommerce_flat_rate_tax_status' => 'taxable',
                'woocommerce_flat_rate_cost' => 0,
                'woocommerce_flat_rate_title' => $title
            ]);

            $wcShippingMethodOptions->process_admin_options();
        }
    }

    /**
     * Create a new deliverea_pickup_point
     * @param $data
     * @return array
     * @throws Exception
     */
    public function newPickupPoint($data)
    {
        $idPickupPoint = (int)$data['id_pickup_point'];
        $alias = $data['alias'];
        $attn = $data['attn'];
        $phone = $data['phone'];
        $email = $data['email'];
        $address = $data['address'];
        $city = $data['city'];
        $zipCode = $data['zip_code'];
        $country = $data['country'];
        $observations = $data['observations'];

        $result = wp_insert_post([
            'ID' => $idPickupPoint,
            'post_title' => 'Deliverea Pickup Point ' . $alias,
            'post_status' => 'created',
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_type' => 'dlvr_pickup_point',
            'meta_input' => [
                '_alias' => $alias,
                '_attn' => $attn,
                '_phone' => $phone,
                '_email' => $email,
                '_address' => $address,
                '_city' => $city,
                '_zip_code' => $zipCode,
                '_country' => $country,
                '_observations' => $observations
            ]
        ], true);

        if ($result instanceof WP_Error) {
            throw new \Exception("Invalid Data");
        }

        return ['id' => $result];
    }

    public function removePickupPoint($data)
    {
        $idPickupPoint = (int)$data['id_pickup_point'];

        $result = wp_delete_post($idPickupPoint);

        if (!$result) {
            throw new \Exception("Invalid Data");
        }

        return ['id' => $idPickupPoint];
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    function getCountries()
    {
        $countriesObj = new WC_Countries();

        return $countriesObj->__get('countries');
    }

    public function getPickupPoints()
    {
        $data = [];

        $pickupPoints = get_posts([
            'post_type' => 'dlvr_pickup_point',
            'post_status' => 'created',
            'order' => 'ASC'
        ]);

        foreach ($pickupPoints as $pickupPoint) {
            $data[$pickupPoint->ID]['ID'] = $pickupPoint->ID;

            array_walk(get_post_meta($pickupPoint->ID, '', true), function ($value, $key) use (&$data, $pickupPoint) {
                $data[$pickupPoint->ID][substr($key, 1)] = $value[0];
            });
        }

        return $data;
    }

    public function getOrders()
    {
        return $this->orders;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function getTotalPages()
    {
        return $this->totalPages;
    }
}

function DelivereaShipping()
{
    return DelivereaShipping::getInstance();
}

$GLOBALS['DELIVEREASHIPPING'] = DelivereaShipping();