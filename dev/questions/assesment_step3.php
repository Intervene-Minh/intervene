<?php
include("header.php");
//if($_POST && count($_POST['qn']) > 0 && $_POST['create_assessment']) { 
 if(isset($_POST['create_assessment'])) {  
     
     if(count($_POST['qn'])>0)
    $_SESSION['qn_list'] = $_POST['qn'];
    header('Location: assesment.php');
    exit;
   //assesment_id=1 die;
}
?>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
<?php include("sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">

<?php

if($_GET['assesment_id'] > 0) {
    unset($_SESSION['list']);
    $edit_assessment = mysql_query('SELECT qn_id  FROM assessments_x_questions WHERE assesment_id = \''.$_GET['assesment_id'].'\' ORDER BY num ASC ');
    while($asses = mysql_fetch_assoc($edit_assessment)) {
        $_SESSION['list'][] = $asses['qn_id'];
    }
    $_SESSION['assess_id'] = $_GET['assesment_id'] ;
}

// Setup title and where clause of query
$termId = (isset($_GET['taxonomy']) && is_numeric($_GET['taxonomy'])) ? $_GET['taxonomy'] : 0;
$select = mysql_fetch_assoc(mysql_query("SELECT * FROM `terms` WHERE `id` = {$termId} AND `active` = 1"));  # Return @boolean false if not found
if ($select) {
    $title = $select['name'];
    $clause = ( $select['taxonomy'] == 'objective' ) ? " INNER JOIN `term_relationships` r ON q.`id` = r.`question_id` WHERE r.`objective_id` = {$termId}" : " WHERE {$select['taxonomy']} = {$termId}";
} else {
    $title = 'List Questions';
    $clause = '';
}

if (isset($_SESSION['list'])) {
    #if !isset $_SESSION['list'] no query it.
    if ($clause == "") {
        $in = " WHERE q.`id` IN ( '" . implode($_SESSION['list'], "', '") . "' )";
    } else {
        $in = " AND q.`id` IN ( '" . implode($_SESSION['list'], "', '") . "' )";
    }
    $orderby = " ORDER BY FIELD(q.`id`,'" . implode($_SESSION['list'], "', '") . "')";
} else {
    if ($clause == "") {
        $in = " WHERE q.`id` IN ( '' )";
    } else {
        $in = " AND q.`id` IN ( '' )";
    }
    $orderby = "";
}

// Pagination
$per_page = 2000;
$paged = ( isset($_GET['paged']) && is_numeric($_GET['paged']) && $_GET['paged'] > 0 ) ? $_GET['paged'] : 1;
$query = mysql_query("SELECT `id` FROM `questions` q" . $clause . $in);  # Count total of records
$count = (int) mysql_num_rows($query);  # Total of records
$total = (int) ceil($count / $per_page); # Total of pages
$start = (int) ($paged - 1) * $per_page; # Start of records
$limit = " LIMIT $start , $per_page";  # Limit number of records will be appeared



$childs = mysql_query("SELECT * FROM `questions` q" . $clause . $in . $orderby . $limit);
?>


                <div id="list-document" class="content_wrap">
                    <form method="post">
                        <div class="ct_heading clear">
                            <h3>Selected Questions</h3>
                            <input type="submit" class="form_button submit_button" name="create_assessment" value="Next" style="float: right; margin-left: 10px;" /> 
                            <a href="/questions/assesment_step1.php" style="width:200px;padding: 5px;margin-left:130px;margin-top:-20px;" class="form_button submit_button" >Add More Questions</a>
                            <button id="removeall-in-list" name="removeall_in_list">Remove All</button>
                        </div>		<!-- /.ct_heading -->

                        <div class="ct_display clear">
<?php
if (mysql_num_rows($childs) > 0) {
    echo '<ul id="ul-list" class="ul-list">';
    $i = 1;
    while ($item = mysql_fetch_assoc($childs)) {
        if ($item['type'] == 1) {
            $echo = '<ul class="list-answers">';
            //lv-edit 04/05/2016
            $lv_answers = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $item['answers']);
            $answers = unserialize($lv_answers);
            // $answers = unserialize($item['answers']);
            //end
            foreach ($answers as $key => $answer) {
                $converted = strtr($answer['answer'], array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES)));
                $clear = strip_tags($converted);
                $result = trim($clear, chr(0xC2) . chr(0xA0));
                $result = trim($result);
                //lv-edit-2
                $answer['answer'] = str_replace('\"', '"', $answer['answer']);
                //end
                $width = ( isset($answer['width']) && $answer['width'] != "" ) ? " width='" . $answer['width'] . "'" : "";
                $height = ( isset($answer['height']) && $answer['height'] != "" ) ? " height='" . $answer['height'] . "'" : "";
                $echo .= ($key % 2 == 1) ? '<li class="col-right">' : '<li>';
                $echo .= $answer['corect'] ? '<i class="fa fa-check fa-check-square-o"></i>' : '';
                $echo .= ( $result == "" ) ? "" : $answer['answer'];
                $echo .= ($answer['image'] != '') ? '<p><img src="' . $answer['image'] . '"' . $width . $height . ' /></p>' : '';
                if ($answer['explain'] != '') {
                    $explain = mysql_fetch_assoc(mysql_query("SELECT `name` FROM `distrators` WHERE `id` = " . $answer['explain']));
                    $echo .= $explain ? '<p><i>' . $explain['name'] . '</i></p>' : '';
                }
                $echo .= ($key % 2 == 1) ? '</li><div class="clearnone">&nbsp;</div>' : '</li>';
            }
            $echo .= '</ul>';
        } else {
            $echo = $item['answers'];
        }
        ?>
                            
                                    <li data-id="<?php echo $item['id']; ?>">
                                   <input   type="hidden"  name="qn[]" value="<?php echo $item['id']; ?>"  />     
                                        
                                        <div class="ques-text">
                                            <div class="ques-name"><?php echo $item['name']; ?></div>
                                            <div class="ques-full">
        <?php echo $item['question']; ?>
                                                <p><strong><u>Answer:</u></strong></p>
        <?php echo $echo; ?>
                                            </div>
                                        </div>

                                        <button class="remove-in-list" name="remove_in_list" value="<?php echo $item['id']; ?>"><i class="fa fa-times"></i>  Remove</button>			
                                    </li>
        <?php
        $i++;
    }
    echo '</ul>';
} else {
    echo '<div class="item-listing clear"><p>There is no item found!</p></div>';
}
?>


                        </div>		<!-- /.ct_display -->
                    </form>
                    <script>
                        $(document).ready(function () {
                            var $count = 0;
                            var $timehidden;
                            $('#removeall-in-list').on('click', function () {
                                $('#list-document .ul-list').slideUp(500);
                                $count = $('#list-document .ul-list').children().length;
                                $('.list-notification>.text>.number').text($count);
                                $('.list-fixed').show();
                                /*removeall id in list*/
                                var $boo = true;
                                $.post("inc/ajax-remove_in_list.php", {"removeall_in_list": $boo});

                                clearTimeout($timehidden);
                                $timehidden = setTimeout(function () {
                                    $('.list-fixed').hide(500);
                                }, 10000);
                            });
                            $('.remove-in-list').on('click', function () {
                                $(this).parents('li').first().slideUp(500);
                                $count++;
                                $('.list-notification>.text>.number').text($count);
                                $('.list-fixed').show();
                                /*remove id in list*/
                                var $id = $(this).val();
                                $.post("inc/ajax-remove_in_list.php", {"remove_in_list": $id});

                                clearTimeout($timehidden);
                                $timehidden = setTimeout(function () {
                                    $('.list-fixed').hide(500);
                                }, 10000);
                            });
                            $('.list-notification').on('click', function () {
                                $(this).parents('.list-fixed').first().hide(500);
                            });
                            $("#ul-list").sortable({
                                connectWith: ".connectedSortable",
                                update: function (event, ui) {
                                    var sort = "";
                                    $("#ul-list>li").each(function () {
                                        sort += "," + $(this).attr("data-id");
                                    });
                                    sort = sort.substring(1, sort.length);
                                    /* alert(sort); */
                                    $.post("inc/ajax-sort_id.php", {"sort": sort});

                                }
                            }).disableSelection();

                            $('.ques-name').on('click', function () {
                                $(this).siblings('.ques-full').slideToggle();
                            });
                        });
                    </script>
                </div>
<?php if (mysql_num_rows($childs) > 0) include("pagination.php"); ?>
            </div>		<!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->
<div class="list-fixed">
    <div class="list-notification">
        <i class="fa fa-times"></i>
        <div class="text">A problem has been removed (<span class="number">0</span> problems total)</div>
    </div>
</div>
<?php
if (isset($_SESSION['list'])):
    require_once('inc/check-status.php');
    $status = checkStatus();
    if ($status == 0):
        ?>
        <div class="alert-q-remaining" style="display:block">
            <div class="list-notification">
                <i class="fa fa-times"></i>
                <div class="text">Please confirm your emaill address to print!</a></div>
            </div>
        </div>
    <?php endif;
endif; ?>
<script>
    $('.alert-q-remaining .fa.fa-times').on('click', function () {
        $(this).parents('.alert-q-remaining').first().hide(500);
    });
</script>
<?php include("footer.php"); ?>
<?php ob_flush(); ?>