<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Application chat - Inscription</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="manifest" href="site.webmanifest">
    <link rel="apple-touch-icon" href="icon.png">
    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/chat.css">
    <script src="js/jquery-3.2.1.min.js"></script>


</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <br><br>
            <h3>Register</h3>

            <?php if (isset($viewVars['error'])) : ?>
                <div class="alert alert-danger" role="alert">
                    <strong><?= $viewVars['error'] ?></strong>
                </div>
            <?php endif;?>

            <?php if (isset($viewVars['success'])) : ?>
                <div class="alert alert-success" role="alert">
                    <strong><?= $viewVars['success'] ?></strong>
                </div>
            <?php endif;?>

            <form class="form-control" action='register' method="POST">
                <div class="control-group">
                    <!-- Username -->
                    <label class="control-label"  for="username">Username</label>
                    <div class="controls">
                        <input type="text" id="username" name="username" placeholder="" required>
                    </div>
                </div>
                <div class="form-group">
                    <!-- Password-->
                    <label class="control-label" for="password">Password</label>
                    <div class="controls">
                        <input type="password" id="password" name="password" placeholder="" required>
                    </div>
                </div>
                <div class="form-group">
                    <!-- Password-->
                    <label class="control-label" for="re_password">Retype Password</label>
                    <div class="controls">
                        <input type="password" id="re_password" name="re_password" placeholder="" required>
                    </div>
                </div>
                <div class="form-group">
                    <!-- Button -->
                    <div class="controls">
                        <button class="btn btn-success">Register</button>
                    </div>
                </div>
                </fieldset>
            </form>
        </div>
        <div class="col-lg-3"></div>

    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
</body>
</html>