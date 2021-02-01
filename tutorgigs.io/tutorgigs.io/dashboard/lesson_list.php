<html> 
 <title>TutorGigs, a P2G company</title>
  
 <style>
    #myInput {
    background-image: url('/css/searchicon.png'); /* Add a search icon to input */
    background-position: 10px 12px; /* Position the search icon */
    background-repeat: no-repeat; /* Do not repeat the icon image */
    width: 100%; /* Full-width */
    font-size: 16px; /* Increase font-size */
    padding: 12px 20px 12px 40px; /* Add some padding */
    border: 1px solid #ddd; /* Add a grey border */
    margin-bottom: 12px; /* Add some space below the input */
}

#myUL {
    /* Remove default list styling */
    list-style-type: none;
    padding: 0;
    margin: 0;
}

#myUL li a {
    border: 1px solid #ddd; /* Add a border to all links */
    margin-top: -1px; /* Prevent double borders */
    background-color: #f6f6f6; /* Grey background color */
    padding: 12px; /* Add some padding */
    text-decoration: none; /* Remove default text underline */
    font-size: 18px; /* Increase the font-size */
    color: black; /* Add a black text color */
    display: block; /* Make it into a block element to fill the whole list */
}

#myUL li a:hover:not(.header) {
    background-color: #eee; /* Add a hover effect to all links, except for headers */
}
 </style>
 <body>
<nav class="navbar navbar-light bg-light static-top">
      <div class="container">
        <a class="navbar-brand pull-left" style="width:5px;" href="#" ><img src="logo.png"></a> 
        <br><a href="https://tutorgigs.io/dashboard/sessions-listing.php">Go Back</a>
      </div>
    </nav>
 <h1 align="center">Download Lessons</h1>
 <h4>Search for TEKS.  Example: 4.4(C)</h4>
 <h4>Just click the TEKS and a lesson will download automatically</h4>
<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for TEKS here">
<ul id="myUL">
<li><a href="/dashboard/lessons/math/texas/gr3/3.2A.pdf">3.2(A) compose and decompose numbers up to 100,000 as a sum of so many ten thousands, so many thousands, so many hundreds, so many tens, and so many ones using objects, pictorial models, and numbers, including expanded notation as appropriate
</a></li>
<li><a href="/dashboard/lessons/math/texas/gr3/3.2B.pptx">3.2(B) describe the mathematical relationships found in the base-10 place value system through the hundred thousands place
</a></li>
<li><a href="/dashboard/lessons/math/texas/gr3/3.2C.pptx">3.2(C) represent a number on a number line as being between two consecutive multiples of 10; 100; 1,000; or 10,000 and use words to describe relative size of numbers in order to round whole numbers
</a></li>



</ul>

 


 <script>
function myFunction() {
    // Declare variables
    var input, filter, ul, li, a, i;
    input = document.getElementById('myInput');
    filter = input.value.toUpperCase();
    ul = document.getElementById("myUL");
    li = ul.getElementsByTagName('li');

    // Loop through all list items, and hide those who don't match the search query
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("a")[0];
        if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}
</script>
   
 </body>
 </html>