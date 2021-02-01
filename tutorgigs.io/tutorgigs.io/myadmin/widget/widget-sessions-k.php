<h4 class="widget-title"><i class="fa fa-paragraph"></i>Tutor Sessions</h4>
<div  class="widget-content">
	
    <p class="list" title="List of Tutor Sessions"><i class="fa fa-th-list"></i><a href="list-tutor-sessions.php">List of Tutor Sessions</a></p>

     <p class="list" title="Unresolved Sessions"><i class="fa fa-th-list"></i><a 
        href="cancelled_sessions.php">Cancelled Sessions History</a></p>

    <p class="list" title=""><i class="fa fa-th-list"></i>
        <a href="tutor-sessions.php">Booked/Unbooked Sessions</a></p>


        
        <p title="Sessions Calendar" class="list"><i class="fa fa-th-list"></i>
        <a href="sessions-calendar.php">Calendar</a></p>    
      
    
    <p class="list" title="Jobs Board"><i class="fa fa-th-list"></i>
        <a href="jobs-board.php">Jobs Board</a></p>

    <?php
        if ($_SESSION['login_role'] == "3") {

    ?>
    <p class="list" title="Sessions Payment History"><i class="fa fa-th-list"></i>
        <a href="payment-history.php">Payment History</a></p>
    <?php 
        } 
    ?>
        <p class="list" title="Tutoring- suspended sessions log"><i class="fa fa-th-list"></i>
        <a href="suspended_sessions_log.php">Test Link 1 </a></p>
     
    
        
    
</div>

<!--https://tutorgigs.io/myadmin/session-calendar/-->