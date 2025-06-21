<?php 
include 'util_config.php';
include 'util_session.php';

$cleaner_id="";
$div_id = 0;
if(isset($_POST['cleaner_id'])){
    $cleaner_id=$_POST['cleaner_id'];
}
?>
<div class="row div-background pt-3 pb-4" >
    <div  id="" class="col-lg-4  col-md-4">
        <h3>Rooms Assign</h3>
    </div>
    <div  id="" class="col-lg-8  col-md-8">
        <div class="row">
            <div  id="" class="col-lg-4  col-md-4">
                <div class="row">
                    <div  id="" class="col-lg-2  col-md-2 center_div1">
                        <div class="box_assign"></div>
                    </div>
                    <div  id="" class="col-lg-10  col-md-10">
                        <small>Assign to this user</small>
                    </div>
                </div>


            </div>
            <div  id="" class="col-lg-4  col-md-4">
                <div class="row">
                    <div  id="" class="col-lg-2  col-md-2 center_div1">
                        <div class="box_other_user_assign"></div>
                    </div>
                    <div  id="" class="col-lg-10  col-md-10">
                        <small>Assign to other user</small>
                    </div>
                </div>


            </div>
            <div  id="" class="col-lg-4  col-md-4">
                <div class="row">
                    <div  id="" class="col-lg-2  col-md-2 center_div1">
                        <div class="box_unassign"></div>
                    </div>
                    <div  id="" class="col-lg-10  col-md-10">
                        <small>Unassigned</small>
                    </div>
                </div>


            </div>
        </div>

    </div>

    <?php
    $sql="SELECT * FROM `tbl_rooms`  WHERE `hotel_id` = $hotel_id and `is_active` = 1 ORDER BY `tbl_rooms`.`room_num` ASC";
    $result = $conn->query($sql);
    $i = 0;
    if ($result && $result->num_rows > 0) {
        while($row = mysqli_fetch_array($result)) {
            $rms_id         = $row["rms_id"];
            $room_num       = $row["room_num"];
            $room_name      = $row["room_name"];
            $room_name_it   = $row["room_name_it"];
            $room_name_de   = $row["room_name_de"];
            $floor_id       = $row["floor_id"];
            $room_cat_id    = $row["room_cat_id"]; 
            $assigned = "";
            $check_user = 0;
            $sql_check="SELECT assign_to FROM tbl_housekeeping WHERE room_id = $rms_id";
            $result_check = $conn->query($sql_check);
            if ($result_check && $result_check->num_rows > 0) {
                while($row_check = mysqli_fetch_array($result_check)) {
                    $check_user  = $row_check[0];
                }
            }
            if($check_user == $cleaner_id){
                $assigned = "assign_background";
            }else if($check_user == 0) {
                $assigned = "";

            }else{
                $assigned = "assign_background2";   
            }



    ?> 
    <div  id="" class="col-lg-2  col-md-2">
        <div onclick ="room_assign_update(this,<?php echo $rms_id;?>,<?php echo $cleaner_id;?>)" id="<?php echo "single_".$div_id ?>"  class="row <?php echo $assigned; ?> div-white-background pointer room_text_center pt-2 pb-2 ml-1 mr-1 mt-2">
            <div  class="col-lg-12">
                <span><?php echo $room_num ?></span>
            </div>


        </div>

    </div>



    <?php

        $div_id = $div_id + 1;

        }}?>

</div>
<?php   
?>
