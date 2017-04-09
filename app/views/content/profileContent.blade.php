<div class="rightWrapper">
<section role="main">
	<nav id="subBar">
		<h2>Statistik</h2>
	</nav>
	<article role="statistics">
		<div class="rightSmallerWrapper fancy fadeIn">
			<div id="taskFrequency">
                <h4>Antalet avklarade uppgifter</h4>
                <div id="graph-description">
                    <p><span class="graph-color" style="background: rgba(255,186,115,1);"></span> Lätta</p>
                    <p><span class="graph-color" style="background: rgba(255,213,115,1);"></span> Medel</p>
                    <p><span class="graph-color" style="background: rgba(107,127,207,1);"></span> Svåra</p>
                    <p><span class="graph-color" style="background: rgba(93, 176, 198,1);"></span> Totalt</p>
                </div>
                <div class="clear"></div>
                <div id="graph-wrapper">
				    <canvas id="tfGraph"></canvas>
                </div>
                <div id="graph-settings">
                    <p>
                        <input checked type="radio" name="graph-show" id="graph-show-week" value="week"><label for="graph-show-week">Vecka</label><br>
                        <input type="radio" name="graph-show" id="graph-show-month" value="month"><label for="graph-show-month">Månad</label>
                    </p>
                </div>
                <div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>

        <!--<div id="level-statistics" class="fancy fadeIn">
            <h4>Rank och lösningspoäng</h4>
            <div class="clear"></div>
            <div id="level-info">
                <div class="level-info-box">
                    <p id="level-info-value">1</p>
                    <p id="level-info-text">Din nivå</p>
                </div>
                <div class="level-info-box">
                    <p id="level-info-value">70</p>
                    <p id="level-info-text">Poäng till nästa nivå</p>
                </div>
                <div class="level-info-box">
                    <p id="level-info-value">6</p>
                    <p id="level-info-text">Dina lösningspoäng</p>
                </div>
            </div>
        </div>-->
	</article>
</section>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script>
var newChart;
var myLineChart;
var data;
var options;
var ctx;
var datasets;
var weekEasy, weekMedium, weekHard, weekAll, weekLabels;
var monthEasy, monthMedium, monthHard, monthAll, monthLabels;
var data = {{ json_encode($data) }}
data = JSON.parse(data)

