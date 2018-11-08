<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/config/functions.php';




class CreatePaymentHistory extends Functions {

    private $data , $referenceCode , $payment_date , $error = "unable to connect to database" , $successText = "success" , $debug_data;



    function __construct()
    {
        parent::__construct();
    }

    function __destruct()
    {
        parent::__destruct(); // TODO: Change the autogenerated stub
    }

    private final  function isReady() : bool  {

        return !empty($this->data = json_decode($_POST['data'] , true));

    }


    private final function setDetails () : bool  {

        $this->referenceCode = $this->data['referenceCode'];
        return true;
    }

    private final function  performAction() : bool  {
        $this->payment_date = date("d-M-Y h:i:s A");
        $history = $this->fetch_data_from_table($this->withdrawals_table_name , "reference_code" , $this->referenceCode)[0];
        $this->insert_into_table($this->payment_history_table_name , [
            "user_id" => $history['user_id'] ,
            "reference_code" => $history['reference_code'] ,
            "time_stamp" => $history['time_stamp'] ,
            "payment_date" => $this->payment_date ,
            "amount" =>  $history['amount'] ,
            "bank_name" => $history['bank_name'] ,
            "account_name" => $history['account_name'] ,
            "account_number" => $history['account_number'] ,
            "type" => $history['type']
            ] , function ($msg){

            $this->debug_data = $msg;

            $this->delete_record($this->withdrawals_table_name , "reference_code" , $this->referenceCode);
        });


        return true;
    }


    final  public  function Processor () : string  {
        if(!($this->isReady() and $this->setDetails())) return json_encode([$this->successText => "0" , "error" => $this->error]);
        $this->performAction();
        return json_encode(["success" => "1" , "error" => "null" , "data" => $this->debug_data]);

    }

}



$createPaymentHistory = new CreatePaymentHistory();

echo $createPaymentHistory->Processor();


?>