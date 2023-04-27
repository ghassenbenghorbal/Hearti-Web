<template>
<admin-layout>
    <div style="height:100%" class="d-flex flex-column">

        <div class="mb-4 d-flex align-center justify-center flex-wrap" v-if="$vuetify.breakpoint.lgAndUp">
            <div style="width:250px;" class="mr-5 white">
                <div class="d-inline-flex">
                    <v-select v-model="block" @input="changePatient" :items="patients" item-text="name" item-value="id" label="Patient" dense outlined hide-details></v-select>
                </div>
            </div>
            <div class="d-flex flex-wrap justify-center">
                <div v-for="(overview, index) in overviews" :key="index" class="mx-1">
                    <v-chip :color="overview.color" pill class="elevation-2">
                        <v-icon right>
                            {{ overview.icon }}
                        </v-icon>
                        <b class="ml-3">{{ overview.text }}</b> : {{ patient[index] }}
                    </v-chip>
                </div>
            </div>
        </div>
        <div class="mb-4 d-flex align-center justify-center flex-wrap" v-if="$vuetify.breakpoint.mdAndDown">
            <div class="d-flex flex-wrap justify-center">
                <div v-for="(overview, index) in overviews" :key="index" class="mx-1 mb-1">
                    <v-chip :color="overview.color" pill class="elevation-2 text-wrap py-5">
                        <v-icon right>
                            {{ overview.icon }}
                        </v-icon>
                        <b class="ml-3">{{ overview.text }}</b> : {{ patient[index] }}
                    </v-chip>
                </div>
            </div>
            <div style="width:250px;" class="mt-3 mr-5 white">
                <div class="d-inline-flex">
                    <v-select v-model="block" @input="changePatient" :items="patients" item-text="name" item-value="id" label="Patient" dense outlined hide-details></v-select>
                </div>
            </div>
        </div>

        <div style="" class="flex-grow-1" v-if="$vuetify.breakpoint.mdAndUp">
            <v-row align="center" justify="center" style="height:50%">
                <v-col md="6" cols="12" v-for="chart in chartsP1" :key="chart.id" style="height:100%">
                    <v-card class="" width="100%" height="100%">
                        <apexchart :ref="chart.id" height="94%" width="100%" type="line" :options="chart.chartOptions" :series="chart.series"></apexchart>
                    </v-card>
                </v-col>
                <v-col md="6" cols="12">
                    <v-row class="px-7">
                        <v-col cols="6" md="3" lg="3" xl="3" style="height:100%" v-for="(item, index) in readings" :key="index">
                            <div class="mx-auto">
                                <v-sheet elevation="2" rounded color="white">
                                    <v-container fluid class=" pa-0">
                                        <div class="mx-auto" style="">
                                            <v-img class="imgStyle" :contain="true" :aspect-ratio="4/3" :src="item.image"></v-img>
                                        </div>
                                        <div style="background-color: #e3e3e3" class="pb-1">
                                            <div style="color:black" class="text-caption pt-1 text-center">
                                                <b>{{ item.text }}</b>
                                            </div>
                                            <div style="color:black" class="text-caption text-center">
                                                {{ item.value + item.unit }}
                                            </div>
                                            <div style="color:black" class="text-caption text-center pt-0 pb-0">
                                                <small>Status:
                                                    <v-chip v-if="item.text == 'Overall'" x-small class=" text-uppercase" :color="dangerSteps[sick].color">
                                                        {{ dangerSteps[sick].text }}
                                                    </v-chip>
                                                    <v-chip v-else x-small class=" text-uppercase" :color="dangerSteps[item.status].color">
                                                        {{ dangerSteps[item.status].text }}
                                                    </v-chip>
                                                </small>
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
        </div>

        <!-- Tablets/Phones -->

        <div style="" class=" flex-grow-1" v-if="$vuetify.breakpoint.smAndDown">
            <v-row align="center" justify="center" style="height:50%">

                <v-col md="6" cols="12">
                    <v-row class="px-10">
                        <v-col cols="6" md="3" lg="3" xl="3" style="height:100%" v-for="(item, index) in readings" :key="index">
                            <div>
                                <v-sheet elevation="2" rounded color="white">
                                    <v-container fluid class=" pa-0">
                                        <div class="mx-auto" style="">
                                            <v-img class="imgStyle" :contain="true" :aspect-ratio="4/3" :src="item.image"></v-img>
                                        </div>
                                        <div style="background-color: #e3e3e3" class="pb-1">
                                            <div style="color:black" class="text-caption pt-1 text-center">
                                                <b>{{ item.text }}</b>
                                            </div>
                                            <div style="color:black" class="text-caption text-center">
                                                {{ item.value + item.unit }}
                                            </div>
                                            <div style="color:black" class="text-caption text-center pt-0 pb-0">
                                                <small>Status:
                                                    <v-chip v-if="item.text == 'Overall'" x-small class=" text-uppercase" :color="dangerSteps[sick].color">
                                                        {{ dangerSteps[sick].text }}
                                                    </v-chip>
                                                    <v-chip v-else x-small class=" text-uppercase" :color="dangerSteps[item.status].color">
                                                        {{ dangerSteps[item.status].text }}
                                                    </v-chip>
                                                </small>
                                            </div>
                                        </div>
                                    </v-container>
                                </v-sheet>
                            </div>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col md="6" cols="12" v-for="chart in chartsP1" :key="chart.id" style="">
                    <v-card class="" width="100%" height="100%">
                        <apexchart :ref="chart.id" width="100%" type="line" :options="chart.chartOptions" :series="chart.series"></apexchart>
                    </v-card>
                </v-col>
                <v-col md="6" cols="12" v-for="chart in chartsP2" :key="chart.id">
                    <v-card class="" width="100%">
                        <apexchart :ref="chart.id" width="100%" type="line" :options="chart.chartOptions" :series="chart.series"></apexchart>
                    </v-card>
                </v-col>
            </v-row>
        </div>
    </div>
