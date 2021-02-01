<?php


include("header.php");
?>
<div class="container">
	<div class="row">
	
		<div style="padding: 40px" class="col-6-md col-6-md col-12-lg text-center">

			<form>
			
				<div style="text-align: left" class="form-group">  <!-- Checkbox Group !-->
					<label class="control-label">Question 1<br><img src="/images/demo1.png" alt="Smiley face" height="494" width="573"></label>
					<div style="text-align: left" class="checkbox">
					  <label>
						<input type="checkbox" name="fav_foods" value="correct">A.  <img src="/images/demo1a.png" alt="Smiley face" height="37" width="122">
						
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<input type="checkbox"  name="fav_foods" value="subtract">B.  <img src="/images/demo1b.png" alt="Smiley face" height="38" width="103">
						
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<input type="checkbox"  name="fav_foods" value="fluency">C.  <img src="/images/demo1c.png" alt="Smiley face" height="35" width="111">
						
					  </label>
					</div>
					<div class="checkbox">
					  <label>
						<input type="checkbox"  name="fav_foods" value="carry">D.  <img src="/images/demo1d.png" alt="Smiley face" height="30" width="104">
						
					  </label>
					</div>
				</div>	
				
				<div class="form-group"> <!-- Submit button !-->
					<button class="btn btn-primary">Next</button>
				</div>
				
			</form>
		</div>
	</div>
</div>


		<!-- /#header -->
<?php include("footer.php"); ?>