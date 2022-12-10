<template>
  <table v-if="finalData.length > 0">
    <table-operation-row :index="'ID'" :operation="tableTitles" />
    <table-operation-row
      v-for="(operation, index) in finalData"
      :key="index"
      :index="index + 1"
      :operation="operation"
      @customerNameClicked="(value)=> $emit('customerNameClicked',value)"
    />
  </table>
</template>

<script>
import TableOperationRow from './TableOperationRow.vue';
export default {
  components: { TableOperationRow },
  props: ["tableTitles", "finalData"],
};
</script>

<style scoped>
table {
  margin-left: auto;
  margin-right: auto;
  margin-top: 3rem;
  border-spacing: 0px !important; 
}
</style>