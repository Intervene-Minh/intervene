<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <title>SimpleCalendar</title>
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lato:300,400,700">
  <link rel="stylesheet" href="http://weloveiconfonts.com/api/?family=fontawesome">
  <link rel="stylesheet" href="assets/css/style-personal.css">
  <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
  <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
  <script src="assets/js/simplecalendar.js" type="text/javascript"></script>
  
  
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"> List of Tutor sessions
        </h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="calendar hidden-print">
          <header>
            <h2 class="month"></h2>
            <a class="btn-prev fontawesome-angle-left" href="#"></a>
            <a class="btn-next fontawesome-angle-right" href="#"></a>
          </header>
          <table>
            <thead class="event-days">
              <tr></tr>
            </thead>
            <tbody class="event-calendar">
              <tr class="1"> </tr>
              <tr class="2"></tr>
              <tr class="3"></tr>
              <tr class="4"></tr>
              <tr class="5"></tr>
            </tbody>
          </table>
          <div class="list">
            <div class="day-event" date-day="2" date-month="2" date-year="2018"  data-number="1">
              <a href="#" class="close fontawesome-remove"></a>
              <h2 class="title">3 session</h2>
             
              <p>11 am , sesion by Rahul</p>
              <p>13 am , sesion by Tom</p>
              <p>15 pm , sesion by SAM</p>
              
              
              <label class="check-btn">
              <input type="checkbox" class="save" id="save" name="" value=""/>
              <span>Save to personal list!</span>
              </label>
            </div>
              
              
              <div class="day-event" date-day="5" date-month="2" date-year="2018"  data-number="1">
              <a href="#" class="close fontawesome-remove"></a>
              <h2 class="title">5 Session today</h2>
              <p>11 am , sesion by Rahul</p>
              <p>13 am , sesion by Tom</p>
              <p>15 pm , sesion by SAM</p>
               
              <p>13 am , sesion by Tom</p>
              <p>15 pm , sesion by SAM</p>
              
              
              
              <label class="check-btn">
              <input type="checkbox" class="save" id="save" name="" value=""/>
              <span>Save to personal list!</span>
              </label>
            </div>
              
              <div class="day-event" date-day="11" date-month="2" date-year="2018"  data-number="1">
              <a href="#" class="close fontawesome-remove"></a>
              <h2 class="title">10-Session today</h2>
              <p>Lorem Ipsum är en utfyllnadstext från tryck- och förlagsindustrin. Lorem ipsum har varit standard ända sedan 1500-talet, när en okänd boksättare tog att antal bokstäver och blandade dem för att göra ett provexemplar av en bok.</p>
              <label class="check-btn">
              <input type="checkbox" class="save" id="save" name="" value=""/>
              <span>Save to personal list!</span>
              </label>
            </div>
              
              
              
              
              
          </div>
        </div>
      </div>
        
     
        
        
        
    </div>
  </div>
</body>
</html>
