<template>
  <div>
    <operation-table :finalData="finalData" :tableTitles="tableTitles" @customerNameClicked="move2CustomerPage" />
    <add-new-operation-dialog :customers="customers" @addNewCustomer="addNewCustomer" @addNewExport="addNewExport"
      @selectCousmter="(value)=>selectedCustomer=value" :selectedCustomer="selectedCustomer"
      @addNewImport="addNewImport" />
    <div class="floating-container">
      <div class="floating-button">
        <b-icon v-b-modal.my-modal color="black" stacked icon="plus"></b-icon>
      </div>
    </div>
  </div>
</template>
<script>
import OperationTable from "../components/OperationTable.vue";
import AddNewOperationDialog from '../components/AddNewOperationDialog.vue';
import dateFormat from "dateformat";
import moment from 'moment';
export default {
  components: { OperationTable, AddNewOperationDialog },
  mounted() {

    var token = localStorage.getItem("bill-user-token");
    if (token == undefined || token == null) {
      this.$router.push({
        name: "login",
      });
    }
    this.checkUser();

  },
  methods: {
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
          } else {
            alert(response.msg);
          }
        })
        .catch((err) => { });
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
    onSearch(value) {
      if (value === '') {
        this.getTodayData();
        return;
      }
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
          customers.forEach(customer => {
            imports.push(...customer.imports);
            exports.push(...customer.exports);
          });

          this.finalData = this.reshape({
            exports: exports,
            imports: imports
          });

        }).catch(console.error);
    },
    move2CustomerPage(value) {
      this.$router.push({
        name: 'customer-page', params: {
          customer_name: value
        }
      })
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

    addNewCustomer(newCustomer) {
      axios.post('/admin/add-customer', newCustomer)
        .then((result) => {
          let data = result.data;
          if (data.code != 200) {
            // this.$toast.error(data.msg);
            return;
          }
          // this.$toast.success(data.msg);
          let customer = data.customer;
          this.customers.unshift({
            text: customer.name,
            value: customer
          });
          this.selectedCustomer = this.customers[0].value;

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
          this.selectedCustomer = null;
          this.$toast.success(data.msg);
          this.getTodayData();
        }).catch((err) => {

        });
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
          this.selectedCustomer = null;
          this.$toast.success(data.msg);
          this.getTodayData();
        }).catch((err) => {

        });
    }

  },
  data() {
    return {
      customers: [],
      selectedCustomer: null,
      data: null,
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