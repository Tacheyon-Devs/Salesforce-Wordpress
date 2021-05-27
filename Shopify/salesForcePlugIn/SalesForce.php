<?php
class SalesForce
{
    public $version = "v51.0";
    public $password = "_badmalik_21mHVQ8zcTjuGwgyTkIqMno5e2b";
    public $clientId = "3MVG9fe4g9fhX0E5OcdClSH.aJ7rJsOiu7J9Q1RE4sYuqyvtdfe4m9LQVMmPRDOaQGq6ThtTceTY.T5JMQLQ8";
    public $clientSecretKey = "3BE69CB6CF614A0250DF368747C0743169848E366825C9961BA1CC84CB4C2EC2";
    public $userName = "bilal%40yourvteams.com"; 
    public $grantType = "password";
    public $accessToken;
    public $jsonContentType = "application/json";
    public $response;
    public $query;
    public $queryResult;
    public $sObjectId;
    public $userIdLink;
    public $localDBQuery;
    public $data;
    public $sObject;
    public $sessionId;
    public $instanceURL;
    public $contentType = " application/x-www-form-urlencoded";
    function __construct()
    {
        try
        {
            $curl = curl_init();
            curl_setopt_array
            ($curl, array
                (
                    CURLOPT_URL => 'https://login.salesforce.com/services/oauth2/token',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => 
                    'grant_type='.$this->grantType.
                    '&client_id='.$this->clientId.
                    '&client_secret='.$this->clientSecretKey.
                    '&username='.$this->userName.
                    '&password='.$this->password,
                    CURLOPT_HTTPHEADER => array
                    (
                        'Content-Type:'.$this->contentType,
                        // 'Cookie: BrowserId=VTS0poAYEeul8oHPrIvvbg'
                        
                    ),
                )
            );
            $this->response = json_decode(curl_exec($curl));
            $this->accessToken = $this->response->access_token;
            $this->instanceURL = $this->response->instance_url;
            $this->userIdLink = $this->response->id;
            curl_close($curl);
            $this->sessionId = $this->response->token_type.' '.$this->accessToken;
            
            // return ($this->response);
            return  ($this->response);
        }
        catch(Exception $e)
        {
          throw new Exception("Error in CUrl ".$e->getMessage);
        }
    }
    public function getUserData()
    {
          try
          {
              // echo $this->query;die;
              $curl = curl_init();
              curl_setopt_array
              ($curl, array
                  (
                      CURLOPT_URL => $this->userIdLink,
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'GET',
                      CURLOPT_HTTPHEADER => array
                      (
                          'Content-Type:'.$this->jsonContentType,
                          'Authorization:'.$this->sessionId ,
                          // 'Cookie: BrowserId=VTS0poAYEeul8oHPrIvvbg'
                      ),
                  )
              );
              // return $curl;
              $this->response = json_decode(curl_exec($curl));
              curl_close($curl);
              // return ($this->response);
              return ($this->response);
          }
          catch(Exception $e)
          {
            throw new Exception("Error in CUrl ".$e->getMessage);
            // echo $e->getMessage();
            // die;
          }
    }
    public function query($start,$props=[],$table=null,$where=null,$orderBy=null,$limit=null)
    {
      $type = strtolower($start);
      // echo json_encode(sizeof($props));die;

      $string="";
      if($type =="select")
      {
          $string.=$type.=" ";
          foreach($props as $prop)
          {
            $string.= $prop.",";
          }
          $string = rtrim($string,",")." ";
          if(isset($table))
          {
              $string.= "from ".$table.'';
          }
          else
          {
            echo "Please select a table";die;
          }

          if(isset($where))
          {
              $string .=" where";
              foreach($where as $prop => $value)
              {
                if(is_int($value))
                  $string.=" ".$prop." = ".$value. " AND";
                else
                  $string.=" ".$prop." = '".$value."' AND";
              }
              $string = rtrim($string," AND");
          }
          if(isset($orderBy))
          {
            $string.=" ORDER BY ".$orderBy;
          }
          if(isset($limit))
          {
            $string.=" LIMIT ".$limit;
          }
          $this->localDBQuery = $string;
          $this->query=urlencode($string);
          return $this->query;
      }
      else if($type == "insert")
      {
          $this->sObject=$table;
          $this->localDBQuery = "insert into `".$table."`(";
          $string ="{";
            
          foreach($props as $key => $value)
          {
            $this->localDBQuery.=$key.",";
          }
          $this->localDBQuery = rtrim($this->localDBQuery,",").") values (";
          foreach($props as $prop => $value)
          {
              
              if(is_int($value))
              {
                $this->localDBQuery.=$value.",";
              }
              else
              {
                $this->localDBQuery.="'".$value."',";
              }
          }
          $this->localDBQuery = rtrim($this->localDBQuery,",").")";
          foreach($props as $prop => $value)
          {
              $string.="'".$prop."':";
              if(is_int($value))
              {
                $string.=$value.",";
              }
              else
              {
                $string.="'".$value."',";
              }
          }
          $string = rtrim($string,",")."}";
          $string = str_replace("'",'"',$string);
          $this->data = $string;
          // $this->sync();
          return $string;
      }
      else if($type =="patch" || $type == "update")
      {
          $this->localDBQuery = "update ".$table." set ";
          $this->sObjectId = $where['id'];
          $this->sObject=$table;
          $string ="{";
          foreach($props as $prop => $value)
          {
              $string.="'".$prop."':";
              if(is_int($value))
              {
                $string.=$value.",";
              }
              else
              {
                $string.="'".$value."',";
              }
          }
          
          foreach($props as $key => $value)
          {
            if(is_int($value))
            {
              $this->localDBQuery.=$key."=".$value.",";
            }
            else
            {
              $this->localDBQuery.=$key."="."'".$value."',";
            }
          } 
          $this->localDBQuery = rtrim($this->localDBQuery,",");
          if(isset($where))
          {
              $this->localDBQuery .=" where ";

              foreach($where as $key2 => $value)
              {
                if(is_int($value))
                  $this->localDBQuery.=" ".$key2." = ".$value. " AND";
                else
                  $this->localDBQuery.=" ".$key2." = '".$value."' AND";
              }
              $this->localDBQuery = rtrim($this->localDBQuery," AND");
          }
          if(isset($orderBy))
          {
            $this->localDBQuery.=" ORDER BY ".$orderBy;
          }
          if(isset($limit))
          {
            $this->localDBQuery.=" LIMIT ".$limit;
          }
          $this->sync();
          // $this->localDBQuery = $string;
          // $this->query=urlencode($string);
          // return $this->query;
          $string = rtrim($string,",")."}";
          $string = str_replace("'",'"',$string);
          $this->data = $string;
          // return $string;
      }
      else if($type=="delete")
      {
          $this->sObjectId=$where[0];
          $this->sObject=$table;
          return $this->sObjectId;
      }
    }
    public function delete()
    {
        try
        {
            $curl = curl_init();
            curl_setopt_array
            ($curl, array
                (
                    CURLOPT_URL => $this->instanceURL."/services/data/".$this->version."/sobjects/".$this->sObject."/".$this->sObjectId,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'DELETE',
                    CURLOPT_POSTFIELDS =>$this->data,
                    'grant_type='.$this->grantType.
                    '&client_id='.$this->clientId.
                    '&client_secret='.$this->clientSecretKey.
                    '&username='.$this->userName.
                    '&password='.$this->password,
                    CURLOPT_HTTPHEADER => array
                    (
                        'Authorization:'.$this->sessionId ,
                    ),
                )
            );
            $this->response = json_decode(curl_exec($curl));
            curl_close($curl);
            return json_encode($this->response);
        }
        catch(Exception $e)
        {
          throw new Exception("Error in CUrl ".$e->getMessage);
        }
    }
    public function update()
    {
      try
      {
          $curl = curl_init();
          curl_setopt_array
          ($curl, array
              (
                  CURLOPT_URL => $this->instanceURL."/services/data/".$this->version."/sobjects/".$this->sObject."/".$this->sObjectId,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'PATCH',
                  CURLOPT_POSTFIELDS =>$this->data,
                  'grant_type='.$this->grantType.
                  '&client_id='.$this->clientId.
                  '&client_secret='.$this->clientSecretKey.
                  '&username='.$this->userName.
                  '&password='.$this->password,
                  CURLOPT_HTTPHEADER => array
                  (
                      'Content-Type:'.$this->jsonContentType,
                      'Authorization:'.$this->sessionId ,
                  ),
              )
          );
          $this->response = json_decode(curl_exec($curl));
          curl_close($curl);
          return json_encode($this->response);
      }
      catch(Exception $e)
      {
        throw new Exception("Error in CUrl ".$e->getMessage);
      }
    }
    public function insert()
    {
      try
      {
          $curl = curl_init();
          curl_setopt_array
          ($curl, array
              (
                  CURLOPT_URL => $this->instanceURL."/services/data/".$this->version."/sobjects/".$this->sObject."/",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>$this->data,
                  'grant_type='.$this->grantType.
                  '&client_id='.$this->clientId.
                  '&client_secret='.$this->clientSecretKey.
                  '&username='.$this->userName.
                  '&password='.$this->password,
                  CURLOPT_HTTPHEADER => array
                  (
                      'Content-Type:'.$this->jsonContentType,
                      'Authorization:'.$this->sessionId ,
                  ),
              )
          );
          $this->response = json_decode(curl_exec($curl));
          $id = $this->response->id;
          $this->localDBQuery = str_replace("`(","` (id,",$this->localDBQuery);
          $this->localDBQuery = str_replace("values (","values ('".$id."',",$this->localDBQuery);
          $this->sync();
          // return ($this->localDBQuery);
          curl_close($curl);  
          return json_encode($this->response);
      }
      catch(Exception $e)
      {
        throw new Exception("Error in CUrl ".$e->getMessage);
      }
    }
    public function getSpecificObject($object,$objectId)
    {
        try
        {
            // echo $this->query;die;
            $curl = curl_init();
            curl_setopt_array
            ($curl, array
                (
                    CURLOPT_URL => $this->instanceURL."/services/data/".$this->version."/sobjects/".$object."/".$objectId,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_POSTFIELDS => 
                    'grant_type='.$this->grantType.
                    '&client_id='.$this->clientId.
                    '&client_secret='.$this->clientSecretKey.
                    '&username='.$this->userName.
                    '&password='.$this->password,
                    CURLOPT_HTTPHEADER => array
                    (
                        'Content-Type:'.$this->jsonContentType,
                        'Authorization:'.$this->sessionId ,
                        // 'Cookie: BrowserId=VTS0poAYEeul8oHPrIvvbg'
                    ),
                )
            );
            // return $curl;
            $this->response = json_decode(curl_exec($curl));
            curl_close($curl);
            // return ($this->response);
            return json_encode($this->response);
        }
        catch(Exception $e)
        {
          throw new Exception("Error in CUrl ".$e->getMessage);
          // echo $e->getMessage();
          // die;
        }
    }
    public function getAccounts()
    {
        try
        {
            // echo $this->query;die;
            $curl = curl_init();
            curl_setopt_array
            ($curl, array
                (
                    CURLOPT_URL => $this->instanceURL."/services/data/".$this->version."/query/?q=".$this->query,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_POSTFIELDS => 
                    'grant_type='.$this->grantType.
                    '&client_id='.$this->clientId.
                    '&client_secret='.$this->clientSecretKey.
                    '&username='.$this->userName.
                    '&password='.$this->password,
                    CURLOPT_HTTPHEADER => array
                    (
                        'Content-Type:'.$this->contentType,
                        'Authorization:'.$this->sessionId ,
                        // 'Cookie: BrowserId=VTS0poAYEeul8oHPrIvvbg'
                    ),
                )
            );
            // return $curl;
            $this->response = json_decode(curl_exec($curl));
            curl_close($curl);
            // return ($this->response);
            return ($this->response);
        }
        catch(Exception $e)
        {
          throw new Exception("Error in CUrl ".$e->getMessage);
          // echo $e->getMessage();
          // die;
        }
    }
    public function sync()
    {
        try 
        {
              $pdo = new PDO('mysql:host=localhost;dbname=sf_local', 'root', '');
              $this->queryResult = $pdo->query($this->localDBQuery);
              // return $this->queryResult;

        } 
        catch (PDOException $e) 
        {
            return  json_encode("Error!: " . $e->getMessage() . "<br/>");
          
        }
    }
    public function localDBQuery()
    {
      try 
      {
            $pdo = new PDO('mysql:host=localhost;dbname=sf_local', 'root', '');
            $this->queryResult = $pdo->query($this->localDBQuery,PDO::FETCH_ASSOC);
            // return $this->queryResult;
            return $this->queryResult->fetchAll();
      } 
      catch (PDOException $e) 
      {
          return  json_encode("Error!: " . $e->getMessage() . "<br/>");
        
      }
    }
    public function fetchData()
    {
        $fields=
        [
          '0'=> [
                    "table"=>"account",
                    "fields"=>
                    [
                        "Id","Name","IsDeleted","Type","ParentId","BillingStreet","BillingCity","Phone",
                        "AccountNumber","PhotoUrl","Description"
                    ]
                ],
          '1'=> [
                    "table"=>"contact",
                    "fields"=>
                    [

                        'Id','IsDeleted',"AccountId","LastName","FirstName","Salutation",'Name',"MailingCity",
                        "MailingState","MailingPostalCode","MailingCountry","Phone","Email","Title","Department",
                        "LeadSource","Description",'OwnerId','CreatedById'
                    ]
                ],
          '2'=> [
                    "table"=>"contract",
                    "fields"=>
                    [

                        'Id','Pricebook2Id',"AccountId","StartDate","EndDate","ContractTerm",'Status',"CustomerSignedId",
                        "CustomerSignedTitle","CustomerSignedDate","SpecialTerms","ActivatedById","StatusCode","IsDeleted",
                        'OwnerId'
                    ]
                ],
          '3'=> [
                    "table"=>"order",
                    "fields"=>
                    [

                        'Id','Pricebook2Id',"AccountId","EffectiveDate","EndDate","Status","CustomerAuthorizedById","OrderNumber","TotalAmount",
                        "CompanyAuthorizedById","CustomerAuthorizedDate","CompanyAuthorizedDate","Type","Name","OrderReferenceNumber",'ActivatedById',
                        "OwnerId","ContractId","OriginalOrderId","promotion__c","sales_tax__c"
                    ]
                ],
          '4'=> [
                    "table"=>"orderitem",
                    "fields"=>
                    [
                        "Id","Product2Id","IsDeleted","OrderId","PricebookEntryId","OriginalOrderItemId","AvailableQuantity","Quantity",
                        "UnitPrice","ListPrice","TotalPrice","ServiceDate","EndDate","Description","CreatedById","OrderItemNumber"
                    ]
                ],
          '5'=> [
                    "table"=>"opportunity",
                    "fields"=>
                    [
                        "Id","IsDeleted","Description","CreatedById","AccountId","IsPrivate","Name","StageName","Amount",
                        "Probability","ExpectedRevenue","TotalOpportunityQuantity","Type","CloseDate","NextStep","LeadSource",
                        "IsClosed","IsWon","ForecastCategory","ForecastCategoryName","CampaignId","HasOpportunityLineItem",
                        "Pricebook2Id","OwnerId","LastActivityDate","ContactId"
                    ]
                ],
          '6'=> [
                    "table"=>"orderitem",
                    "fields"=>
                    [
                        "Id","IsDeleted","Description","Product2Id","OrderId","PricebookEntryId","OriginalOrderItemId",
                        "AvailableQuantity","Quantity","UnitPrice","ListPrice","TotalPrice","ServiceDate","EndDate","CreatedById",
                        "LastModifiedById","OrderItemNumber"
                    ]
                ],
          '7'=> [
                    "table"=>"pricebook2",
                    "fields"=>
                    [
                        "Id","IsDeleted","Description","Name","CreatedById","LastModifiedById","IsActive",
                        "IsArchived","IsStandard"
                    ]
                ],
          '8'=> [
                    "table"=>"product2",
                    "fields"=>
                    [
                        "Id","ProductCode","Description","Name","CreatedById","LastModifiedById","IsActive",
                        "IsDeleted","IsArchived"
                    ]
                ],
          '9'=> [
                    "table"=>"lead",
                    "fields"=>
                    [
                        "Id","IsDeleted","LastName","FirstName","Name","Salutation","Title","Company","Street","City","State",
                        "PostalCode","Country","Phone","MobilePhone","Fax","Email","Website","PhotoUrl","Description","LeadSource",
                        "Status","Industry","Rating","AnnualRevenue","NumberOfEmployees","OwnerId","IsConverted","ConvertedAccountId",
                        "ConvertedOpportunityId","IsUnreadByOwner","CreatedById","LastModifiedById",
                    ]
                ],
          '10'=>[
                    "table"=>"user",
                    "fields"=>
                    [
                        "Id","Username","FirstName","LastName","Name","CompanyName","Division","Department","Title","Street",
                        "PostalCode","Country","Email","SenderEmail","SenderName","Signature","Phone","MobilePhone","Alias",
                        "CommunityNickname","IsActive","UserRoleId","ProfileId","UserType","EmployeeNumber","DelegatedApproverId",
                        "ManagerId","CreatedById","LastModifiedById","ContactId","AccountId","CallCenterId","AboutMe","FullPhotoUrl",
                        "SmallPhotoUrl","MediumPhotoUrl","BannerPhotoUrl","SmallBannerPhotoUrl","MediumBannerPhotoUrl","IsProfilePhotoActive",
                        "IndividualId"
                    ]
                ],
          '11'=>[
                    "table"=>"pricebookentry",
                    "fields"=>
                    [
                        "Id","Name","Pricebook2Id","UnitPrice","IsActive","Product2Id","CreatedById","LastModifiedById",
                        "ProductCode","IsArchived","IsDeleted"
                    ]
                ]
        ];
        // echo json_encode(count($fields));die;
        // var_dump($fields);die;
        
        for($i=0;$i<count($fields);$i++)
        {
            $query = "select ".implode(",",$fields[$i]['fields'])." from ".$fields[$i]['table'];
            $this->query = urlencode($query);
            // $this->query = urlencode($contacts);
            $records =  $this->getAccounts()->records;
            // echo json_encode(($records));die;
            foreach($records as $record)
            {
              $this->localDBQuery= "select ".implode(",",$fields[$i]['fields'])." from "."`".$fields[$i]['table']."` where id='".$record->Id."'";
              // echo $this->localDBQuery;die;  
              $result = $this->localDBQuery();
                
              if(($result))
              {
                // echo "found";
                // die;
                  $result = (object)$result[0];
                  $update = "update `".$fields[$i]['table']."` set ";
                  foreach($fields[$i]['fields'] as $value)
                  {
                      if($record->$value==null || $record->$value == "")
                      {
                        continue;
                      }
                      else if(is_int($record->$value) || is_bool($record->$value))
                      {
                        $update.=$value."=".$record->$value.",";
                      }
                      else
                      {
                        $update.=$value."="."'".$record->$value."',";
                      }
                  }
                  $update = rtrim($update,",")." where id = '".$record->Id."'";
                  $this->localDBQuery=$update;
                  $this->sync();  
                  // echo $update;
                  
              }
              else
              {
                // echo " not found";
                // die;
                // echo json_encode($record);die;
                  $insert = "insert into `".$fields[$i]['table']."` (";
                  foreach($fields[$i]['fields'] as $value)
                  {
                      if($record->$value==null || $record->$value == "" || isset($record->$value) == false)
                      {
                          continue;                        
                      }
                      else
                      {
                          $insert.=$value.",";
                      }                    
                  }
                  $insert = rtrim($insert,",").") values (";
                  foreach($fields[$i]['fields'] as $value)
                  {   
                      if($record->$value==null || $record->$value == "" || isset($record->$value) == false)
                      {
                          continue;
                      }
                      else if(is_bool($record->$value) || is_int($record->$value))
                      {
                        $insert.=$record->$value.","; 
                      } 
                      else
                      {
                        $insert.="'".$record->$value."',";
                      }
                  }
                  $insert = rtrim($insert,",").")";
                  $this->localDBQuery=$insert;
                  // echo $insert;
                  $this->sync();  
                 
              }  
              
            }

        }
    }
}
          
?> 
