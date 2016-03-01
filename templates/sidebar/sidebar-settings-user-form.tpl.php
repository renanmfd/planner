<form id="editUserForm" action="" method="post">
    <div class="container">

        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" id="uEditName" class="form-control" name="uEditName"
                           value="<?php echo $user->getName(); ?>">
                </div>
            </div>

            <div class="col-xs-12">
                <div class="form-group">
                    <label for="">E-mail</label>
                    <input type="text" id="uEditEmail" class="form-control" name="uEditEmail"
                           value="<?php echo $user->getEmail(); ?>" disabled>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="form-group">
                    <label for="">Password</label>
                    <input type="password" id="uEditPass" class="form-control" name="password"
                           placeholder="Enter new password">
                </div>
            </div>

            <div class="col-xs-12">
                <div class="form-group">
                    <label for="uEditPassConfirm">Confirm password</label>
                    <input type="password" id="uEditPassConfirm" class="form-control"
                           placeholder="Confirm chosen password">
                </div>
            </div>
        </div>

        <div class="form-group">
            <input type="hidden" name="user" value="<?php echo $user->getId(); ?>">
            <input type="hidden" name="services" value="user_edit">

            <input type="submit" id="editUserSubmit" class="btn btn-primary" value="Save">
        </div>

    </div>
</form>
