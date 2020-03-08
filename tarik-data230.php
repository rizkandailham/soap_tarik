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

function Parse_Data($data,$p1,$p2){
        $data=" ".$data;
        $hasil="";
        $awal=strpos($data,$p1);
        if($awal!=""){
                $akhir=strpos(strstr($data,$p1),$p2);
                if($akhir!=""){
                        $hasil=substr($data,$awal+strlen($p1),$akhir-strlen($p1));
                }
        }
        return $hasil;
}
?>
<?php
$IP="172.16.9.235";
$Key="0";
        $Connect = fsockopen($IP, "80", $errno, $errstr, 1);
        if($Connect){
                $soap_request="<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
                $newLine="\r\n";
                fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
            fputs($Connect, "Content-Type: text/xml".$newLine);
            fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
            fputs($Connect, $soap_request.$newLine);
                $buffer="";
                while($Response=fgets($Connect, 1024)){
                        $buffer=$buffer.$Response;
                }
        }else echo "Koneksi Gagal";

//      include("parse.php");
        $buffer=Parse_Data($buffer,"<GetAttLogResponse>","</GetAttLogResponse>");
        $buffer=explode("\r\n",$buffer);
        $no=1;
        $data_gagal=0;
        for($a=0;$a<count($buffer);$a++){
        $data=Parse_Data($buffer[$a],"<Row>","</Row>");
        $DateTime=Parse_Data($data,"<DateTime>","</DateTime>");
        if(!dateIsBetween('2020-02-18 07:30:00','2020-02-25 07:30:59',$DateTime))continue;
                $no++;
                $PIN=Parse_Data($data,"<PIN>","</PIN>");
                $Verified=Parse_Data($data,"<Verified>","</Verified>");
                $Status=Parse_Data($data,"<Status>","</Status>");
                if($Status=='0')$Status="Masuk";
                else $Status="Keluar";
                $waktu=date("H:i:s",strtotime($DateTime));
                if($waktu>="07:30:01"&&$waktu<="07:30:59"){
                $sql="insert into csv_apel(no_id,waktu,status) values('$PIN','$DateTime','$Status')";
                if($conn->query($sql)==TRUE){}
                else{$data_gagal++; echo $sql."\n"; continue;}
                }
}
echo $data_gagal;

?>

