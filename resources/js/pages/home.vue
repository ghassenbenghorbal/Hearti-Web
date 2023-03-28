<template>
<admin-layout>
    <div style="height:100%" class="d-flex flex-column">
        <!-- <v-snackbar v-model="sick" v-if="true" timeout="-1" dense top right color="error">
            <b>Patient is sick!</b>
        </v-snackbar> -->
        <!-- Customization -->
        
        <div class="mb-4 d-flex">
            <div style="width:250px;" class="mr-3 white">
                <div class="d-inline-flex">
                    <v-select v-model="block" @input="changePatient" :items="patients" item-text="name" item-value="id" label="Patient" dense outlined hide-details></v-select>
                </div>
            </div>
            <div class="mt-1">
                <v-chip class="ml-2" v-for="(overview, index) in overviews" :key="index" :color="overview.color" pill>
                    <v-icon right>
                        {{ overview.icon }}
                    </v-icon>
                    <b class="ml-3">{{ overview.text }}</b> : {{ patient[index] }}
                </v-chip>
            </div>
        </div>
        <div style="" class=" flex-grow-1">
            <v-row align="center" justify="center" style="height:50%">
                <v-col md="6" cols="12" v-for="chart in chartsP1" :key="chart.id" style="height:100%">
                    <v-card class="" width="100%" height="100%">
                        <apexchart :ref="chart.id" height="94%" width="100%" type="line" :options="chart.chartOptions" :series="chart.series"></apexchart>
                    </v-card>
                </v-col>
                <v-col md="6" cols="12">
                    <v-row class="px-10">
                        <v-col style="height:100%" class="">
                            <div>
                                <v-sheet elevation="2" rounded color="white">
                                    <v-container fluid class=" pa-0">
                                        <div class="mx-auto" style="height: 105px; width: 105px;">
                                            <v-img class="imgStyle" src="https://apollohealthlib.blob.core.windows.net/health-library/2021/06/shutterstock_1236631984-scaled.jpg"></v-img>
                                        </div>
                                        <div style="background-color: #e3e3e3">
                                            <div style="color:black" class="text-caption pt-1 text-center">
                                                <b>Heart Rate</b>
                                            </div>
                                            <div style="color:black" class="text-caption text-center">
                                                {{ heartRate }} bpm
                                            </div>
                                            <div style="color:black" class="text-caption text-center pt-0 pb-0">
                                                <small>Status: Normal</small>
                                            </div>
                                        </div>
                                    </v-container>
                                </v-sheet>
                            </div>
                        </v-col>
                        <v-col style="height:100%" class="">
                            <div>
                                <v-sheet elevation="2" rounded color="white">
                                    <v-container fluid class=" pa-0">
                                        <div class="mx-auto" style="height: 105px; width: 105px;">
                                            <v-img class="imgStyle" src="https://img.freepik.com/premium-vector/arterial-blood-pressure-icon-flat-style-heartbeat-monitor-vector-illustration-isolated-background-pulse-diagnosis-sign-business-concept_157943-665.jpg?w=2000"></v-img>
                                        </div>
                                        <div style="background-color: #e3e3e3">
                                            <div style="color:black" class="text-caption pt-1 text-center">
                                                <b>Blood Pressure</b>
                                            </div>
                                            <div style="color:black" class="text-caption text-center">
                                                {{ bloodPressure }} mmHg
                                            </div>
                                            <div style="color:black" class="text-caption text-center pt-0 pb-0">
                                                <small>Status: Normal</small>
                                            </div>
                                        </div>
                                    </v-container>
                                </v-sheet>
                            </div>
                        </v-col>
                        <v-col style="height:100%" class="">
                            <div>
                                <v-sheet elevation="2" rounded color="white">
                                    <v-container fluid class=" pa-0">
                                        <div class="d-flex align-center mx-auto" style="height: 105px; width: 105px;">
                                            <v-img class="imgStyle" src="https://t3.ftcdn.net/jpg/02/87/70/82/360_F_287708200_wTq4URYtOsqH1Mjk2VxxulrkcTo0EkY0.jpg"></v-img>
                                        </div>
                                        <div style="background-color: #e3e3e3">
                                            <div style="color:black" class="text-caption pt-1 text-center">
                                                <b>CGM</b>
                                            </div>
                                            <div style="color:black" class="text-caption text-center">
                                                {{glucose}} mg/dL
                                            </div>
                                            <div style="color:black" class="text-caption text-center pt-0 pb-0">
                                                <small>Status: Normal</small>
                                            </div>
                                        </div>
                                    </v-container>
                                </v-sheet>
                            </div>
                        </v-col>
                        <v-col style="height:100%" class="">
                            <div>
                                <v-sheet elevation="2" rounded color="white">
                                    <v-container fluid class=" pa-0">
                                        <div class="mx-auto" style="height: 105px; width: 105px;">
                                            <v-img class="imgStyle" src="https://media.istockphoto.com/id/1133812963/vector/love-icon-or-valentines-day-sign-designed-for-celebration.jpg?s=612x612&w=0&k=20&c=mmDpiIJO0hVaqkVP7lvzpD9iZKg9Z5TMOIRPOEUZiig="></v-img>
                                        </div>
                                        <div style="background-color: #e3e3e3">
                                            <div style="color:black" class="text-caption pt-1 text-center">
                                                <b>Overall</b>
                                            </div>
                                            <div style="color:black" class="text-caption text-center">
                                                <v-chip x-small :color="sick ? 'error' : 'success'">
                                                    {{sick ? 'Sick' : 'Healthy'}}
                                                </v-chip>
                                            </div>
                                            <div style="color:black" class="text-caption text-center pt-0 pb-0">
                                                <small>Status: Normal</small>
                                            </div>
                                        </div>
                                    </v-container>
                                </v-sheet>
                            </div>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col md="6" cols="12" v-for="chart in chartsP2" :key="chart.id" style="height:100%">
                    <v-card class="" width="100%" height="100%">
                        <apexchart :ref="chart.id" height="94%" width="100%" type="line" :options="chart.chartOptions" :series="chart.series"></apexchart>
                    </v-card>
                </v-col>
            </v-row>

            <!-- <v-row align="center" justify="center" style="height:50%" class="mt-2">
                <v-col v-for="chart in chartsP2" :key="chart.id" style="height:100%">
                    <v-card class="" width="95%" height="100%">
                        <apexchart :ref="chart.id" height="94%" width="100%" type="line" :options="chart.chartOptions" :series="chart.series"></apexchart>
                    </v-card>
                </v-col>
            </v-row> -->
        </div>
        <!-- Laptop and above -->
    </div>
