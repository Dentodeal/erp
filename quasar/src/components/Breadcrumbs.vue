<template>
   <div class="q-pa-md q-gutter-sm">
      <q-breadcrumbs>
        <template v-for="item in breadcrumbs">
          <q-breadcrumbs-el :to="item.link" :label="item.label" :disabled="item.disabled ? true : false" :key="item.label"/>
        </template>
      </q-breadcrumbs>
    </div>
</template>

<script>
export default {
  props: {
    breadcrumbs: {
      type: Array,
      required: true
    }
  },
  data () {
    return {}
  }
}
</script>
