$(document).ready(function () {
 
// $('#d_school').change(function () {
//   //console.log('d_school');
//   console.log($(this).val()+'::Y');
//   // (this).val();
// });

       $('#d_school').chosen();
        $('#d_school').change(function () {
        var school_selected= $(this).val();
        console.log(school_selected+'==school_selected');
        $('#grade_div').html('Grade Loading...');
        $.ajax({
                type: "POST",
                url: "ajax-sessions.php",
                data: {schoolid: school_selected, action: 'get_school_grades', 
                    },
                success: function (response) {
                  //  alert('Grade::'+response);

                    $('#grade_div').html(response);
                    $('#grade_id').chosen();
                    //var url = "intervention.js";
                    $.getScript("load_grades.js"); //exit();
                },
                async: true
            });



        });
        $('#d_school').change();
        //////Grade: to Select Studets /////////////
      
});