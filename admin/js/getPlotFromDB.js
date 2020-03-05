var ctx = document.getElementById('admcreChart').getContext('2d');
var admcrechart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',
    // The data for our dataset
    data: {
        labels: [<?php echo $dates?>],
datasets: [{
    label: 'My First dataset',
    backgroundColor: 'rgba(69, 92, 105,0.5)',
    borderColor: '#45ff73',
    data: [<?php echo $opa1_all ?>]
},{
    label: 'My First dataset second',
        backgroundColor: 'blue',
        borderColor: 'blue',
        data: [<?php echo $opa2_all ?>]
}]
},
// Configuration options go here
options: {
    legend:{
        display:true,
            position: 'bottom',
            labels:{
            fontColor:'red',
        }
    },
    tooltips:{
        mode:'x',
    },
    scales:{
        yAxes:[{
            ticks:{
                beginAtZero:false,
            }
        }],
            xAxes:[{
            ticks: {
                autoSkip:true,
                maxTicks: 2,
            }
        }]
    }
}
});