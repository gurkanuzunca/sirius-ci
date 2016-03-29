<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <base href="<?php echo base_url('/') ?>" />

    <link rel="stylesheet" type="text/css" href="../public/admin/plugin/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="../public/admin/plugin/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../public/admin/css/main.css" />
    <?php if (isset($public['css'])): ?>
        <?php foreach ($public['css'] as $css): ?>
            <link rel="stylesheet" type="text/css" href="<?php echo $css ?>" />
        <?php endforeach; ?>
    <?php endif; ?>

    <script type="text/javascript" src="../public/admin/js/jquery.js"></script>
    <script type="text/javascript" src="../public/admin/plugin/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../public/admin/js/bootstrap.filestyle.min.js"></script>
    <?php if (isset($public['js'])): ?>
        <?php foreach ($public['js'] as $js): ?>
            <script type="text/javascript" src="<?php echo $js ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    <script type="text/javascript" src="../public/admin/js/main.js"></script>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements html5shiv.js -->
    <!--[if lt IE 9]><script src="../public/admin/js/html5shiv.js'"></script><![endif]-->
</head>
<body>

<div id="login" class="container">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">Sisteme Giriş</div>
            <div class="panel-body">
                <form action="" method="post">
                    <?php echo $this->utils->alert() ?>

                    <div class="input-group form-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" class="form-control" placeholder="Kullanıcı adı" name="username" value="<?php echo set_value('username') ?>">
                    </div>

                    <div class="input-group form-group">
                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                        <input type="password" class="form-control" placeholder="Parola" name="password">
                    </div>

                    <button class="btn btn-success" type="submit">Giriş Yap</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
