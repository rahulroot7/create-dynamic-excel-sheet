<?php
  $user_name = "xeamvent_xeamventures";
  $password  = "ZR[N!eUf!gbf";
  $database  = "xeamvent_xeamventures";
  $server    = "localhost";
  function cleanData(&$str)
  {   
      $str = preg_replace("/\t/", "\\t", $str);
      $str = preg_replace("/\r?\n/", "\\n", $str);
      $str = str_replace('\n', ' ', $str);
      if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  function FormatCSV($entry) {
      if(strpos($entry, ',') !== false || strpos($entry, '"') !== false) return '"'.str_replace('"', '""', $entry).'"';
      return $entry;
  }


  // filename for download
  $filename = "mpsegc_record_" . date('Y-m-d') . ".xls";
  header("Content-Disposition: attachment; filename=".$filename);
  header("Content-Type: application/vnd.ms-excel");

  $flag = false;
  $maxQualification = '';
  $db_handle = mysqli_connect($server, $user_name, $password);
  $db_found = mysqli_select_db($db_handle,$database);

  // get all max qualification
  $maxNumQualification = mysqli_query($db_handle,"SELECT count(*) as qualification_count, tsrlm_application_id FROM `tsrlm_qualification` GROUP By tsrlm_application_id ORDER By qualification_count DESC limit 1 ");
  while ($datarow = mysqli_fetch_assoc($maxNumQualification)) {
    $maxQualification =  $datarow['qualification_count'];
  }
  // end get all qualification

  // get Other Maxmum Qualification 
    $maxNumQualification = mysqli_query($db_handle,"SELECT count(*) as qualification_count, tsrlm_application_id FROM `tsrlm_other_qualification` GROUP By tsrlm_application_id ORDER By qualification_count DESC limit 1 ");
      while ($otherMaxQualificationRow = mysqli_fetch_assoc($maxNumQualification)) {
        $otherMaxQualification =  $otherMaxQualificationRow['qualification_count'];
      }
  // End other max qualification

  // get all max workexperience
  $maxNumWorkexperience = mysqli_query($db_handle,"SELECT count(*) as workexprience_count, tsrlm_application_id FROM `tsrlm_application_work_experience` GROUP By tsrlm_application_id ORDER By workexprience_count DESC limit 1");
  while ($dataWork = mysqli_fetch_assoc($maxNumWorkexperience)) {
    $maxWorkexperience =  $dataWork['workexprience_count'];
  }
   // end get all max workexperience


  // get all max workexperience
  $OtherMaxNumWorkexperience = mysqli_query($db_handle,"SELECT count(*) as workexprience_count, tsrlm_application_id FROM `tsrlm_other_work_experience` GROUP By tsrlm_application_id ORDER By workexprience_count DESC limit 1");
  while ($otherDataWork = mysqli_fetch_assoc($OtherMaxNumWorkexperience)) {
    $OtherMaxWorkexperience =  $otherDataWork['workexprience_count'];
  }
   // end get all max workexperience


    $result = mysqli_query($db_handle,"SELECT * FROM `tsrlm_application_form` ");
    $recordRow1 = array("Applied Job (Date)", "Registration No.","Post Name","Jobseeker Name","Aadhar No","Father Name", "Mother name","Email", "Date of Birth", "Mobile","Gender");

    // column qualification
    for($i = 1; $i <= $maxQualification; $i++){
       $recordRow1[] = "Qualification".$i;
    }
    // // end column qualification

    // other column qualification
    for($i = 1; $i <= $otherMaxQualification; $i++){
       $recordRow1[] = "Other Qualification".$i;
    }
    // other end column qualification

    // // column maxWorkexperience
    for($i = 1; $i <= $maxWorkexperience; $i++){
       $recordRow1[] = "Work Experience".$i;
    }
    // end column maxWorkexperience

    // other column OtherMaxWorkexperience
    for($i = 1; $i <= $OtherMaxWorkexperience; $i++){
       $recordRow1[] = " Other Work Experience".$i;
    }
    // other end column OtherMaxWorkexperience

    $recordRow3 = array("Total Exprience","Permanent Address", "Present Address", "Domcile State","Certificate Number", "Date of Issue"); 

    $dataArray = [];
    $columnName = array_merge($recordRow1,$recordRow3);  
    echo implode("\t", $columnName) . "\r\n";

    while ($row = mysqli_fetch_assoc($result)) {
          $dataId = $row['id'];

          // echo '<pre>';
           $joiningDate = date("d/m/Y",strtotime($row['create_at']));
          
            $dataArray = array($joiningDate,$row['registration_number'],$row['job_by_function'],$row['first_name'],$row['aadhar_number'],$row['father_or_husband_first_name'],$row['mother_first_name'],$row['email'],$row['date_of_birth'],$row['mobile_no'],$row['gender']);

          // print_r($joiningDate);
          $qualification = mysqli_query($db_handle,"SELECT * FROM `tsrlm_qualification` where `tsrlm_application_id`='".$dataId."' ");

          $qualificationRow = '';
          // $passing_year = '';
          while ($qualificationRow = mysqli_fetch_assoc($qualification)) {         
            $dataArray[] = $qualificationRow['dgree'].' [ '.$qualificationRow['course'].' ] ';
          }

          $diff = $maxQualification -$qualification->num_rows;
          for($i = 1; $i <= $diff; $i++){
            $dataArray[] = " ";
          }

          // other Qualification 
            $otherQualification = mysqli_query($db_handle,"SELECT * FROM `tsrlm_other_qualification` where `tsrlm_application_id`='".$dataId."' ");

            $otherQualificationRow = '';
            // $passing_year = '';
            while ($otherQualificationRow = mysqli_fetch_assoc($otherQualification)) {         
              $dataArray[] = $otherQualificationRow['other_dgree'].' [ '.$otherQualificationRow['other_course'].' ] ';
            }

            $otherDiff = $otherMaxQualification -$otherQualification->num_rows;
            for($i = 1; $i <= $otherDiff; $i++){
              $dataArray[] = " ";
            }
          // end other quallification

          // wxprience show
          $experience = mysqli_query($db_handle,"SELECT * FROM `tsrlm_application_work_experience` where `tsrlm_application_id`='".$dataId."' ");
          // $exprence = '';
          $experienceRow = '';
          $duration = '';
          while ($experienceRow = mysqli_fetch_assoc($experience)) {
            $dataArray[] = $experienceRow['designation'].' [ '.$experienceRow['duration'].' ] ';
            $duration .= $experienceRow['duration'].' ,';
            // print_r($exprence);
          }

          $differenceWork = $maxWorkexperience -$experience->num_rows;
          for($i = 1; $i <= $differenceWork; $i++){
            $dataArray[] = " ";
          }


           // other Work Exprieance 
            $otherExperience = mysqli_query($db_handle,"SELECT * FROM `tsrlm_other_work_experience` where `tsrlm_application_id`='".$dataId."' ");

            $otherQualificationRow = '';
            // $passing_year = '';
            while ($otherExprieanceRow = mysqli_fetch_assoc($otherExperience)) {         
              $dataArray[] = $otherExprieanceRow['other_designation'].' [ '.$otherExprieanceRow['other_duration'].' ] ';
            }

            $otherWork = $OtherMaxWorkexperience -$otherExperience->num_rows;
            for($i = 1; $i <= $otherWork; $i++){
              $dataArray[] = " ";
            }
          // end other Work Exprieance 


          $newArr = [$duration,$row['permanent_address'],$row['present_address'],$row['domicile_state'],$row['domicile_certificate'],$row['issue_date']];
         $dataArray = array_merge($dataArray, $newArr);
         // echo '<pre>';
         //  print_r($dataArray);
        // exit;
          // display field/column names as first row        
        // $dataArray = array($joiningDate,$row['registration_number'],$row['job_by_function'],$row['first_name'],$row['aadhar_number'],$row['father_or_husband_first_name'],$row['mother_first_name'],$row['email'],$row['date_of_birth'],$row['mobile_no'],$row['gender'],$dgree,$exprence,$duration,$row['permanent_address'],$row['present_address'],$row['domicile_state'],$row['domicile_certificate'],$row['issue_date']);

        array_walk($dataArray, 'cleanData');
        $csv_output = '';
        $tempArray = $dataArray;
        // print_r($dataArray);
        foreach ($dataArray as $key => $value) {
          $value = str_replace(';', '', $value);
          //$value = str_replace('"', '', $value);
          $formattedrow = FormatCSV($value);
          $tempArray[$key] = $formattedrow;  
        }
        array_walk($tempArray, 'cleanData');
        echo implode("\t", array_values($tempArray)) . "\r\n";
    }
  exit;
  ?>