<?php

require_once  $_SERVER['DOCUMENT_ROOT'].'/static/incs/header.php';
require_once  $_SERVER['DOCUMENT_ROOT'].'/funcs/user_functions.php';


class HomePage extends  Functions {

    public $DefaultHeaders;
    public $WebsiteDetails;
    public $Header;
    public  $UserFunctions;
    private $WholePage;
    public  $loggedInUserDetails;


    function __construct()
    {

        parent::__construct();
        $this->WebsiteDetails = new WebsiteDetails();
        $this->UserFunctions = new UserManagementFunctions();
        $this->DefaultHeaders = new  WebsiteHeader($this->WebsiteDetails->SiteName." • Online Game Play Now and Win" ,
            "Online Game where you play with friends and Get paid for Winning." ,
            "Space Invaders , Play Now , Win , ₦2000 , ₦5000 , ₦10000 Instantly");
        $this->Header = new Header();

            $this->loggedInUserDetails = ($this->UserFunctions->isLoggedInUser())? $this->UserFunctions->getLoggedInUserDetails() : $this->loggedInUserDetails;

    }



    function __destruct()
    {
        parent::__destruct(); // TODO: Change the autogenerated stub

    }


    public function DisplayWholePage () : string {


        $this->WholePage = <<<HomePage

HomePage;

        return $this->WholePage;





    }




}



$HomePage = new HomePage();
//echo $HomePage->DisplayWholePage();

?>

<!DOCTYPE html>


<html lang = "en-us" dir="ltr">


<head>
    <?php echo $HomePage->DefaultHeaders->GetDefaultPageHeadTags(); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $HomePage->WebsiteDetails->CSS_FOLDER ?>profile.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $HomePage->WebsiteDetails->CSS_FOLDER ?>new-profile.css" />
    <script type="text/javascript" language="JavaScript" src="<?php echo $HomePage->WebsiteDetails->JS_FOLDER; ?>main.js"></script>
    <script type="text/javascript" language="JavaScript" src="<?php echo  $HomePage->WebsiteDetails->JS_FOLDER; ?>defaults.js"></script>

       <?php //echo  '<script src="https://js.paystack.co/v1/inline.js"; ></script>';  ?>
        <script type="text/javascript" language="JavaScript" src="<?php echo  $HomePage->WebsiteDetails->JS_FOLDER; ?>GameControl.js"></script>
        <script type="text/javascript" language="JavaScript" src="<?php echo  $HomePage->WebsiteDetails->JS_FOLDER; ?>control.js"></script>




    <link rel="stylesheet" type="text/css" href="<?php echo  $HomePage->WebsiteDetails->CSS_FOLDER; ?>defaults.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  $HomePage->WebsiteDetails->CSS_FOLDER; ?>footer.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  $HomePage->WebsiteDetails->CSS_FOLDER; ?>control.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo  $HomePage->WebsiteDetails->CSS_FOLDER; ?>game.css" />

</head>


<body>
<?php echo $HomePage->Header->DisplayHeader(); ?>
<div class="page" id="home-page">
<?php


$email_about_to_be_verified = false;
$account_about_to_be_recovered = false;
$about_to_be_verified_user="";
$about_to_be_recovered_user="";
$about_to_change_bank_details = false;
$about_to_be_changed_bank_details_user = "";
$about_to_be_changed_bank_details_user_details = "";
if (isset($_GET['action']) && !empty($_GET['action'])){

    $action = $_GET['action'];
    switch ($action) {
        case 'verify_account' :
            $code = $_GET['code'];
            if (!isset($code) or strlen($code) != $HomePage->email_verification_code_length)
                header("Location:{$HomePage->WebsiteDetails->ErrorPage}");

            $is_valid_code = !empty($about_to_be_verified_user = $HomePage->fetch_data_from_table($HomePage->users_table_name , 'email_verification_code' , $code)[0]);
            if(!$is_valid_code or $about_to_be_verified_user["email_verified"] == "1")
                header("Location:{$HomePage->WebsiteDetails->ErrorPage}");

            $HomePage->update_record($HomePage->users_table_name , 'email_verified' , '1' , 'email_verification_code' , $code);
            $new_email_verification_code = str_shuffle($HomePage->generateID($HomePage->email_verification_code_length));
            $HomePage->update_record($HomePage->users_table_name , 'email_verification_code' , $new_email_verification_code , 'email_verification_code' , $code);

            $email_about_to_be_verified = true;







            break;
        case 'account_recovery' :
            $code = $_GET['code'];
            if (!isset($code) or strlen($code) != $HomePage->password_reset_code_length)
                header("Location:{$HomePage->WebsiteDetails->ErrorPage}");

            $is_valid_code = !empty($about_to_be_recovered_user = $HomePage->fetch_data_from_table($HomePage->users_table_name , 'password_reset_code' , $code)[0]);
            if(!$is_valid_code)
                header("Location:{$HomePage->WebsiteDetails->ErrorPage}");

            $account_about_to_be_recovered = true;
            break;
        case 'update_bank_details' :
            $code = $_GET['code'];
            if (!isset($code) or strlen($code) != $HomePage->bank_details_verification_code_length)

                header("Location:{$HomePage->WebsiteDetails->ErrorPage}");



            $is_valid_code = !empty($about_to_be_changed_bank_details_user = $HomePage->fetch_data_from_table($HomePage->pending_bank_details_table_name , 'verification_code' , $code)[0]);
            if(!$is_valid_code)
                header("Location:{$HomePage->WebsiteDetails->ErrorPage}");
            $about_to_change_bank_details = true;
            $HomePage->update_multiple_fields($HomePage->users_table_name , ["bank_name" => $about_to_be_changed_bank_details_user["bank_name"] , "account_name" => $about_to_be_changed_bank_details_user["account_name"] , "account_number" => $about_to_be_changed_bank_details_user["account_number"] ] , "user_id='{$about_to_be_changed_bank_details_user['user_id']}'");
            $about_to_be_changed_bank_details_user_details = $HomePage->fetch_data_from_table($HomePage->users_table_name , "user_id" , $about_to_be_changed_bank_details_user["user_id"])[0];
            $HomePage->delete_record($HomePage->pending_bank_details_table_name , 'verification_code' , $code);
            $HomePage->update_multiple_fields($HomePage->withdrawals_table_name , ["bank_name" => $about_to_be_changed_bank_details_user["bank_name"] , "account_name" => $about_to_be_changed_bank_details_user["account_name"] , "account_number" => $about_to_be_changed_bank_details_user["account_number"]] , "user_id = '{$about_to_be_changed_bank_details_user['user_id']}'" );
            break;



    }

}