</admin-layout>
</template>

<script>
import AdminLayout from "../layouts/AdminLayout.vue";
import {
    getCo2Data,
    co2ChartOptions
} from "../methods/charts/co2/co2-chart.js";
import {
    getHumidityData,
    humidityChartOptions
} from "../methods/charts/humidity/humidity-chart.js";
import {
    getTemperatureData,
    temperatureChartOptions
} from "../methods/charts/temperature/temperature-chart.js";
import {
    getMovementData,
    movementChartOptions
} from "../methods/charts/movement/movement-chart.js";

import {
    getBlocks
} from "../methods/charts/blocks/blocks";

export default {
    components: {
        AdminLayout
    },
    inject: {
        theme: {
            default: {
                isDark: false
            }
        }
    },
    props: {
        patients_: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            dateMenu: false,
            heartRate: 0,
            heartRateArray: [],
            bloodPressure: 0,
            bloodPressureArray: [],
            glucose: 0,
            glucoseArray: [],
            overviews: [{
                    text: "Name",
                    value: null,
                    loading: false,
                    color: "primary"
                },
                {
                    text: "Age",
                    value: null,
                    loading: false,
                    color: "success"
                },
                {
                    text: "Address",
                    value: null,
                    loading: false,
                    color: "warning"
                },
                {
                    text: "Relative",
                    value: null,
                    loading: false,
                    color: "error"
                }
            ],

            date: new Date(Date.now() - new Date().getTimezoneOffset() * 60000)
                .toISOString()
                .substr(0, 10),
            patients: this.patients_,
            patient: [],
            block: 1,
            overviews: [{
                    text: "Name",
                    icon: "mdi-account",
                    loading: false,
                    color: "primary"
                },
                {
                    text: "Age",
                    icon: "mdi-calendar",
                    loading: false,
                    color: "secondary"
                },
                {
                    text: "Address",
                    icon: "mdi-map-marker",
                    loading: false,
                    color: "info"
                },
                {
                    text: "Relative Contact",
                    icon: "mdi-phone",
                    loading: false,
                    color: "error"
                }
            ],
            intervals: [{
                    text: "1min",
                    value: 1
                },
                {
                    text: "5min",
                    value: 5
                },
                {
                    text: "15min",
                    value: 15
                },
                {
                    text: "30min",
                    value: 30
                },
                {
                    text: "1H",
                    value: 60
                },
                {
                    text: "1D",
                    value: 1440
                },
                {
                    text: "1W",
                    value: 1080
                },
                {
                    text: "1M",
                    value: 43200
                },
                {
                    text: "1Y",
                    value: 525600
                }
            ],
            interval: 1,
            charts: [{
                    id: "co2",
                    chartOptions: co2ChartOptions,
                    series: [{
                        name: "Heart Rate",
                        data: []
                    }]
                },
                {
                    id: "temperature",
                    chartOptions: temperatureChartOptions,
                    series: [{
                        name: "Blood Pressure",
                        data: []
                    }]
                },
                {
                    id: "humidity",
                    chartOptions: humidityChartOptions,
                    series: []
                },
                {
                    id: "movement",
                    chartOptions: movementChartOptions,
                    series: []
                }
            ],
            breadcrumbs: [{
                    text: "App",
                    disabled: false,
                    href: "/home"
                },
                {
                    text: "Home",
                    disabled: true,
                    href: "/home"
                }
            ],
            timer: null
        };
    },
    methods: {
        setHeartRate() {
            // random number from 80 to 90
            let x = Math.floor(Math.random() * (91 - 80)) + 80
            x = Math.round(x);
            this.heartRateArray
            this.heartRate = x;

            var date = new Date;
            var seconds = date.getSeconds();
            var minutes = date.getMinutes();
            var hours = date.getHours();
            let now = hours + ':' + minutes + ':' + seconds;
            this.heartRateArray.push({
                'x': now,
                'y': x
            })
            if (this.heartRateArray.length > 10) {
                this.heartRateArray.shift();
            }
            this.$refs.co2[0].updateSeries([{
                data: [...new Set(this.heartRateArray)]
            }], false, true)

        },

        setBloodPressure() {
            // random number from 100 to 120
            let x = Math.floor(Math.random() * (121 - 100)) + 100;
            x = Math.round(x);
            this.bloodPressureArray
            this.bloodPressure = x;

            var date = new Date;
            var seconds = date.getSeconds();
            var minutes = date.getMinutes();
            var hours = date.getHours();
            let now = hours + ':' + minutes + ':' + seconds;
            this.bloodPressureArray.push({
                'x': now,
                'y': x
            })
            if (this.bloodPressureArray.length > 10) {
                this.bloodPressureArray.shift();
            }
            this.$refs.temperature[0].updateSeries([{
                data: this.bloodPressureArray
            }], false, true)

        },

        setGlucose() {
            // random number from 90 to 100
            let x = Math.floor(Math.random() * (101 - 90)) + 90
            x = Math.round(x);
            this.glucoseArray
            this.glucose = x;

            var date = new Date;
            var seconds = date.getSeconds();
            var minutes = date.getMinutes();
            var hours = date.getHours();
            let now = hours + ':' + minutes + ':' + seconds;
            this.glucoseArray.push({
                'x': now,
                'y': x
            })
            if (this.glucoseArray.length > 10) {
                this.glucoseArray.shift();
            }
            this.$refs.humidity[0].updateSeries([{
                data: this.glucoseArray
            }], false, true)

        },
        getChartData() {
            clearInterval(this.timer);

            // getCo2Data(this.block, this.date).then(s => {
            //     this.charts[0].series = s;
            // this.setHeartRate()
            // });
            // getHumidityData(this.block, this.date).then(s => {
            //     this.charts[2].series = s;
            // });
            // getTemperatureData(this.block, this.date).then(s => {
            //     this.charts[1].series = s;
            // });
            // getMovementData(this.block, this.date).then(s => {
            //     this.charts[3].series = s;
            // });

            this.timer = setInterval(() => {

                // getCo2Data(this.block, this.date).then(s => {
                // this.charts[0].series = s;
                this.setHeartRate()
                // });
                // getHumidityData(this.block, this.date).then(s => {
                //     this.charts[1].series = s;
                // });
                // getTemperatureData(this.block, this.date).then(s => {
                //     this.charts[2].series = s;
                // });
                // getMovementData(this.block, this.date).then(s => {
                //     this.charts[3].series = s;
                // });
            }, 1025);
        },
        datePickerOnInput() {
            this.dateMenu = false;
            this.getChartData();
        },
        changePatient() {
            this.$refs.temperature[0].updateSeries([{
                data: []
            }], false, true)
            this.$refs.humidity[0].updateSeries([{
                data: []
            }], false, true)
            this.$refs.co2[0].updateSeries([{
                data: []
            }], false, true)
        }
    },
    computed: {
        chartsP1: function () {
            return this.charts.slice(0, 1);
        },
        chartsP2: function () {
            return this.charts.slice(1, 3);
        },
        sick() {
            return this.heartRate > 88 || this.glucose > 98 || this.bloodPressure > 118
        }
    },
    watch: {
        block: function () {
            let p = this.patients[this.block - 1];
            this.patient[0] = p.name;
            this.patient[1] = p.age;
            this.patient[2] = p.address;
            this.patient[3] = p.relative_contact;
        }
    },
    created() {
        getBlocks().then(b => {
            this.blocks = b;
        });
    },
    mounted() {
        // this.getChartData();
        this.timer = setInterval(() => {
            this.setHeartRate()
            this.setBloodPressure()
            this.setGlucose()
        }, 1600);
        let p = this.patients[0];
        this.patient.push(p.name);
        this.patient.push(p.age);
        this.patient.push(p.address);
        this.patient.push(p.relative_contact);
    },
    beforeDestroy() {
        clearInterval(this.timer);
    }
};
</script>

<style>
.apexcharts-toolbar {
    z-index: 0 !important;
}

.imgstyle {
    width: 15% !important;
    aspect-ratio: 3/2 !important;
    object-fit: contain !important;
    mix-blend-mode: color-burn !important
}
</style>
