<form id="adminCreateUserForm">

	<div class="row">
		<div class="col">
			    <label>Username</label>
				<input type="text" name="username" maxlength="16" min="1" class="form-control" value="" required>
		</div>
		<div class="col">
			    <label>Password</label>
				<input type="password" name="password" maxlength="16" min="1" class="form-control" value="" required>
		</div>
    </div>


	<div class="row">
		<div class="col">
			    <label>Firstname</label>
				<input type="text" name="firstname" maxlength="32" min="1" class="form-control" value="" required>
		</div>
		<div class="col">
			    <label>Surname</label>
				<input type="text" name="surname" maxlength="64" min="1" class="form-control" value="" required>
		</div>
    </div>
    

	<div class="row">
		<div class="col">
			    <label>Date of Birth</label>
				<input name="dob" type="date" min="1919-01-01" max="2018-01-01" class="form-control" value="" required>
        </div>
        
		<div class="col">
				<label>Phone Number</label>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">07</div>
                    </div>
                    <input name="telephoneNumber" type="text" maxlength="9" min="9" class="form-control" value="" required>
                </div>
		</div>
	</div>

	<div class="row">
			<div class="col">
			    <label>Email</label>
				<input type="email" name="email" maxlength="64" min="7" class="form-control" value="" required>
            </div>
			<div class="col">
			    <label>Account Type</label>
                <select name="accountType" class="custom-select">
                    <option value="default" selected>Default</option>
                    <option value="admin">Admin</option>
                </select>
		    </div>
	</div>

    <br>
    <div id="accountCreationError"></div>
    <button id="adminCreateNewAccount" class="btn btn-primary" style="float:right;" >Submit</button><br>

</form><br>