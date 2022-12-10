<template>
  <tr :style="color">
    <td>{{ index }}</td>
    <td @click="customerNameClicked" style="cursor: pointer;">{{ operation.customer.name }}</td>
    <td>{{ operation.customer.phone }}</td>
    <td>{{ operation.payment }}</td>
    <td>{{ operation.detials }}</td>
    <td>{{ operation.opration_date }}</td>
    <td>{{ operation.type }}</td>
  </tr>
</template>

<script>
export default {
  props: ["operation", "index"],
  computed: {
    color() {
      return {
        "background-color":
          this.operation.type === "export"
            ? "#86e186"
            : this.operation.type === "import"
            ? "#ffb3b3"
            : "white",
      };
    },
  },
  methods:{
    customerNameClicked(){
      this.$emit('customerNameClicked',this.operation.customer.name)
    }
  }
};
</script>

<style scoped>
tr,
td {
  /* border: 1px solid black; */
  border-right: 3px solid black;
  border-bottom: 3px solid black;
  margin: 14px;
  padding: 14px;
}
td:last-child {
  border-right: 0px solid black ;
  border-bottom: 3px solid black ;
}
tr:last-child,tr:last-child td {
  border-right: 3px solid black ;
  border-bottom: 0px solid black ;
}
tr:last-child td:last-child{
     border-right: 0px solid black ;
}
td {
  text-align: center;
}
</style>