<template>
    <div>
        <nav-bar :name="pageName" :cName="cName" />
        <operation-table :finalData="pageData" :tableTitles="tableTitles" @customerNameClicked="move2CustomerPage" />
        <b-pagination align="center" class="m-3" v-model="currentPage" :total-rows="finalData.length"
            :per-page="perPage" @input="changePage">
        </b-pagination>


    </div>
</template>

<script>
import NavBar from '../components/NavBar.vue';
import OperationTable from "../components/OperationTable.vue";
import dateFormat from "dateformat";
import AddNewOperationDialog from "../components/AddNewOperationDialog.vue";
export default {
    components: { NavBar, OperationTable, AddNewOperationDialog },
    mounted() {

        var token = localStorage.getItem("bill-user-token");
        if (token == undefined || token == null) {
            this.$router.push({
                name: "login",
            });
        }
        this.checkUser();

    },
    data() {
        return {
            currentPage: 1,
            countPage: 0,
            pageData: [],
            perPage: 5,
            cName: {
                name: ''
            },
            pageName: 'home',
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
            customers: [],
            selectedCustomer: null,
            data: null,
        }
    },
    methods: {
        changePage(pageNumber) {
            let firstIndex = (pageNumber-1) * this.perPage;
            let lastIndex = firstIndex + this.perPage
            console.log(`firstIndex : ${firstIndex} , lastIndex : ${lastIndex} ,this.finalData.length : ${this.finalData.length} `);
            if (this.finalData.length <= lastIndex) lastIndex = this.finalData.length;
            this.pageData = this.finalData.slice(firstIndex, lastIndex);
        },
        checkUser() {
            axios
                .post("/admin/me")
                .then((result) => {
                    this.getTodayData();
                    this.loadCustomers();
                })
                .catch((err) => {
                    console.log(err);
                    localStorage.removeItem("bill-user-token");
                    this.$router.push({
                        name: "login",
                    });
                });
        },
        move2CustomerPage(value) {

            if (this.pageName != 'home') {
                this.pageName = 'home';

            } else {

                this.loadCustomerData(value);
            }



        },
        loadCustomerData(value) {
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
                        this.cName = this.selectedCustomer;
                        this.pageName = 'customer';
                    });

                    this.finalData = this.reshape({
                        exports: exports,
                        imports: imports
                    });

                }).catch(console.error);
        },
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
        getTodayData() {
            // send http request to laravel serve and get today data
            axios
                .get("/admin/show")
                .then((result) => {
                    var response = result.data;
                    if (response.code == 200) {
                        this.data = response.data;
                        this.finalData = this.reshape({
                            exports: this.data.exports,
                            imports: this.data.imports
                        });
                        this.countPage = Math.ceil(this.finalData.length / this.perPage);
                        this.changePage(1);
                    } else {
                        alert(response.msg);
                    }
                })
                .catch((err) => { });
        },
        loadCustomers() {
            axios.get('/admin/get-all-customers')
                .then((result) => {
                    let customers = result.data.customers;
                    this.customers = customers.map((customer) => {
                        return {
                            value: customer,
                            text: customer.name
                        }
                    });
                }).catch(console.error);
        },

    },
}
</script>

<style scoped>

</style>