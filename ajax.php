<?php


require_once "helper/GifCreator.php";
$descp = $_GET["descp"];
$sp = intval($_GET['sp']);
$sp = $sp?$sp:1;
$length = strlen($descp);
if($length){
	if($length>33){
		$msg = array(
			"status" => 0,
			"errmsg" => "请保证你的字数在11个中文字或者30个字符以内"
			);
	}else{
		$gif_url = "";
		
		$filename = time().rand(10000,99999);
		$font = "./helper/simhei.ttf";
		//每个人说话的生成图
		$personal_url = "./photo/".$filename.".jpg";
		//完成的gif图路径
		$gif_url = "./myfolder/".$filename.".gif";

		$input = array("1","2","3","4","5","6","7","8","9","11","12","13","14","15","16","17","18");

		$randinput = array_rand($input,6);

		$frames = array();
		foreach ($randinput as $key => $value) {
			if($key==3){
				$fontSize = 18;
				$width = 440;
				$img=imagecreatefromjpeg("./gifResource/0000.jpg");
				$text_color=imagecolorallocate($img, 225, 204, 10);
				$fontBox = imagettfbbox($fontSize, 0, $font, $descp);//文字水平居中实质
	        	imagettftext($img,$fontSize,0,ceil(($width - $fontBox[2]) / 2),360,$text_color,$font,$descp);
			  	imagejpeg($img,$personal_url);
			  	imagedestroy($img);
			  	$frames[]=$personal_url;
			}
			$pkey = $input[$value];
			$frames[]="./gifResource/".$pkey.".jpg";
		}
		
		for($i=0;$i<=6;$i++){
		    $durations[]=$sp;
		}
		// Create an array containing the duration (in millisecond) of each frames (in order too)
		//$durations = array(40, 80, 40, 20);

		// Initialize and create the GIF !
		$gc = new GifCreator();
		$gc->create($frames, $durations, 0);
		$gifBinary = $gc->getGif();

		file_put_contents($gif_url, $gifBinary);

		if(file_exists($gif_url)){
			$msg = array(
				"status" => 1,
				"errmsg" => "gif生成",
				"pic" => $gif_url
				);
		}else{
			$msg = array(
				"status" => 2,
				"errmsg" => "生成失败"
				);
		}
		
	}
}else{
	$msg = array(
		"status" => 0,
		"errmsg" => "请输入你要说的话"
		);
}
if(file_exists($gif_url)){
	echo '<meta http-equiv="Content-Disposition" content="attachment;">';
	// $filename = "./myfolder/".intval($filename).".gif";
	echo "<a href='./myfolder/down.php?filename=".$filename.".gif' target='_blank'>图片下载</a> ";
	echo "<br><br><br>";
	echo '<img src="'.$gif_url.'">';
	// var_dump($msg);
		// echo json_encode($msg);
}else{
	echo $msg['errmsg'];
}
?>