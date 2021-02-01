<?php 
    /*
        @@ Attendance Ajax
    */
    include('inc/conn.php');
    session_start();

    @extract($_GET);
    @extract($_POST);


    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";

    if ($action == "districts") {
        $sql = "SELECT 
            d.id,
            d.district_name as name,
            count( s.SchoolId ) AS numSchools,
            group_concat(s.SchoolName) AS schools,
            group_concat(s.SchoolId) AS schoolIDs
            FROM `schools` s
            LEFT JOIN loc_district d ON s.district_id = d.id
            WHERE s.district_id >0
            GROUP BY s.district_id
            ORDER BY d.district_name
        ";        
        print_r(dataOp($sql));
    }
    if ($action == "schools") {        
        $sql = "SELECT 
            SchoolId as `id`, 
            SchoolName as `name` 
            FROM 
            schools 
            WHERE 
            district_id = $districtID
        ";
        print_r(dataOp($sql));
    }
    if ($action == "school_sessions") {
        $sql = "SELECT 
            ss.id,
            ss.ses_start_time,
            ss.ses_end_time,
            ss.board_type,
            ss.type,
            ss.district_id,
            ss.school_id,
            ss.grade_id,
            ss.teacher_id,
            ss.lesson_id,
            c.class_name as lesson_name,
            nr.name,
            nr.newrow_room_id,
            (SELECT group_concat(nrs.newrow_user_id) FROM newrow_room_users nrs WHERE nrs.newrow_room_id = nr.newrow_room_id AND nrs.user_type='student') as nr_student_ids,
            (SELECT group_concat(s.id) FROM newrow_room_users nrs JOIN students s ON s.id=nrs.intervene_user_id WHERE nrs.newrow_room_id = nr.newrow_room_id AND nrs.user_type='student' ) as student_ids,
            (
                SELECT
                group_concat(CONCAT(s.first_name,' ', s.last_name)) as name
                FROM newrow_room_users nrs     
                JOIN students s ON s.id=nrs.intervene_user_id 	
                WHERE     
                nrs.newrow_room_id = nr.newrow_room_id AND
                nrs.user_type='student'
            ) as student_names

            FROM 
            int_schools_x_sessions_log  ss
            JOIN
            newrow_rooms nr ON nr.ses_tutoring_id=ss.id 
            LEFT JOIN (SELECT * FROM classes group by class_name) AS c 
                ON c.school_id=ss.school_id AND c.grade_level_id=ss.grade_id
            WHERE
            ss.school_id = $schoolID
        ";
        if ($_REQUEST['startDate'] > 0) {
            $sql .= "
                AND
                ss.ses_start_time >= '".$startDate."'
                AND ss.ses_start_time <= '".$endDate."'
            ";
        }
        $sql .= "
            ORDER BY
            ss.id 
            DESC;
        ";       
        print_r(dataOp($sql));
    }

    function dataOp ($query) {
        global $link;
        $list = array();
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = $row;
        }
        $jsonlist = json_encode($list);
        return $jsonlist;
    }
?>