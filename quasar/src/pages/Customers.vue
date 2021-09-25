<template>
  <q-page >
    <router-view :key="$route.fullPath"/>
  </q-page>
</template>

<script>
export default {
  name: 'CustomersRoot'
}
</script>
