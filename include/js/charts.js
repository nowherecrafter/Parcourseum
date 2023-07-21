// Requête pour obtenir les données du serveur
const httpRequest = new XMLHttpRequest()
httpRequest.onreadystatechange = () => {
    if (httpRequest.readyState === XMLHttpRequest.DONE) {
        if (httpRequest.status === 200) {
            // On a reçu la réponse du serveur, on peut générer la graphique
            const response = JSON.parse(httpRequest.responseText)
            google.charts.load("current", { "packages": ["corechart"], "language": "fr" })
            google.charts.setOnLoadCallback(function() {
                drawChart(response)
            })
        } else {
            console.log("Erreur de connexion au serveur !")
        }
    }
}
httpRequest.open('POST', 'include/php/data.php', true)
httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
// Requête SQL
const query = "SELECT acad_mies, sum(capa_fin) AS places FROM parcoursup GROUP BY acad_mies ORDER BY places DESC"
httpRequest.send(`query=${encodeURIComponent(query)}`)

/**
 * Fonction qui fait deux graphiques avec les données du serveur
 */
function drawChart(result) {
    // Données de la graphique
    let data = new google.visualization.DataTable()
    data.addColumn('string', 'Académie')
    data.addColumn('number', 'Places')
    // Utiliser les données du serveur
    let dataArray = []
    for (const item of result) {
        dataArray.push([item.acad_mies, Number(item.places)])
    }
    data.addRows(dataArray)

    let piechart_options = {
        title: "Pie Chart : Nombre de places dans chaque académie",
        height: 600
    }
    let piechart = new google.visualization.PieChart(document.getElementById('piechart_div'))
    piechart.draw(data, piechart_options)

    let barchart_options = {
        title: "Barchart : Nombre de places dans chaque académie",
        height: 600,
        legend: 'none'
    }
    let barchart = new google.visualization.BarChart(document.getElementById('barchart_div'))
    barchart.draw(data, barchart_options)
}