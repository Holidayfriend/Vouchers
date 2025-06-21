<?php 
include '../util_config.php';
include '../util_session.php';


$lan = '';
$v_code="";
$new_user_id = "";
if(isset($_POST['v_code'])){
    $v_code=$_POST['v_code'];
}
if(isset($_POST['new_user_id'])){
    $new_user_id=$_POST['new_user_id'];
}
if(isset($_POST['lan'])){
    $lan = $_POST['lan'];
}


$sql="SELECT * from tbl_user WHERE auth_code = '$v_code' AND user_id = '$new_user_id'";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $row = mysqli_fetch_array($result);

    $user_type_ = $row['user_type'];
    $foreign_id_ = $row['foreign_id'];    
    $my_user_id_ = $row['user_id']; 
    $language = $row['language'];
    $name_ = $row['name'];
    $email_ = $row['email'];
    $image_ = $row['image'];

    if($user_type_ == 'SUPER_ADMIN'){
        $is_active = 'ACTIVE';
        $is_delete = '0';
        $saved_user_id = $row['user_id'];

    }
    else if($user_type_ == 'CITY_ADMIN'){

        $sql_new=" SELECT * FROM `tbl_city` WHERE `city_id` =  $foreign_id_ ";

        $result_new = $conn->query($sql_new);
        if ($result_new && $result_new->num_rows > 0) {
            $row_new = mysqli_fetch_array($result_new);


            $saved_user_id = $row_new['city_id'];


        }

    }

    $is_active = '';
    $company_name = '';
    $company_telephone = '';
    $company_tex_info = '';
    $company_additional_info = '';
    $is_delete = '';
    $city_id = '';



    $_SESSION['saved_user_id'] = $saved_user_id;
    $_SESSION['name_is'] = $name_;
    $_SESSION['email_is'] = $email_;
    $_SESSION['user_type_is'] = $user_type_;
    $_SESSION['image_is'] = $image_;
    $_SESSION['foreign_id_is'] = $foreign_id_;  
    $_SESSION['company_name'] = $company_name;
    $_SESSION['company_telephone'] = $company_telephone;
    $_SESSION['company_tex_info'] = $company_tex_info;
    $_SESSION['city_id'] = $city_id;
    $_SESSION['my_user_id_'] = $my_user_id_;
    $_SESSION['language'] = $language;



    if($user_type_ == 'SUPER_ADMIN'){

        if($lan == 'en'){

            if($language == 'EN'){
                echo '<script type="text/javascript">window.location.href = "super_dashboard.php";</script>';
            }else if($language == 'DE'){
                echo '<script type="text/javascript">window.location.href = "de/super_dashboard.php";</script>';
            }else if ($language == 'IT'){
                echo '<script type="text/javascript">window.location.href = "it/super_dashboard.php";</script>';
            }
        }else if($lan == 'it' ){
            if($language == 'EN'){
                echo '<script type="text/javascript">window.location.href = "../super_dashboard.php";</script>';
            }else if($language == 'DE'){
                echo '<script type="text/javascript">window.location.href = "../de/super_dashboard.php";</script>';
            }else if ($language == 'IT'){
                echo '<script type="text/javascript">window.location.href = "super_dashboard.php";</script>';
            }
        }else if($lan == 'de'){
            if($language == 'EN'){
                echo '<script type="text/javascript">window.location.href = "../super_dashboard.php";</script>';
            }else if($language == 'DE'){
                echo '<script type="text/javascript">window.location.href = "super_dashboard.php";</script>';
            }else if ($language == 'IT'){
                echo '<script type="text/javascript">window.location.href = "../it/super_dashboard.php";</script>';
            }
        }

    }else if($user_type_ == 'CITY_ADMIN'){
        if($lan == 'en'){
            if($language == 'EN'){
                echo '<script type="text/javascript">window.location.href = "city_dashboard.php";</script>';
            }else if($language == 'DE'){
                echo '<script type="text/javascript">window.location.href = "de/city_dashboard.php";</script>';
            }else if ($language == 'IT'){
                echo '<script type="text/javascript">window.location.href = "it/city_dashboard.php";</script>';
            }
        }else if($lan == 'it' ){
            if($language == 'EN'){
                echo '<script type="text/javascript">window.location.href = "../city_dashboard.php";</script>';
            }else if($language == 'DE'){
                echo '<script type="text/javascript">window.location.href = "../de/city_dashboard.php";</script>';
            }else if ($language == 'IT'){
                echo '<script type="text/javascript">window.location.href = "city_dashboard.php";</script>';
            }
        }else if($lan == 'de'){
            if($language == 'EN'){
                echo '<script type="text/javascript">window.location.href = "../city_dashboard.php";</script>';
            }else if($language == 'DE'){
                echo '<script type="text/javascript">window.location.href = "city_dashboard.php";</script>';
            }else if ($language == 'IT'){
                echo '<script type="text/javascript">window.location.href = "../it/city_dashboard.php";</script>';
            }
        }

    }
    //    echo '1';
}



else{
    echo '0';
}


?>