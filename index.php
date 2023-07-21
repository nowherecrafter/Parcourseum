<?php
/**
 * Barre de recherche de formations avec affichage d'informations
 * 
 * L'utilisateur choisir dans le formulaire une formation.
 * Le formulaire envoie cette information vers la même page, et on
 * affiche le nombre de places et le nombre de candidats.
 */
include 'include/php/functions.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>SAE 303 PAGE FORMULAIRE</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="include/css/styles.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
	<style>
		body {
		font-family: 'Lato', sans-serif;
}
	</style>
	
</head>
<body>

<?php include 'include/php/header.php';?>
	<div class="container">
		<div class="row ensemble">
			<form method="post" action="include/php/search-validation.php">
				<fieldset>
					<h2>Rechercher une formation</h2>
							
					<div class="form-group" >
						<label for="filiere">Choisis une formation&nbsp;:</label>
						<input list="formations" name="formation" type="text" class="form-control" id="filiere" placeholder="Ex: BUT - Information communication - Parcours information numérique dans les organisations">
						<datalist id="formations">
					<?php
					/* On remplit l'élément datalist (les suggestions pour le formulaire)
					 * avec les noms des formations
					 */
					$dbh = db_connection();
					$sth = $dbh->prepare('SELECT lib_comp_voe_ins FROM parcoursup
						ORDER BY lib_comp_voe_ins ASC');
					$sth->execute();
					$result = $sth->fetchAll();
					foreach ($result as $row) {
						echo '<option value="' . $row['lib_comp_voe_ins'] . '">';
					}
					?>
							</datalist>
						<input class="button-confirmer" type="submit" value="Confirmer">
				
			
			
					<?php
					/* Si $_GET['formation'] est défini, ça veut dire qu'on est arrivé dans cette page
					 * après avoir sélectionné une formation. Dans ce cas, on affiches les information
					 * de cette formation
					 */
					if (isset($_GET['formation'])) {
						$sth = $dbh->prepare('SELECT capa_fin, voe_tot FROM parcoursup
						WHERE lib_comp_voe_ins = :formation');
						$donnees = [
							"formation" => $_GET['formation']
						];
						$sth->execute($donnees);
						$result = $sth->fetchAll();
						if (count($result) == 1) {
							// Si on obtient un résultat, afficher les informations
							echo "<h2>" . $_GET['formation'] . "</h2>";
							echo "<p>" . $result[0]['voe_tot'] . " candidats pour " . $result[0]['capa_fin'] . " places</p>";
						}
					} 
					?>
					</div>
				</fieldset>
			</form>
		</div>	
		<!-- Affichage sur la page d'accueil le formulaire -->
		<div class="row">
			<div class="col">
				<form method="post" action="resultats.php">
					<fieldset>	
						<br><div class="row section">
							<div class="form-group">
								<b><span>Sélectionnez le type de formation :</span></b>     <!-- Ici la section pour séléctionnez le type de formation -->

							<?php // Importation de la base de données avec les classes correspondant aux résultat souhaité
								$sth = $dbh->prepare('SELECT DISTINCT fili FROM parcoursup ORDER BY fili'); 
								$sth->execute();
								$result = $sth->fetchAll();
								
								foreach($result as $r) {
							?> 
							<!-- Affichage sur la page d'accueil en l'occurence ici la fillière depuis les données de la base -->
								<br><label for="<?php echo $r['fili']; ?>">
									<input type="checkbox" id="<?php echo $r['fili']; ?>" name="type_formation[]" value="<?php echo $r['fili']; ?>" >
									<?php echo $r['fili']; ?>
								</label>
							 <?php 
								} 
							?>
					
							</div>
						</div> 
						
						<!-- Ici une nouvelle section pour le statut de l'établissement présentant deux choix (publique ou privée) -->
						<div class="row section">
							<div class="form-group">
								<b><span>Sélectionnez le statut de l'établissement :</span></b>
								
								
								<br><label for="statut_etablissement">
									<input type="checkbox" name="statut_etablissement[]" value="Public" />
									Publique
								</label>
								
								<br><label for="statut_etablissement">
									<input type="checkbox" name="statut_etablissement[]" value="Privé" />
									Privée
								</label>
											
							</div>
						</div>
						
						<div class="row section">
							<div class="form-group">
								<b><span>Sélectionnez la région :</span></b>
											
											
						<?php // Ici importation des données de la région depuis la base de données
							$sth = $dbh->prepare('SELECT DISTINCT region_etab_aff FROM `parcoursup`ORDER BY region_etab_aff;');
							$sth->execute();
							$result = $sth->fetchAll();
							
							foreach($result as $r) {
						?>
								<br><label for="<?php echo $r['region_etab_aff']; ?>">
									<input type="checkbox" id="<?php echo $r['region_etab_aff']; ?>" name="region[]" value="<?php echo $r['region_etab_aff']; ?>" >
									<?php echo $r['region_etab_aff']; ?>
								</label>
						 <?php 
							} 
						?>
				 
							</div>
						</div>
						
						<!-- Affichage du taux d'accès dans la page d'acceuil dans une nouvelle section -->
						<div class="row justify-items-center text-center taux_dacces">
							<div class="form-group">
								<b><span>Sélectionnez un taux d'accès minimal :</span></b>
								<br><label for="taux_acces">
									0%
									<input type="range" name="taux_acces" min="0" max="100" step="1" value="0" oninput="taux_acces_output.value=parseInt(taux_acces.value)"/>
									100%
								</label><br />
								<b><output name="taux_acces_output">0</output>%</b>
										
							   
							</div>
						</div>
						
						<div class="row"> <!-- Le bouton Rechercher avec du style CSS -->
							<input class="button-rechercher" type="submit" value="Rechercher">
						</div>	
					</fieldset>
				</form>
			</div>
		</div>
</div>	


		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"> </script>

</body>
