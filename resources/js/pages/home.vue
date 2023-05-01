<template>
<admin-layout>
    <div style="height:100%" class="d-flex flex-column">

        <div class="mb-4 d-flex align-center justify-center flex-wrap" v-if="$vuetify.breakpoint.lgAndUp">
            <div style="" class="mr-5 d-flex">
                <div>
                    <v-select class="mr-2" style="width:150px" label="Mode" v-model="mode" :items="modes" outlined dense hide-details></v-select>
                </div>
                <div v-if="!$page.props.auth.user.is_patient">
                    <v-autocomplete v-model="patient" @input="changePatient" :items="patients" item-text="name" :item-value="null" label="Patient" dense outlined hide-details></v-autocomplete>
                </div>
            </div>
            <div class="d-flex flex-wrap justify-center">
                <div v-for="(overview, index) in overviews" :key="index" class="mx-1">
                    <v-chip :color="overview.color" pill class="elevation-2">
                        <v-icon right>
                            {{ overview.icon }}
                        </v-icon>
                        <b class="ml-3">{{ overview.text }}</b> : {{ overview.value }}
                    </v-chip>
                </div>
            </div>
        </div>
        <div class="mb-4 d-flex align-center justify-center flex-wrap" v-if="$vuetify.breakpoint.mdAndDown">
            <div class="d-flex flex-wrap justify-center">
                <div v-for="(overview, index) in overviews" :key="index" class="mx-1 mb-1">
                    <v-chip :color="overview.color" pill class="elevation-2 text-wrap py-lg-3 py-md-3 py-sm-3 py-3">
                        <v-icon right>
                            {{ overview.icon }}
                        </v-icon>
                        <b class="ml-3">{{ overview.text }}</b> : <span>{{ overview.value }}</span>
                    </v-chip>
                </div>
            </div>
            <div class="mt-3 mr-5 d-flex">
                <div>
                    <v-select class="mr-2" style="width:150px" label="Mode" v-model="mode" :items="modes" outlined dense hide-details></v-select>
                </div>
                <div v-if="!$page.props.auth.user.is_patient">
                    <v-autocomplete v-model="patient" @input="changePatient" :items="patients" item-text="name" :item-value="null" label="Patient" dense outlined hide-details></v-autocomplete>
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
                                <v-sheet elevation="2" color="white" class="rounded-xl">
                                    <v-container fluid class=" pa-0">
                                        <div class="mx-auto" style="">
                                            <v-img class="imgStyle rounded-t-xl" :contain="true" :aspect-ratio="4/3" :src="item.image"></v-img>
                                        </div>
                                        <div style="background-color: #e3e3e3" class="pb-1 rounded-b-xl">
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
                                <v-sheet elevation="2" rounded color="white" class="rounded-xl">
                                    <v-container fluid class=" pa-0">
                                        <div class="mx-auto" style="">
                                            <v-img class="imgStyle rounded-t-xl" :contain="true" :aspect-ratio="4/3" :src="item.image"></v-img>
                                        </div>
                                        <div style="background-color: #e3e3e3" class="pb-1 rounded-b-xl">
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
                    <v-card class="" width="100%">
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
import socketio from "socket.io-client";

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
            token: "",
            modes: ["Realtime", "All"],
            mode: "Realtime",
            dateMenu: false,
            heartRate: 0,
            heartRateArray: [],
            heartRateToSave: [],
            bloodPressure: 0,
            bloodPressureArray: [],
            bloodPressureToSave: [],
            temperature: 0,
            temperatureArray: [],
            temperatureToSave: [],
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
            date: new Date(Date.now() - new Date().getTimezoneOffset() * 60000)
                .toISOString()
                .substr(0, 10),
            patients: this.patients_,
            patient: this.patients_[0],
            block: 0,
            overviews: [{
                    text: "Name",
                    value: "",
                    icon: "mdi-account",
                    loading: false,
                    color: "primary"
                },
                {
                    text: "Age",
                    value: "",
                    icon: "mdi-calendar",
                    loading: false,
                    color: "secondary"
                },
                {
                    text: "Address",
                    value: "",
                    icon: "mdi-map-marker",
                    loading: false,
                    color: "info"
                },
                {
                    text: "Relative Contact",
                    value: "",
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
                            size: 0
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
                            type: 'datetime',
                            // tickAmount: 5,
                            labels: {
                                rotate: 0,
                                hideOverlappingLabels: true,
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
                            size: 0
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
                            type: 'datetime',
                            // tickAmount: 5,
                            labels: {
                                rotate: 0,
                                hideOverlappingLabels: true,
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
                            size: 0
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
                            type: 'datetime',
                            // tickAmount: 5,
                            labels: {
                                rotate: 0,
                                hideOverlappingLabels: true,
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
                    series: [],
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
            timer: null,
            dataSocket: socketio("http://localhost:3001", {
                transports: ['websocket'],
                autoConnect: false,
                debug: true,
            }),
        };
    },
    methods: {
        async saveHeartRate(heartRates) {
            await axios.post(route("heart-rate.store"), heartRates).then((response) => {}).catch((error) => {
                console.log(error);
            })
        },
        setHeartRate(heartRate) {
            // random number from 80 to 90
            this.heartRateArray;
            this.readings[0].value = heartRate.y;
            this.readings[0].status = Number(heartRate.y > 88);
            this.heartRateArray.push(heartRate);

            if (this.heartRateArray.length > 10) {
                const hr = this.heartRateArray.shift();
                // let heartRate = {
                //     user_id: this.patient.id,
                //     heart_rate: hr.y,
                //     time: hr.x
                // }
                // this.heartRateToSave.push(heartRate);
            }
            // if (this.heartRateToSave.length == 10) {
            //     this.saveHeartRate({
            //         heart_rates: this.heartRateToSave
            //     });
            //     this.heartRateToSave = [];
            // }
            this.$refs.heartRate[0].updateSeries(
                [{
                    data: [...new Set(this.heartRateArray)]
                }],
                false,
                true
            );
        },
        async saveBloodPressure(bloodPressures) {
            await axios.post(route("blood-pressure.store"), bloodPressures).then((response) => {}).catch((error) => {
                console.log(error);
            })
        },
        setBloodPressure(bloodPressure) {
            // random number from 100 to 120
            this.bloodPressureArray;
            this.readings[1].value = bloodPressure.y;
            this.readings[1].status = Number(bloodPressure.y > 118);

            this.bloodPressureArray.push(bloodPressure);

            if (this.bloodPressureArray.length > 10) {
                const bp = this.bloodPressureArray.shift();
                // let bloodPressure = {
                //     user_id: this.patient.id,
                //     blood_pressure: bp.y,
                //     time: bp.x
                // }
                // this.bloodPressureToSave.push(bloodPressure);
            }
            // if (this.bloodPressureToSave.length == 10) {
            //     this.saveBloodPressure({
            //         blood_pressures: this.bloodPressureToSave
            //     });
            //     this.bloodPressureToSave = [];
            // }
            this.$refs.bloodPressure[0].updateSeries(
                [{
                    data: this.bloodPressureArray
                }],
                false,
                true
            );
        },
        async saveTemperature(temperatures) {
            await axios.post(route("temperature.store"), temperatures).then((response) => {}).catch((error) => {
                console.log(error);
            })
        },
        setTemperature(temperature) {
            // random number from 90 to 100
            this.temperatureArray;
            this.readings[2].value = temperature.y;
            this.readings[2].status = Number(temperature.y > 37);
            this.temperatureArray.push(temperature);
            if (this.temperatureArray.length > 10) {
                const temp = this.temperatureArray.shift();
                // let temperature = {
                //     user_id: this.patient.id,
                //     temperature: temp.y,
                //     time: temp.x
                // }
                // this.temperatureToSave.push(temperature);
            }
            // if (this.temperatureToSave.length == 10) {
            //     this.saveTemperature({
            //         temperatures: this.temperatureToSave
            //     });
            //     this.temperatureToSave = [];
            // }
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
            this.dataSocket.disconnect(true)
            this.connectToBracelet()
            this.setPatientOverview()

        },
        async connectToBracelet() {
            try {
                if(!this.token)
                await axios.post(route('create-token')).then(async (resp) => {
                    this.dataSocket.auth = {
                    id: this.$page.props.auth.user.id,
                    braceletId: this.patient.secret_phrase,
                    username: this.$page.props.auth.user.email,
                    token: resp.data.token
                    };
                    this.dataSocket.io.uri = this.patient.bracelet_url;
                    await this.dataSocket.connect();
                })
                else{
                    this.dataSocket.auth = {
                    id: this.$page.props.auth.user.id,
                    braceletId: this.patient.secret_phrase,
                    username: this.$page.props.auth.user.email,
                    token: resp.data.token
                    };
                    this.dataSocket.io.uri = this.patient.bracelet_url;
                    await this.dataSocket.connect();
                }
            } catch (e) {
                console.log(e);
            }
        },
        setPatientOverview() {
            this.overviews[0].value = this.patient.name;
            this.overviews[1].value = this.patient.age;
            this.overviews[2].value = this.patient.address;
            this.overviews[3].value = this.patient.relative_contact;
        },
        async fetchBloodPressure() {
            await axios.get(route("blood-pressure.index", this.$page.props.auth.user.id)).then((response) => {
                this.bloodPressureArray = response.data;
                this.$refs.bloodPressure[0].updateSeries(
                    [{
                        data: this.bloodPressureArray
                    }],
                    false,
                    true
                );
            }).catch((error) => {
                console.log(error);
            })
        },
        async fetchTemperature() {
            await axios.get(route("temperature.index", this.$page.props.auth.user.id)).then((response) => {
                this.temperatureArray = response.data;
                this.$refs.temperature[0].updateSeries(
                    [{
                        data: this.temperatureArray
                    }],
                    false,
                    true
                );
            }).catch((error) => {
                console.log(error);
            })
        },
        async fetchHeartRate() {
            await axios.get(route("heart-rate.index", this.$page.props.auth.user.id)).then((response) => {
                this.heartRateArray = response.data;
                this.$refs.heartRate[0].updateSeries(
                    [{
                        data: this.heartRateArray
                    }],
                    false,
                    true
                );
            }).catch((error) => {
                console.log(error);
            })
        },
        async saveHeartRateInBackground(hr) {
            let heartRate = {
                user_id: this.$page.props.auth.user.id,
                heart_rate: hr.y,
                time: hr.x
            }
            this.heartRateToSave.push(heartRate);

            if (this.heartRateToSave.length == 10) {
                await this.saveHeartRate({
                    heart_rates: this.heartRateToSave
                });
                this.heartRateToSave = [];
            }
        },
        async saveBloodPressureInBackground(bp) {
            let bloodPressure = {
                user_id: this.$page.props.auth.user.id,
                blood_pressure: bp.y,
                time: bp.x
            }
            this.bloodPressureToSave.push(bloodPressure);
            if (this.bloodPressureToSave.length == 10) {
                await this.saveBloodPressure({
                    blood_pressures: this.bloodPressureToSave
                });
                this.bloodPressureToSave = [];
            }
        },
        async saveTemperatureInBackground(temp) {
            let temperature = {
                user_id: this.$page.props.auth.user.id,
                temperature: temp.y,
                time: temp.x
            }
            this.temperatureToSave.push(temperature);
            if (this.temperatureToSave.length == 10) {
                await this.saveTemperature({
                    temperatures: this.temperatureToSave
                });
                this.temperatureToSave = [];
            }
        },
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
        mode: function (val) {
            this.heartRateArray = [];
            this.bloodPressureArray = [];
            this.temperatureArray = [];
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
            )
            if (val == "All") {
                this.fetchBloodPressure();
                this.fetchTemperature();
                this.fetchHeartRate();
            }
        }
    },
    mounted() {
        this.connectToBracelet();
        this.setPatientOverview();

        this.dataSocket.on("connect", () => {
            console.log("connected");
        });

        this.dataSocket.on("disconnect", () => {
            console.log("disconnected");
        });

        this.dataSocket.on('connection_error', (data) => {
            // Fired when couldn’t establish a connection with the server.
            console.log("connection_error")
            this.dataSocket.disconnect(true)
        })

        this.dataSocket.on("realtimeData", (data) => {
            if (this.mode == "Realtime") {
                this.setHeartRate(data.heartRate);
                this.setBloodPressure(data.bloodPressure);
                this.setTemperature(data.temperature);
            } 
            // else if (this.mode == "All") {
            //     this.saveBloodPressureInBackground(data.bloodPressure);
            //     this.saveTemperatureInBackground(data.temperature);
            //     this.saveHeartRateInBackground(data.heartRate);
            // }
        })
    },
    beforeDestroy() {
        clearInterval(this.timer);
        this.dataSocket.disconnect(true);
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
