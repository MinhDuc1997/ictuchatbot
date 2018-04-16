<?php
require_once 'configDB.php';
require_once 'view/textView.php';
require_once 'view/imageView.php';
require_once 'view/tkbView.php';
require_once 'model/send_requestModel.php';
require_once 'controller/matchController.php';

$input = json_decode(file_get_contents('php://input'), true);

if(isset($input['entry'][0]['messaging'][0]['sender']['id'])){
    $match = new matchController();

    $sender = $input['entry'][0]['messaging'][0]['sender']['id']; //ng gui den
    $message = $input['entry'][0]['messaging'][0]['message']['text'];

    $url = 'https://graph.facebook.com/v2.11/me/messages?access_token=EAAB8w9jAh1MBAAl3lKTbJDJSDZCTmgfWAFq1JS5Co4bkj2kYJQdFEloayk5cDmcPd2zAmccj0JZAWUc3nIcR3pvqNPulTYkS3ec28xAvjxIU5jZBGAkbpHdnZCLnpD4Dj7U99EAv6l5ZAQvd1sfjmuEse7anZAiHgz96R6Cc7uQwZDZD';

    $row = $match->start($sender);
    $jsonData = '';
    $check = 0;
    $message_chuan_hoa = mb_strtolower($message);

    if($match->status($row) === '0'){
        $match->QA($sender);
        //phan hoi QA
        $get_json = file_get_contents('question/question.json');
        $obj = json_decode($get_json);
        foreach ($obj->intents as $value) {
            foreach ($value->patterns as $value_) {
                if($value_ == $message_chuan_hoa){
                    $check = 1;
                    if($value->type == 'text'){
                        $replace = str_replace('-br', '\u000A', $value->response);
                        $jsonData = return_text($sender, $replace);
                        send_request($url,$jsonData,$message);
                    }
                    if($value->type == 'image'){
                        foreach ($value->response as $image) {
                            $jsonData = return_image($sender, $image);
                            send_request($url,$jsonData,$message);
                        }
                    }
                }
            }
        }
        if($check == 0){
            switch ($message_chuan_hoa) {
                case 'chat':
                    $jsonData = return_text($sender, "Bắt đầu trò chuyện. Gõ 'End' để kết thúc sau đó tìm lại");
                    send_request($url,$jsonData,$message);
                    $match->reallyChat($sender);
                    $matched = $match->matching_($sender); //find id user have status 2
                    if(!empty($matched)){
                        $match->matched($sender,$matched);
                        $match->matched($matched,$sender);
                        $jsonData = return_text($sender,"Tìm thấy ");
                        send_request($url,$jsonData,$message);
                        $jsonData_ = return_text($matched,"Tìm thấy ");
                        send_request($url,$jsonData_,$message);
                    }
                    break;
                case 'end':
                    $jsonData = return_text($sender, "Bạn vẫn chưa Chat, gõ 'Chat' để chat với 1 người");
                    send_request($url,$jsonData,$message);
                    break;
                case 'id':
                    $jsonData = return_text($sender, $sender);
                    send_request($url,$jsonData,$message);
                    break;
                case 'tkb':
                    $jsonData = return_text($sender, "Gõ <Msv>:<Pass>\u000AVD: DTC155D4802222222:123456789 1 lần duy nhất\u000ASau đó chỉ gõ mytkb để xem tkb\u000AĐể cập nhật, gõ lại <Msv>:<Pass>\u000A(Có thể tốn nhiều thời gian)");
                    send_request($url,$jsonData,$message);
                    break;
                case 'mytkb':                    
                    $jsonData = return_text($sender, tkbView($sender, $con));
                    send_request($url,$jsonData,$message);
                    break;
                default:
                    if(strpos($message_chuan_hoa,':') == true){
                        $username = strstr($message_chuan_hoa, ':', true);
                        $pass_temp = strstr($message_chuan_hoa, ':', false);
                        $password = substr($pass_temp, 1, strlen($pass_temp)-1);
                        $uri = "https://techitvn.com/ictuchatbot/model/tkbModel.php?username=$username&password=$password&userid=$sender";
                        $file = file_get_contents($uri);
                        $json = json_decode($file);
                        $replace_char = str_replace('-br', '\u000A', $json->today);
                        $jsonData = return_text($sender, $replace_char);
                    }else{
                        $jsonData = return_text($sender, 'Cảm ơn đã tới với ICTU Chatbot \u000A - Gõ (help) để tôi có thể giúp cậu');
                    }
                        send_request($url,$jsonData,$message);
                    break;
            }
        }
    }
    if($match->status($row) === '1'){
        if($check == 0){
            switch ($message_chuan_hoa) {
                case 'end':
                    $match->QA($sender);
                    $match->QA($row['matched']);
                    $jsonData = return_text($sender, "Đã bỏ chat");
                    send_request($url,$jsonData,$message);
                    $jsonData = return_text($row['matched'], "Bên kia đã bỏ chat :)");
                    send_request($url,$jsonData,$message);
                    break;       
                default:
                    $jsonData = return_text($row['matched'],$message);
                    send_request($url,$jsonData,$message);
                    break;
            }
        }
    }
    if($match->status($row) === '2'){
        if($check == 0){
            switch ($message_chuan_hoa) {
                case 'end':
                    $match->QA($sender);
                    $jsonData = return_text($sender, "Bạn chưa chat với ai");
                    send_request($url,$jsonData,$message);
                    break;       
                default:
                    //
                    break;
            }
        }
    }
    
}
?>