$(function() {
// Get context with jQuery - using jQuery's .get() method.

var $cvs = $('#tfGraph');

if (window.devicePixelRatio != 1) {
    $cvs.removeAttr('width')
    $cvs.removeAttr('height')
}

ctx = $("#tfGraph").get(0).getContext("2d")
// This will get the first returned node in the jQuery collection.
newChart = new Chart(ctx)


weekLabels = data.week_data[0];

weekEasy = {
    label: "E",
    fillColor: "rgba(255,186,115,0.2)",
    strokeColor: "rgba(255,186,115,1)",
    pointColor: "rgba(255,186,115,1)",
    pointStrokeColor: "#fff",
    pointHighlightFill: "#fff",
    pointHighlightStroke: "rgba(255,186,115,1)",
    data: data.week_data[1][0],
}

weekMedium = {
    label: "C",
    fillColor: "rgba(255,213,115,0.2)",
    strokeColor: "rgba(255,213,115,1)",
    pointColor: "rgba(255,213,115,1)",
    pointStrokeColor: "#fff",
    pointHighlightFill: "#fff",
    pointHighlightStroke: "rgba(255,213,115,1)",
    data: data.week_data[1][1]
}

weekHard = {
    label: "A",
    fillColor: "rgba(107,127,207,0.2)",
    strokeColor: "rgba(107,127,207,1)",
    pointColor: "rgba(107,127,207,1)",
    pointStrokeColor: "#fff",
    pointHighlightFill: "#fff",
    pointHighlightStroke: "rgba(107,127,207,1)",
    data: data.week_data[1][2]
}

weekAll = {
    label: "Totalt",
    fillColor: "rgba(93, 176, 198,0.2)",
    strokeColor: "rgba(93, 176, 198,1)",
    pointColor: "rgba(93, 176, 198,1)",
    pointStrokeColor: "#fff",
    pointHighlightFill: "#fff",
    pointHighlightStroke: "rgba(93, 176, 198,1)",
    data: data.week_data[1][3]
}

monthLabels = data.month_data[0]

monthEasy = {
    label: "E",
    fillColor: "rgba(255,186,115,0.2)",
    strokeColor: "rgba(255,186,115,1)",
    pointColor: "rgba(255,186,115,1)",
    pointStrokeColor: "#fff",
    pointHighlightFill: "#fff",
    pointHighlightStroke: "rgba(255,186,115,1)",
    data: data.month_data[1][0]
}

monthMedium = {
    label: "C",
    fillColor: "rgba(255,213,115,0.2)",
    strokeColor: "rgba(255,213,115,1)",
    pointColor: "rgba(255,213,115,1)",
    pointStrokeColor: "#fff",
    pointHighlightFill: "#fff",
    pointHighlightStroke: "rgba(255,213,115,1)",
    data: data.month_data[1][1]
}

monthHard = {
    label: "A",
    fillColor: "rgba(107,127,207,0.2)",
    strokeColor: "rgba(107,127,207,1)",
    pointColor: "rgba(107,127,207,1)",
    pointStrokeColor: "#fff",
    pointHighlightFill: "#fff",
    pointHighlightStroke: "rgba(107,127,207,1)",
    data: data.month_data[1][2]
}

monthAll = {
    label: "Totalt",
    fillColor: "rgba(93, 176, 198,0.2)",
    strokeColor: "rgba(93, 176, 198,1)",
    pointColor: "rgba(93, 176, 198,1)",
    pointStrokeColor: "#fff",
    pointHighlightFill: "#fff",
    pointHighlightStroke: "rgba(93, 176, 198,1)",
    data: data.month_data[1][3]
}

datasets = [weekEasy, weekMedium, weekHard, weekAll]

data = {
    labels: weekLabels,
    datasets: datasets
}

options = {

    ///Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines : true,

    //String - Colour of the grid lines
    scaleGridLineColor : "rgba(0,0,0,.05)",

    //Number - Width of the grid lines
    scaleGridLineWidth : 1,

    //Boolean - Whether the line is curved between points
    bezierCurve : true,

    //Number - Tension of the bezier curve between points
    bezierCurveTension : 0.4,

    //Boolean - Whether to show a dot for each point
    pointDot : true,

    //Number - Radius of each point dot in pixels
    pointDotRadius : 4,

    //Number - Pixel width of point dot stroke
    pointDotStrokeWidth : 1,

    //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
    pointHitDetectionRadius : 5,

    //Boolean - Whether to show a stroke for datasets
    datasetStroke : true,

    //Number - Pixel width of dataset stroke
    datasetStrokeWidth : 2,

    //Boolean - Whether to fill the dataset with a colour
    datasetFill : false,

    scaleBeginAtZero : true,

    responsive : true,

    scaleFontFamily: "'Open Sans', 'Helvetica', 'Arial', sans-serif",

    // Number - Scale label font size in pixels
    scaleFontSize: 11,

    // String - Scale label font weight style
    scaleFontStyle: 700,

    scaleFontColor: "#666",

    //String - A legend template
    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"

};

myLineChart = new Chart(ctx).Line(data, options)

//    myLineChart.destroy();
//    myLineChart = new Chart(ctx).Line(data, options);
//    myLineChart.datasets[0].points[2].value = 50;
//    myLineChart.update();
})

function updateChart() {
    myLineChart.destroy();
    myLineChart = new Chart(ctx).Line(data, options)
}

$("input[name=graph-show]:radio").change(function () {
    var val = $(this).val()

    if(val == "week") {
        myLineChart.destroy()
        datasets = [weekEasy, weekMedium, weekHard, weekAll]

        data = {
            labels: weekLabels,
            datasets: datasets
        }
        myLineChart = new Chart(ctx).Line(data, options)
    }
    else if(val == "month") {
        myLineChart.destroy()
        datasets = [monthEasy, monthMedium, monthHard, monthAll]

        data = {
            labels: monthLabels,
            datasets: datasets
        }
        myLineChart = new Chart(ctx).Line(data, options)
    }
})


</script>
