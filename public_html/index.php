<?php

    session_start();
	
	//protect access direclty to pages (for example main.php)
	define("IN_INDEX", 1);
	
	//include variable $config - data needed to connect to database
    include("config.inc.php");
	
	/* connection to database
	in database there are two tables: photos and categories
	photos are stored in the server in directory "photos"
	table photos - column with id - "id", column with path to the photo (with filename) - "path", column with description - "description" and column with category - "category"
	table categories - column with id - "id", column with name of category - "category"
	*/
	if (isset($config) && is_array($config)) {

        try {
            $dbh = new PDO('mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'] . ';charset=utf8mb4', $config['db_user'], $config['db_password']);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print "Nie mozna polaczyc sie z baza danych: " . $e->getMessage();
            exit();
        }

    } else {
        exit("Nie znaleziono konfiguracji bazy danych.");
    }

?>
<!DOCTYPE html>
<html>
    <head>
	
        <meta charset="utf-8">
        <title>Galeria zdjęć</title>
		
		<!-- icon -->
		<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" /> 
        
		<!-- adapt to mobile device-->
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		
		<!-- style - css -->
		<link rel="stylesheet" href="/style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        
		<!-- javascript -->
		<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>        
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

		<!-- css and javascript to slideshow gallery -->
		<!-- slideshow gallery is created with usage of fancybox library -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
		
    </head>
	
	
    <body>

		<!-- navbar and menu -->
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark fixed-top">
			<div class="container">
				<a class="navbar-brand d-flex align-items-center" href="/">
					<!-- icon camera -->
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" aria-hidden="true" class="mr-2" viewBox="0 0 24 24" focusable="false"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
					Galeria
				</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarNav">
					<ul class="navbar-nav" id="menu-buttons">
						<li class="nav-item active">
							<a class="nav-link" href="/">Strona główna</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/add">Dodaj zdjęcie</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/categories">Kategorie</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/instruction">Instrukcja</a>
						</li>
					</ul>
				</div>
			</div>
        </nav>


        <div class="jumbotron">
            <div class="container">
                <h4 class="display-4">Profesjonalna galeria zdjęć</h4>
                <p class="lead">Strona poświęcona zdjęciom.</p>
            </div>
        </div>

		
		<div class="container mb-5">
			<div class="row">
				<?php
					//select page - if nothing match, choose main
					$pages = ['main', 'add', 'categories', 'instruction'];
					if (isset($_GET['page']) && $_GET['page'] && in_array($_GET['page'], $pages)) {
						if (file_exists($_GET['page'] . '.php')) {
							include($_GET['page'] . '.php');
						} else {
							print 'Plik ' . $_GET['page'] . '.php nie istnieje.';
						}
                    } else {
                        include('main.php');
                    }
				?>
			</div>
		</div>

		<!-- footer -->
        <footer class="footer mt-auto" style="background-color: #f5f5f5;">
          <div class="container">
            <span class="text-muted">Aktualna data: <?php print date('Y-m-d'); ?></span>
          </div>
        </footer>

    </body>
</html>
