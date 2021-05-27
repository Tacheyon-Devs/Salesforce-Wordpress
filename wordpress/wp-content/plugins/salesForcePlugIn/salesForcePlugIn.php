<?php
/**
 * Plugin Name: SalesForce 
 */
/**
 * Check if WooCommerce is active
 **/
require "SalesForce.php";
if(! defined('ABSPATH'))
{
        die;
}
if(!function_exists('add_action'))
{
        die;
}
class salesForcePlugIn extends SalesForce
{
    
        function getOrderDetailById($id, $fields = null, $filter = array()) 
        {
                $clientName = wp_get_current_user()->display_name;
                $data = new SalesForce();
                $accountId = "0015g000005vcrdAAA";
                $standardPriceBook = "01s5g000008PkdbAAC";
                $opportunityId = "0065g000002RgKZAA0";
                // $userEmail = wp_get_current_user()->user_email;
                $order = wc_get_order($id); //getting order Object
                // var_dump($order);die;
                $totalBill = $order->get_total();
                $discountTotal = $order->discount_total;
                $shippingTotal = $order->shipping_total;
                echo $discountTotal." : ".$shippingTotal;
              
                date_default_timezone_set('America/Los_Angeles');
                $date = date('Y-m-d');
                $date = new DateTime($date);
                $date->modify("+7 day");
                $date= $date->format("Y-m-d");
                // $krr    = explode('-', $date);
                // $result = implode("", $krr);
                // $data->query = $data->query
                // (
                //         'insert',
                //         [
                //                 'AccountId'=>"0015g000005vVGJAA2",
                //                 "OwnerId"=>"0055g000004XpSdAAK",
                //                 // "CreatedById"=>"0055g000004XpSdAAK"
                //                 "Name"=>"Woo Commerce",
                //                 "StageName"=>"Prospecting",
                //                 'CloseDate'=>$date,
                //                 'Pricebook2Id'=>"01s5g000008PkdbAAC"
                //         ],
                //         'opportunity'
                // );
                // {
                //         "AccountId":"0015g000005vJw0AAE",
                //         "EffectiveDate":"2021-05-13",
                //         "Status":"Draft",
                //         "Name":"Woocommerce order ",
                //         "OpportunityId":"0065g000002RgKZAA0"
                                //order
                        
                //     }
                // {
                //         "OrderId":"8015g000000bsnhAAA",
                //         "UnitPrice":19,
                //         "Quantity":2,
                        
                //         "PriceBookEntryId":"01u5g000001ELQuAAO"
                //orderitem
                //     }
                //create new order with dummy account and targeted opportunity
                 $data->query = $data->query
                (
                        'insert',
                        [
                                "AccountId"=>$accountId,
                                "EffectiveDate"=>date('Y-m-d'),
                                "Status"=>"Draft",
                                "Name"=>$clientName,
                                "OpportunityId"=>$opportunityId,
                                "Discount_Total__c"=>$discountTotal,
                                "Shipping_Total__c"=>$shippingTotal

                        ],
                        'Order'
                );
                // var_dump($data->insert());die;
                $orderId = json_decode($data->insert())->id;
                // //attach standard pricebook2
                $data->query
                (
                        'update',
                        [
                                "Pricebook2Id"=>$standardPriceBook
                                // 'TotalAmount'=>$totalBill
                        ],
                        'Order',
                        [
                                'id'=>$orderId
                        ]
                );
                // var_dump($data->data);die;
                $data->update();
               
                // // var_dump($data);die;
                // // if()
                // $oppId =  $data->insert();
                // echo json_decode($oppId)->id;
                // die;
                // $temp = (object)$order;
                // echo count($order);die;
                // echo json_encode($order->get_items());die;
                $orderItems = $order->get_items();
                // var_dump($orderItems);die;
                // echo count($orderItems);die;
                foreach($orderItems as $item )
                {
                        // var_dump($item);die;
                        $product= $item->get_product();
                        
                       $data->query = $data->query
                       (
                               'select',
                               [
                                       'id','Name','Pricebook2Id','Product2Id',"UnitPrice","ProductCode","CreatedById"
                               ],
                               'pricebookentry',
                               [
                                   'Product2Id'=>$product->get_sku()    
                               ]
                        );
                       $priceBookEntry=$data->getAccounts()->records[0]->Id;
                        
                       
                       $data->query = $data->query
                       (
                               'insert',
                               [
                                        "OrderId"=>$orderId,
                                        "UnitPrice"=>$product->get_price(),
                                        "Quantity"=>$item->get_quantity(),
                                        "PriceBookEntryId"=>$priceBookEntry
                               ],
                               'OrderItem'
                       );
                       $data->insert();
                        // foreach($key as $k => $v)
                        // {
                        //         echo "key : ".$k." : ".$v."\n";
                        // }
                        // $product = new WC_Order_Item_Product($key->get_product_id());
                        // var_dump($product);die;
                        // var_dump($key);die;
                        // echo"-------------------------------";
                        // echo "id : ".$key->get_id()."\n";//orderID
                        // echo "quantity : ".$key->get_data()['quantity'];
                        // echo  "product : ".$key->get_product()."\n";
                        // $array = ($key->get_data());
                        // echo $product_type   = $product->get_type()."\n";
                        // echo $product_sku    = $product->get_sku()."\n";
                        // echo $product_price  = $product->get_price()."\n";
                        // echo $product->get_name();
                }
                $data->query
                (
                        'update',
                        [
                                "TotalAmount"=>$totalBill,
                                
                                // 'TotalAmount'=>$totalBill
                        ],
                        'Order',
                        [
                                'id'=>$orderId,
                                
                        ]
                );
                // var_dump($data->data);die;
                $data->update();
                // die;
            
        
        }
        function activate()
        {

        }
        function deActivate()
        {

        }
        function unInstall()
        {

        }

}
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
{
        
        // Put your plugin code here
        $salesForcePlugIn = new salesForcePlugIn();
        
        register_activation_hook(__FILE__,array($salesForcePlugIn,'activate'));
        register_deactivation_hook(__FILE__,array($salesForcePlugIn,'deactivate'));
        $response = add_action('woocommerce_thankyou', array($salesForcePlugIn,'getOrderDetailById'), 10, 1);
}       
        // do_action( 'woocommerce_payment_complete', 'so_payment_complete',10,1 );
        //         function so_payment_complete( $order_id )
        //         {
        //                 $order = new WC_Order($order_id);

        //                 var_dump( $order,1);
        //                 die;
        //                 // $order = wc_get_order( $order_id );
        //                 // $user = $order->get_user();
        //                 // if( $user )
        //                 // {
                                
        //                 // }
        //         }
        // function woocommerce_init_test() {
        //         global $woocommerce;
        //         var_dump($woocommerce->session);
        //       }
        //       add_action('woocommerce_init', 'woocommerce_init_test');
       
        // var_dump($response);die;
        // add_action( 'init', function(){
        //         // if ( ! WC()->session->has_session('message') ) {
        //         //     WC()->session->set_customer_session_cookie( true );
        //         // }
        //         session_start();
        //     } );
        //     $wp_session = WP_Session::get_instance();
       

                // if($order_id)
                // {
                //         $order = wc_get_order( $order_id );
                //         // $orderId = $order->get_id();
                //         // echo $orderId;die;
                //         // $order = json_decode( $order);  
                //         // $_SESSION['message']=$order;
                //         // $_SESSION['message']=(($order));
                //         session_start();
                //         // $order="Session set";
                //         // WC()->session->set( 'message', $order );
                //         // echo WC()->session->get('message');
                //         // die;
                        // var_dump( $order->get_data());die;
                //         // if(WC()->session->has_session('message'))
                //         // {

                //         // }
                //         $_SESSION['data']=$order;
                //         // var_dump($_SESSION['data']);die;
                        
                //         header('Location: http://localhost/wordpress/wp-content/plugins/salesforceplugin/response.php');
                //         // include('response.php');
                // } 
                // else
                // {
                //         var_dump("woocommerce is deactivated");
                // }
        // }
        