if ($email_about_to_be_verified){ ?>

    <div class="alert alert-info fade in" id = "email-verified-warning-container">
        <span id="email-verified-warning-text" data-wait-text = "please wait.....">Your email address <strong id = "verified-email-address"><?php echo  strtolower($about_to_be_verified_user["email"]); ?> </strong>has been verified.

        <a href="#" class="close" data-dismiss="alert" aria-label="close" id = "">&times;</a>
        </span>
    </div>




<?php }


elseif ($account_about_to_be_recovered){

    require_once $HomePage->WebsiteDetails->INCS_FOLDER.'password-reset-modal-form.php';



    ?>



<?php } elseif($about_to_change_bank_details)  { ?>





<div class="alert alert-info fade in" id = "bank-details-changed-container">
        Dear , <strong><?php echo $about_to_be_changed_bank_details_user_details["fullname"]; ?></strong> Your bank details has been updated successfully.

        <a href="#" class="close" data-dismiss="alert" aria-label="close" id = "">&times;</a>

</div>



<?php  ?>





<?php } elseif(!$HomePage->UserFunctions->isVerifiedEmail())  { ?>





<div class="alert alert-info fade in" id = "email-not-verified-warning-container">
        <span id="email-not-verified-warning-text" data-wait-text = "please wait.....">Your email address <strong id = "unverified-email-address"><?php echo  strtolower($HomePage->UserFunctions->user_details["email"]); ?> </strong>has not been verified yet.
        <a href="#" class="alert-link" id="resend-verification-link">Click here to send verification link</a>.

        <a href="#" class="close" data-dismiss="alert" aria-label="close" id = "">&times;</a>
        </span>
</div>



<?php } ?>


<?php

if(!$HomePage->UserFunctions->isLoggedInUser()) {

    require_once $HomePage->WebsiteDetails->INCS_FOLDER.'default-user.php';
    require_once $HomePage->WebsiteDetails->INCS_FOLDER.'login-warning.php';
}

else {
   //echo($HomePage->UserFunctions->getLoggedInUserDetails());

       require_once $HomePage->WebsiteDetails->INCS_FOLDER.'user.php';
   // require_once $HomePage->WebsiteDetails->INCS_FOLDER.'settings.php';


}

?>
<input type="hidden" data-bank-details-about-to-be-changed = "<?php echo ($about_to_change_bank_details)?"1" : "0";?>" data-account-about-to-be-recovered = "<?php echo ($account_about_to_be_recovered)?"1" : "0";?>" data-email-about-to-be-verified = "<?php echo ($email_about_to_be_verified)? '1' : '0'?>" data-user-details = '<?php echo (empty($HomePage->UserFunctions->user_details))?'0' : json_encode($HomePage->UserFunctions->user_details , true)?>' id="page-information" data-verified-email = "<?php echo ($HomePage->UserFunctions->isVerifiedEmail())?"1":"0"; ?>" data-logged-in-user = "<?php echo ($HomePage->UserFunctions->isLoggedInUser())? '1':'0'?>"/>

</div>

<div id="game-page" class="container page">
                 <div id="game-start-circle-container">
                    <div class="circle circle1">
                        <a href="#section_1"><h2><span id="game-number-of-players-start-count">10</span><small id="game-start-users-text">users</small><br /><p>Joined</p></h2></a>
                    </div>
                 </div>


</div>
<?php require_once $HomePage->WebsiteDetails->INCS_FOLDER.'footer.php'; ?>
</body>

</html>

