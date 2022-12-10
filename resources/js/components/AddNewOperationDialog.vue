<template>
    <b-modal id="my-modal" hide-footer>
        <template #modal-title>
            Add New Operation
        </template>
        <div>
            <b-form-select v-model="innerSelectedCustomer" @change="$emit('selectCousmter',innerSelectedCustomer)"
                :options="customers"></b-form-select>
            <b-icon color="black" @click="()=>isNewCustomerClicked=true" style="width:36px;cursor: pointer;" stacked
                icon="plus"></b-icon>
            <div v-if="isNewCustomerClicked">
                <b-form @submit.prevent>
                    <b-input type="text" v-model="newCustomer.name" placeholder="Customer Name"></b-input>
                    <b-input type="text" v-model="newCustomer.phone" placeholder="Customer Phone"></b-input>
                    <b-button block @click="$emit('addNewCustomer',newCustomer)">Add New Customer</b-button>
                </b-form>

            </div>
            <div>
                <b-form-group label="Operation Type">
                    <b-form-radio @change="()=>isShowExportFieldsClicked=true" v-model="selectedOperationType"
                        name="some-radios" value="Export">Export
                    </b-form-radio>
                    <b-form-radio @change="()=>isShowExportFieldsClicked=false" v-model="selectedOperationType" name="some-radios" value="Import">Import
                    </b-form-radio>
                </b-form-group>
            </div>
        </div>
        <div v-if="isShowExportFieldsClicked">
            <b-form @submit.prevent>
                <b-input type="number" v-model="newExport.material_price" placeholder="Material Price"></b-input>
                <b-input type="number" v-model="newExport.wages" placeholder="Wage"></b-input>
                <b-input type="text" v-model="newExport.detials" placeholder="Details"></b-input>
                <!-- <b-input type="date" v-model="newExport.opration_date" placeholder="opration date"></b-input> -->
                <date-picker v-model="newExport.opration_date" :config="options"></date-picker>
                <b-button block @click="$emit('addNewExport',{newExport,innerSelectedCustomer})">Add New Export
                </b-button>
            </b-form>
        </div>
        <div v-else>
            <b-form @submit.prevent>
                <b-input type="number" v-model="newImport.payment" placeholder="Payment"></b-input>
                <b-input type="text" v-model="newImport.detials" placeholder="Details"></b-input>
                <!-- <b-input type="date" v-model="newExport.opration_date" placeholder="opration date"></b-input> -->
                <date-picker v-model="newImport.opration_date" :config="options"></date-picker>
                <b-button block @click="$emit('addNewImport',{newImport,innerSelectedCustomer})">Add New Import
                </b-button>
            </b-form>
        </div>

        <b-button class="mt-3" block @click="$bvModal.hide('my-modal')">Close Me</b-button>
    </b-modal>
</template>

<script>
import datePicker from "vue-bootstrap-datetimepicker";
import "pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css";
import "@fortawesome/fontawesome-free/css/all.css";
export default {
    props: ['customers', 'selectedCustomer'],
    components: {
        datePicker,
    },
    mounted(){
        this.innerSelectedCustomer = this.$props.selectedCustomer;
    },
    watch: {
        selectedCustomer: function (nV, oV) {
            this.innerSelectedCustomer = nV;
            this.isNewCustomerClicked = false;
            this.isShowExportFieldsClicked = false;
            this.newCustomer = {};
            this.newExport = {
                opration_date: new Date(),
            };
            this.newImport = {
                opration_date: new Date(),
            };
        }
    },
    data() {
        return {
            innerSelectedCustomer: null,
            isNewCustomerClicked: false,
            isShowExportFieldsClicked: false,
            newCustomer: {
                name: "",
                phone: ""
            },
            selectedOperationType: "",
            newExport: {
                opration_date: new Date(),
            }, newImport: {
                opration_date: new Date(),
            },
            options: {
                format: "YYYY/MM/DD h:m:s a",
                useCurrent: true,
                icons: {
                    time: "far fa-clock",
                    date: "far fa-calendar",
                    up: "fas fa-arrow-up",
                    down: "fas fa-arrow-down",
                    previous: "fas fa-chevron-left",
                    next: "fas fa-chevron-right",
                    today: "fas fa-calendar-check",
                    clear: "far fa-trash-alt",
                    close: "far fa-times-circle",
                },
            }
        }
    }

}
</script>

<style scoped>

</style>