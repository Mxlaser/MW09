function AfficherGraphe()
{
  //---
  var choix = document.getElementsByName('etat[]');
  var idvol = document.getElementsByName('idvol');
  var id = idvol[0].value;
  //console.debug(choix);
  //console.debug(idvol);
  //console.debug(idvol[0].value);
  //console.debug(id);

  for(let i = 0; i < choix.length; i++)
  {
    if(choix[i].checked)
    {
      //console.debug(choix[i].value);
      var etat = choix[i].value;
      break;
    }
  }
  //console.debug(etat);

//------GRAPHE
  //Création dun graph dans la div "container_graphe"
  var chart = am4core.create("container_graphe", am4charts.XYChart);

  //Les données à afficher
  chart.dataSource.url = "rest.php/graph_test/"+id+"/"+etat;

  //Définition des axes
  var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
  categoryAxis.dataFields.category = "idetat";
  var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

  valueAxis.title.text = "Etat";

  //Affectation des séries
  var series = chart.series.push(new am4charts.ColumnSeries());
  series.dataFields.valueY = etat;
  series.dataFields.categoryX = "idetat";
  series.name = "Etat";
  series.strokeWidth = 3;
  series.tensionX = 0.7;
  series.bullets.push(new am4charts.CircleBullet());
  series.columns.template.tooltipText = "Series: {name}\nCategory: {categoryX}\nValue: {valueY}";
  series.columns.template.fill = am4core.color("#ffffff"); // fill
}
