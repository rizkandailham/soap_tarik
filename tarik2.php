<?php
function dateIsBetween($from, $to, $date) {
    $date = new \DateTime($date);
    $from= new \DateTime($from);
    $to = new \DateTime($to);
    if ($date >= $from && $date <= $to) {
        return true;
    }
    return false;
}

$servername = "localhost";
$username = "rsud";
$password = "RSUDSekayu18@";
$dbname = "akter";

$conn= new mysqli($servername, $username,$password,$dbname);

if($conn->connect_error){
	die("Connection failed: ".$conn->connect_error);
}
$data="select * from retina;";
$result = $conn->query($data);
while($row = $result->fetch_assoc()){
//if(!dateIsBetween('2020-02-18 07:30:00','2020-02-25 07:30:59',$DateTime))continue;
        $no++;
        $Status=$row["status"];
        $waktu=$row["waktu"];
        $waktu2=$row["waktu"];
        $no_id=$row["no_id"];
        $waktu=date("H:i:s",strtotime($waktu));
        if($waktu>="07:00:00"&&$waktu<="07:30:59"){
        $sql="insert into csv_apel(no_id,waktu,status) values('$no_id',STR_TO_DATE('$waktu2','%d-%m-%Y %h:%i:%s'),'$Status')";

          if($conn->query($sql)==TRUE){}
          else{$data_gagal++; echo $conn->error; break;}
      }

        }


?>
