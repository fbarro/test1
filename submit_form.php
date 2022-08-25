<?php 
    $validFields = array('positionApplied' => 'Position Applied For',
    'lastName' => 'Last Name', 'firstName' => 'First Name','middleName' => 'Middle Name',
    'passportNo' => 'Passport No', 'birthdate' => 'Birth Date', 'age' => 'Age',
    'placeOfBirth' =>'Place Of Birth', 'address' => 'Address', 'mobileNo' => 'Mobile No',
    'height' =>'Height', 'weight' => 'Weight',
    'fatherName' =>"Father's Name", 'motherName' => "Mother\'s Name", 
    'noOfBrothers' =>"No Of Brothers", 'noOfSisters' => 'No Of Sisters', 'spouseName' =>"Husband's Name",
    'noOfChildren' => 'No. of Children', 'ageOfEldestChild' => 'Age Of Eldest Child', 'ageOfYoungestChild' => 'Age Of Youngest Child',
    'nameOfSchool' => 'Name Of School (High School)', 'yearGraduated' => 'Year Graduated',
    'nameOfSchool2' => 'Name Of School (College/Vocational)', 'course' => 'Course', 'yearGraduated' => 'Year Graduated',
    'nameOfEmployer' =>'Name Of Employer', 'position' => 'Position', 'yearFrom' => 'Year (From)', 'yearTo' => 'Year (To)', 'countryEmployer' => 'Country',
    'nameOfEmployer1' =>'Name Of Employer~NR', 'position1' => 'Position~NR', 'yearFrom1' => 'Year (From)~NR', 'yearTo1' => 'Year (To)~NR', 'countryEmployer1' => 'Country~NR',
    'nameOfEmployer2' =>'Name Of Employer~NR', 'position2' => 'Position~NR', 'yearFrom2' => 'Year (From)~NR', 'yearTo2' => 'Year (To)~NR', 'countryEmployer2' => 'Country~NR');

    $isFieldsValidated = true;
    foreach ($validFields as $field => $value) {
        $pieces = explode("~", $value);

        if(isset($pieces[1])) continue;

        if (!isset($_POST[$field]) 
            || (isset($_POST[$field]) && empty($_POST[$field]))) {
            $isFieldsValidated = false;
        }
    }

    if( !isset($_FILES['umid']) || !isset($_FILES['photo']) ) {
        $isFieldsValidated = false;
    }

    $status = 'invalid';
    if(!$isFieldsValidated) {
        echo $status;die;
    }
    
    // Email body content 
    $htmlContent = getTextContents($validFields);


    // Recipient 
    // $to = 'fidelbarro@gmail.com'; 
    $to = 'mvfproims@gmail.com';
    
    // Sender 
    $from = 'info@mvfproims.com'; 
    $fromName = 'MFVProIMS'; 
    
    // Email subject 
    $subject = 'Taiwan Application : '.$_POST["lastName"].', '.$_POST["firstName"];  
    
    // Attachment file 
    $file = "assets/img/about.jpg"; 
    
    // Header for sender info 
    $headers = "From: $fromName"." <".$from.">"; 
    
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
    if( isset($_FILES['umid'])) {
        // if(is_file($file)){ 
             //Get uploaded file data using $_FILES array
            $tmp_name = $_FILES['umid']['tmp_name']; // get the temporary file name of the file on the server
            $name	 = $_FILES['umid']['name']; // get the name of the file
            $size	 = $_FILES['umid']['size']; // get size of the file for size validation
            $type	 = $_FILES['umid']['type']; // get type of the file
            $error	 = $_FILES['umid']['error']; // get the error (if any)

            //validate form field for attaching the file
            if($error > 0){
                $status = 'Invalid File.';
                echo $status;die;
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

    if( isset($_FILES['photo'])) {
        // if(is_file($file)){ 
             //Get uploaded file data using $_FILES array
            $tmp_name = $_FILES['photo']['tmp_name']; // get the temporary file name of the file on the server
            $name	 = $_FILES['photo']['name']; // get the name of the file
            $size	 = $_FILES['photo']['size']; // get size of the file for size validation
            $type	 = $_FILES['photo']['type']; // get type of the file
            $error	 = $_FILES['photo']['error']; // get the error (if any)

            //validate form field for attaching the file
            if($error > 0){
                $status = 'Invalid File.';
                echo $status;die;
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
 
    $returnpath = "-f" . $from; 
    // Send email 
    $mail = @mail($to, $subject, $message, $headers, $returnpath);  
    
    // Email sending status 
    echo $mail?"ok":"err>"; 

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
 
?>