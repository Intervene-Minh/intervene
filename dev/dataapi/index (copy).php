<?php 

public function getUsersByType($type,$keyword='',$projectId=''){   // Project Users list.
  
    
        $this->db->select("`cu.id`, `cu.guid_id`, `cu.name`, `cu.email_id`, `cu.location_id`, `cu.status`, `cu.ip_address`, `cu.created`, `cu.avatar`, `cu.phone_no`, `cu.address`, `cu.user_type`, `cu.created_at`, `cu.updated_at`, `cu.employee_name`, `cu.client_name`, `cu.designation`,if(cu.user_type =2 ,`cu.user_title` , '') as `user_title`, ( select count(cur.id) from checkmate_user_rabbit cur where cur.team_member_id = cu.id limit 1 ) as isAvailabelInRabbit");
        $this->db->from('checkmate_user cu');
        if(!empty($projectId))
        {
            $this->db->join('checkmate_project_resources cp','cp.user_id = cu.id','inner');
            $this->db->where('cp.project_id', $projectId);
        }
        if(!empty($type))
        {
            $type = intval($type);
            $this->db->where('user_type', $type);
        }
        
        if(!empty($keyword))
        {
            $this->db->or_where('id', $keyword);
            $this->db->like('name', $keyword, 'both');
            $this->db->like('email_id', $keyword, 'both');
        }
        $result = $this->db->get()->result_array();
        return $result;
    }

?>


<style>
.roow-2{
    margin: 18px -25px 0px;
    padding-bottom: 40px;
    width: 100%;
   display: inline-block;
}
.roow-2 .col-md-4{
   padding:0px 25px;
}
.program-white-2{
    background: #fff;
    padding: 0px 0px;
    text-align: left;
   box-shadow: 0px 0px 8px #cecece;
}
.program-white-2 img{
    margin-bottom: 0px;
   max-width:100%;
}
.program-white-2 h3{
    font-family: 'Times New Roman', Times, serif;
    font-weight:400;
    font-size: 20px;
   color: #333333;
    margin: 20px 0px 10px;
    display: inline-block;
    width: 90%;
    padding: 0px 20px;
}
.program-white-2 p.tpty{
   font-family: Verdana;
color: #333333;
font-size: 12px;
font-weight: 400;
padding:10px 0px;
display: inline-block;
margin:10px 0px 30px;
padding: 0px 20px;
} 
.program-white-2 .img-full{
   position:relative;
}
.program-white-2 .img-full span.views-field-field-event-category {
    position: absolute;
    background: #ff5c26;
    padding: 10px;
    font-family: Verdana;
    padding: 10px;
    bottom: 0px;
    left: 0px;
    color: #ffffff;
    font-size: 12px;
}
.view-footer {
    text-align: center;
    margin: 20px 0px;
   display:inline-block;
   width:100%;
}
.view-footer a {
    color: #777777;
    font-family: 'Times New Roman', Times, serif;
    font-size: 14px;
    text-align: center;
    padding: 10px 30px;
    background: #e1e1e1;
    font-style: italic;
}
</style>
<div class="roow-2">
              <div class="col-md-4 col-sm-4">
                     <div class="program-white-2">
                           <div class="img-full">
                                <img src="/sites/all/themes/miutheme/less/mmmc-img/4.jpg">
                                       <span class="views-field views-field-field-event-category">News</span>
                                  </div>
                                   <h3>Two day Orientation Program for FIS Batch</h3>
                                   <p class="tpty">The Chief Executive of MMMC, Prof. Dr. Jaspal Singh Sahota shared some words of wisdom with the students.</p>
                     </div>
             </div>
            <div class="col-md-4 col-sm-4">
                       <div class="program-white-2">
                                <div class="img-full">
                                <img src="/sites/all/themes/miutheme/less/mmmc-img/5.jpg">
                                       <span class="views-field views-field-field-event-category">News</span>
                                  </div>
                                   <h3>Career Progression Talk for the Batch 14</h3>
                                 <p class="tpty">The Chief Executive of MMMC, Prof. Dr. Jaspal Singh Sahota shared some words of wisdom with the students.</p>    
                    </div>
            </div>
           <div class="col-md-4 col-sm-4">
                  <div class="program-white-2">
                   <div class="img-full">
                                <img src="/sites/all/themes/miutheme/less/mmmc-img/6.jpg">
                                      <span class="views-field views-field-field-event-category">News</span>
                         </div>
                         <h3>Application for FIS-March 2018 is now open</h3>    
                         <p class="tpty">The Chief Executive of MMMC, Prof. Dr. Jaspal Singh Sahota shared some words of wisdom with the students.</p>
                 </div>
           </div>
