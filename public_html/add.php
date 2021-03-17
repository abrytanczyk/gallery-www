<?php
	//protect access direclty to page
	if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }
?>

<div class="container">
	
	<h1 class="font-weight-light text-center text-lg-center mt-4 mb-0">Dodaj nowe zdjęcie do galerii</h1>

	<hr class="mt-2 mb-5">
	
</div>

<div class="container mb-5">
	
	<?php
	// upload image - form action
	if(isset($_FILES["fileToUpload"]) && isset($_POST['description']) && isset($_POST['category'])) {
		//check if file was choosen
		if (empty($_FILES["fileToUpload"]["name"])){
			print '<p class="alert-negative">Nie wybrano pliku!</p>';
		}
		//check if description is ok (not empty)
		else if (empty($_POST['description'])){
			print '<p class="alert-negative">Pole podpis jest wymagane!</p>';
		}
		//category is always choosen (default option is no category), so it is not necessary to check it
		else {
			
			//file path
			$target_dir = "./photos/";
			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			
			//file path to store in database
			$target_dir_database = "/photos/";
			$target_file_database = $target_dir_database . basename($_FILES["fileToUpload"]["name"]);
			
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			
			//check if file is an image
			if($check == false) {
				print '<p class="alert-negative">Plik nie jest zdjęciem!</p>';
			}
			// Check if file already exists
			else if (file_exists($target_file)) {
				print '<p class="alert-negative">Plik o nazwie ' . basename( $_FILES["fileToUpload"]["name"]). ' już istnieje.  Jeżeli nie widzisz go w galerii, zmień nazwę dodawanego pliku i spróbuj ponownie.</p>';
			}
			// Allow certain file formats
			else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
				print '<p class="alert-negative">Niedozwolony format pliku! Dozwolone formaty to: JPG, JPEG, PNG, GIF.</p>';
			}
			// if everything is ok, try to upload file
			else {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					
					//add photo to database
					$path = $target_file_database;
                    $description = $_POST['description'];
                    $category = $_POST['category'];
					
					//if no category is added to photo insert to database category as null
					if($category == ""){
						$stmt = $dbh->prepare("INSERT INTO photos (path, description) VALUES (:path, :description)");
						$stmt->execute([':path' => $path, ':description' => $description]);
					} else {
						$stmt = $dbh->prepare("INSERT INTO photos (path, description, category) VALUES (:path, :description, :category)");
						$stmt->execute([':path' => $path, ':description' => $description, ':category' => $category]);
					}
					print '<p class="alert-positive">Plik ' . basename( $_FILES["fileToUpload"]["name"]). ' został dodany. Możesz teraz zobaczyć to zdjęcie w galerii.</p>';

				} else {
					print '<p class="alert-negative">Niestety wystąpił błąd. Spróbuj ponownie.</p>';
				}
			}
		}
	}

	?>
	
	<!-- form - add pictures -->
	<form action="/add" method="POST" enctype="multipart/form-data">
		<div class="form-group row">
			<label for="fileToUpload" class="col-sm-2 col-form-label">Wybierz zdjęcie z pliku albo upuść</label>
			<div class="col-sm-10">
				<input type="file" class="form-control-file" name="fileToUpload" id="fileToUpload" accept="image/*" >
			</div>
		</div>
		<div class="form-group row">
			<label for="description" class="col-sm-2 col-form-label">Podpis</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" name="description" id="description" placeholder="Podpis">
			</div>
		</div>
		<div class="form-group row">
			<label for="category" class="col-sm-2 col-form-label">Kategoria</label>
			<div class="col-sm-10">
				<select class="form-control" name="category" id="category" aria-descibedby="categoryHelp">
					<?php
						//add categories from database to the form as options to choose
						//select categories from database
						$stmt = $dbh->prepare("SELECT category FROM categories");
						$stmt->execute();
						
						//default option - no category
						print '<option selected></option>';
						
						while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
							print '<option>' . htmlspecialchars($row['category']) . '</option>';
						}
					?>
				</select>
				<small id="categoryHelp" class="form-text text-muted">
					<p>
						Puste pole oznacza brak kategorii. Zawsze możesz później przypisać zdjęcie do kategorii.
						<br/>
						Gdy chcesz dodać zdjęcie do kategorii, która nie istnieje, najpierw utwórz ją w zakładce "Kategorie".
					</p>
				</small>
			</div>
		</div>
		<button type="submit" class="btn btn-primary mb-3 float-right">Dodaj</button>
	</form>

</div>


