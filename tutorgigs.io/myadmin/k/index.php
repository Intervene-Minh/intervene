<?php include("header.php"); ?>
<?php include("adm/manage-tutors.php"); ?>
<?php
    @extract($_REQUEST) ;
    $today = date("Y-m-d H:i:s"); 
    $error='';
?>
<script>
    var tutorList = <? echo getTutorList(); ?> || [];
    var tutorListCount = tutorList.length;
</script>
<!-- HTML DOCUMENT BODY CONTENT -->
        <!-- Page Content  -->
            <div id="content">

                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">

                        <button type="button" id="sidebarCollapse" class="btn btn-info">
                            <i class="fas fa-align-left"></i>
                            <span>Menu</span>
                        </button>
                        <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fas fa-align-justify"></i>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="nav navbar-nav ml-auto">
                                <li class="nav-item active">
                                    <a class="nav-link" href="#">Page</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Page</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Page</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Page</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <h2>Tutor List</h2>
                <table id="tutorList" class="table table-responsive table-striped" data-order='[[ 1, "asc" ]]' data-page-length='100'>
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        <? echo getTutorList_HTML(); ?>                  
                    </tbody>
                </table>
                <script>
                    for (var i = 0; i < tutorList.length; i++) {
                        let newRow = document.createElement('tr');
                        let id
                    }
                </script>
            </div>            
        <!-- -->
<?php include("footer.php"); ?>