(function ($, window, Drupal) {

  //var window = window;

  'use strict';

  Drupal.behaviors.bar_exchange_charts = {
    attach: function () {
      google.charts.load('current', {packages: ['corechart', 'line']});
      google.charts.setOnLoadCallback(drawCurveTypes);
    }
  };

})(jQuery, window, Drupal);


function drawCurveTypes () {
  var data = new google.visualization.DataTable();

  jQuery.getJSON( "/pos/feed", function(json) {

    var beers = [];
    var rows = [];

    var dateFormatter = new google.visualization.DateFormat({formatType: 'short'});

    data.addColumn('date', 'Date');
    data.addColumn('number', 'Price');
    jQuery.each( json[0][1], function(date, price) {
      console.log(date);
      var humandate = dateFormatter.formatValue(new Date(1394104654000));
      console.log(new Date(1394104654000));
      rows.push([humandate, price]);
    });

    console.log(rows)
    data.addRows(rows);
    console.log(beers);
  });

  var options = {
    hAxis: {
      title: 'Time'
    },
    vAxis: {
      title: 'Price'
    },
    series: {
      1: {curveType: 'function'}
    }
  };

  var chart = new google.visualization.LineChart(document.getElementById('chart'));
  chart.draw(data, options);
}

var getJSON = function(url, callback) {
  var xhr = new XMLHttpRequest();
  xhr.open('GET', url, true);
  xhr.responseType = 'json';
  xhr.onload = function() {
    var status = xhr.status;
    if (status === 200) {
      callback(null, xhr.response);
    } else {
      callback(status, xhr.response);
    }
  };
  xhr.send();
};