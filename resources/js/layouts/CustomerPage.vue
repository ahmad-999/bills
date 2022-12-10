<template>
    <div>
        <operation-table :finalData="finalData" :tableTitles="tableTitles" />
        <add-new-operation-dialog :customers="customers" @addNewExport="addNewExport"
            :selectedCustomer="selectedCustomer" @addNewImport="addNewImport" v-if="customers.length>0" />
        <div class="floating-container">
            <div class="floating-button">
                <b-icon v-b-modal.my-modal color="black" stacked icon="plus"></b-icon>
            </div>
        </div>
    </div>
</template>
<script>
import OperationTable from "../components/OperationTable.vue";
import dateFormat from "dateformat";
import AddNewOperationDialog from "../components/AddNewOperationDialog.vue";
export default {
    props: ['customer_name'],
    components: { OperationTable, AddNewOperationDialog },
    mounted() {
        this.onSearch(this.customer_name);
    },
    watch: {
        customer_name: function (nV, oV) {
            if (nV == '') {
                this.$router.push({ name: 'home-page' });
                return;
            }
            this.onSearch(nV);
        }
    },
    methods: {

        reshape({ exports, imports }) {
            var arr = [];
            for (let index = 0; index < exports.length; index++) {
                const element = exports[index];
                arr.push({
                    ...element,
                    type: "export",
                });
            }

            imports.forEach((element) => {
                arr.push({ ...element, type: "import" });
            });

            arr.sort((a, b) => -1 * (a.created_at - b.created_at));
            return arr;
        },
        onSearch(value) {
            axios.post(`/admin/show/${value}`)
                .then((result) => {
                    let data = result.data;
                    if (data.code > 200) {
                        this.$toast.error(data.msg);
                        return;
                    }
                    let customers = result.data.customers_operations;
                    let imports = [];
                    let exports = [];
                    this.customers = [];

                    customers.forEach(customer => {
                        imports.push(...customer.imports);
                        exports.push(...customer.exports);
                        this.customers.push({
                            value: {
                                name: customer.name,
                                id: customer.id,
                            },
                            text: customer.name
                        });
                        this.selectedCustomer = this.customers[0].value;
                    });

                    this.finalData = this.reshape({
                        exports: exports,
                        imports: imports
                    });

                }).catch(console.error);
        },
        addNewExport({ newExport, innerSelectedCustomer }) {
            let date = new Date(newExport.opration_date);
            newExport.opration_date = dateFormat(date, 'yyyy-mm-dd HH:MM:ss');

            axios.post('/admin/add-export', { ...newExport, customer_id: innerSelectedCustomer.id })
                .then((result) => {
                    let data = result.data;
                    if (data.code > 200) {
                        alert(data.msg);
                        return;
                    }
                    this.$bvModal.hide('my-modal');
                    this.onSearch(this.selectedCustomer.name);
                    this.selectedCustomer = null;
                    this.$toast.success(data.msg);
                }).catch(console.error);
        },
        addNewImport({ newImport, innerSelectedCustomer }) {

            let date = new Date(newImport.opration_date);
            newImport.opration_date = dateFormat(date, 'yyyy-mm-dd HH:MM:ss');

            axios.post('/admin/add-import', { ...newImport, customer_id: innerSelectedCustomer.id })
                .then((result) => {
                    let data = result.data;
                    if (data.code > 200) {
                        alert(data.msg);
                        return;
                    }
                    this.$bvModal.hide('my-modal');
                    this.onSearch(this.selectedCustomer.name);
                    this.selectedCustomer = null;
                    this.$toast.success(data.msg);
                }).catch(console.error);
        }
    },
    data() {
        return {
            data: null,
            selectedCustomer: null,
            customers: [],
            finalData: [],
            tableTitles: {
                customer: {
                    name: "Customer Name",
                    phone: "Customer Phone",
                },
                payment: "Payment",
                detials: "Detials",
                opration_date: "Date",
                type: "Type",
            },
        };
    },

};
</script>
<style scoped>

</style>