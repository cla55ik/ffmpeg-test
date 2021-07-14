<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Создаем видео</title>
    <link rel="stylesheet" type="text/css" href="/src/style/style.css">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="/src/js/script.js"></script>
  </head>
  <body>
    <main>
      <div class="head-title">
        <h1>Создаем видео из набора файлов</h1>
      </div>
      <div class="head-content">
        <p id="instruction">
          Вставьте ссылки на изображения в поля формы <br /> и нажмите кнопку 'Сгенерировать'
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


        </div>

      </div>

    </main>
  </body>
</html>
