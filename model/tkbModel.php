<?php
header('Content-Type: application/json; charset=UTF-8');
require '../configDB.php';
$name = $_GET['username'];
$pass = $_GET['password'];
$userid = $_GET['userid'];
$values = '';
$jsonDB = '';
if(isset($_GET['username']) && isset($_GET['password']) && isset($_GET['userid'])){
    $token_uri = "http://tnusocial.otvina.com/api/school/api.php?api=login-app&from=DTC&app-id=475681656679&app-secret=LsO189xl1p5b5673t87pQ05w6d3k9KeP&username=$name&password=$pass";
    $token_uri = utf8_decode(urldecode($token_uri));

    $file = file_get_contents($token_uri);
    $json = json_decode($file, true);
    $token = $json['access-token'];
    
    if(strlen($token) == 0){
        $values = "Kiểm tra lại mã sinh viên và mật khẩu";
    }
    
    $schedule_uri = "http://tnusocial.otvina.com/api/school/api.php?api=get&path=student-time-table&from=DTC&access-token=$token&semester=73FB2DDC455D410C978AB31459812122";
    $file = file_get_contents($schedule_uri);
    $json = json_decode($file);
    $date = date('d/m/Y');
    $next_date = date('d/m/Y',strtotime('+1 days'));
    //save json to DB
    $serialize = serialize($json->data->current->table);
    $sql = "UPDATE userdata SET json='$serialize' WHERE userid='$userid'";
    $query = $con->query($sql);
    if($query){
        $sql = "SELECT json FROM userdata WHERE userid='$userid'";
        $query = $con->query($sql);
        $row = $query->fetch_assoc();
        $jsonDB = $row['json'];
        $jsonDB = unserialize($jsonDB);
    }
    
    //echo $date;
    $i = 0; $j = 0;
    foreach( $jsonDB as $value){
        if($value->subjectDate == $date){
            if($i==0){
                $values .= 'HÔM NAY: -br';
                $i++;
            }
            $values .= '- ['.$value->subjectTime.'] ['.strstr($value->subjectPlace, ' ', true).'] '.strstr($value->subjectId, '-', true).'-br';
        }
        if($value->subjectDate == $next_date){
            if($j==0){
                $values .= 'NGÀY MAI: -br';
                $j++;
            }
            $values .= '- ['.$value->subjectTime.'] ['.strstr($value->subjectPlace, ' ', true).'] '.strstr($value->subjectId, '-', true).'-br';
        }
    }
    if(strlen($values) == 0){
        $values = 'Nghỉ hôm nay và ngày mai';
    }
    $values .= "(Lần tới để xem chỉ cần gõ: mytkb)";
    
}else{
    $values = "Kiểm tra lại mã sinh viên và mật khẩu";
}
$arr = array('today'=>$values);
echo json_encode($arr);

?>