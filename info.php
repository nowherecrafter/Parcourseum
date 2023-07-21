<?php
/**
 * Page d'information sut la formation sélectionnée
 */
include 'include/php/functions.php';
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>R312-TP2</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
		<link rel="stylesheet" href="include/css/styles.css">
		<style>
			.chart {
			  width: 100%; 
			  min-height: 450px;
			}
		</style>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
	</head>
	<body>
	<?php include 'include/php/header.php';?>
	<button  onclick="window.location.href='index.php';">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
			<path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
		</svg> Retour 
	</button>
		<div class="container">
	<?php
		if ( isset($_GET['id']) ){
			$dbh = db_connection();
			$sth = $dbh->prepare('SELECT * FROM parcoursup
				WHERE id ='. $_GET['id']);
			$sth->execute();
			$result = $sth->fetchAll();
			
	?>
		
								
			<div class="row section"> 
				<!-- titre info -->
				<div class="col-12 header-info">
					<b><p> <?php echo $result[0]['g_ea_lib_vx']; ?> </p></b>
					<b><p> <?php echo $result[0]['lib_for_voe_ins']; ?> </p></b>
				</div>
			</div>

			<div class="row section align-items-center">
				<!-- c'est la carte #Dora -->
				<div class="col-6">
					<div class="row"> 
						<div id="regions_div">
							<iframe 
								src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3301928.7218768997!2d2.878210843520767!3d43.51159360466309!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12b676717e4bee3d%3A0x6b60cf7fd933e35d!2sIUT%20d&#39;Aix-Marseille%20site%20d&#39;Arles!5e0!3m2!1sfr!2sfr!4v1674303850387!5m2!1sfr!2sfr" 
								width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
							</iframe>

						</div>
					</div>
				</div>
						
						
						
				<!-- texte abc-->				 
				<div class="col-6 ">
					
					
					
					<div class="row"> 
						<p><b>Région : </b><?php echo $result[0]['region_etab_aff']; ?></p>
					</div>
					<div class="row"> 
						<p><b>Département : </b><?php echo $result[0]['dep_lib']; ?></p>
					</div>
					<div class="row"> 	
						<p><b> Académie : </b><?php echo $result[0]['acad_mies']; ?> </p>
					</div>
					<div class="row"> 	
						<p><b> Statut de l’établissement : </b><?php echo $result[0]['contrat_etab']; ?> </p>
					</div>
					<div class="row"> 	
						<p><b> Sélectivité : </b><?php echo $result[0]['select_form']; ?> </p>
					</div>

				</div>
			</div>

			<div class="row align-items-center text-center section"> 
				<!-- nombre de places -->
					<div class="col col-xl-6 place-dispo">
						<p><b> <?php echo $result[0]['capa_fin']; ?></b> places disponibles pour cette formation !</p>
					</div>
					
				<!-- cammenbert taux d'accès -->
				<div class="col col-xl-3">
					<span id="taux-acc"></span>
				</div>
				<div class="col col-xl-3">
					<span id="taux-bac"></span>
				</div>
				
			</div>

				<!--Graphique de barre-->
			<div class="row justify-items-center text-center section" style="margin:auto">
				<div class="col"> 
					<div id="candidats" class="chart"></div>
				</div>
			</div>

			
		</div>
		<button  onclick="window.location.href='index.php';">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
			<path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
		</svg> Retour 
	</button>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
		
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> <!-- Affichage des graphiques Google Chart -->
		<script type="text/javascript">
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChart);

			function drawChart() {

				var data = google.visualization.arrayToDataTable([
					["taux d'accès", 'en pourcentage'],
					['autorisé', <?php echo $result[0]['taux_adm_psup']; ?>],
					['non autorisé', <?php echo (100-$result[0]['taux_adm_psup']); ?>]
				]);
				var options = {
					pieSliceBorderColor : "#2a4b70",
					title: "Taux d'accès",
					width: 300,
					height: 300 ,
					legend: 'none' ,
					slices: {
							0: { color: '#2a4b70' },
							1: { color: '#ffffff' }
						}

				};

				var chart = new google.visualization.PieChart(document.getElementById('taux-acc'));

				chart.draw(data, options);
			}
			

		</script>
		
		
		<script type="text/javascript">
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChart);

			function drawChart() {

				var data = google.visualization.arrayToDataTable([
					["Repartition de bac", 'en pourcentage'],
					['Bac général', <?php echo $result[0]['taux_adm_psup_gen']; ?>],
					['Bac technologique', <?php echo $result[0]['taux_adm_psup_techno']; ?>],
					['Bac professionnel', <?php echo $result[0]['taux_adm_psup_pro']; ?>]
				]);
				var options = {
					title: "Repartition de bac",
					width: 300,
					height: 300 ,
					legend: 'none' ,
					slices: {
							0: { color: '#2a4b70' },
							1: { color: '#467DBD' },
							2: { color: '#7E9BBD' }
						}

				};

				var chart = new google.visualization.PieChart(document.getElementById('taux-bac'));

				chart.draw(data, options);
			}
			

		</script>
	

		<script type="text/javascript">
		google.charts.load("current", {packages:['corechart']});
		google.charts.setOnLoadCallback(drawChart);
		function drawChart() {
		  var data = google.visualization.arrayToDataTable([
			["Element", "Nombre", { role: "style" } ],
			["Total de candidats pour la formation", <?php echo $result[0]['voe_tot']; ?>, "#467DBD"],
			["Candidats ayant reçu la proposition", <?php echo $result[0]['prop_tot']; ?>, "#5B7088"],
			["Candidats ayant accepté la proposition", <?php echo $result[0]['acc_tot']; ?>, "#2A4B70"],
			["Capacité de la formation", <?php echo $result[0]['capa_fin']; ?>, "#7E9BBD"]
		  ]);

		  var view = new google.visualization.DataView(data);
		  view.setColumns([0, 1,
						   { calc: "stringify",
							 sourceColumn: 1,
							 type: "string",
							 role: "annotation" },
						   2]);

		  var options = {
			title: "Les candidats",
			height: 500,
			bar: {groupWidth: "95%"},
			legend: { position: "none" },
		  };
		  var chart = new google.visualization.BarChart(document.getElementById("candidats"));
		  chart.draw(view, options);
	  }
	  
		$(window).resize(function(){
		  drawChart();
		});

	  </script>
	</body>

	<?php
	} else {
		
		
	}
	?>