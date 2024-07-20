<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{title}</title>
        
        <link rel="shortcut icon" href="/template/GRP/favicon.ico" type="image/x-icon">
        
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&amp;subset=latin" rel="stylesheet" type="text/css">
		
        <link href="/template/GRP/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        
        <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        
        <meta name="author" content="zerik">
        <meta name="copyright" content="Samp Good RolePlay">
        
        <link href="/template/GRP/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        
        <link href="/template/GRP/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
        
		{scripts}
		
		<script type="text/javascript" src="/assets/view/javascript/tinymce/tinymce.min.js"></script>
		
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
    </head>
    <body class="skin-blue sidebar-mini">
        <div class="wrapper">

            <header class="main-header">
                
                <a href="/" class="logo">
                    <span class="logo-mini"><b>G</b>RP</span>
                    <span class="logo-lg"><b>Samp</b>GRP</span>
                </a>

                <nav class="navbar navbar-static-top" role="navigation">

                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Переключить навигацию</span>
                    </a>
                    
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            {menu_0}
                        </ul>
						
                    </div>
                </nav>
            </header>

            <aside class="main-sidebar">
                <section class="sidebar">
                    <ul class="sidebar-menu">
                        <li class="header"><i class="menu-icon fa fa-dashboard"></i> Главное меню</li>
						 <form action="/user/view/" method="post" class="sidebar-form">
                        <div class="input-group">
                            <input type="text" name="name" class="form-control" placeholder="Поиск игроков"/>
                            <span class="input-group-btn">
                                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </form>
					{menu_1}

					<li class="header"><i class="menu-icon fa fa-star"></i> Статистика</li>
					<li><a href="/monitoring/stats" id="noAjax"><i class="fa fa-chevron-circle-right"></i> <span>Good Role Play</span></a></li>
                    </ul>
					
                </section>
                <!-- /.sidebar -->
            </aside>
            <div class="content-wrapper">
                <section class="content2">
                    <!-- Main content -->
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title titleContent">{title}</h3>
                        </div>
                        <div class="box-body">
                            {content}
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
							<!-- webmoney passport label#6A7B3E07-68A8-4A81-8A5C-06091864C5D1 begin -->
		<a href="//passport.webmoney.ru/asp/certview.asp?wmid=374300665726">
		<img src="https://files.webmoney.ru/files/5xtc9oj4/inline" border="0"/>
		</a> 
		<!-- webmoney passport label#6A7B3E07-68A8-4A81-8A5C-06091864C5D1 end -->
					<div class="pull-right">Good Role Play © 2013 - 2019. Все права защищены.</div>
                </section>
            </div>
        </div><!-- /.content-wrapper -->

        <script src="/template/GRP/plugins/slimScroll/jquery.slimScroll.min.js" type="text/javascript"></script>
        <script src='/template/GRP/plugins/fastclick/fastclick.min.js'></script>
        <script src="/template/GRP/dist/js/app.min.js" type="text/javascript"></script>
    </body>
</html>
