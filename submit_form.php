<?php
      if( file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php' )) {
        include( $php_email_form );
      } else {
        die( 'Unable to load the Email Library!');
      }

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
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $isFieldsValidated = false;
        }
    }

    $status = 'invalid';
    if(!$isFieldsValidated) {
        echo $status;die;
    }
    /*
     * Send email to admin
     */
    $to     = 'applymvfpro@gmail.com';
    $subject= 'Taiwan Application';
    
    $htmlContent = '
    <h4>Taiwan Application details are given below.</h4>
    <table cellspacing="0" style="width: 300px; height: 200px;">';
    
    foreach ($validFields as $field => $value) {
        $pieces = explode("~", $value);

        $htmlContent .= '<tr>
             <th>'.$pieces[0].':</th><td>'.$_POST[$field].'</td>
         </tr>';
    }
    
    $htmlContent .= '</table>';
    
    // Set content-type header for sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    
    // Additional headers
    $headers .= 'From: MVFProIMS<sender@mvfproims.com>' . "\r\n";
    
    // Send email
    if(mail($to,$subject,$htmlContent,$headers)){
        $status = 'ok';
    }else{
        $status = 'err';
    }
    
    // Output status
    echo $status;die;