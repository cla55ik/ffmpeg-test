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

          if (typeof result.error == "undefined" && result.video !="undefined") {
            $('#result_video').html(
              '<video src="'+ result.video +'" controls>');
              viewVideo();
          }else{


            $('#result_video').html(result.error);
          }

    	},
    	error: function(response) { // Данные не отправлены
            $('#result_video').html('Ошибка. Данные не отправлены.');
    	}
 	});
}



function viewVideo(){
  document.getElementById('form_wrapper').classList.add('hidden');
  document.getElementById('instruction').innerHTML = "Видео готово";
}
