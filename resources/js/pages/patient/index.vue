<template>
<admin-layout>
    <div class="d-flex flex-wrap mb-3">
        <v-text-field v-model="search" prepend-inner-icon="mdi-magnify" label="Search" single-line dense clearable hide-details class="" solo style="max-width: 300px" />
        <v-spacer />
        <v-btn color="primary" @click="create">
            <v-icon dark left> mdi-plus </v-icon> New
        </v-btn>
    </div>
    <v-data-table :items="items.data" :headers="headers" :options.sync="options" :server-items-length="items.total" :loading="isLoadingTable" class="elevation-1">
        <template #[`item.index`]="{ index }">
            {{ (options.page - 1) * options.itemsPerPage + index + 1 }}
        </template>
        <template #[`item.action`]="{ item }">
            <v-btn x-small icon @click="editItem(item)">
                <v-icon small> mdi-pencil </v-icon>
            </v-btn>
            <v-btn x-small color="red" icon dark @click="deleteItem(item)">
                <v-icon small> mdi-delete </v-icon>
            </v-btn>
        </template>
    </v-data-table>
    <v-dialog v-model="dialog" max-width="500px" scrollable>
        <v-card>
            <v-toolbar dense dark color="primary" class="text-h6">
                {{formTitle}}
            </v-toolbar>
            <v-card-text class="pt-4">
                <v-text-field v-model="form.secret_phrase" label="Secret Phrase" :error-messages="form.errors.secret_phrase" type="text" outlined dense />
                <v-text-field v-model="form.name" label="Name" :error-messages="form.errors.name" type="text" outlined dense />
                <v-text-field v-model="form.age" label="Age" :error-messages="form.errors.age" outlined dense />
                <v-text-field v-model="form.relative_name" label="Relative Name" :error-messages="form.errors.relative_name" outlined dense />
                <v-text-field v-model="form.relative_contact" label="Relative Contact" :error-messages="form.errors.relative_contact" outlined dense />
                <v-textarea v-model="form.address" label="Address" :error-messages="form.errors.address" outlined dense :rows="3" />
                <div class="d-flex"></div>
            </v-card-text>
            <v-card-actions>
                <v-btn :disabled="form.processing" text color="error" @click="dialog = false">Cancel</v-btn>
                <v-spacer />
                <v-btn :loading="form.processing" color="primary" @click="submit">Save</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <v-dialog v-model="dialogDelete" max-width="500">
        <v-card>
            <v-toolbar dense dark color="primary" class="text-h6">Delete Patient</v-toolbar>
            <v-card-text class="text-h6 mt-3">Are you sure delete this item ?</v-card-text>
            <v-card-actions>
                <v-spacer />
                <v-btn :disabled="form.processing" text color="error" @click="dialogDelete = false">Cancel</v-btn>
                <v-btn :loading="form.processing" text color="primary" @click="destroy">Yes</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</admin-layout>
</template>

<script>
import AdminLayout from "../../layouts/AdminLayout.vue";
export default {
    props: ["items"],
    components: {
        AdminLayout
    },
    data() {
        return {
            headers: [{
                    text: "No",
                    value: "index",
                    sortable: false
                },
                {
                    text: "Name",
                    value: "name"
                },
                {
                    text: "Age",
                    value: "age"
                },
                {
                    text: "Relative Name",
                    value: "relative_name"
                },
                {
                    text: "Relative Contact",
                    value: "relative_contact"
                },
                {
                    text: "Address",
                    value: "address"
                },
                {
                    text: "Actions",
                    value: "action",
                    sortable: false
                },
            ],
            breadcrumbs: [{
                    text: "App",
                    disabled: false,
                    href: "/home",
                },
                {
                    text: "Patient",
                    disabled: true,
                    href: "/patient",
                },
            ],
            dialog: false,
            dialogDelete: false,
            isUpdate: false,
            isLoading: false,
            isLoadingTable: false,
            itemId: null,
            options: {},
            search: null,
            params: {},
            form: this.$inertia.form({
                secret_phrase: "",
                name: null,
                age: null,
                relative_name: null,
                relative_contact: null,
                address: null,
            }),
        };
    },
    computed: {
        formTitle() {
            return this.isUpdate ? "Edit Patient" : "Create Patient";
        },
    },
    watch: {
        options: function (val) {
            this.params.page = val.page;
            this.params.page_size = val.itemsPerPage;
            if (val.sortBy.length != 0) {
                this.params.sort_by = val.sortBy[0];
                this.params.order_by = val.sortDesc[0] ? "desc" : "asc";
            } else {
                this.params.sort_by = null;
                this.params.order_by = null;
            }
            this.updateData();
        },
        search: function (val) {
            this.params.search = val;
            this.updateData();
        },
    },
    methods: {
        updateData() {
            this.isLoadingTable = true
            this.$inertia.get("/patient", this.params, {
                preserveState: true,
                preverseScroll: true,
                onSuccess: () => {
                    this.isLoadingTable = false
                },
            });
        },
        create() {
            this.dialog = true;
            this.form.reset();
            this.form.clearErrors();
        },
        editItem(item) {
            this.form.clearErrors();
            this.form.secret_phrase = item.secret_phrase
            this.form.name = item.name;
            this.form.email = item.email;
            this.form.age = item.age;
            this.form.relative_name = item.relative_name;
            this.form.relative_contact = item.relative_contact;
            this.form.address = item.address;
            this.isUpdate = true;
            this.itemId = item.id;
            this.dialog = true;
        },
        deleteItem(item) {
            this.itemId = item.id;
            this.dialogDelete = true;
        },
        destroy() {
            this.form.delete(route("patient.destroy", this.itemId), {
                preverseScroll: true,
                onSuccess: () => {
                    this.dialogDelete = false;
                    this.itemId = null;
                },
            });
        },
        submit() {
            if (this.isUpdate) {
                this.form.put(route("patient.update", this.itemId), {
                    preverseScroll: true,
                    onSuccess: () => {
                        this.isLoading = false;
                        this.dialog = false;
                        this.isUpdate = false;
                        this.itemId = null;
                        this.form.reset();
                    },
                });
            } else {
                this.form.post(route("patient.store"), {
                    preverseScroll: true,
                    onSuccess: () => {
                        this.isLoading = false;
                        this.dialog = false;
                        this.form.reset();
                    },
                });
            }
        },
    },
};
</script>