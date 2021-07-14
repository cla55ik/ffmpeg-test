<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Создаем видео</title>
    <link type="image/x-icon" rel="shortcut icon" href="/src/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/src/style/style.css">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="/src/js/script.js"></script>
  </head>
  <body>
    <main>
      <div class="head-title">
        <h1>Создать видео из нескольких изображений</h1>
      </div>
      <div class="head-content">
        <p id="instruction">
          Вставьте ссылки на изображения в поля формы <br /> и нажмите кнопку 'Сгенерировать'
          <br /> Можно добавить от 1 до 5 ссылок
        </p>
      </div>
      <div class="content">
        <div class="form-wrapper" id="form_wrapper">
          <form class="" action="" id="video_form" method="post">
            <?php for ($i=1; $i < 6; $i++) {
              echo('<input type="text" name="link'.$i.'" placeholder="ссылка '.$i.'"><br>');
            } ?>

            <button type="button" id="btn_get" name="btn-get">Сгенерировать</button>
          </form>
        </div>
        <div id="result_video" class="result_video">
          <div class="error" id="error">

          </div>
          <div id="video_wrapper" class="video-wrapper hidden">
            <video src="" controls  id="video_file">
          </div>


        </div>

      </div>

    </main>
    <footer>
      <div class="footer-wrapper">
        <div class="name">
          <span>Created by</span> Ivan Chelnokov

        </div>
        <div class="contacts">
          <div class="">
            <a href="tel:+79102814760"> + 7 910 281 47 60</a>
          </div>
          <div class="">
              <a href="mailto:cla55ik@yandex.ru">cla55ik@yandex.ru</a>
          </div>
        </div>
      </div>
    </footer>
  </body>
</html>
