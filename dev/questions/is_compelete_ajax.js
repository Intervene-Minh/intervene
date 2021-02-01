  $('#IScomp').click(function(){
    $(this).attr('disabled','disabled');
    $.ajax({
              type: 'POST',
              url: 'is_compelete_ajax.php',
              timeout: 3000,
              data:{course_id:$(this).attr('data_id'),iscom:'yes',course_cat_id: +'<?php echo $cat_id; ?>' },
              success: function (data) {
                if(data > 0 )
                  {
                   window.location='https://intervene.io/questions/telpas_practice.php';}
                  console.log(data);
              }
          });
  });