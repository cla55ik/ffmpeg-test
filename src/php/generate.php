<?php

if (isset($_POST)) {
    $result = [];
    $links = array();
    $i = 1;

    foreach ($_POST as $key => $value) {
        if ($value != ''){
            $val=strip_tags(htmlspecialchars(stripslashes($value)));
            if(validateLink($val) && isImage($val)){
                array_push($links, $val);
            }else{
                $result['error'] = 'некорректная ссылка №' . $i;
            }

        }
        $i++;
    }

    $lengthLinks = count($links);


    if (isCorrectArray($result, $lengthLinks)) {
        [$new_dir, $new_folder] = makeDir();
    }

    if($new_dir){
        $images = saveImage($links,$new_dir, $new_folder, $result);
        createVideo($new_dir);
        $result['video'] = '/upload/' . $new_folder . 'result.mp4';
    }

    echo json_encode($result);
}




function validateLink($link){
    $file_headers = @get_headers($link);
    if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
        return false;
        }
        else {
            return true;
        }

}


function isImage($link){
    if (@GetImageSize($link)) {
        return true;
    } else {
        return false;
    }
}

function isCorrectArray($array, $length){
    if (!isset($array['error']) && $length > 0) {
        return true;
    }else{
        return false;
    }
}


function makeDir(){
    $path = $_SERVER['DOCUMENT_ROOT'] .'/upload/';
    $folder = time() . '/';
    $recursive = true;

    if (mkdir($path . $folder, 0777, $recursive) == false) {
        return false;
    }else{
        $new_path = $path . $folder;
        return [$new_path, $folder];
    }
}


function saveImage($links, $dir, $folder, $array){
    $i=1;
    $images = [];
    $file = $_SERVER['DOCUMENT_ROOT'] . '/upload/' . $folder . 'list.txt';
    foreach ($links as $link) {
        $image = @file_get_contents($link);
        if(!$image){
            return $array['error'] = 'не удалось сохранить файл';
        }else{
            $filename = 'img' . $i . '.jpg';
            $createfile = fopen($dir . $filename, "w+");
            $writefile = fwrite($createfile, $image);
            saveFileToList($file, $i);
        }
        $i++;
    }
    return true;
}


function saveFileToList($file,$i){
    $strFile = "file 'img" . $i . ".jpg'" . PHP_EOL;
    $duration = 'duration 2' . PHP_EOL;
    $text = $strFile . $duration;

    file_put_contents($file, $text, FILE_APPEND);
}


function createVideo($dir){
    $direct = "ffmpeg -f concat -safe 0 -i '" . $dir . "list.txt' '" . $dir ."result.mp4'";
    passthru($direct, $output);
}
