# ffmpeg-test
WebApp - генерирование видео на основании ссылок на изображения

## Задача
Создать веб-приложение которое будет генерировать видео на основании указанных ссылок на картинки.

>Применяемые технологии
>1. ubuntu
>2. apache
>3. Letsencrypt сертификат
>4. php и зависимости
>5. ffmpeg
>6. html
>7. css
>8. javascript

## Реализация

### 1. Развертывание сервера
На сервере VPS были установлены:
* Ubuntu 18.04
* Apache2
* PHP 7.2 и необходимые зависимости

Подключен домен [webdev.ru.com](https://webdev.ru.com/) 
<br>Установлен SSL сертификат средствами `certbot`
<br>Настроен FTP-доступ с помощью `proftpd`
<br>Установлен пакет для обработки медиафайлов - `ffmpeg`

### 2. Структура приложения
За работу приложения отвечают следующие файлы:
* `index.php` - основная страница приложения с формой для загрузки ссылок на изображения
* `script.js` - реализует отправку ajax запросов на сервер и логиику отображения элементов на странице
* `generate.php` - обрабатывает и валидирует данные, полученные из формы браузера; инициирует запуск `ffmpeg`
* `style.css` - таблица стилей приложения

### 3. Логика работы приложения

Пользователь может добавить от 1 до 5 ссылок на изображения
<br>в результате получает видео из данных изображений
<br>продолжительность показа каждого изображения - 2 скунды

#### Валидация данных

Валидация реализована двумя функциями:
1. `function validateLink($link)` - проверяет, что указанные в поле формы данные являются ссылкой

```
function validateLink($link){
    $file_headers = @get_headers($link);
    if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
        return false;
        }
        else {
            return true;
        }

}
```

2. `function isImage($link)` - проверяет, что по данной ссылке существует изображение

```
    if (@GetImageSize($link)) {
        return true;
    } else {
        return false;
    }
}
```

3. После успешной валидации ссылка добавляется в массив `$links = array();`

#### Скачивание изображений на сервер

1. Создание папки на сервере
   Для каждого нового запроса создается уникальная папка, куда будут закачаны изображения
   <br>Чтобы избежать дублирования имен папок - Имя папки - теущее время в формате UNIX
   <br>Путь к созданной папке и ее имя сохраняем в переменные `$new_path` и `$folder` для дальнейшего использования

2. Скачивание файлов
   * Перебираем все ссылки из массива `$links`
   * Считываем файл стандартной функцией PHP `file_get_contents`
   * Создаем новый файл через `fopen`
   * Записывем через `fwrite`

```
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
```

#### Подготовка к генерации видео
   При сохранении файлов на сервер вызывается функция `saveFileToList($file,$i)`
   <br> которая в той же папке создает файл `list.txt`
    
    
    file 'img1.jpg'
    duration 2
    file 'img2.jpg'
    duration 2
    file 'img3.jpg'
    duration 2
    file 'img4.jpg'
    duration 2
   
    
   Это список изображений для `ffmpeg` из которых будет сгенерировано видео
    
    
#### Создание видео
  Функция `createVideo($dir)` принимает путь к созданной папке с загруженными изображениями
  <br> и инициирует запуск `ffmpeg`
  
  ```
  function createVideo($dir){
    $direct = "ffmpeg -f concat -safe 0 -i '" . $dir . "list.txt' '" . $dir ."result.mp4'";
    passthru($direct, $output);
  }
  ```
  
  ### Ответ браузеру
  
  PHP обработчик возвращает ответом массив $result в JSON 
  <br>`echo json_encode($result);`
   
   <br>Массив содержит список ошибок или если их нет - ссылку на готовое видео
   
   ### Обработка данных в JS
   
   ```
   function sendAjaxForm(result_video, video_form) {
    $.ajax({
        url:     "/src/php/generate.php",
        type:     "POST",
        dataType: "html",
        data: $("#"+video_form).serialize(),
        success: function(response) {
        	result = $.parseJSON(response);
          if (typeof result.error == "undefined" && typeof result.video !="undefined") {
              viewVideo(result.video);
          }else{
            if (typeof result.error != "undefined") {
              $('#error').html(result.error);
            }else if (result.length == 0) {
              $('#error').html('Добавьте хотя бы одну ссылку');
            }

          }

    	},
    	error: function(response) { 
            $('#error').html('Ошибка. Данные не отправлены.');
    	}
 	});
}
   ```
   
   Функция реализована на jQuery
   * Отправляет на сервер данные формы
   * Принимает ответ от сервера
   * В зависимости от содержания полученного ответа:
   <br>
   <br>*Вызывает функцию показа видео на странице* `function viewVideo(url)`
   <br>*Показывает сообщение об ошибке*
   <br>*Говорит, что форма не заполнена*

   #### Отображение видео на странице
   
   После получения от сервера данных с адресом расположения сгенерированного видео-файла
   <br> js функция скрывает форму и отображает блок с видео
   <br> добавляя в параметр src тэга <video> ссылку на полученное видео 
   
   ```
   function viewVideo(url){
      document.getElementById('form_wrapper').classList.add('hidden');
      document.getElementById('video_wrapper').classList.toggle('hidden');
      document.getElementById('error').classList.toggle('hidden');
      document.getElementById('video_file').src = url;
      document.getElementById('instruction').innerHTML = "Видео готово";
    }

   ```
  

