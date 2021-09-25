<template>
  <q-page>
    <page-toolbar
        :buttons="toolbarButtons"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
      <div class="row q-col-gutter-md q-pa-md ">
        <div class="col-12">
          <q-markup-table wrap-cells separator="cell">
            <tbody>
              <tr>
                <td>Name</td>
                <td>{{model.name}}</td>
                <td>SKU</td>
                <td>{{model.sku}}</td>
              </tr>
            </tbody>
          </q-markup-table>
        </div>
      </div>
      <div class="row q-col-gutter-md q-pa-md ">
        <div class="col">
          <q-table
            class="my-sticky-header-table"
            :columns="columns"
            title="Items"
            :data="model.items"
            row-key="sl_no"
            wrap-cells
            :loading="table_loading"
            :rows-per-page-options="[0]"
            :filter="search"
            >
            <template v-slot:body="props">
              <q-tr :props="props">
                <q-td class="text-right">{{props.rowIndex+1}}</q-td>
                <q-td>
                  {{props.row.product.name}}
                  <q-btn icon="content_copy" flat round @click="$store.dispatch('copyToClipboard', props.row.product.name)"></q-btn>
                </q-td>
                <q-td class="text-right">
                  {{ parseFloat(props.row.qty).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}
                </q-td>
              </q-tr>
            </template>
            <template v-slot:top-right="props">
              <q-input borderless dense debounce="300" v-model="search" placeholder="Search">
              <template v-slot:append>
                <q-icon name="search" />
              </template>
              </q-input>
              <q-btn
                flat round dense
                :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                @click="props.toggleFullscreen"
                class="q-ml-md"
              />
            </template>
          </q-table>
        </div>
      </div>
    </div>
  </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'ProductBundlesView',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  data () {
    return {
      model: {
        name: null,
        sku: null,
        items: []
      },
      toolbarButtons: [
        {
          label: 'Edit',
          id: 'edit',
          type: 'a',
          link: '/product_bundles/edit/' + this.$route.params.id,
          color: 'teal',
          icon: 'edit'
        }
      ],
      columns: [
        {
          name: 'sl_no',
          field: 'sl_no',
          label: '#',
          sortable: true
        },
        {
          name: 'name',
          field: 'name',
          label: 'Product',
          sortable: true,
          align: 'left'
        },
        {
          name: 'qty',
          field: 'qty',
          label: 'Qty',
          sortable: true
        }
      ]
    }
  },
  mounted () {
    this.getDataFromApi()
  },
  computed: {
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Product Bundles', link: '/product_bundles' },
        { label: this.model.name }
      ]
      return arr
    }
  },
  methods: {
    getDataFromApi () {
      this.$q.loading.show()
      this.$axios.get('product_bundles/' + this.$route.params.id).then((res) => {
        this.model = res.data
      }).finally(() => {
        this.$store.commit('setPageTitle', this.model.name)
        this.$q.loading.hide()
      })
    },
    getLocaleString (val) {
      if (val) {
        const d = new Date(val)
        return d.toLocaleString('en-IN')
      }
      return ''
    }
  }
}
</script>
