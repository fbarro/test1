<?php 
    // path of the log file where errors need to be logged
    $log_file = "./logging/system-errors_".date("Ymd").".log";

    // setting error logging to be active
    ini_set("log_errors", 1); 
    
    // setting the logging file in php.ini
    ini_set('error_log', $log_file);

    $validFields = array('positionApplied' => 'Position Applied For',
    'lastName' => 'Last Name', 'firstName' => 'First Name','middleName' => 'Middle Name',
    'passportNo' => 'Passport No', 'birthdate' => 'Birth Date', 'age' => 'Age',
    'placeOfBirth' =>'Place Of Birth', 'address' => 'Address', 'mobileNo' => 'Mobile No',
    'civilStatus' =>'Civil Status', 'religion' => 'Religion',
    'height' =>'Height', 'weight' => 'Weight',
    'fatherName' =>"Father's Name", 'motherName' => "Mother's Name", 
    'noOfBrothers' =>"No Of Brothers", 'noOfSisters' => 'No Of Sisters', 'spouseName' =>"Husband's Name",
    'noOfChildren' => 'No. of Children', 'ageOfEldestChild' => 'Age Of Eldest Child', 'ageOfYoungestChild' => 'Age Of Youngest Child',
    'nameOfSchool' => 'Name Of School (High School)', 'yearGraduated' => 'Year Graduated',
    'nameOfSchool2' => 'Name Of School (College/Vocational)', 'course' => 'Course', 'yearGraduated2' => 'Year Graduated',
    'nameOfEmployer' =>'Name Of Employer', 'position' => 'Position', 'yearFrom' => 'Year (From)', 'yearTo' => 'Year (To)', 'countryEmployer' => 'Country',
    'nameOfEmployer1' =>'Name Of Employer~NR', 'position1' => 'Position~NR', 'yearFrom1' => 'Year (From)~NR', 'yearTo1' => 'Year (To)~NR', 'countryEmployer1' => 'Country~NR',
    'nameOfEmployer2' =>'Name Of Employer~NR', 'position2' => 'Position~NR', 'yearFrom2' => 'Year (From)~NR', 'yearTo2' => 'Year (To)~NR', 'countryEmployer2' => 'Country~NR',
    'optionBaby' =>'Do you have experience in taking care of babies?', 'optionBabyYes' => 'If Yes: Please write down the age and gender, and how long you take care of them',
    'optionElderly' =>'Do you have experience in taking care of elderly?', 
    'optionElderlyYes' => 'If YES: Please write down the age and gender, their condition (bedridden, diabetic etc.) and how long you take care of them:');

    $isFieldsValidated = true;
    foreach ($validFields as $field => $value) {
        $pieces = explode("~", $value);

        if(isset($pieces[1])) continue;

        if (!isset($_POST[$field]) 
            || (isset($_POST[$field]) && empty($_POST[$field]) &&!is_numeric($_POST[$field]))) {
             error_log("validated false field: $field - value : ". (!isset($_POST[$field]) ? '' : $_POST[$field]));
            $isFieldsValidated = false;
        }
    }

    // Recipient 
    $to = 'applymvfpro@gmail.com';
    if(isset($_POST['type']) && $_POST['type']=='test'){ //for debug purpose only
        $to = 'fidelbarro@gmail.com'; 
    } 

    if(!isset($_FILES['photo']) ) {
        error_log("photo false field");
        $isFieldsValidated = false;
    }
    
     if( !isset($_FILES['umid']) ) {
         error_log("umid false field");
        $isFieldsValidated = false;
    }

    $status = 'valid';
    if(!$isFieldsValidated) {
        $status = 'invalid';
        $msg = "Please fill up required fields, please try again. Err: $status";
        debugContents($validFields, true, $msg, '');
        error_log($msg);
        echo $msg;die;
    }

    // $secret = ""; 
    // $response = $_POST["captcha"];

    // error_log($response);

    // $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
    // $captcha_success = json_decode($verify);
    // if ($captcha_success->success==false) {
    //     $status = 'robot: '.$response. ' verify: '.$verify;
    //     // $status = 'robot';
    //     $msg = "Captcha validation failed. Make sure you have checked the Captcha Validation. Err: $status";
    //     error_log($msg);
    //     debugContents($validFields, true, $msg, '');
    //     echo $msg;die;
    // }
    
    // Email body content 
    $htmlContent = getTextContents($validFields);


    // Sender 
    $from = 'info@mvfproims.com'; 
    $fromName = 'MFVProIMS'; 
    
    // Email subject 
    $subject = 'Taiwan Application : '.$_POST["lastName"].', '.$_POST["firstName"];  
    
    // Attachment file 
    $file = "assets/img/about.jpg"; 
    
    // Header for sender info 
    // $headers = "From: $fromName"." <".$from.">"; 
    
    // Boundary  
    $semi_rand = md5(time());  
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
    
    // Headers for attachment  
    // $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
    $boundary = md5("random"); // define boundary with a md5 hashed value

    $headers = "MIME-Version: 1.0\r\n"; // Defining the MIME version
    $headers .= "From:".$from."\r\n"; // Sender Email
    // $headers .= "Reply-To: ".$reply_to_email."\r\n"; // Email address to reach back
    $headers .= "Content-Type: multipart/mixed;"; // Defining Content-Type
    $headers .= "boundary = $boundary\r\n"; //Defining the Boundary

     //plain text
     $message = "--$boundary\r\n";
     $message .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
     $message .= "Content-Transfer-Encoding: base64\r\n\r\n";
     $message .= chunk_split(base64_encode($htmlContent));
    
    // Preparing attachment 
    if (isset($_FILES['umid']) && $_FILES['umid']['size'] > 0){
        $tmp_name = $_FILES['umid']['tmp_name']; // get the temporary file name of the file on the server
        $name	 = $_FILES['umid']['name']; // get the name of the file
        $size	 = $_FILES['umid']['size']; // get size of the file for size validation
        $type	 = $_FILES['umid']['type']; // get type of the file
        $error	 = $_FILES['umid']['error']; // get the error (if any)

        //validate form field for attaching the file
        if($error > 0){
            $fileContents = "Invalid File :  $tmp_name, $name, $size, $type, $error";

            $status = 'Invalid File UMID.';
            $msg = "Invalid File, please try again. Err: $status";
            debugContents($validFields, true, $msg, $fileContents);
            error_log($msg);
            echo $msg;die;
        }
        
        // $message .= "--{$mime_boundary}\n"; 
        $fp =    @fopen($tmp_name,"r"); 
        $data =  @fread($fp, $size); 

        @fclose($fp); 
        $encoded_content = chunk_split(base64_encode($data)); 
        
            //attachment
        $message .= "--$boundary\r\n";
        $message .="Content-Type: $type; name=".$name."\r\n";
        $message .="Content-Disposition: attachment; filename=".$name."\r\n";
        $message .="Content-Transfer-Encoding: base64\r\n";
        $message .="X-Attachment-Id: ".rand(1000, 99999)."\r\n\r\n";
        $message .= $encoded_content; // Attaching the encoded file with email
    } 

    if (isset($_FILES['photo']) && $_FILES['photo']['size'] > 0){
             //Get uploaded file data using $_FILES array
            $tmp_name_p = $_FILES['photo']['tmp_name']; // get the temporary file name of the file on the server
            $name_p	 = $_FILES['photo']['name']; // get the name of the file
            $size_p	 = $_FILES['photo']['size']; // get size of the file for size validation
            $type_p	 = $_FILES['photo']['type']; // get type of the file
            $error_p	 = $_FILES['photo']['error']; // get the error (if any)

            //validate form field for attaching the file
            if($error_p > 0){
                $fileContents = "Invalid File :  $tmp_name_p, $name_p, $size_p, $type_p, $error_p";

                $status = 'Invalid File Photo.';
                $msg = "Invalid File, please try again. Err: $status";
                debugContents($validFields, true, $msg, $fileContents);
                error_log($msg);
                echo $msg;die;
            }
            
            // $message .= "--{$mime_boundary}\n"; 
            $fp_p =    @fopen($tmp_name_p,"r"); 
            $data_p =  @fread($fp_p, $size_p); 
    
            @fclose($fp_p); 
            $encoded_content_p = chunk_split(base64_encode($data_p)); 
           
             //attachment
            $message .= "--$boundary\r\n";
            $message .="Content-Type: $type_p; name=".$name_p."\r\n";
            $message .="Content-Disposition: attachment; filename=".$name_p."\r\n";
            $message .="Content-Transfer-Encoding: base64\r\n";
            $message .="X-Attachment-Id: ".rand(1000, 99999)."\r\n\r\n";
            $message .= $encoded_content_p; // Attaching the encoded file with email
    } 

    if (isset($_FILES['passport']) && $_FILES['passport']['size'] > 0){
             //Get uploaded file data using $_FILES array
            $tmp_name2 = $_FILES['passport']['tmp_name']; // get the temporary file name of the file on the server
            $name2	 = $_FILES['passport']['name']; // get the name of the file
            $size2	 = $_FILES['passport']['size']; // get size of the file for size validation
            $type2	 = $_FILES['passport']['type']; // get type of the file
            $error2	 = $_FILES['passport']['error']; // get the error (if any)

            //validate form field for attaching the file
            if($error2 > 0){
                $fileContents = "Invalid File :  $tmp_name2, $name2, $size2, $type2, $error2";

                $status = 'Invalid File Passport.';
                $msg = "Invalid File, please try again. Err: $status";
                debugContents($validFields, true, $msg, $fileContents);
                error_log($msg);
                echo $msg;die;
            }
            
            // $message .= "--{$mime_boundary}\n"; 
            $fp2 =    @fopen($tmp_name2,"r"); 
            $data2 =  @fread($fp2, $size2); 
    
            @fclose($fp2); 
            $encoded_content2 = chunk_split(base64_encode($data2)); 
           
            
             //attachment
            $message .= "--$boundary\r\n";
            $message .="Content-Type: $type2; name=".$name2."\r\n";
            $message .="Content-Disposition: attachment; filename=".$name2."\r\n";
            $message .="Content-Transfer-Encoding: base64\r\n";
            $message .="X-Attachment-Id: ".rand(10, 99999)."\r\n\r\n";
            $message .= $encoded_content2; // Attaching the encoded file with email
    } 

    if (isset($_FILES['birthCert']) && $_FILES['birthCert']['size'] > 0){
             //Get uploaded file data using $_FILES array
            $tmp_name3 = $_FILES['birthCert']['tmp_name']; // get the temporary file name of the file on the server
            $name3	 = $_FILES['birthCert']['name']; // get the name of the file
            $size3	 = $_FILES['birthCert']['size']; // get size of the file for size validation
            $type3	 = $_FILES['birthCert']['type']; // get type of the file
            $error3	 = $_FILES['birthCert']['error']; // get the error (if any)

            //validate form field for attaching the file
            if($error3 > 0){
                $fileContents = "Invalid File :  $tmp_name3, $name3, $size3, $type3, $error3";

                $status = 'Invalid File birthCert.';
                $msg = "Invalid File, please try again. Err: $status";
                debugContents($validFields, true, $msg, $fileContents);
                error_log($msg);
                echo $msg;die;
            }
            
            // $message .= "--{$mime_boundary}\n"; 
            $fp3 =    @fopen($tmp_name3,"r"); 
            $data3 =  @fread($fp3, $size3); 
    
            @fclose($fp3); 
            $encoded_content_3 = chunk_split(base64_encode($data3)); 
           
            
             //attachment
            $message .= "--$boundary\r\n";
            $message .="Content-Type: $type3; name=".$name3."\r\n";
            $message .="Content-Disposition: attachment; filename=".$name3."\r\n";
            $message .="Content-Transfer-Encoding: base64\r\n";
            $message .="X-Attachment-Id: ".rand(10, 99999)."\r\n\r\n";
            $message .= $encoded_content_3; // Attaching the encoded file with email
    } 
 
    $returnpath = "-f" . $from; 
    // Send email 
    $mail = @mail($to, $subject, $message, $headers, $returnpath);  
    
    // // Email sending status 
    // echo $mail?"ok":"err"; 

    if(!$mail) {
        $status = 'mail';
        $msg = "Submission of application failed, please try to submit again. Err: $status";
        debugContents($validFields, true, $status, '');
        error_log($msg);
        echo $msg;die;
    } else {
        error_log("Email send Successful :: Aplicant Name = ".$_POST['lastName']." ".$_POST['firstName']);
        echo 'ok';
    }

    function getHTMLContents($validFields){
        $htmlContent = '
        <h4>Taiwan Application details are given below.</h4>
        <table cellspacing="0" style="width: 300px; height: 200px;" border="1" style="border-style: dotted solid;">';
        
        foreach ($validFields as $field => $value) {
            $pieces = explode("~", $value);

            $htmlContent .= '<tr>
                <th>'.$pieces[0].':</th><td>'.$_POST[$field].'</td>
            </tr>';
        }
        
        return $htmlContent .= '</table>';
    
    }

    function getTextContents($validFields){
        $htmlContent = "Taiwan Application details are given below: \r\n \r\n";
       
        foreach ($validFields as $field => $value) {
            $pieces = explode("~", $value);

            $fieldName = $pieces[0];
            $fieldValue = $_POST[$field];
            
            $htmlContent .= " $fieldName :  $fieldValue \r\n";
        }
        
        return $htmlContent;
    }

    function debugContents($validFields, $err, $status, $fileContents){
        $strContents = '';
        error_log('debugContents..');
        foreach ($validFields as $field => $value) {
            $value = '';
            if (isset($_POST[$field])) {
                $value = $_POST[$field];
            }

            $strContents .= "Field : ".htmlspecialchars($field)." is ".htmlspecialchars($value)." \r\n";
        }

        if(!empty($fileContents)){
            $strContents .= "File Details : $fileContents";
        }
        $strContents .= "\r\n MESSAGE: $status";

        error_log('\r\n DEBUG LOG :: '. $strContents);
    }
 
?>