</div>
<div class="view-footer">
      <a href="/events-listing">View All</a>   
</div>









\\\\



<?php 

$project_users=" SELECT project_id, COUNT(project_id) as totrecord 
FROM `checkmate_project_resources` WHERE 1 GROUP BY project_id";



 public function subTaskList($user_id=null, $project_id=null, $task_id=null) 
    {
        $this->db->select('*,RST.id as id');
        $this->db->from('ref_sub_task RST');
        $this->db->join('ref_task RT', 'RT.id = RST.task_id','left'); 

        $this->db->where('RST.project_id', $project_id);
        $this->db->where('RT.user_id', $user_id);

        $this->db->where('RST.task_id', $task_id); //echo $this->db->last_query();
        return $this->db->get()->result_array();
    }



?>

<input type="checkbox" id="hamburger"/>
    <label class="menuicon" for="hamburger">
      <span></span>
    </label>
    <div class="menu-2">
      <ul>
      <li>
         <div class="menu-menu-2">
          <input id="check01" type="checkbox" name="menu"/>
          <label for="check01">Study</label>
          <ul class="submenu">
              <h2 class="block-title">Programs</h2>
              <li><a href="/course-listing?field_course_level_tid=7" title="Under Graduate">Under Graduate</a></li>
            <li><a href="/course-listing?field_course_level_tid=8" title="Post Graduate">Post Graduate</a></li>
            <li><a href=/course-listing?field_course_level_tid=9" title="Foundation">Foundation</a></li>
            <li><a href="/course-listing?field_course_level_tid=10" title="Diploma">Diploma</a></li>
                        <h2 class="block-title">Admissions</h2>
            <li><a href="/admissions/overview" title="Admissions Overview">Overview</a></li>
            <li><a href="/course-listing" title="Find a course">Find a course</a></li>
            <li><a href="https://www.miu.edu.my/miu/lp/apply-now.html" title="How to Apply ">How to Apply</a></li>
            <li><a href="/admissions/early-admissions" title="Early Admissions">Early Admissions</a></li>
            <li><a href="/admissions/overview#block-views-events-block-1" title="Open Days &amp; Events">Open Days &amp; Events</a></li>
            <li><a href="/admissions/outreach-pathways" title="Outreach &amp; Pathways">Outreach &amp; Pathways</a></li>
            <li><a href="/admissions/scholarships" title="Scholarships">Scholarships</a></li>
            <li><a href="/admissions/important-dates" title="Important Dates">Important Dates</a></li>
            <li ><a href="/admissions/book-campus-visit" title="Book a campus visit">Book a campus visit</a></li>
            <h2 class="block-title">Why MIU</h2>
                        <li><a href="/why-miu/industry-partners" title="Industry Partners">Industry Partners</a></li>
            <li><a href="/why-miu/academic-partners" title="Academic Partners">Academic Partners</a></li>
            <li><a href="/why-miu/international-curriculum" title="International Curriculum">International Curriculum</a></li>
            <li><a href="/why-miu/recognition-professional-bodies" title="Recognition by Professional Bodies">Recognition by Professional Bodies</a></li>
            <li><a href="/why-miu/manipal-heritage" title="Manipal Heritage">Manipal Heritage</a></li>
            <li><a href="/why-miu/malaysia-study-destination" title="Malaysia as a Study">Malaysia as a Study Destination</a></li>

          </ul>
         </div> 
      </li>
      <li>
         <div class="menu-menu-2">
           <input id="check02" type="checkbox" name="menu"/>
          <label for="check02">Discover</label>
          <ul class="submenu">
            <h2 class="block-title">About Us</h2>
              <li><a href="/about/about-university-mission" title="University Mission">University Mission</a></li>
            <li><a href="/about/about-leadership" title="Leadership">Leadership</a></li>
            <li><a href="/miu-world-wide-manipal" title="Manipal World ">Manipal World Wide</a></li>
            <li><a href="/about/manipal-time-line" title="Manipal Timeline">Manipal Timeline</a></li>
            <li><a href="/about/lake-side-campus" title="Lakeside campus">Lakeside campus</a></li>
            <li><a href="/about/manipal-legacy" title="Manipal Legacy">Manipal Legacy</a></li>
            <li><a href="/about/academic-partners" title="Academic Partners">Academic Partners</a></li>
            <li><a href="/about/job-placement" title="Job Placement">Job Placement</a></li>
                        <h2 class="block-title">Schools At  MIU</h2>
            <li><a href="/schools/centre-foundation-and-language-studies" title="Centre for Foundation and Language Studies">Centre for Foundation and Language Studies</a></li>

            <li><a href="/schools/school-management-and-business" title="School of Management and Business">School of Management and Business</a></li>
            <li><a href="/schools/school-science-and-engineering" title="School of Science and Engineering">School of Science and Engineering</a></li>
                        <h2 class="block-title">Campus Life</h2>
            <li><a href="/campus-life/overview" title="Campus Life Overview">Overview</a></li>
            <li><a href="/campus-life/hostel" title="MIU Hostel">Hostel</a></li>
            <li><a href="/campus-life/library" title="Library">Library</a></li>
            <li><a href="/campus-life/sports-and-fitness" title="Sport and Fitness">Sport and Fitness</a></li>
            <li><a href="/campus-life/transport" title="Transport">Transport</a></li>
            <li><a href="/campus-life/cafeteria" title="Cafeteria">Cafeteria</a></li>
            <li><a href="/campus-life/labs-workshops" title="Labs &amp; Workshops">Labs &amp; Workshops</a></li>
            <li><a href="/campus-life/student-clubs" title="Student Clubs">Student Clubs</a></li>
            <li><a href="/campus-life/lifestyle-nilai" title="Lifestyle in Nilai" class="active-trail active">Lifestyle in Nilai</a></li>
            <li><a href="/campus-life/other-facilities" title="Other Facilities">Other Facilities</a></li>
            <li><a href="/campus-life/student-support-services" title="Student Support Services">Student Support Services</a></li>
            
          </ul>
        </div>
      </li>
      <li>
          <div class="menu-menu-2">
          <input id="check03" type="checkbox" name="menu"/>
          <label for="check03">International</label>
          <ul class="submenu">
                        <h2 class="block-title">International</h2>
            <li><a href="/international/overview" title="International Overview">Overview</a></li>
            <li><a href="/international/international-curriculum" title="International Curriculum">International Curriculum</a></li>
            <li><a href="/international/global-university-network" title="Global University Network">Global University Network</a></li>
            <li><a href="/international/student-abroad-exchange" title="Student Abroad &amp; Exchange">Student Abroad &amp; Exchange</a></li>
            <li><a href="/international/global-reputation-accreditations" title="Global Reputation/ Accreditations">Global Reputation/ Accreditations</a></li>
            <li><a href="/international/international-scholarships" title="International Scholarships">International Scholarships</a></li>
            <li><a href="/international/overview#student-speak" title="Testimonials">Testimonials</a></li>
            <li><a href="/international/studying-malaysia" title="Studying in Malaysia">Studying in Malaysia</a></li>
            <li><a href="/international/future-students" title="Future Students">Future Students</a></li>

          </ul>
        </div>  
      </li>
      </ul>
    </div>