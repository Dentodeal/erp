<template>
  <q-toolbar class=" q-pr-none" :style="{background:'rgba(200,200,200,0.5)'}">
    <div class="row col self-stretch">
      <div class="col-12 justify-end row">
        <q-btn v-if="$q.screen.gt.sm" icon="arrow_back" flat label="Back" color="teal-9" @click="$router.back()" style="border-radius:0;" class="self-stretch"/>
        <template v-for="item in buttons">
          <q-btn
            :key="item.label"
            :color="item.color || 'teal'"
            :text-color="item.textColor"
            :label="item.label"
            :flat="item.flat ? true : false"
            tile unelevated
            style="border-radius:0;"
            :class="$q.screen.gt.sm ? 'q-ml-sm':'q-ml-sm q-mt-sm'"
            :icon="item.icon"
            :type="item.type"
            :to="item.link"
            @click="$emit(item.id)"/>
        </template>
      </div>
    </div>
  </q-toolbar>
</template>

<script>
export default {
  props: {
    buttons: {
      type: Array,
      default: () => ([])
    }
  },
  data () {
    return {}
  },
  methods: {
  }

}
</script>
