$(document).ready(function () {
      // $('#grade_id').chosen();//
        $('#grade_id').change(function () {
        
          console.log('GradeSelected:'+$(this).val());
           var schoolid_2='';
           var gradeid = $(this).val();
           //var schoolid= $("#d_school:selected").value;
              schoolid_2=$('#d_school').val();
             console.log('SelectedSchoolID::'+schoolid_2);

            $('#student_div').html('Students Loading...');
            $.ajax({
                type: "POST",
                url: "ajax-ses.php",
                data: {grade_id: gradeid, action: 'get_multiple_students', 
                    schoolid:schoolid_2},
                success: function (response) {
                  //  alert(response);
                    $('#student_div').html(response);
                    $('#students_id').chosen();
                },
                async: true
            });
        });
        $('#grade_id').change();
});