<template>
  <q-page >
      <breadcrumbs :breadcrumbs="breadcrumbs"/>
      <div class="row q-px-md">
        <div class="col-6 column">
          <q-btn class="self-end" flat dense no-caps label="Mark all as read" color="blue-8"/>
          <q-list>
            <q-item clickable v-ripple class="bg-blue-2 shadow-1 q-mt-md">
              <q-item-section>
                <q-item-label>
                  New Sale Order Created
                </q-item-label>
                <q-item-label caption lines="1">Created By: Muneer M.P</q-item-label>
              </q-item-section>
              <q-item-section side top>
                <q-item-label caption>5 min ago</q-item-label>
                <q-btn flat dense label="Mark as read" no-caps color="blue-8" size="md"/>
              </q-item-section>
            </q-item>
            <q-item clickable v-ripple class="bg-blue-1 q-mt-md shadow-1">
              <q-item-section>
                <q-item-label>
                  New Sale Order Created
                </q-item-label>
                <q-item-label caption lines="1">Created By: Muneer M.P</q-item-label>
              </q-item-section>
              <q-item-section side top>
                <q-item-label caption>5 min ago</q-item-label>
                <q-btn flat dense label="view"/>
              </q-item-section>
            </q-item>
          </q-list>
        </div>
      </div>
  </q-page>
</template>

<script>
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'Notifications',
  components: {
    Breadcrumbs
  },
  data () {
    return {
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Notifications', link: '/notifications' }
      ]
    }
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Notifications')
  }
}
</script>