// }
// }
//metadata
// (array) [13 elements]
// id: (integer) 125 
// order_id: (integer) 50 
// name: (string) "Syed"
// product_id: (integer) 11 
// variation_id: (integer) 0 
// quantity: (integer) 1 
// tax_class: (string) ""
// subtotal: (string) "1"
// subtotal_tax: (string) "0"
// total: (string) "1"
// total_tax: (string) "0"
// taxes: 
// (array) [2 elements]
// total: 
// (array) [1 element]
// 1: (string) "0"
// subtotal: 
// (array) [1 element]
// 1: (string) "0"
// meta_data: 
// (array) [0 elements]


//==============

    // if ($order === false)
                //     return false;
        
                // $order_data = array(
                //     'id' => $order->get_id(),
                //     'order_number' => $order->get_order_number(),
                //     'created_at' => $order->get_date_created()->date('Y-m-d H:i:s'),
                //     'updated_at' => $order->get_date_modified()->date('Y-m-d H:i:s'),
                //     'completed_at' => !empty($order->get_date_completed()) ? $order->get_date_completed()->date('Y-m-d H:i:s') : '',
                //     'status' => $order->get_status(),
                //     'currency' => $order->get_currency(),
                //     'total' => wc_format_decimal($order->get_total(), $dp),
                //     'subtotal' => wc_format_decimal($order->get_subtotal(), $dp),
                //     'total_line_items_quantity' => $order->get_item_count(),
                //     'total_tax' => wc_format_decimal($order->get_total_tax(), $dp),
                // //     'total_shipping' => wc_format_decimal($order->get_total_shipping(), $dp),
                //     'cart_tax' => wc_format_decimal($order->get_cart_tax(), $dp),
                //     'shipping_tax' => wc_format_decimal($order->get_shipping_tax(), $dp),
                //     'total_discount' => wc_format_decimal($order->get_total_discount(), $dp),
                //     'shipping_methods' => $order->get_shipping_method(),
                //     'order_key' => $order->get_order_key(),
                //     'payment_details' => array(
                //         'method_id' => $order->get_payment_method(),
                //         'method_title' => $order->get_payment_method_title(),
                //         'paid_at' => !empty($order->get_date_paid()) ? $order->get_date_paid()->date('Y-m-d H:i:s') : '',
                //     ),
                //     'billing_address' => array(
                //         'first_name' => $order->get_billing_first_name(),
                //         'last_name' => $order->get_billing_last_name(),
                //         'company' => $order->get_billing_company(),
                //         'address_1' => $order->get_billing_address_1(),
                //         'address_2' => $order->get_billing_address_2(),
                //         'city' => $order->get_billing_city(),
                //         'state' => $order->get_billing_state(),
                //         'formated_state' => WC()->countries->states[$order->get_billing_country()][$order->get_billing_state()], //human readable formated state name
                //         'postcode' => $order->get_billing_postcode(),
                //         'country' => $order->get_billing_country(),
                //         'formated_country' => WC()->countries->countries[$order->get_billing_country()], //human readable formated country name
                //         'email' => $order->get_billing_email(),
                //         'phone' => $order->get_billing_phone()
                //     ),
                //     'shipping_address' => array(
                //         'first_name' => $order->get_shipping_first_name(),
                //         'last_name' => $order->get_shipping_last_name(),
                //         'company' => $order->get_shipping_company(),
                //         'address_1' => $order->get_shipping_address_1(),
                //         'address_2' => $order->get_shipping_address_2(),
                //         'city' => $order->get_shipping_city(),
                //         'state' => $order->get_shipping_state(),
                //         'formated_state' => WC()->countries->states[$order->get_shipping_country()][$order->get_shipping_state()], //human readable formated state name
                //         'postcode' => $order->get_shipping_postcode(),
                //         'country' => $order->get_shipping_country(),
                //         'formated_country' => WC()->countries->countries[$order->get_shipping_country()] //human readable formated country name
                //     ),
                //     'note' => $order->get_customer_note(),
                //     'customer_ip' => $order->get_customer_ip_address(),
                //     'customer_user_agent' => $order->get_customer_user_agent(),
                //     'customer_id' => $order->get_user_id(),
                //     'view_order_url' => $order->get_view_order_url(),
                //     'line_items' => array(),
                //     'shipping_lines' => array(),
                //     'tax_lines' => array(),
                //     'fee_lines' => array(),
                //     'coupon_lines' => array(),
                // );
        
                //getting all line items
                // foreach ($order->get_items() as $item_id => $item) {
        
                //     $product = $item->get_product();
        
                //     $product_id = null;
                //     $product_sku = null;
                //     // Check if the product exists.
                //     if (is_object($product)) {
                //         $product_id = $product->get_id();
                //         $product_sku = $product->get_sku();
                //     }
        
                //     $order_data['line_items'][] = array(
                //         'id' => $item_id,
                //         'subtotal' => wc_format_decimal($order->get_line_subtotal($item, false, false), $dp),
                //         'subtotal_tax' => wc_format_decimal($item['line_subtotal_tax'], $dp),
                //         'total' => wc_format_decimal($order->get_line_total($item, false, false), $dp),
                //         'total_tax' => wc_format_decimal($item['line_tax'], $dp),
                //         'price' => wc_format_decimal($order->get_item_total($item, false, false), $dp),
                //         'quantity' => wc_stock_amount($item['qty']),
                //         'tax_class' => (!empty($item['tax_class']) ) ? $item['tax_class'] : null,
                //         'name' => $item['name'],
                //         // 'product_id' => (!empty($item->get_variation_id()) && ('product_variation' === $product->post_type )) ? $product->get_parent_id() : $product_id,
                //         // 'variation_id' => (!empty($item->get_variation_id()) && ('product_variation' === $product->post_type )) ? $product_id : 0,
                //         'product_url' => get_permalink($product_id),
                //         'product_thumbnail_url' => wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'thumbnail', TRUE)[0],
                //         'sku' => $product_sku,
                //         'meta' => wc_display_item_meta($item, ['echo' => false])
                //     );
                // }
        
                //getting shipping
                // foreach ($order->get_shipping_methods() as $shipping_item_id => $shipping_item) {
                //     $order_data['shipping_lines'][] = array(
                //         'id' => $shipping_item_id,
                //         'method_id' => $shipping_item['method_id'],
                //         'method_title' => $shipping_item['name'],
                //         'total' => wc_format_decimal($shipping_item['cost'], $dp),
                //     );
                // }
        
                //getting taxes
                // foreach ($order->get_tax_totals() as $tax_code => $tax) {
                //     $order_data['tax_lines'][] = array(
                //         'id' => $tax->id,
                //         'rate_id' => $tax->rate_id,
                //         'code' => $tax_code,
                //         'title' => $tax->label,
                //         'total' => wc_format_decimal($tax->amount, $dp),
                //         'compound' => (bool) $tax->is_compound,
                //     );
                // }
        
                //getting fees
                // foreach ($order->get_fees() as $fee_item_id => $fee_item) {
                //     $order_data['fee_lines'][] = array(
                //         'id' => $fee_item_id,
                //         'title' => $fee_item['name'],
                //         'tax_class' => (!empty($fee_item['tax_class']) ) ? $fee_item['tax_class'] : null,
                //         'total' => wc_format_decimal($order->get_line_total($fee_item), $dp),
                //         'total_tax' => wc_format_decimal($order->get_line_tax($fee_item), $dp),
                //     );
                // }
        
                //getting coupons
                // foreach ($order->get_items('coupon') as $coupon_item_id => $coupon_item) {
        
                //     $order_data['coupon_lines'][] = array(
                //         'id' => $coupon_item_id,
                //         'code' => $coupon_item['name'],
                //         'amount' => wc_format_decimal($coupon_item['discount_amount'], $dp),
                //     );
                // }
        
        //         return array('order' => apply_filters('woocommerce_api_order_response', $order_data, $order, $fields));
        //     }
?>
