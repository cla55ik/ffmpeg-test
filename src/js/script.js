$( document ).ready(function() {
    $("#btn_get").click(
    		function(){
    			sendAjaxForm('result_video', 'video_form');
    			return false;
    		}
    	);


});


function sendAjaxForm(result_video, video_form) {
    $.ajax({
        url:     "/src/php/generate.php", //url страницы (action_ajax_form.php)
        type:     "POST", //метод отправки
        dataType: "html", //формат данных
        data: $("#"+video_form).serialize(),  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
        	result = $.parseJSON(response);
          console.log(result);

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
    	error: function(response) { // Данные не отправлены
            $('#error').html('Ошибка. Данные не отправлены.');
    	}
 	});
}



function viewVideo(url){
  document.getElementById('form_wrapper').classList.add('hidden');
  document.getElementById('video_wrapper').classList.toggle('hidden');
  document.getElementById('error').classList.toggle('hidden');
  document.getElementById('video_file').src = url;
  document.getElementById('instruction').innerHTML = "Видео готово";
}
