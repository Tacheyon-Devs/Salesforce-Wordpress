<?php
// require 'woocommerce/woocommerce.php';
        // $wp_session = WP_Session::get_instance();
        // include dirname(__FILE__).'/salesForcePlugIn.php';
        // $salesForcePlugIn = new salesForcePlugIn();
        // include 'reponse.php';
        if(($_SESSION['data']))
        {
                // echo "sd";die;
                // echo (WC()->session->get('message'));die;
                // $data = fixObject($_SESSION['data']);
                // echo json_encode($data);die;
                // var_dump($_SESSION['message']);
                // die;
                // echo "session ";
                // die();
                // $data = $_SESSION['response'];
                // $fixedData = fixObject($data);
                // unset($_SESSION['message']);
                // unset( $_SESSION['message'] );
                // echo json_encode($_SESSION['message']);
                // unset($_SESSION['message']);
                // echo "<hr><br>";
                // $message = ($_SESSION['message']);
                // $message= fixObject($user);
                // $orderDetails = WC()->session->get('message');
                // $orderDetails = fixObject($_SESSION['message']);
                // $data['orderDetails']=$orderDetails;
                // $data['items']=$orderDetails->get_id();
                // echo json_encode($fixedData);
                // die;
        }
        else
        {
                echo "error";
        }        


function fixObject(&$object) {
   if (!is_object($object) && gettype($object) == 'object')
      return ($object = unserialize(serialize($object)));
   return $object;
}