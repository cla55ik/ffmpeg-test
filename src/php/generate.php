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
                //$links += $val;
                $result['arr'] = $links;

            }else{
                $result['error'] = 'неверная ссылка №' . $i;

            }
            $i++;
        }
    }

    $lengthLinks = count($links);
    $result['length'] = $lengthLinks;

    //$result['message'] = isCorrectArray($result);

    if (isCorrectArray($result, $lengthLinks)) {
        [$new_dir, $new_folder] = makeDir();
        //$new_dir = makeDir();
        $result['new_dir'] = $new_dir;
        $result['new_folder'] = $new_folder;
    }

    if($new_dir){
        $images = saveImage($links,$new_dir, $new_folder);
        $result['arr'] = $images;
        $result['ff'] = createVideo($new_dir);
    }


	/*// Формируем массив для JSON ответа
    $result = array(
    	'link1' => $_POST["link1"],
    	'link2' => $_POST["link2"],
        'link3' => $_POST["link3"],
        'link4' => $_POST["link4"],
        'link5' => $_POST["link5"],

    );*/

    // Переводим массив в JSON
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
    //$mode = '0777';
    $recursive = true;



    if (mkdir($path . $folder, 0777, $recursive) == false) {
        return false;
    }else{
        $new_path = $path . $folder;
        return [$new_path, $folder];
    }
}


function saveImage($links, $dir, $folder){
    $i=1;
    $images = [];
    $file = $_SERVER['DOCUMENT_ROOT'] . '/upload/' . $folder . 'list.txt';
    foreach ($links as $link) {
        $image = @file_get_contents($link);
        if(!$image){
            return $message = 'не удалось сохранить файл';
        }else{
            $filename = 'img' . $i . '.jpg';
            $createfile = fopen($dir . $filename, "w+");
            $writefile = fwrite($createfile, $image);
            array_push($images, $dir . $filename);
            saveFileToList($file, $i);
            $message = 'File is write';
        }
        $i++;
    }
    return $images;
}


function saveFileToList($file,$i){


    $strFile = "file 'img" . $i . ".jpg'" . PHP_EOL;
    $duration = 'duration 2' . PHP_EOL;
    $text = $strFile . $duration;


    file_put_contents($file, $text, FILE_APPEND);

}


function createVideo($dir){
    //$ffmpeg = 'ffmpeg';
    $direct = "ffmpeg -f concat -safe 0 -i '" . $dir . "list.txt' '" . $dir ."slideshowtest2.mp4'";

    //$test = "ffmpeg -f concat -safe 0 -i '/var/www/webdev.ru.com/upload/1626260228/list.txt' '/var/www/webdev.ru.com/upload/1626259778/sli2.mp4/'";
    passthru($direct, $output);
    return $direct;
}
