<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-body">
                        幣別:
                        <select name="category" v-model="category">
                            <option v-for="name in categories" v-bind:value="name">
                                {{ name }}
                            </option>
                        </select>
                        年份：
                        <select name="year" v-model="year">
                            <option v-for="year in years" v-bind:value="year">
                                {{ year }}
                            </option>
                        </select>
                        月份：
                        <select name="month" v-model="month">
                            <option v-for="month in months" v-bind:value="month">
                                {{ month }}
                            </option>
                        </select>
                        <button class="btn btn-info" type="button" @click="search()">查詢</button>
                        <button class="btn btn-danger" type="button" @click="closeHighcharts()">關閉</button>
                        <div id="container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Highcharts from 'highcharts';
    export default {
        data() {
            return {
                categories: [],
                years: [],
                months: [],
                category: '',
                year: '',
                month: '',
            };
        },
        mounted() {
            this.initPage();
        },
        methods: {
            async initPage () {
                let response = await this.getSearchBarInfo();
                this.makeSearchBar(response.data);
            },
            getSearchBarInfo() {
                return axios.post(`/api/currency/searchBarInfo`);
            },
            makeSearchBar(data) {
                this.categories = data.categories;
                this.years = data.years;
                this.months = data.months;
                this.category = data.categories[0];
                this.year = data.years[0];
                this.month = data.months[0];
            },
            async search() {
                let response = await axios.post(`/api/currency/highcharts`, {
                    'category': this.category,
                    'year': this.year,
                    'month': this.month
                });

                let data = response.data;
                this.makeHighcharts(
                    this.category, data.categories, data.immediateBuys, data.immediateSells, data.cashBuys, data.cashSells
                );
            },
            makeHighcharts(category, categories, immediateBuys, immediateSells, cashBuys, cashSells) {
                Highcharts.chart('container', {
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: category + '匯率動向'
                    },
                    subtitle: {
                        text: ''
                    },
                    xAxis: {
                        categories: categories
                    },
                    yAxis: {
                        title: {
                            text: '匯率'
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
                    }, {
                        name: immediateSells.title,
                        data: immediateSells.values
                    }, {
                        name: cashBuys.title,
                        data: cashBuys.values
                    }, {
                        name: cashSells.title,
                        data: cashSells.values
                    }]
                });
            },
            closeHighcharts () {
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
        }
    }
</script>
<style lang="scss">
    #container {
        min-width: 310px;
        height: 400px;
        margin: 0 auto
    }
</style>
