<?php
$get_json = file_get_contents('https://techitvn.com/ictuchatbot/question/question.json');
    $obj = json_decode($get_json);
    foreach ($obj->intents as $value) {
        foreach ($value->patterns as $value_) {
            if($value_ == 'hi'){
                if($value->type == 'text'){
                	echo '<pre>'.$value->response.'</pre>';
                }
                if($value->type == 'image'){
                    echo $value->response;
                }
            }
        }
    }
echo $jsonData;
?>