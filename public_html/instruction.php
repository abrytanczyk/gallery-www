<?php
	//protect access direclty to page
	if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }
?>

<div class="container">

  <h1 class="font-weight-light text-center text-lg-center mt-4 mb-0">Instrukcja</h1>

  <hr class="mt-2 mb-5">
  
</div>


<div class="container mb-5">

	<div class="card mb-3 box-shadow">
	
		<div class="card-header">
			<h5>Podstrony</h5>
		</div>
		
		<div class="card-body">
			<ul class="pages-list">
				<li>
					<p class="longer-text">
						Strona główna -  tutaj znajdują się miniaturki wszystkich zdjęć z galerii. Wszystkie miniaturki mają wymiar 350 x 225 px. Klikając na zdjęcie przechodzimy 
						w tryb pokazu slajdu ze wszystkich zdjęć. Oczywiście pokaz rozpoczyna się od klikniętego przez nas zdjęcia. Każde zdjęcie można edytować, czyli zmienić 
						podpis oraz przypisać je do innej kategorii lub też pozostawić je bez kategorii. Przycisk "Edycja" umożliwia przejście do trybu edycji zdjęcia. Klikając 
						w przycisk "Usuń" - usuwamy na stałe zdjęcie z galerii.
					</p>
				</li>
				<li>
					<p class="longer-text">
						Dodaj zdjęcie - w tej zakładce można dodać nowe zdjęcie do galerii. Wystarczy wybrać zdjęcie z pliku albo upuścić je na wyznaczony do tego obszar 
						(funkcjonalność drag & drop). Dozwolone formaty plików to: JPG, JPEG, PNG, GIF. Podpis zdjęcia jest wymagany, natomiast przypisanie do kategorii już nie. 
						Puste pole kategorii oznacza brak kategorii.
					</p>
				</li>
				<li>
					<p class="longer-text">
						Kategorie - tutaj można dodać nową kategorię, bądź usunąć istniejącą. Dodanie kategorii o tej samej nazwie, co kategoria już istniejąca jest zabronione.
						W przypadku usunięcia kategorii, do której przypisane są zdjęcia, zostają one bez kategorii.
					</p>
				</li>
				<li>
					<p class="longer-text">
						Instrukcja - czyli strona, na której się właśnie znajdujesz ;)
					</p>
				</li>
			</ul>
		</div>
		
	</div>
	
</div>