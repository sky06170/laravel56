<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-body">
                        檢視單位：
                        <select v-model="view_mode" @change="getCurrentDays()">
                            <option v-for="item in view_modes" v-bind:value="item">
                                {{ item }}
                            </option>
                        </select>
                        幣別：
                        <select name="category" v-model="category">
                            <option v-for="name in categories" v-bind:value="name">
                                {{ name }}
                            </option>
                        </select>
                        年份：
                        <select name="year" v-model="year" @change="getCurrentDays()">
                            <option v-for="year in years" v-bind:value="year">
                                {{ year }}
                            </option>
                        </select>
                        月份：
                        <select name="month" v-model="month" @change="getCurrentDays()">
                            <option v-for="month in months" v-bind:value="month">
                                {{ month }}
                            </option>
                        </select>
                        <span v-if="view_mode === '時'">
                            日期：
                            <select name="day" v-model="day">
                                <option v-for="item in days" v-bind:value="item">
                                    {{ item }}
                                </option>
                            </select>
                        </span>
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
    import { mapGetters, mapActions } from 'vuex';
    export default {
        data() {
            return {
                view_modes: ['日','時'],
                view_mode: '日',
                days: [],
                category: '',
                year: '',
                month: '',
                day: '',
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
            async search () {
                let response = await axios.post(`/api/currency/highcharts`, {
                    'category': this.category,
                    'year': this.year,
                    'month': this.month,
                    'day': this.day,
                    'view_mode': this.view_mode
                });

                let data = response.data;
                this.makeHighcharts(
                    this.category, data.categories, data.immediateBuys, data.immediateSells, data.cashBuys, data.cashSells
                );
            },
            makeHighcharts (category, categories, immediateBuys, immediateSells, cashBuys, cashSells) {
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
