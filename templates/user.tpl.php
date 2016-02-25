<div id="userPage" class="container">
    <h1>Planner</h1>
    <p>To use the app, login or register.</p>
    <hr>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="panel panel-default" style="max-width:360px;margin:10px auto;">
                <div class="panel-heading">
                    Login
                </div>
                <div class="panel-body">
                    <form id="loginForm" method="post" action="">
                        <div class="form-group">
                            <label for="loginFormEmail">E-mail</label>
                            <input type="email" id="loginFormEmail" class="form-control"
                                   placeholder="E-mail" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="loginFormPassword">Password</label>
                            <input type="password" id="loginFormPassword" class="form-control"
                                   placeholder="Password" name="password" required>
                        </div>
                        <input type="hidden" name="action" value="login">
                        <input type="submit" value="Login" id="loginFormSubmit" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="panel panel-default" style="max-width:360px;margin:10px auto;">
                <div class="panel-heading">
                    Register
                </div>
                <div class="panel-body">
                    <form id="registerForm" method="post" action="">
                        <div class="form-group">
                            <label for="registerFormName">Name</label>
                            <input type="text" id="registerFormName" class="form-control"
                                   placeholder="Name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="registerFormEmail">E-mail</label>
                            <input type="email" id="registerFormEmail" class="form-control"
                                   placeholder="E-mail" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="registerFormPassword">Password</label>
                            <input type="password" id="registerFormPassword" class="form-control"
                                   placeholder="Password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="registerFormPasswordConf">Password</label>
                            <input type="password" id="registerFormPasswordConf" class="form-control"
                                   placeholder="Confirm your password" name="password_conf" required>
                        </div>
                        <input type="hidden" name="action" value="register">
                        <input type="submit" value="Login" id="registerFormSubmit" class="btn btn-primary">
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
