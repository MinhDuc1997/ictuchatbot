<?php
function tkbView($userid, $con){
    $values = '';
    $sql = "SELECT json FROM userdata WHERE userid='$userid'";
    $query = $con->query($sql);
    $row = $query->fetch_assoc();
    $jsonDB = $row['json'];
    $jsonDB = unserialize($jsonDB);
    $date = date('d/m/Y');
    $next_date = date('d/m/Y',strtotime('+1 days'));
    $i = 0; $j = 0;
    if(strlen($jsonDB>0)){
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
        
        $values = $replace = str_replace('-br', '\u000A', $values);
    }else{
        $values = "Gõ tkb để biết cách xem tkb";
    }
    return $values;
}
?>