$(document).ready(function () {
    // $('#teacher_id').chosen();//
    $('#teacher_id').change(function () {
        
        console.log('Teacher Selected:'+$(this).val());
        var schoolID3='';
        schoolID3=$('#d_school').val();
        var teacherid = $(this).val();
        var grade_id = $('#grade_id').val();
        //console.log('Selected School ID: '+schoolID3);

        $('#student_div').html('Students Loading...');
        $.ajax({
            type: "POST",
            url: "ajax-sessions-k.php",
            data: {teacher_id: teacherid, action: 'get_multiple_students', 
            grade_id:grade_id, school_id: schoolID3},
            success: function (response) {
                //  alert(response);
                $('#student_div').html(response);
                $('#students_id').chosen();
            },
            async: true
        });
    });
    $('#teacher_id').change();
});