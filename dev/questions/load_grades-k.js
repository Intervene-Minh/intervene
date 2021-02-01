$(document).ready(function () {
      // $('#grade_id').chosen();//
        $('#grade_id').change(function () {        
            var grade_id = $(this).val();
            var school_id_2=$('#d_school').val();

            console.log('Grade ID Change:')
            console.log('- Grade ID: '+ $(this).val());
            console.log('- School ID: '+school_id_2);

            $('#classes_div').html('classes Loading...');
            $.ajax({
                type: "POST",
                //url: "ajax-ses.php", loads the students
                url: "ajax-sessions-k.php",
                data: {
                    action: 'get_classes', 
                    grade_id: grade_id, 
                    school_id: school_id_2
                },
                success: function (response) {
                    $('#classes_div').html(response);
                    $('#class_id').chosen();
                    $.getScript("load_classes.js");
                },
                async: true
            });
        });
        $('#grade_id').change();
});