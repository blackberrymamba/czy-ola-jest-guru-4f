var chartInstance = null;
$(document).ready(function () {
    fillChart();
    var form = $("#f1");
    $(":submit", form).click(function (e) {
        $(form).find('input[name="sayyes"]').remove();
        $(form).find('input[name="sayno"]').remove();
        if ($(this).attr('name')) {
            $(form).append(
                    $("<input type='hidden'>").attr({
                name: $(this).attr('name'),
                value: $(this).attr('value')
            })
                    );
        }
    });

    $(form).submit(function (e) {
        var url = "/api/v1/Vote/";
        $("#f1").hide();
        $(".loader").show();
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: function (data) {
                $("#info").addClass("info").removeClass("error").text(data.message.text);
                $("#f1").remove();
                $(".loader").hide();
                fillChart();
            },
            error: function (data) {
                $("#f1").show();
                $(".loader").hide();
                if (data.responseJSON){
                    $("#info").addClass("error").removeClass("info").text(data.responseJSON.error.text);
                }else if(data.statusText){
                    $("#info").addClass("error").removeClass("info").text(data.statusText);
                }
            }
        });
        e.preventDefault();
    });
});

function fillChart() {
    function formatResult(value) {
        return SiteConfig.results_text;
    }
    $.getJSON("/api/v1/GetGuruLevels/", function (json) {
        if (!$.isEmptyObject(json)) {
            var data = json.slice(-1)[0];
            if (data.total > 0) {
                var value = Math.round((data.value * 100), 2);
                var result = SiteConfig.results_text.replace("${value}", value);
                $("#today").text(result);
            } else {
                $("#today").text(SiteConfig.empty_results_text);
            }
        }

        var chartjsData = [];
        var labelsData = [];
        for (var i = 0; i < json.length; i++) {
            chartjsData.push(parseFloat(json[i].value));
            labelsData.push(json[i].date);
        }

        var barChartData = {
            labels: labelsData,
            datasets: [{
                    fillColor: "rgba(220,280,220,0.5)",
                    strokeColor: "rgba(220,220,220,1)",
                    data: chartjsData

                }]
        };
        var ctx = document.getElementById("canvas");
        if (chartInstance !== null)
            chartInstance.destroy();

        chartInstance = new Chart(ctx, {
            type: 'line',
            data: barChartData,
            options: {
                responsive: false,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                            type: 'linear',
                            position: 'left',
                            ticks: {
                                beginAtZero: true,
                                callback: function (value, index, values) {
                                    return Math.round((value * 100), 0) + "%";
                                }
                            }
                        }]
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            return Math.round((tooltipItem.yLabel * 100), 0) + "%";
                        }
                    }
                }
            }
        });
    });
}