<template>
  <q-expansion-item
    group="group"
    v-if="children.length > 0"
    :icon="icon"
    dark
    :label="title">
    <q-list v-for="item in children" :key="item.link" class="bg-blue-grey-8" :content-inset-level="0">
      <q-item
        style="padding-left:15px;"
        active-class="bg-light-blue-5 text-lime-5"
        clickable
        tage="a"
        :to="link+item.link"
      >
        <q-item-section>
            <q-item-label>{{ item.title }}</q-item-label>
        </q-item-section>
      </q-item>
    </q-list>
  </q-expansion-item>
  <q-item
    exact
    v-else
    clickable
    tag="a"
    active-class="bg-light-blue-5 text-lime-5"
    :to="link"
  >
    <q-item-section
      v-if="icon"
      avatar
    >
      <q-icon :name="icon" />
    </q-item-section>
    <q-item-section>
      <q-item-label>{{ title }}</q-item-label>
    </q-item-section>
  </q-item>
</template>

<script>
export default {
  name: 'EssentialLink',
  props: {
    title: {
      type: String,
      required: true
    },

    caption: {
      type: String,
      default: ''
    },

    link: {
      type: String,
      default: '#'
    },

    icon: {
      type: String,
      default: ''
    },

    children: {
      type: Array,
      default: () => ([])
    }
  }
}
</script>
