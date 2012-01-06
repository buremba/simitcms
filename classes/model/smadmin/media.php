<?php

class Model_admin_media extends Model {
	public function upload($targetDir, $fileHandler) {

		// $fileHandler is usually $_FILES['name']

		// For uploading little parts
		// $chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
		// $chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
		$chunk = 0;
		$chunks = 0;
		$fileName = $fileHandler["name"];
		$return = array("jsonrpc", "2.0");
		// Clean the fileName for security reasons
		$fileName = preg_replace('/[^\w\._]+/', '', $fileName);

		// Make sure the fileName is unique but only if chunking is disabled
		if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
			$ext = strrpos($fileName, '.');
			$fileName_a = substr($fileName, 0, $ext);
			$fileName_b = substr($fileName, $ext);

			$count = 1;
			while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
				$count++;

			$fileName = $fileName_a . '_' . $count . $fileName_b;
		}

		// Create target dir
		$uploadyear= '/' . date("Y" . '/');
		$uploadDir = '/' . date("n" . '/');
		if (!is_dir($targetDir) || !is_dir($targetDir.$uploadDir)) {
			if (!is_dir($targetDir . $uploadyear) && !mkdir($targetDir . $uploadyear))
					return array('jsonrpc' => '2.0', 'id' => 'id', 'error' => array('code' => '101', 'message' => "Server error. Couldn't created year directory in upload directory:  '/".date('Y')."/'"));
			if (!is_dir($targetDir.$uploadyear.$uploadDir) && !mkdir($targetDir.$uploadyear.$uploadDir))
					return array('jsonrpc' => '2.0', 'id' => 'id', 'error' => array('code' => '101', 'message' => "Server error. Couldn't created thumbnail directory in upload directory:  '/".date("Y/n")."/'"));
			
			if (!is_dir($targetDir.$uploadyear.$uploadDir . '/thumbs/') && !mkdir($targetDir .$uploadyear.$uploadDir.'/thumbs/'))
					return array('jsonrpc' => '2.0', 'id' => 'id', 'error' => array('code' => '101', 'message' => "Server error. Couldn\'t created thumbnail directory in upload directory:  '/".date("Y/n")."/thumbs/'"));
		}
		$targetDir = $targetDir.$uploadyear.$uploadDir;
		if (!is_writable($targetDir)) {
			return array('jsonrpc' => '2.0', 'id' => 'id', 'error' => array('code' => '101', 'message' => "Server error. Upload directory is not writable."));
		}
		if (!is_writable($targetDir . '/thumbs')) {
			return array('jsonrpc' => '2.0', 'id' => 'id', 'error' => array('code' => '101', 'message' => "Server error. Thumbnail directory is not writable."));
		}

		// Look for the content type header
		if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
			$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

		if (isset($_SERVER["CONTENT_TYPE"]))
			$contentType = $_SERVER["CONTENT_TYPE"];

		// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
		if (strpos($contentType, "multipart") !== false) {
			if (isset($fileHandler['tmp_name']) && is_uploaded_file($fileHandler['tmp_name'])) {
				// Open temp file
				$out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
				if ($out) {
					// Read binary input stream and append it to temp file
					$in = fopen($fileHandler['tmp_name'], "rb");

					if ($in) {
						while ($buff = fread($in, 4096))
							fwrite($out, $buff);
					} else
						return array('jsonrpc' => '2.0', 'id' => 'id', 'error' => array('code' => '101', 'message' => "Failed to open input stream."));
					fclose($in);
					fclose($out);
					@unlink($fileHandler['tmp_name']);
				} else
					return array('jsonrpc' => '2.0', 'id' => 'id', 'error' => array('code' => '102', 'message' => "Failed to open input stream."));
			} else
				return array('jsonrpc' => '2.0', 'id' => 'id', 'error' => array('code' => '103', 'message' => "Failed to move uploaded file."));
		} else {
			// Open temp file
			$out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = fopen("php://input", "rb");

				if ($in) {
					while ($buff = fread($in, 4096))
						fwrite($out, $buff);
				} else
					return array('jsonrpc' => '2.0', 'id' => 'id', 'error' => array('code' => '101', 'message' => "Failed to open input stream."));
				fclose($in);
				fclose($out);
			} else
				return array('jsonrpc' => '2.0', 'id' => 'id', 'error' => array('code' => '102', 'message' => "Failed to open input stream."));
		}
		$thumb = $this->create_thumbnail($targetDir . DIRECTORY_SEPARATOR . $fileName, $targetDir . '/thumbs/' .DIRECTORY_SEPARATOR . $fileName, 250, 200);
		
		// Return JSON-RPC response
		if($thumb) {
			return array('jsonrpc' => '2.0', 'id' => 'id', 'url' => $uploadyear.$uploadDir.$fileName);
		}else {
			return array('jsonrpc' => '2.0', 'id' => 'id', 'error' => array('code' => '103', 'message' => "Failed to create thumbnail file."));
		}
	}

	public function create_thumbnail($filename, $dest, $max_width, $max_height) {
		$format = strtolower(substr(strrchr($filename,"."),1));
		switch($format)
		{
			case 'gif' :
				$type ="gif";
				$img = imagecreatefromgif($filename);
				break;
			case 'png' :
				$type ="png";
				$img = imagecreatefrompng($filename);
				break;
			case 'jpg' :
				$type ="jpg";
				$img = imagecreatefromjpeg($filename);
				break;
			case 'jpeg' :
				$type ="jpg";
				$img = imagecreatefromjpeg($filename);
				break;
			default : 
				die (array('error' => "Unsupported image type: {$type}"));
				break;
		}
		
		list($org_width, $org_height) = getimagesize($filename);
		/*
		if($org_width==$org_height) { $case = 'first'; }
		elseif($org_width > $org_height) { $case = 'second'; }
		else { $case = 'third'; }

		switch($case) {
			case 'first':
				$thumb_width = $max_width;
				$thumb_height = $max_height;
			break;
			case 'second':
				$ratio = $org_width/$org_height;
				$amount = $org_width - $max_width;
				$thumb_width = $org_width - $amount;
				$thumb_height = $org_height - ($amount/$ratio);
			break;
			case 'third':
				$ratio = $org_height/$org_width;
				$amount = $org_height - $max_height;
				$thumb_height = $org_height - $amount;
				$thumb_width = $org_width - ($amount/$ratio);
			break;
		}
		*/
		if($org_width > $org_height) {
			$thumb_height = $max_height;
			$thumb_width = ($org_width*$thumb_height)/$org_height;
			$offset_x = ($thumb_width-$max_width)/2;
		}else {
			$thumb_width = $max_width;
			$thumb_height = ($org_height*$thumb_width)/$org_width;
			$offset_y = ($thumb_height-$max_height)/2;
		}


		$img_n=imagecreatetruecolor ($max_width, $max_height);
		
		imagecopyresampled($img_n, $img, 0, 0, ((isset($offset_x)) ? $offset_x: 0), ((isset($offset_y)) ? $offset_y: 0), $thumb_width, $thumb_height, $org_width, $org_height);

		if($type=="gif")	{imagegif($img_n, $dest); return true;}
		elseif($type=="jpg"){imagejpeg($img_n, $dest); return true;}
		elseif($type=="png"){imagepng($img_n, $dest); return true;}
		elseif($type=="bmp"){imagewbmp($img_n, $dest); return true;}else {return false;}
	}
	
	public function thumb_of($url) {
		return preg_replace('/\/([^\/]*)$/i', '/thumbs/$1', $url);
	}
	
	public function delete($id) {
		$address = db::getvalue("SELECT address FROM media WHERE id = $id");
		if(!is_file(Kohana::$config->load('upload.directory').DIRECTORY_SEPARATOR.$address)) {
			throw new HTTP_Exception_503("Wrong request. The media file couldn't find : ".Kohana::$config->load('upload.directory').DIRECTORY_SEPARATOR.$address);
		}
		
		if(file_exists(Kohana::$config->load('upload.directory').DIRECTORY_SEPARATOR.$this->thumb_of($address))){
			$unlink = unlink(Kohana::$config->load('upload.directory').DIRECTORY_SEPARATOR.$this->thumb_of($address));
		}
			$unlink2 = unlink(Kohana::$config->load('upload.directory').DIRECTORY_SEPARATOR.$address);
		
		$action = db::delete("media", array("id" => $id));
		if($action && $unlink2) {
			return true;
		}else {
			return false;
		}
	}
}
