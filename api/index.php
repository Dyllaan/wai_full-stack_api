<?php
/**
 * Index is used to send requests through the router, to be processed by the correct endpoint
 * 
 * @author Louis Figes W21017657
 * @generated Github CoPilot was used during the creation of this code
 */

/**
 * Outputs relevant errors
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define('SECRET', 'j332BPkxV9');
include_once('API.php');

/**
 * @param $endpointParser - the endpoint parser class
 */
/**
 * @param $api - the api class
 */

$api = new API();