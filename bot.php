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
		if($event['type'] == 'sticker'){
		  $sticker = [
		  	'type' => 'sticker',
			'packageID' => '1',
			'stickerID' => '1'  
		  ];	
		}
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			//$text = "ประวัติสุดหล่อ จริง ๆ";
			// Get replyToken
			$replyToken = $event['replyToken'];
			if($text=='สวัสดี'){
			   $text = "สวัสดี ".$event['message']['text'];	
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
				'sticker' => [$sticker],
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