</admin-layout>
</template>

<script>
import AdminLayout from "../layouts/AdminLayout.vue";
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
        },
        heartRateImage: String,
        bloodPressureImage: String,
        temperatureImage: String,
        overallImage: String,
    },
    data() {
        return {
            dateMenu: false,
            heartRate: 0,
            heartRateArray: [],
            bloodPressure: 0,
            bloodPressureArray: [],
            temperature: 0,
            temperatureArray: [],
            dangerSteps: [{
                    text: "stable",
                    color: "success"
                },
                {
                    text: "danger",
                    color: "error"
                },
                {
                    text: "warning",
                    color: "warning"
                }
            ],
            readings: [{
                    text: "Heart Rate",
                    value: "-",
                    image: this.heartRateImage,
                    status: 0,
                    unit: " bpm"
                },
                {
                    text: "Blood Pressure",
                    value: "-",
                    image: this.bloodPressureImage,
                    status: 0,
                    unit: " mmHg"
                },
                {
                    text: "Temperature",
                    value: "-",
                    image: this.temperatureImage,
                    status: 0,
                    unit: " °C"
                },
                {
                    text: "Overall",
                    value: "-",
                    image: this.overallImage,
                    status: 0,
                    unit: "-"
                }
            ],
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
                    id: "heartRate",
                    chartOptions: {
                        chart: {
                            id: "heart-rate-chart"
                        },
                        stroke: {
                            curve: "smooth"
                        },
                        markers: {
                            size: 1
                        },
                        title: {
                            text: "Heart Rate"
                        },
                        colors: ["#EF5350", "#EF5350"],

                        legend: {
                            show: true,
                            position: "top"
                        },
                        xaxis: {
                            tickAmount: 5,
                            labels: {
                                rotate: 0,
                                hideOverlappingLabels: true
                            }
                        },
                        yaxis: {
                            forceNiceScale: true,
                            labels: {
                                formatter: function (value) {
                                    return value + " bpm";
                                }
                            }
                        },
                        noData: {
                            text: "Loading..."
                        }
                    },
                    series: [{
                        name: "Heart Rate",
                        data: []
                    }]
                },
                {
                    id: "bloodPressure",
                    chartOptions: {
                        chart: {
                            id: "blood-pressure-chart"
                        },
                        stroke: {
                            curve: "smooth"
                        },
                        markers: {
                            size: 1
                        },
                        title: {
                            text: "Blood Pressure"
                        },
                        legend: {
                            show: true,
                            position: "top"
                        },
                        colors: ["#66DA26", "#546E7A", "#E91E63", "#FF9800"],
                        yaxis: {
                            labels: {
                                formatter: function (value) {
                                    return value + " mmHg";
                                }
                            },
                            forceNiceScale: true
                        },
                        xaxis: {
                            tickAmount: 5,
                            labels: {
                                rotate: 0,
                                hideOverlappingLabels: true
                            }
                        },
                        noData: {
                            text: "Loading..."
                        }
                    },
                    series: [{
                        name: "Blood Pressure",
                        data: []
                    }]
                },
                {
                    id: "temperature",
                    chartOptions: {
                        chart: {
                            id: "temperature-chart"
                        },
                        stroke: {
                            curve: "smooth"
                        },
                        markers: {
                            size: 1
                        },
                        title: {
                            text: "Temperature"
                        },
                        legend: {
                            show: true,
                            position: "top"
                        },
                        colors: ["#FF9800"],
                        xaxis: {
                            tickAmount: 5,
                            labels: {
                                rotate: 0,
                                hideOverlappingLabels: true
                            }
                        },
                        yaxis: {
                            labels: {
                                formatter: function (value) {
                                    return value + " °C";
                                }
                            },
                            forceNiceScale: true
                        },

                        noData: {
                            text: "Loading..."
                        }
                    },
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
        getHourFormatted() {
            var date = new Date();
            var seconds = date.getSeconds();
            var minutes = date.getMinutes();
            var hours = date.getHours();
            let now = hours + ":" + minutes + ":" + seconds;
            return now;
        },
        setHeartRate() {
            // random number from 80 to 90
            let x = Math.floor(Math.random() * (91 - 80)) + 80;
            x = Math.round(x);
            this.heartRateArray;
            this.readings[0].value = x;
            this.readings[0].status = Number(x > 88);

            let now = getHourFormatted()
            this.heartRateArray.push({
                x: now,
                y: x
            });
            if (this.heartRateArray.length > 10) {
                this.heartRateArray.shift();
            }
            this.$refs.heartRate[0].updateSeries(
                [{
                    data: [...new Set(this.heartRateArray)]
                }],
                false,
                true
            );
        },

        setBloodPressure() {
            // random number from 100 to 120
            let x = Math.floor(Math.random() * (121 - 100)) + 100;
            x = Math.round(x);
            this.bloodPressureArray;
            this.readings[1].value = x;
            this.readings[1].status = Number(x > 118);

            let now = getHourFormatted()
            this.bloodPressureArray.push({
                x: now,
                y: x
            });
            if (this.bloodPressureArray.length > 10) {
                this.bloodPressureArray.shift();
            }
            this.$refs.bloodPressure[0].updateSeries(
                [{
                    data: this.bloodPressureArray
                }],
                false,
                true
            );
        },

        setTemperature() {
            // random number from 90 to 100
            let x = Math.floor(Math.random() * (41 - 36)) + 36;
            x = Math.round(x);
            this.temperatureArray;
            this.readings[2].value = x;
            this.readings[2].status = Number(x > 37);

            let now = getHourFormatted()
            this.temperatureArray.push({
                x: now,
                y: x
            });
            if (this.temperatureArray.length > 10) {
                this.temperatureArray.shift();
            }
            this.$refs.temperature[0].updateSeries(
                [{
                    data: this.temperatureArray
                }],
                false,
                true
            );
        },
        changePatient() {
            this.$refs.bloodPressure[0].updateSeries(
                [{
                    data: []
                }],
                false,
                true
            );
            this.$refs.temperature[0].updateSeries(
                [{
                    data: []
                }],
                false,
                true
            );
            this.$refs.heartRate[0].updateSeries(
                [{
                    data: []
                }],
                false,
                true
            );
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
            let count = 0;
            if (this.readings[2].value > 37 || this.readings[2].value < 36) count++;
            if (this.readings[0].value > 88) count++;
            if (this.readings[1].value > 118) count++;
            if (count >= 2) return 1;
            if (count > 0) return 2;
            return count;
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
    mounted() {
        this.timer = setInterval(() => {
            this.setHeartRate();
            this.setBloodPressure();
            this.setTemperature();
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
    width: 10% !important;
    aspect-ratio: 3/2 !important;
    object-fit: contain !important;
    mix-blend-mode: color-burn !important;
}
</style>
