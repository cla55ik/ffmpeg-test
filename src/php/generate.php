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

    $length = count($links);
    $result['length'] = $length;

    //$result['message'] = isCorrectArray($result);

    if (isCorrectArray($result, $length)) {
        $new_dir = makeDir();
        $result['new_dir'] = $new_dir;
    }

    if($new_dir){
        $images = saveImage($links,$new_dir);
        $result['arr'] = $images;
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
        //$message = 'массив NOT готов';
        //return $message;
        return true;
    }else{
        //$message = 'массив готов';
        //return $message;
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

        return $path . $folder;
    }
}


function saveImage($links, $dir){
    $i=1;
    $images = [];
    foreach ($links as $link) {
        $image = @file_get_contents($link);
        if(!$image){
            return $message = 'не удалось сохранить файл';
        }else{
            $filename = 'img' . $i . '.jpg';
            $createfile = fopen($dir . $filename, "w+");
            $writefile = fwrite($createfile, $image);
            array_push($images, $dir . $filename);
            $message = 'File is write';
        }
        $i++;
    }
    return $images;
}
