<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Highcharts</title>
</head>
<body>
    <div>
        幣別:
        <select name="category">
            @foreach($categories as $category)
            <option value="{{ $category->name }}">{{ $category->name }}</option>
            @endforeach
        </select>
        年份:
        <select name="year">
            @foreach($years as $year)
            <option value="{{ $year }}">{{ $year }}</option>
            @endforeach
        </select>
        月份:
        <select name="month">
            @for ($i = 1; $i <= 12; $i++)
            <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
        <button type="button" onclick="search()">查詢</button>
        <button type="button" onclick="closeHighcharts()">關閉</button>
    </div>
    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    <script src="https://code.jquery.com/jquery-1.11.3.js"></script>
    <script src="https://code.highcharts.com/highcharts.src.js"></script>
    <script>
    function makeHighcharts(categories, immediateBuys) {
        Highcharts.chart('container', {
            chart: {
                type: 'line'
            },
            title: {
                text: '大標題'
            },
            subtitle: {
                text: '子標題'
            },
            xAxis: {
                categories: categories
            },
            yAxis: {
                title: {
                    text: 'Temperature (°C)'
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                }
            },
            series: [{
                name: immediateBuys.title,
                data: immediateBuys.values
            }]
        });
    }
    function closeHighcharts() {
        Highcharts.chart('container', {
            title: {
                text: ''
            },
            noData: {
                position: {
                    align: 'center',
                    verticalAlign: 'middle',
                    x: 0,
                    y: 0
                },
                style: {
                    color: '#666666',
                    fontSize: '12px',
                    fontWeight: 'bold'
                },
                useHTML: false
            }
        });
    }
    function initHighcharts(category, year, month) {
        $.ajax({
            url: '/api/currency/highcharts',
            type: 'post',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'category': category,
                'year': year,
                'month': month
            },
            dataType: 'json',
            success:function (response) {
                console.log(response);
                if (response.status) {
                    makeHighcharts(response.categories, response.immediateBuys);
                }
            },
            error:function (xhr) {
                console.log(xhr.responseText());
                closeHighcharts();
            }
        });
    }
    function search() {
        let category = $('select[name="category"]').val();
        let year = $('select[name="year"]').val();
        let month = $('select[name="month"]').val();
        initHighcharts(category, year, month);
    }
    </script>
</body>
</html>