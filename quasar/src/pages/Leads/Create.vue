<template>
    <customer-form :is-lead="true"/>
</template>
<script>
import CustomerForm from 'components/CustomerForm.vue'
export default {
  name: 'LeadsCreate',
  components: {
    CustomerForm
  },
  data () {
    return {}
  }
}
</script>
