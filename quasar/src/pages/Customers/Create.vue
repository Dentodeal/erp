<template>
    <customer-form :is-lead="false"/>
</template>
<script>
import CustomerForm from 'components/CustomerForm.vue'
export default {
  name: 'CustomersCreate',
  components: {
    CustomerForm
  },
  data () {
    return {}
  }
}
</script>
