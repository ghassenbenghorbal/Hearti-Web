<template>
<admin-layout>
    <div class="d-flex justify-center">
        <v-card width="40%">
            <v-toolbar dense dark color="red" class="text-h6">
                <v-toolbar-title>Heart Disease Analysis</v-toolbar-title>
            </v-toolbar>
            <v-card-text class="pb-0">Please fill this form to run the analysis!</v-card-text>
            <v-form @submit.prevent="submitForm">
                <v-container>
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-text-field :rules="numberRules" v-model="form.age" label="Age" type="number" required filled outlined dense></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select :rules="numberRules" v-model="form.sex" :items="sexOptions" label="Sex" required filled outlined dense></v-select>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select :rules="numberRules" v-model="form.chestPain" :items="chestPainOptions" label="Chest Pain Type" required filled outlined dense></v-select>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field :rules="numberRules" v-model="form.bloodPressure" label="Resting Blood Pressure" type="number" required filled outlined dense></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field :rules="numberRules" v-model="form.cholestoral" label="Serum Cholestoral (mg/dl)" type="number" required filled outlined dense></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select :rules="numberRules" v-model="form.fastingBloodSugar" :items="fastingBloodSugarOptions" label="Fasting Blood Sugar > 120 mg/dl" required filled outlined dense></v-select>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select :rules="numberRules" v-model="form.restingEcg" :items="restingEcgOptions" label="Resting Electrocardiographic Results" required filled outlined dense></v-select>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field :rules="numberRules" v-model="form.maxHeartRate" label="Maximum Heart Rate Achieved" type="number" required filled outlined dense></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select :rules="numberRules" v-model="form.exerciseAngina" :items="exerciseAnginaOptions" label="Exercise Induced Angina" required filled outlined dense></v-select>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field :rules="numberRules" v-model="form.oldpeak" label="ST Depression Induced by Exercise Relative to Rest" type="number" required filled outlined dense></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select :rules="numberRules" v-model="form.slope" :items="slopeOptions" label="Slope of the Peak Exercise ST Segment" required filled outlined dense></v-select>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select :rules="numberRules" v-model="form.numVessels" :items="numVesselsOptions" label="Number of Major Vessels Colored by Fluoroscopy" required filled outlined dense></v-select>
                        </v-col>
                        <v-col cols="12">
                            <v-select :rules="numberRules" v-model="form.thal" :items="thalOptions" label="Thal" required filled outlined dense></v-select>
                        </v-col>
                    </v-row>
                    <v-card-actions class="d-flex justify-end">
                        <v-btn type="submit" color="primary" dark :loading="analysing">
                            <v-icon>mdi-magnify</v-icon>Analyse
                        </v-btn>
                    </v-card-actions>
                </v-container>
            </v-form>
        </v-card>
    </div>
    <v-dialog v-model="resultDialog" :value="true" max-width="500px" scrollable>
        <v-card>
            <v-toolbar dense dark :color="result ? 'red' : 'green'" class="text-h6">
                Analysis Result
            </v-toolbar>
            <v-card-text class="pt-5 pb-0">
                <v-alert type="error" outlined v-if="result">The patient is more likely to have a heart disease</v-alert>
                <v-alert type="success" color="green" outlined v-else>The patient is less likely to have heart disease</v-alert>
            </v-card-text>
            <v-card-actions class="d-flex justify-end">
                <v-btn :color="result ? 'red' : 'green'" dark @click="resultDialog = false">Close</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</admin-layout>
</template>

<script>
import AdminLayout from "../layouts/AdminLayout.vue";
import axios from "axios";
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
    props: {},
    data() {
        return {
            numberRules: [
                value => value !== null && value !== undefined && value !== '' || "Field is required",
                value => !isNaN(Number(value)) || "Field must be numeric"
            ],
            result: 0,
            resultDialog: false,
            analysing: false,
            sexOptions: [{
                    text: "Female",
                    value: 0
                },
                {
                    text: "Male",
                    value: 1
                }
            ],

            chestPainOptions: [{
                    text: "Typical Angina",
                    value: 0
                },
                {
                    text: "Atypical Angina",
                    value: 1
                },
                {
                    text: "Non-Anginal Pain",
                    value: 2
                },
                {
                    text: "Asymptomatic",
                    value: 3
                }
            ],

            fastingBloodSugarOptions: [{
                    text: "Lower than 120mg/ml",
                    value: 0
                },
                {
                    text: "Greater than 120mg/ml",
                    value: 1
                }
            ],

            restingEcgOptions: [{
                    text: "Normal",
                    value: 0
                },
                {
                    text: "ST-T wave abnormality",
                    value: 1
                },
                {
                    text: "Left ventricular hypertrophy",
                    value: 2
                }
            ],

            exerciseAnginaOptions: [{
                    text: "No",
                    value: 0
                },
                {
                    text: "Yes",
                    value: 1
                }
            ],

            slopeOptions: [{
                    text: "Upsloping",
                    value: 0
                },
                {
                    text: "Flat",
                    value: 1
                },
                {
                    text: "Downsloping",
                    value: 2
                }
            ],

            numVesselsOptions: [{
                    text: "0",
                    value: 0
                },
                {
                    text: "1",
                    value: 1
                },
                {
                    text: "2",
                    value: 2
                },
                {
                    text: "3",
                    value: 3
                }
            ],

            thalOptions: [{
                    text: "Normal",
                    value: 0
                },
                {
                    text: "Fixed defect",
                    value: 1
                },
                {
                    text: "Reversible defect",
                    value: 2
                }
            ],
            form: {
                age: null,
                sex: null,
                chestPain: null,
                bloodPressure: null,
                cholestoral: null,
                fastingBloodSugar: null,
                restingEcg: null,
                maxHeartRate: null,
                exerciseAngina: null,
                oldpeak: null,
                slope: null,
                numVessels: null,
                thal: null
            }
        };
    },
    methods: {
        async submitForm() {
            if (this.$refs.form.validate()) {
                this.analysing = true;
                axios.post("http://127.0.0.1:8000/predict", this.form).then(response => {
                    this.analysing = false;
                    this.result = response.data.heart_disease_risk;
                    this.resultDialog = true;
                });
            }
        }
    },
    computed: {},
    watch: {},
    mounted() {},
    beforeDestroy() {}
};
</script>

<style>
.imgstyle {
    width: 15% !important;
    aspect-ratio: 3/2 !important;
    object-fit: contain !important;
    mix-blend-mode: color-burn !important;
}
</style>
