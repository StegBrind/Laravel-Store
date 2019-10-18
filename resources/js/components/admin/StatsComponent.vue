<template>
    <div class="panel panel-default" style="margin-top: 30px">
        <div class="panel-heading">Статистика</div>
        <div id="panel-chart" class="panel-body">
            <h3>Посещаемость</h3>
            <line-chart :chart-data="data" :height="100" :options="{responsive: true}"></line-chart>
        </div>
        <div class="form-inline" style="text-align: center">
            С даты:
            <datepicker :disabled-dates="disabledDates" :value="date_from" @selected="updateDateValue($event, 'from')"
                        :bootstrap-styling="true" format="yyyy-MM-dd"
                        wrapper-class="form-group"></datepicker>
            По дату:
            <datepicker :disabled-dates="disabledDates" :value="date_to" @selected="updateDateValue($event, 'to')"
                        :bootstrap-styling="true" format="yyyy-MM-dd"
                        wrapper-class="form-group"></datepicker>
        </div>
    </div>
</template>

<script>
    import LineChart from "../LineChart";
    import axios from "axios";
    import Datepicker from 'vuejs-datepicker';
    import {ResizeSensor} from "css-element-queries";

    export default {
        name: "StatsComponent",
        components: {
            LineChart,
            Datepicker
        },
        data: function () {
            return {
                loadedLabels: [],
                loadedData: [],
                data: {
                    labels: ['', ''],
                    datasets: [
                        {
                            label: 'Количество пользователей',
                            backgroundColor: '#0091ab',
                            data: [0, 0]
                        }
                    ]
                },
                date_from: '',
                date_to: '',
                disabledDates: {}
            }
        },
        mounted() {
            new ResizeSensor(document.getElementById('panel-chart'), () => {}); // to resize the chart
            this.updateChart();
        },
        methods: {
            updateChart: function () {
                if (this.loadedLabels.length == 0)
                {
                    axios.get('get/stats').then(response => {
                        for (let i = 0; i < response.data.length; i++)
                        {
                            this.loadedLabels[i] = response.data[i].date;
                            this.loadedData[i] = response.data[i].users_count;
                        }
                        this.date_from = this.loadedLabels[0];
                        this.date_to = this.loadedLabels[this.loadedLabels.length - 1];
                        this.disabledDates = {
                            to: this.toDate(this.date_from),
                            from: this.toDate(this.date_to, true)
                        };
                        this.renderChart(this.loadedLabels, this.loadedData);
                    });
                }
                else
                {
                    let _from = this.findIndex(this.date_from, this.loadedLabels);
                    let to = this.findIndex(this.date_to, this.loadedLabels) + 1;
                    if (_from > to - 1)
                    {
                        to += _from;
                        _from = to - _from;
                        to -= _from;
                        if (_from != 0) --_from;
                        if (to != this.loadedLabels.length) ++to;
                    }
                    let selectedLabels = this.loadedLabels.slice(_from, to);
                    let selectedData = this.loadedData.slice(_from, to);
                    if (selectedLabels.length == 1)
                    {
                        selectedLabels.push(selectedLabels[0]);
                        selectedData.push(selectedData[0]);
                    }
                    this.renderChart(selectedLabels, selectedData);
                }
            },
            renderChart: function (labels, data) {
                this.data = {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Количество пользователей',
                            backgroundColor: '#0091ab',
                            data: data
                        }
                    ]
                };
            },
            updateDateValue: function (selectedDate, who) {
                if (who == 'from') this.date_from = this.formatDate(selectedDate);
                else this.date_to = this.formatDate(selectedDate);
                this.updateChart();
            },
            formatDate: function (date) {
                return date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
            },
            toDate: function (date_str, increaseByOne = false) {
                let date_arr = date_str.split('-');
                if (increaseByOne) ++date_arr[2];
                return new Date(date_arr[0], date_arr[1] - 1, date_arr[2]);
            },
            findIndex: function(date, dates_array) {
                for (let i = 0; i < dates_array.length; i++)
                {
                    if (dates_array[i] == date)
                        return i;
                }
            }
        }
    }
</script>