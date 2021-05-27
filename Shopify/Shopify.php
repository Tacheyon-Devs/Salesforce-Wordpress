<?php

define('SHOPIFY_APP_SECRET', 'aeeafee820d0faf5fd08fae70ed966be4c7849528899e316efea10f89afc7ad5');
require('vendor/autoload.php');
require("salesForcePlugIn/SalesForce.php");
if(file_exists("newfile.txt"))
{
    unlink("newfile.txt");
}
function verify_webhook($data, $hmac_header)
{
  $calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_APP_SECRET, true));
  return hash_equals($hmac_header, $calculated_hmac);
}
 $urlMethod = strtolower($_SERVER['REQUEST_URI']);
 $urlMethod=ltrim($urlMethod,"/");
 $config = array(
    'ShopUrl' => '5b-enterprises.myshopify.com',
    'ApiKey' => 'f2bb23549a4a312ff77fd86608071a3d',
    'Password' => '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',
);
$supportedActions = 
[
    'order',
    'payment'
];
        // $content = file_get_contents('php://input');
        // $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
        // fwrite($myfile, $content);
        // fclose($myfile);die;
        
        $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
        $content = file_get_contents('php://input');
        $verified = verify_webhook($content, $hmac_header);
        $content= error_log('Webhook verified: '.var_export($verified, true)); //check error.log to see the result
        /// later verify the content
        PHPShopify\ShopifySDK::config($config);
        $shopify = new PHPShopify\ShopifySDK;
        $input = file_get_contents('php://input');

        $orderId = json_decode(json_encode($input))->id;
        $order = json_decode(json_encode($shopify->Order($orderId)->get()));
        $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
        fwrite($myfile, $order);
        fclose($myfile);
        $clientEmail = $order->customer->email;
        $clientName = $order->customer->first_name." ".$order->customer->last_name;
// var_dump($clientName);die;
                $data = new SalesForce();
                $accountId = "0015g000005vcrdAAA";
                $standardPriceBook = "01s5g000008PkdbAAC";
                $opportunityId = "0065g000002RgKZAA0";
                // $userEmail = wp_get_current_user()->user_email;
                // $order = wc_get_order($id); //getting order Object
                // var_dump($order);die;
                // $totalBill = $order->total_price();
                $discountTotal = $order->total_discounts;
                $totalTax = $order->total_tax;
                $shippingTotal=0;
                foreach($order->shipping_lines as $shipment) 
                {
                    $shippingTotal+=$shipment->price;
                }
                // echo ($shippingTotal);die;
                // $discountTotal = $order->discount_total;
                // echo $discountTotal." : ".$shippingTotal;
                // date_default_timezone_set('America/Los_Angeles');
                $date = date('Y-m-d');
                $date = new DateTime($date);
                $date->modify("+7 day");
                $date= $date->format("Y-m-d");
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
                                "Shipping_Total__c"=>$shippingTotal,
                                "Shopify_Tax__c"=>$totalTax

                        ],
                        'Order'
                );
                $orderId = json_decode($data->insert())->id;
                // var_dump($orderId);die;
                $data->query
                (
                        'update',
                        [
                                "Pricebook2Id"=>$standardPriceBook
                        ],
                        'Order',
                        [
                                'id'=>$orderId
                        ]
                );
                $data->update();
                $orderItems = $order->line_items;
                // echo json_encode($orderItems);die;
                foreach($orderItems as $item )
                {
                        // var_dump((intval($item->price)));die;
                        // $product= $item->get_product();
                        
                       $data->query = $data->query
                       (
                               'select',
                               [
                                       'id','Name','Pricebook2Id','Product2Id',"UnitPrice","ProductCode","CreatedById"
                               ],
                               'pricebookentry',
                               [
                                   'Product2Id'=>$item->sku    
                               ]
                        );
                       $priceBookEntry=$data->getAccounts()->records[0]->Id;
                        
                       
                       $data->query = $data->query
                       (
                               'insert',
                               [
                                        "OrderId"=>$orderId,
                                        "UnitPrice"=>($item->price),
                                        "Quantity"=>($item->quantity),
                                        "PriceBookEntryId"=>$priceBookEntry
                               ],
                               'OrderItem'
                       );
                       $data->insert();
                      return  http_response_code(200);
                }
                // <!-- chrones -->
// $lineItems=json_decode(json_encode($Order))->line_items;
// echo json_encode($Order);die;
// // $products = $shopify->Order()->get();
// echo json_encode($products);
// $config = array(
//     'ShopUrl' => 'yourshop.myshopify.com',
//     'AccessToken' => '***ACCESS-TOKEN-FOR-THIRD-PARTY-APP***',
// );

// PHPShopify\ShopifySDK::config($config);

// if(in_array("order",$supportedActions))
// {
//     // $order = $shopify->Order("eeafa272cebfd4b22385bc4b645e762c")->get();
//     if(file_exists("newfile.txt"))
//     {
//         unlink("newfile.txt");
//     }
//     // echo json_encode($order);
//     $data = json_encode(json_decode(file_get_contents('php://input')));

//     $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
//     fwrite($myfile, $data);
//     fclose($myfile);
// }if(in_array("payment",$supportedActions))
// {
//     // $order = $shopify->Order("eeafa272cebfd4b22385bc4b645e762c")->get();
//     if(file_exists("newfile.txt"))
//     {
//         unlink("newfile.txt");
//     }
//     // echo json_encode($order);
//     $data = json_encode(json_decode(file_get_contents('php://input')));

//     $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
//     fwrite($myfile, $data);
//     fclose($myfile);
// }
// else
// {

//     $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
//     fwrite($myfile, "none");
//     fclose($myfile);
// }
// // //check request method
// if(file_exists("newfile.txt"))
// {
//     unlink("newfile.txt");
// }

// $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
// fwrite($myfile, $content);
// fclose($myfile);

