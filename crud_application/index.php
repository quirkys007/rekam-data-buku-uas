<?php
	include "config.php";
?>
<html>
	<head>
		<title>projek CRUD buku</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"> 
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	</head>
	<body>
	<div class="container">
		<h3 class='text-center'>projek CRUD buku</h3><hr>
		<div class='row'>
			<div class="col-md-5">
				<form id='frm'>
				  <div class="form-group">
					<label>Judul Buku</label>
					<input type="text" class="form-control" name="name" id='name' required placeholder="Enter Title">
				  </div>
				  <div class="form-group">
					<label>Penerbit</label>
					<input type="text" class="form-control" name="email" id='email' required placeholder="Enter Penerbit">
				  </div>
				  <div class="form-group">
					<label>Penulis</label>
					<input type="text" class="form-control"  name="mobile" id='mobile' required placeholder="Enter Penulis">
				  </div>
				  
				  <input type="hidden" class="form-control" name="uid" id='uid' required value='0' placeholder="">
				  
				  <button type="submit" name="submit" id="but" class="btn btn-success">Add Buku</button>
				  <button type="button" id="clear" class="btn btn-warning">Clear</button>
				</form> 
			</div>
			<div class="col-md-7">
				<table class="table table-bordered" id='table'>
					<thead>
						<tr>
							<th>Judul</th>
							<th>Penerbit</th>
							<th>Penulis</th>
							<th>Edit</th>
							<th>Delete</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$sql="select * from user";
							$res=$con->query($sql);
							if($res->num_rows>0)
							{
								while($row=$res->fetch_assoc())
								{	
									echo"<tr class='{$row["UID"]}'>
										<td>{$row["NAME"]}</td>
										<td>{$row["EMAIL"]}</td>
										<td>{$row["MOBILE"]}</td>
										<td><a href='#' class='btn btn-primary edit' uid='{$row["UID"]}'>Edit</a></td>
										<td><a href='#' class='btn btn-danger del' uid='{$row["UID"]}'>Delete</a></td>					
									</tr>";
								}
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>	
	<script>
		$(document).ready(function(){
			
			//Clear all the Fields
			$("#clear").click(function(){
				$("#name").val("");
				$("#email").val("");
				$("#mobile").val("");
				$("#uid").val("0");
				$("#but").text("Add User");
			});
			
			//Insert and update using jQuery ajax
			$("#but").click(function(e){
				e.preventDefault();
				var btn=$(this);
				var uid=$("#uid").val();
				
				//Check All Fields are filled
				var required=true;
				$("#frm").find("[required]").each(function(){
					if($(this).val()==""){
						alert($(this).attr("placeholder"));
						$(this).focus();
						required=false;
						return false;
					}
				});
				if(required){
					$.ajax({
						type:'POST',
						url:'ajax_action.php',
						data:$("#frm").serialize(),
						beforeSend:function(){
							$(btn).text("Wait...");
						},
						success:function(res){
							
							var uid=$("#uid").val();
							if(uid=="0"){
								$("#table").find("tbody").append(res);
							}else{
								$("#table").find("."+uid).html(res);
							}
							
							$("#clear").click();
							$("#but").text("Add User");
						}
					});
				}
			});
			
			//Delete row using jQuery ajax
			$("body").on("click",".del",function(e){
				e.preventDefault();
				var uid=$(this).attr("uid");
				var btn=$(this);
				if(confirm("Are You Sure ? ")){
					$.ajax({
						type:'POST',
						url:'ajax_delete.php',
						data:{id:uid},
						beforeSend:function(){
							$(btn).text("Deleting...");
						},
						success:function(res){
							if(res){
								btn.closest("tr").remove();
							}
						}
					});
				}
			});
			
			//fill all fields from table values
			$("body").on("click",".edit",function(e){
				e.preventDefault();
				var uid=$(this).attr("uid");
				$("#uid").val(uid);
				var row=$(this);
				var name=row.closest("tr").find("td:eq(0)").text();
				$("#name").val(name);
				var email=row.closest("tr").find("td:eq(1)").text();
				$("#email").val(email);
				var mobile=row.closest("tr").find("td:eq(2)").text();
				$("#mobile").val(mobile);
				$("#but").text("Update User");
			});
		});
	</script>
	</body>
</html>