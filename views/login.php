<?php include "includes/head.php" ?>
<body class="login @if (env("SITE_Portal")) login_portal  @endif ">

    <div class="login-card mb-30">
        <img src="<?=url("");?>uploads/app/login_stacked.png" style="display: block;width: 200px">
        <div class="sign-in">
            <h5 class="mb-30">Sign in to your account </h5>
            <form class="text-left simcy-form" action="<?=url("Auth@signin");?>" data-parsley-validate="" loader="true" method="POST">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Email address</label>
                            <input type="email" class="form-control" name="email" placeholder="Email address" required>
                            <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Password</label>
                            <input type="Password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="pull-left m-t-5"><a href="" target="forgot-password">Forgot password?</a></p>
                            <button class="btn btn-primary pull-right" type="submit" name="login">Login</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="forgot-password" style="display: none;">
            <h5 class="mb-30">Forgot password? don't worry, we'll <br>send your a reset link.</h5>
            <form class="text-left simcy-form" action="<?=url("Auth@forgot");?>" method="POST" data-parsley-validate="" loader="true">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Email address</label>
                            <input type="text" class="form-control" name="email" placeholder="Email address" required>
                            <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="pull-left m-t-5"><a href="" target="sign-in">Sign In?</a></p>
                            <button class="btn btn-primary pull-right" type="submit">Send Email</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="copyright">
            <p class="text-center"><?=date("Y")?> &copy; <?=env("APP_NAME")?> | All Rights Reserved.</p>
        </div>
    </div>

    <!-- scripts -->
    <script src="<?=url("");?>assets/js/jquery-3.2.1.min.js"></script>
    <script src="<?=url("");?>assets/libs/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?=url("");?>assets/js//jquery.slimscroll.min.js"></script>
    <script src="<?=url("");?>assets/js/simcify.min.js"></script>
    <script src="<?=url("");?>assets/js/jquery-validate.min.js"></script>
    <script src="<?=url("");?>assets/js/jquery-additional-methods.min.js"></script>

    <!-- custom scripts -->
    <script src="<?=url("");?>assets/js/app.js"></script>
    <script src="<?=url("");?>assets/js/custom.js"></script>
</body>

</html>
