<?php

require_once('SalesForce.php');

$salesForce = (new SalesForce());

echo json_encode($salesForce->fetchData());

// echo ($salesForce->sync());
// if()
// echo json_encode($salesForce);
// $product =  json_decode($salesForce->getSpecificObject("Order",$data['firstOrderId']));
// echo json_encode($product);die;
//insert
// $salesForce->sObject= "Order";
// $salesForce->sObjectId= "8015g000000bsL4AAI";
// echo ($salesForce->getSpecificObject("Product2","01t5g000001ayGZAAY"));
// $salesForce->query      
// (
//         'select',
//         [
//                 'name',
//                 'id',
                
//         ],
//         "product2",
//         null,
//         null,
//         null
// );
// // echo $query;
// $allOrders =  ($salesForce->getAccounts());
// echo json_encode($allOrders);die;
// // $data['firstOrderId'] = ($allOrders)->records[0]->Id;
// $query = $salesForce->query
// (
//         'update',
//         [
//                 'name'=>"total updation"
//         ],
//         "Product2",
//         [
//                 'id'=>'01t5g000000dopBAAQ'
//         ],

//         null,
//         null
// );
// // echo $query;
// ($salesForce->update());
// echo json_encode($salesForce);die;
// $data['productId']=$data['products']->records[0]->Id;
// $query = $salesForce->query
// (
//         'insert',
//         [
//                 // 'OrderId' =>$data['firstOrderId'],
//                 // 'Product2Id'=>$data['productId'],
//                 // 'PriceBookEntryId'=>"01s5g000008PkdaAAC",
//                 // 'ListPrice'=>100,
//                 // 'UnitPrice'=>50,
//                 'name'=>"chlja bhai version 2 ",
//                 'description'=>"My chlja bhai dedscription",
//                 'productcode'=>"GC9290",
//                 'isactive'=>1
//         ],
//         'Product2',
//         null,null,null
// );
// echo json_encode($salesForce);die;
//   $result = ($salesForce->insert());
//   echo ($result);die;
//  echo $result;
// echo json_encode($salesForce);
// $data['userData'] = ($salesForce->getUserData());


// // echo json_encode($product);die;
// $allOrders =  ($salesForce->getAccounts());
// $data['firstOrderId'] = $allOrders->records[0]->Id;
// $query = $salesForce->query
// (
//         'select',
//         [
//                 'name',
//                 'id',
                
//         ],
//         "Product2",
//         ['id'=>'01t5g000000djbBAAQ'],
//         null,
//         null
// );
// $product =  json_decode($salesForce->getSpecificObject("Order",$data['firstOrderId']));
// // $product =  json_decode($salesForce->getSpecificObject("Product2","01t5g000000djbBAAQ"));
// // echo json_encode($product);die;
// $data['products']= $salesForce->getAccounts();
// $data['productId']=$data['products']->records[0]->Id;
// // echo ($userData);
// // echo json_encode($data);die;
// $priceBook = $salesForce->query
// (
//         'select',
//         [
//                 'id'
//         ],
//         "PriceBook2",
//         ['name'=>'standard'],
//         null,
//         null,
//         null
// );
// $priceBookId = $salesForce->getAccounts()->records[0]->Id;
// // echo json_encode($priceBook);die;
// // $records = ($response->records);
// $query = $salesForce->query
// (
//         'insert',
//         [
//                 'OrderId' =>$data['firstOrderId'],
//                 'Product2Id'=>$data['productId'],
//                 'PriceBookEntryId'=>"01s5g000008PkdaAAC",
//                 'ListPrice'=>100,
//                 'UnitPrice'=>50,
//         ],
//         'OrderItem',
//         null,null,null
// );
// // echo ($query);
// echo $salesForce->insert();
// retail sale is 2 dollares 
// price book for hole sale is different from
// price book 
// $query = $salesForce->query
// (       
//         'select',


// )
//  $orderId =  $records[0]->Id;

// $query = $salesForce->
// $query = $salesForce->query
// (
//         'select',
//         [
//                 'name',
//                 'id',
//                 "AccountId",
//                 "EffectiveDate",
//                 "Status"
//         ],
//         "Order",
//         null,
//         null,
//         null
// );
// $query = $salesForce->query
// (
//         'insert',
//         [
//                 'name'=>'My First Product', 
//                 // "AccountId"=>"0015g000005vVGJAA2",
//                 // "EffectiveDate"=>date('Y-m-d'),
//                 // "Status"=>"Draft"
//         ],
//         "Product2",
//         null,
//         null,
//         null
// );

// $salesForce->query = $query;

// echo $salesForce->delete();


// echo $salesForce->getAccounts();
// echo $salesForce->insert();
// echo json_encode($salesForce);die;
// echo $query;
//ORder
// $query = $salesForce->query
// (
//         'insert',
//         [
//                 'name'=>'My First ORder', 
//                 "AccountId"=>"0015g000005vVGJAA2",
//                 "EffectiveDate"=>"2021-11-03",
//                 "Status"=>"Draft"
//         ],
//         "Order",
//         null,
//         null,
//         null
// );