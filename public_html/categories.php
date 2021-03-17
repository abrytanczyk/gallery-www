<?php
	//protect access direclty to page
	if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }
?>

<div class="container">

  <h1 class="font-weight-light text-center text-lg-center mt-4 mb-0">Kategorie</h1>

  <hr class="mt-2 mb-5">
  
</div>

<div class="container mb-5">

	<div class="card mb-3 box-shadow">
	
		<div class="card-header">
			<h5>Dodaj nową kategorię</h5>
		</div>
		
		<div class="card-body">
		
			<?php
				//form action - add new category
			    if (isset($_POST['category'])) {
					
					$category = $_POST['category'];
					
					//check if new category is not empty
					if (!empty($category)) {
						
						//get data from database
						$stmt = $dbh->prepare("SELECT * FROM categories WHERE category = :category");
						$stmt->execute([':category' => $category]);
						$count = $stmt->rowCount();
						
						//if there is no record in database with this category add new category
						if($count==0) {	
							$stmt = $dbh->prepare("INSERT INTO categories (category) VALUES (:category)");
							$stmt->execute([':category' => $category]);
							
							print '<p class="alert-positive">Kategoria '. htmlspecialchars($category) .' została dodana.</p>';
						}
						else {
							print '<p class="alert-negative">Kategoria '. htmlspecialchars($category) .' już istnieje!</p>';
						}
					} else {
						print '<p class="alert-negative">Podane dane są nieprawidłowe.</p>';
					} 
				}
			?>

			<!-- form - add new category-->
			<form action="/categories" method="POST">
				<div class="form-group row">
					<label for="category" class="col-sm-2 col-form-label">Kategoria</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="category" id="category" placeholder="Kategoria">
					</div>
				</div>
				<button type="submit" class="btn btn-primary float-right">Dodaj</button>
			</form>
			
		</div>
	</div>
	
	<div class="card mb-3 box-shadow">
	
		<div class="card-header">
			<h5>Kategorie zdjęć</h5>
		</div>
	
		<div class="card-body">
		
			<?php
				//delete category
				if (isset($_GET['delete']) && intval($_GET['delete']) > 0) {

					$id = intval($_GET['delete']);
					$stmt = $dbh->prepare("SELECT * FROM categories WHERE id = :id");
					$stmt->execute([':id' => $id]);
					
					//check if that category exist - in case of going to page /categories/delete/number directly 
					if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
								
						$stmt = $dbh->prepare("DELETE FROM categories WHERE id = :id");
						$stmt->execute([':id' => $id]);
						
						print '<p class="alert-positive">Kategoria '. htmlspecialchars($row['category']) .' została usunięta.</p>';
							
						//set category to null, when photo belongs to deleted category
						$stmt = $dbh->prepare("UPDATE photos SET category = NULL WHERE category = :category");
						$stmt->execute([':category' => $row['category']]);
						 
					} else {
						print '<p class="alert-negative">Nie ma takiej kategorii!</p>';
					}
				}
				
				//schow categories in table
				//select categories from database
				$stmt = $dbh->prepare("SELECT id, category FROM categories");
				$stmt->execute();
				
				//check if there is no category
				$count = $stmt->rowCount();				
				if ($count == 0) {
					print '<p>Nie dodano jeszcze żadnej kategorii.</p>';
				} else {
					print '			
					<table class="table table-striped mt-3" id="my-categories">
						<tbody>';
						
						while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
							print '
							<tr>
								<td>' . htmlspecialchars($row['category']) . '</td>
								<td>	
									<a href="/categories/delete/'.$row['id'].'">
										<button type="button" class="btn btn-primary">Usuń</button>
									</a>
								</td>
							</tr>';
						}
							
					print'
						</tbody>			
					</table>';				
				}
			?>
		
		</div>
	</div>
</div>
