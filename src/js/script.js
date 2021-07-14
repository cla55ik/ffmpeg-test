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

          if (typeof result.error == "undefined") {
            $('#result_video').html('1: '+result.link1+
                                    '<br>2: '+result.link2+
                                    '<br>3: '+result.link3+
                                    '<br>4: '+result.link4+
                                    '<br>5: '+result.link5+
                                    '<br>6: '+result.message+
                                    '<br>new_dir: '+result.new_dir+
                                    '<br>new_folder: '+result.new_folder+
                                    '<br>length: '+result.length+
                                    '<br>array links: '+result.arr);
          }else{


            $('#result_video').html(result.error);
          }

    	},
    	error: function(response) { // Данные не отправлены
            $('#result_video').html('Ошибка. Данные не отправлены.');
    	}
 	});
}
