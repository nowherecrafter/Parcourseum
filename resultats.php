<?php
/**
 * Ici, nous avons la page qui contient les résultats du formulaire
 */
include 'include/php/functions.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>SAE 303 PAGE RÉSULTATS</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="include/css/styles.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
	<style>
		body {
		font-family: 'Lato', sans-serif;
}
	.progress {
				height: 5px;
		}
	</style>
	
</head>
<body>
	<?php include 'include/php/header.php';?>
	<button  onclick="window.location.href='index.php';">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
			<path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
		</svg> Retour 
	</button>
	<?php
		/* On crée une requête SQL dynamique. Les conditions sont rajoutées à partir du formulaire*/
		$dbh = db_connection();
			
		$query = 'SELECT * FROM parcoursup ';
		
		//type de formation
		if ( !empty($_POST["type_formation"]) ){ //vérification si l'utilisateur a choisi au moins 1 élément
				$query = $query . 'WHERE ';	
			
			
			
			if ( count($_POST["type_formation"]) == 1 ){ //si l'utilisateur a sélectionné qu'un seul élément
				$query = $query . '(fili="'. $_POST["type_formation"][0] .'") ';
				
			} 
			else { //si l'utilisateur a sélectionné plusieurs éléments
				foreach ($_POST["type_formation"] as $tf){
					switch ($tf) {
						case $_POST["type_formation"][0]:
							$query = $query . '(fili="'. $tf .'" OR '; //le premier élément
							break;
						case $_POST["type_formation"][count($_POST["type_formation"]) - 1]: 
							$query = $query . 'fili="'. $tf .'") '; //le dernier élément
							break;
						default:
						   $query = $query . 'fili="'. $tf .'" OR '; //les éléments au milieu
					}				
				}				
			}	
		}
		
		//statut de l'établissement
		if ( !empty($_POST["statut_etablissement"]) ){ //vérification si l'utilisateur a choisi au moins 1 élément
			if ($query == 'SELECT * FROM parcoursup '){ //vérification si c'est le premier critère sélectionné par l'utilisateur
				$query = $query . 'WHERE ';	
			} else {
				$query = $query . 'AND ';	
			}
			
			
			if ( count($_POST["statut_etablissement"]) == 1 ){ //si l'utilisateur a sélectionné qu'un seul élément
				$query = $query . '(contrat_etab LIKE "'. $_POST["statut_etablissement"][0] .'%") ';
				
			} 
			else {
				foreach ($_POST["statut_etablissement"] as $se){
					switch ($se) {
						case $_POST["statut_etablissement"][0]: 
							$query = $query . '(contrat_etab LIKE "'. $se .'%" OR '; //le premier élément
							break;
						case $_POST["statut_etablissement"][count($_POST["statut_etablissement"]) - 1]:
							$query = $query . 'contrat_etab LIKE "'. $se .'%") '; //le dernier élément
							break;
						default:
						   $query = $query . 'contrat_etab LIKE "'. $se .'%" OR '; //les éléments au milieu (si on rajoute plus au formulaire)
					}				
				}				
			}	
		}
		
		//région de l'établissement
		if ( !empty($_POST["region"]) ){ //vérification si l'utilisateur a choisi au moins 1 élément
			if ($query == 'SELECT * FROM parcoursup '){ //vérification si c'est le premier critère sélectionné par l'utilisateur
				$query = $query . 'WHERE ';	
			} else {
				$query = $query . 'AND ';	
			}
			
			
			if ( count($_POST["region"]) == 1 ){ //si l'utilisateur a sélectionné qu'un seul élément
				$query = $query . '(region_etab_aff="'. $_POST["region"][0] .'") '; 
				
			} 
			else {
				foreach ($_POST["region"] as $reg){
					switch ($reg) {
						case $_POST["region"][0]:
							$query = $query . '(region_etab_aff="'. $reg .'" OR '; //le premier élément
							break;
						case $_POST["region"][count($_POST["region"]) - 1]:
							$query = $query . 'region_etab_aff="'. $reg .'") '; //le dernier élément
							break;
						default:
						   $query = $query . 'region_etab_aff="'. $reg .'" OR '; //les éléments au milieu 
					}				
				}				
			}	
		}
		
		
		//taux d'accès minimal
		if ( isset($_POST["taux_acces"]) ){ //vérification si l'utilisateur a choisi au moins 1 élément
			if ($query == 'SELECT * FROM parcoursup '){ //vérification si c'est le premier critère sélectionné par l'utilisateur
				$query = $query . 'WHERE ';	
			} else {
				$query = $query . 'AND ';	
			}
			$query = $query . 'taux_adm_psup >= '. $_POST["taux_acces"] .' ';
		}
		
		//echo $query; //pour tester la requête finale
		
		$sth = $dbh->prepare($query);
		$sth->execute();
		$result = $sth->fetchAll();
		
		foreach ($result as $row) {
	?>		
	<div class="container boxresult"> 		<!-- Contient tout les éléments dans une section des résutats proposés -->
		<div class="row">
			<div class="col">
			
				<div class="row">
					<div class="col-12 col-xl-4 offset-1">
						<p class="button-contrat"><?php echo $row['contrat_etab']; ?></p>  <!-- Ici s'il est public ou privé -->
					</div>
				</div>
				<div class="row lien-school"> <!-- Sous forme de lien l'info de l'établissement -->
					<div class="col-10 justify-content-center">

							<a href="info.php?id=<?php echo $row['id']; ?>" id="">
								<div class="row">	
									<span class=""><?php echo $row['g_ea_lib_vx']; ?></span>
								</div>					
								<div class="row">
									<div class="col-10 justify-content-center">
										<span class=""><?php echo $row['lib_for_voe_ins']; ?></span>
									</div>
								</div>		
							</a>
					</div>	
				</div>
				

				<div class="row"> <!-- Affichage de la région et département -->
					<div class="col-4 offset-1">
						<p class="region_etab_aff">
								<span class="">Région&nbsp;<?php echo $row['region_etab_aff']; ?></span>
						</p>
							
						<div class="col-4">	
							<p class="dep_lib">
								<span class="">Département&nbsp;<?php echo $row['dep_lib']; ?></span>
							</p>
						</div>
					</div>		
				</div>



				<div class="row"> <!-- Taux d'accès avec le popover-->
					<div class="col-4 offset-1">	
						<p class="">
							<span class="">Taux d’accès&nbsp; : <?php echo $row['taux_adm_psup']; ?> &#x25;</span>
							<button type="button" class="btn btn-secondary" data-bs-container="body" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-placement="bottom" title="Le taux d'accès de la formation" data-bs-content="Le taux d'accès est celui de la session 2022.

							Le taux d'accès est le pourcentage des candidats à une formation et qui, en phase principale, avaient un rang dans le classement leur permettant de recevoir une proposition d'admission pour cette formation.
							Un taux d'accès à 100% signifie que tous les candidats qui voulaient accéder à cette formation, ont reçu une proposition d'admission en phase principale.">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
									<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
									<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
								</svg>	
						</button>
						</p>
					</div>
				</div>
				

				<div class="row"> <!-- Bouton Voir la formation avec du CSS associer à la classe button-formation dans le fichier style.css-->
					<div class="col-9 col-xl-4 justify-content-center">
						<a class="button-formation" href="info.php?id=<?php echo $row['id']; ?>">
							Voir la formation
						</a>
					</div>
				</div>
				
			</div>
		</div>
	</div>
			
	<?php		
		}
	?>
	
	<button  onclick="window.location.href='index.php';">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
			<path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
		</svg> Retour 
	</button>
	
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"> </script>
	<script>
		const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
		const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
	</script>
</body>
