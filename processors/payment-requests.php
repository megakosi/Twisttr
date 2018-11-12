<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/config/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';

class SendPaymentRequests extends  Functions {

    private $data;
    private $start;
    private $number_of_requests_to_load = 2;
    private $results;
    private $error = "unable to connect to database";
    private $successText = "success";
    private  $emptyResult = false;
    private  $website_details;
    public function isReady() : bool  {
        return !empty($this->data = json_decode($_POST['data'] , true));
    }

    public function __construct()
    {
        parent::__construct();
        $this->website_details = new WebsiteDetails();
    }

    public function __destruct()
    {
        parent::__destruct(); // TODO: Change the autogenerated stub
    }

    private function setDetails() : bool {

        $this->start = (int)$this->data['start'];
        $results = $this->fetch_data_from_table_with_conditions($this->withdrawals_table_name , "id != 0 ORDER BY id ASC LIMIT {$this->start} ,  {$this->number_of_requests_to_load}");

        if(empty($results))$this->emptyResult = true;

        $this->results = "";


        foreach ($results as $result){
            $user_details = $this->fetch_data_from_table($this->users_table_name , "user_id" , $result['user_id'])[0];
            $amount_formated = number_format($result['amount']);
            $this->results.= <<<HTML

  <tr id = "{$result['reference_code']}">
                        <td>{$result['bank_name']}</td>
                        <td>{$result['account_name']}</td>
                        <td>{$result['account_number']}</td>
                        <td>{$user_details['email']}</td>
                        <td>{$user_details['phone']}</td>
                        <td>{$this->website_details->Naira}{$amount_formated}</td>
                        <td><p data-placement="top" data-toggle="tooltip" title="Delete"><button data-account-name = "{$result['account_name']}" data-toggle="modal" data-target="#myModal" class="btn btn-danger btn-xs delete-record-buttons" data-reference-code = "{$result['reference_code']}" data-userid = "{$user_details['user_id']}" data-title="Delete" data-toggle="modal" data-target="#delete" ><span class="fa fa-trash"></span></button></p></td>

                    </tr>

HTML;

        }

        return true;



    }

    public final function  Processor () : string  {

        if(!($this->isReady() and $this->setDetails()))return json_encode([$this->successText => "0" , "error" => $this->error]);


        return json_encode([$this->successText => "1" , "error" => $this->results , "empty" => $this->emptyResult]);

    }


}

$sendPaymentRequests = new SendPaymentRequests();
echo $sendPaymentRequests->Processor();


?>