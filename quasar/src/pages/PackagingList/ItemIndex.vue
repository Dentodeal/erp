<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <div :class="$q.screen.gt.sm ? 'q-px-lg': 'q-px-xs'">
      <q-card>
        <q-card-section>
          <q-table
            title="Items"
            :data="model.items"
            :columns="columns"
            :dense="$q.screen.lt.md"
            :filter="search"
            :loading="loading"
            :rows-per-page-options="[25,50,100]"
            >
            <template v-slot:loading>
                <q-inner-loading showing>
                    <q-spinner-gears size="50px" color="primary" />
                </q-inner-loading>
            </template>
            <template v-slot:top="props">
                <div class="col">
                    <q-input dense debounce="300" placeholder="Search here.." square v-model="search" clearable>
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                </div>
                <q-space/>
                <q-btn
                flat round dense
                :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                @click="props.toggleFullscreen"
                class="q-ml-md"
                />
            </template>
            <template v-slot:body-cell-box="props">
              <q-td>
                {{$_.map(props.row.product.boxes, 'name').join()}}
              </q-td>
            </template>
          </q-table>
        </q-card-section>
      </q-card>
    </div>
  </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'ItemIndex',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  data () {
    return {
      model: {
        items: []
      },
      columns: [
        {
          name: 'product',
          field: row => row.product.name,
          label: 'Product Name',
          sortable: true,
          align: 'left'
        },
        {
          name: 'box',
          field: 'box',
          label: 'Box',
          sortable: false,
          align: 'left'
        }
      ],
      search: null,
      loading: false
    }
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Packaging Items')
    this.loading = true
    this.$axios.get('packaging/' + this.$route.params.so_id + '/items').then((res) => {
      this.model = res.data
    }).finally(() => {
      this.loading = false
    })
  },
  computed: {
    toolbarButtons () {
      const arr = []
      return arr
    },
    breadcrumbs () {
      return [
        { label: 'Dashboard', link: '/' },
        { label: 'Sale Orders', link: '/sale_orders' },
        { label: this.model.serial_no, link: '/sale_orders/view/' + this.$route.params.so_id },
        { label: 'Packaging', link: '/packaging/' + this.$route.params.so_id },
        { label: 'Items', link: '' }
      ]
    }
  },
  methods: {}
}
</script>
