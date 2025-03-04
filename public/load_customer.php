<?php

$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password, "test");


$row = 0;

if(($handle = fopen('customers.csv', 'r')) !== FALSE) {
    // necessary if a large csv file
    set_time_limit(0);   

    while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        $row++;
        //echo $row;
        // if($row<=10){
        //     echo "insert into users(`username`, `name`, `firstname`, `lastname`, `email`, `avatar`, `email_verified_at`, `password`, `role_id`, `is_active`, `user_type`, `user_id`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `address_street`, `address_municipality`, `address_city`, `address_region`, `registration_source`, `contact_tel`, `contact_mobile`, `contact_fax`, `contact_person`, `is_org`, `organization`, `agent_code`, `birthday`, `security_question`, `security_answer`, `branch`, `is_subscribe`) values (
        //     '".sprintf('%06d', $row)."','".$full."','".$firstname."','".$lastname."','lydtemp_".sprintf('%06d', $row)."@lydias.com','',null,'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','6','1','customer','1','',now(),now(),null,'".$data[5]."','','','','','".$data[3]."','".$data[4]."','".$data[3]."','','0','','',null,'','',null,0
        // )<br><br>";
        // }
        // number of fields in the csv
        $firstname = $data[0];
        $lastname = $data[1];
        $full = $firstname." ".$lastname;
        if($data[0] == $data[1]){
            $lastname = '';
            $full = $firstname;
        }
        
        if(strlen($full)>1){
            $insert = mysqli_query($conn, "insert into users(`username`, `name`, `firstname`, `lastname`, `email`, `avatar`, `email_verified_at`, `password`, `role_id`, `is_active`, `user_type`, `user_id`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `address_street`, `address_municipality`, `address_city`, `address_region`, `registration_source`, `contact_tel`, `contact_mobile`, `contact_fax`, `contact_person`, `is_org`, `organization`, `agent_code`, `birthday`, `security_question`, `security_answer`, `branch`, `is_subscribe`) values (
                '".sprintf('%06d', $row)."','".$full."','".$firstname."','".$lastname."','lydtemp_".sprintf('%06d', $row)."@lydias.com','',null,'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','6','1','customer','1','',now(),now(),null,'".$data[5]."','','','','','".$data[3]."','".$data[4]."','".$data[3]."','','0','','',null,'','',null,0
            )");
        }
        

        

    }
    fclose($handle);
}
?>