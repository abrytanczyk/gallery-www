<?php
	//protect access direclty to page
	if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }
?>

<div class="container">

  <h1 class="font-weight-light text-center text-lg-center mt-4 mb-0">Galeria</h1>

  <hr class="mt-2 mb-5">
  
</div>

<?php
	// edit selected photo
	if (isset($_GET['edit']) && intval($_GET['edit']) > 0) {
		print 
		'<div class="container mb-5">';
		
			//get id of selected photo
			$id = intval($_GET['edit']);
			
			//form action - change data if posted
			if (isset($_POST['category']) && isset($_POST['description'])) {
				$category = $_POST['category'];
				$description = $_POST['description'];
				if (!empty($description)) {
					
					//check if no category is choosen, then column category in this photo set to null 
					if($category == ""){
						$stmt = $dbh->prepare("UPDATE photos SET description = :description, category = NULL WHERE id = :id");
						$stmt->execute([':id' => $id, ':description' => $description]);
					} else {
						$stmt = $dbh->prepare("UPDATE photos SET description = :description, category = :category WHERE id = :id");
						$stmt->execute([':id' => $id, ':description' => $description, ':category' => $category]);
					}

					print '<p class="alert-positive">Zmiany zostały zapisane.</p>';
					
				} else {
					print '<p class="alert-negative">Podane dane są nieprawidłowe.</p>';
				} 
			}
			
			//select photo from database
			$stmt = $dbh->prepare("SELECT id, path, description, category FROM photos WHERE id = :id");
			$stmt->execute([':id' => $id]);
			
			//show selected photo
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				print '
				<div class="card card-edit-photo mb-4 box-shadow">
					<img class="card-img-top img-edit-photo" src="'.$row['path'].'">
					<div class="card-body">';
					
						//form - edit photo
						print'
						<form action="/main/edit/' . $row['id'] . '" method="POST">
							<div class="form-group row">
								<label for="description" class="col-sm-2 col-form-label">Podpis</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="description" id="description" placeholder="Podpis" value="'.htmlspecialchars($row['description']).'">
								</div>
							</div>
							<div class="form-group row">
								<label for="category" class="col-sm-2 col-form-label">Kategoria</label>
								<div class="col-sm-10">
									<select class="form-control" name="category" id="category" aria-descibedby="categoryHelp">';
									
										$cat = $row['category'];
										
										//add categories from database to the form as options to choose
										//default option for category - previous category or no category if case of null
										print '<option selected>' . htmlspecialchars($cat) . '</option>';
										
										//select categories from database
										$stmt = $dbh->prepare("SELECT category FROM categories");
										$stmt->execute();
										
										while ($row2 = $stmt->fetch(PDO::FETCH_ASSOC)) {
											if ($row2['category'] != $cat){
												print '<option>' . htmlspecialchars($row2['category']) . '</option>';
											}
										}
										//if previous category is not null, add no category option in the end
										if ($cat != NULL){
											print '<option></option>';
										} 
									print '</select>
									<small id="categoryHelp" class="form-text text-muted">
										<p>Puste pole oznacza brak kategorii.</p>
									</small>
								</div>
							</div>
							<button type="submit" class="btn btn-primary mt-3 ml-3 float-right">Zapisz zmiany</button>
							<a href="/main" class="btn btn-light mt-3 float-right active" role="button" aria-pressed="true">Powrót do poprzedniej strony</a>
						</form>
					</div>
				</div>';
				
			} else {
				print '<p class="alert-negative">Nie ma takiego zdjęcia!</p>';
			}
		print '</div>';
		
	}
	
	// main page
	else {
		
		//delete photo
		if (isset($_GET['delete']) && intval($_GET['delete']) > 0) {
			
			//check if this photo exists in database
			$id = intval($_GET['delete']);
			$stmt = $dbh->prepare("SELECT * FROM photos WHERE id = :id");
			$stmt->execute([':id' => $id]);

			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				
				//try to delete from directory
				if(unlink('.'.$row['path'])) {
					
					//delete from database
					$stmt = $dbh->prepare("DELETE FROM photos WHERE id = :id");
					$stmt->execute([':id' => $id]);
					
					print '<div class="container">
					<p class="alert-positive">Zdjęcie zostało usunięte.</p>
					</div>';
				}
				else {
					print '<div class="container">
					<p class="alert-negative">Niestety, coś poszło nie tak.</p>
					</div>';
				}

			} else {
				print '<div class="container">
				<p class="alert-negative">Nie ma takiego zdjęcia!</p>
				</div>';
			}
		} 
		
		// main page - show photos
		print '
		<div class="album mb-5">
			<div class="container">
				
				<div class="row">';
				
					//select photos from database and show them
					$stmt = $dbh->prepare("SELECT id, path, description, category FROM photos");
					$stmt->execute();
					
					//check if there is no photo
					$count = $stmt->rowCount();
					if ($count == 0) {
						print '
						<div class="container">
							<p>Nie dodano jeszcze żadnego zdjęcia.</p>
						</div>';
					} else {
						while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
							//for each photo show photo, description, category, create links to edit and delete
							print '
								<div class="card card-gallery mb-4 box-shadow">
									<!-- link to start slideshow gallery -->
									<a href=" '.$row['path'].' " data-fancybox="images" data-caption="'.htmlspecialchars($row['description']).'">
										<img class="card-img-top img-gallery" src="'.$row['path'].'">
									</a>
									<div class="card-body">
										<p class="card-text">'.htmlspecialchars($row['description']).'</p>
										<div class="d-flex justify-content-between align-items-center">
											<div class="btn-group">
												<a href="/main/edit/'.$row['id'].'">
													<button type="button" class="btn btn-sm btn-outline-secondary">Edycja</button>
												</a>
												<a href="/main/delete/'.$row['id'].'">
													<button type="button" class="btn btn-sm btn-outline-secondary ml-1">Usuń</button>
												</a>
											</div>
										</div>
									</div>
									<div class="card-footer">';
									//check if photo is added to category
									if($row['category'] != NULL){
										print'
											<small class="text-muted">Kategoria: '.htmlspecialchars($row['category']).'</small>';
									} else {
										print '<small class="text-muted">Brak kategorii</small>';
									}
									print '	
									</div>	
								</div>';
						}
					}
					print '
				</div>
			</div>
		</div>';
	}
?>

<script>

//protect pictures from download
$("img").bind ("contextmenu", function(e) {
	return false;
});

$("img").mousedown (function(e) {
	return false;
});


//slideshow gallery
$('[data-fancybox="images"]').fancybox({
	loop: true,
	buttons: [
    //"zoom",
    //"share",
    "slideShow",
    "fullScreen",
    //"download",
    "thumbs",
    "close"
	],
	protect: true, //protect pictures from download
	animationEffect: "zoom",
	transitionEffect: "tube"
})

</script>

