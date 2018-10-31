<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label for="view_mode">單位</label>
                                <select class="form-control" id="view_mode" v-model="view_mode" @change="getCurrentDays()">
                                    <option v-for="item in view_modes" v-bind:value="item">
                                        {{ item }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="category">幣別</label>
                                <select class="form-control" id="category" v-model="category">
                                    <option v-for="name in categories" v-bind:value="name">
                                        {{ name }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="year">年份</label>
                                <select class="form-control" id="year" v-model="year" @change="getCurrentDays()">
                                    <option v-for="year in years" v-bind:value="year">
                                        {{ year }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="month">月份</label>
                                <select class="form-control" id="month" v-model="month" @change="getCurrentDays()">
                                    <option v-for="month in months" v-bind:value="month">
                                        {{ month }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group" v-if="view_mode === '時'">
                                <label for="day">日期</label>
                                <select class="form-control" id="day" v-model="day">
                                    <option v-for="item in days" v-bind:value="item">
                                        {{ item }}
                                    </option>
                                </select>
                            </div>
                            <button class="btn btn-info" type="button" @click="search()">查詢</button>
                            <button class="btn btn-danger" type="button" @click="closeHighcharts()">關閉</button>
                        </form>
                        <div id="container"></div>
                        <form v-if="highchartsStatus">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary" type="button" @click="setSimulationBuy('ImmediateMin')">即時最小值</button>
                                    <button class="btn btn-outline-secondary" type="button" @click="setSimulationBuy('CashMin')">現金最小值</button>
                                </div>
                                <input class="form-control" id="simulationBuy" v-model="simulationBuy" placeholder="買進匯率...">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary" type="button" @click="setSimulationSell('ImmediateMax')">即時最大值</button>
                                    <button class="btn btn-outline-secondary" type="button" @click="setSimulationSell('CashMax')">現金最大值</button>
                                </div>
                                <input class="form-control" id="simulationSell" v-model="simulationSell" placeholder="賣出匯率...">
                            </div>
                            <div class="form-group">
                                <label for="simulationInvestment">投資金額</label>
                                <input class="form-control" id="simulationInvestment" v-model="simulationInvestment">
                            </div>
                            <div class="form-group">
                                <label for="simulationProfit">所得利潤</label>
                                <input class="form-control" id="simulationProfit" v-model="simulationProfit" disabled>
                            </div>
                            <button class="btn btn-info" type="button" @click="caculate()">計算</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Highcharts from 'highcharts';
    import { mapGetters, mapActions } from 'vuex';
    export default {
        data() {
            return {
                highchartsStatus: false,
                view_modes: ['日','時'],
                view_mode: '日',
                days: [],
                category: '',
                year: '',
                month: '',
                day: '',
                searchResult: null,
                caculateForm: [],
                simulationBuy: '',
                simulationSell:'',
                simulationInvestment: '',
                simulationProfit: 0,
            };
        },
        mounted () {
            this.initPage();
        },
        computed: {
            ...mapGetters ('highcharts', [
                'categories',
                'years',
                'months'
            ]),
        },
        watch: {
            categories: function () {
                this.category = this.categories[0];
            },
            years: function () {
                this.year = this.years[0];
            },
            months: function () {
                this.month = this.months[0];
            }
        },
        methods: {
            ...mapActions('highcharts', [
                'initPage'
            ]),
            makeHighchartsSeries (datas) {
                let series = [];
                for (let i=0; i<datas.length; i++) {
                    series.push({
                        name: datas[i].title,
                        data: datas[i].values,
                    });
                }
                return series;
            },
            async search () {
                let response = await axios.post(`/api/currency/highchartsInfo`, {
                    'category': this.category,
                    'year': this.year,
                    'month': this.month,
                    'day': this.day,
                    'view_mode': this.view_mode
                });
                if (response.data.status) {
                    this.searchResult = response.data.result;
                    let highcharts_series = this.makeHighchartsSeries(this.searchResult.datas);
                    this.makeHighcharts(
                        this.category, this.searchResult.highcharts_categories, highcharts_series
                    );
                } else {
                    this.closeHighcharts();
                }
            },
            setSimulationBuy (value) {
                switch (value) {
                    case 'ImmediateMin':
                        this.simulationBuy = this.searchResult.datas[1].minValue;
                        break;
                    case 'CashMin':
                        this.simulationBuy = this.searchResult.datas[3].minValue;
                        break;
                    default:
                        this.simulationBuy = '';
                        break;
                }
            },
            setSimulationSell (value) {
                switch (value) {
                    case 'ImmediateMax':
                        this.simulationSell = this.searchResult.datas[0].maxValue;
                        break;
                    case 'CashMax':
                        this.simulationSell = this.searchResult.datas[2].maxValue;
                        break;
                    default:
                        this.simulationSell = '';
                        break;
                }
            },
            caculate () {
                console.log(this.caculateForm);
                axios.post(`/api/currency/caculate`, {
                    'simulationBuy': this.simulationBuy,
                    'simulationSell': this.simulationSell,
                    'simulationInvestment': this.simulationInvestment,
                }).
                then((response) => {
                    console.log(response.data);
                    this.simulationProfit = response.data.profit;
                }).
                catch((error) => {
                    console.log(error);
                });
            },
            makeHighcharts (category, categories, series) {
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
                    series: series
                });
                this.highchartsStatus = true;
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
                this.highchartsStatus = false;
            },
            getCurrentDays () {
                this.day = 1;
                let limitInMonth = [0, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                if (this.year % 4 !== 0) {
                    limitInMonth[2] = 28;
                }

                this.days = [];
                for (var i=1; i<=limitInMonth[this.month]; i++) {
                    this.days.push(i);
                }
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
