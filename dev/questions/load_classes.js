$(document).ready(function () {
    // $('#class_id').chosen();//
    $('#class_id').change(function () {
        var school_id_3 = $('#d_school').val();
        var grade_id = $('#grade_id').val();
        var class_id = $(this).val();
        console.log('Classes:');
        console.log(' - Class ID: ' + $(this).val());
        console.log(' - School ID: ' + school_id_3);
        console.log(' - Grade ID: ' + grade_id);

        $('#student_div').html('Students Loading...');
        $.ajax({
            type: "POST",
            url: "ajax-sessions-k.php",
            data: {class_id: class_id, action: 'get_multiple_students', 
            grade_id:grade_id, school_id: school_id_3},
            success: function (response) {
                //  alert(response);
                $('#student_div').html(response);
                $('#students_id').chosen();
            },
            async: true
        });
    });
    $('#class_id').change();
});