/*=========================================================================================
    File Name: realtime.js
    Description: Flot realtime chart
    ----------------------------------------------------------------------------------------
    Item Name: Stack - Responsive Admin Theme
    Version: 1.0
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

// Realtime chart
// ------------------------------
$(window).on("load", function(){

    var time = new Date();

    var data = [{
        x: [time],
        y: [0],
        mode: 'lines',
        line: {color: '#80CAF6'}
    }];

    var defaultPlotlyConfiguration = { modeBarButtonsToRemove: ['sendDataToCloud', 'autoScale2d', 'hoverClosestCartesian', 'hoverCompareCartesian', 'lasso2d', 'select2d'], displaylogo: false, showTips: true };

    Plotly.plot('graph', data,null,defaultPlotlyConfiguration);


    getSystemLoadAVG(function($load){
        var time = new Date();

        var update = {
            x:  [[time]],
            y: [[$load]]
        }

        var olderTime = time.setMinutes(time.getMinutes() - 1);
        var futureTime = time.setMinutes(time.getMinutes() + 1);

        var minuteView = {
            xaxis: {
                type: 'date',
                range: [olderTime,futureTime]
            }
        };

        Plotly.relayout('graph', minuteView);
        Plotly.extendTraces('graph', update, [0])

        if(cnt === 100) clearInterval(interval);

    });




    var cnt = 0;

    var interval = setInterval(function() {

        getSystemLoadAVG(function($load){
            var time = new Date();

            var update = {
                x:  [[time]],
                y: [[$load]]
            }

            var olderTime = time.setMinutes(time.getMinutes() - 1);
            var futureTime = time.setMinutes(time.getMinutes() + 1);

            var minuteView = {
                xaxis: {
                    type: 'date',
                    range: [olderTime,futureTime]
                }
            };

            Plotly.relayout('graph', minuteView);
            Plotly.extendTraces('graph', update, [0])

            if(cnt === 100) clearInterval(interval);

        });

    }, 30000);


});