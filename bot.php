<?php
$access_token = 'yrfCW1MnPm41pHlzJY7biXb8oDpvC1ifWxRTh0+DT78SAHufjXcZFKZVWIuSHtyeU0INkw3W9rAiLQQ14J905erJGVDV89htevLlRcGV2d+11+yg3iC5WgAgPGQveekZVMIdzhC/4+1sVEfixs6/3AdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		/*
		  $sticker = [
		  	'type' => 'sticker',
			'packageID' => '1',
			'stickerID' => '1'  
		  ];	
		*/
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$rtext = explode(":",$event['message']['text']);
			//$text = "ประวัติสุดหล่อ จริง ๆ";
			// Get replyToken
			$replyToken = $event['replyToken'];
			if($rtext[0]=='สวัสดี'){
			   $text = "สวัสดี ".$event['content']['from'];	
			}else if(($rtext[0]=="mw") && ($rtext[1]!="")){
			   //date_default_timezone_set("Asia/Bangkok");
			   //$t =time()-300;
			   //$dt = date("Y-m-d h:i:00",$t);
			   $url="http://maemoh.egat.com/ais/webservice/PlotGraph.php?starttime=2016-10-14%2012:00:00&endtime=2016-10-14%2012:00:00&mmunit=".trim($rtext[1])."&point=d40&_unit=2";
			   $ch1 = curl_init();
                           curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
                           curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
                           curl_setopt($ch1, CURLOPT_URL, $url);
                           $result1 = curl_exec($ch1);
                           curl_close($ch1);
            
                           $obj = json_decode($result1, true);
				
                           if(isset($obj[0]['d40'])){
                              $text = "กำลังผลิตของ UNIT ".$rtext[1]." ขณะนี้คือ ".$obj[0]['d40']." MW";
                           }else{//ถ้าไม่เจอกับตอบกลับว่าไม่พบข้อมูล
                              $text = 'ไม่พบข้อมูล';
				//$text=$url;   
                           }
			   
			   //$text = $url;
			   //$text = $obj[0]['d40'];	
			   
                        }else{
			   $text = "นี่คือคำตอบจาก Pw.bot";
			}
			
	
			
			
			
			
			
			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
				
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
	}
}
echo "OK";
