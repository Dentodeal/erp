<template>
  <q-page class="q-px-lg">
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <div class="row">
      <div class="col-12 col-md-3">
        <q-select dense outlined v-model="entity" :options="options" label="Entity" @input="getTableData"/>
      </div>
    </div>
    <div class="row q-mt-md" v-if="columns.length > 0">
      <div class="col">
        <div class="text-subtitle2" >Select Columns to Import</div>
        <q-btn flat color="grey-10" label="Select All" @click="selectedColumns = $_.map(columns, 'value')"/>
        <q-btn flat color="grey-10" label="Deselect All" @click="selectedColumns = []"/>
      </div>
    </div>
    <!--
    <div class="row q-col-gutter-md" v-if="columns.length > 0">
      <template v-for="(item,i) in columns">
        <div class="" :key="i">
          <q-checkbox v-model="selectedColumns" :val="item.value" :label="item.label"/>
        </div>
      </template>
    </div>
    -->
    <div class="row" v-if="columns.length > 0">
      <div class="col">
        <q-markup-table>
          <thead>
            <th class="text-left">Label</th>
            <th  class="text-left">Include</th>
            <th>Filter</th>
          </thead>
          <tbody>
            <tr v-for="(column,ind) in columns" :key="ind">
              <td>{{column.label}}</td>
              <td><q-checkbox v-model="selectedColumns" :val="column.value"/></td>
              <td>
                <q-input filled dense v-if="column.filterType === 'string'" v-model="columns[ind].filterBy"/>
                <q-select filled dense v-if="column.filterType === 'selection'" :options="column.filterOptions" multiple v-model="columns[ind].filterBy" use-chips/>
                <q-select filled dense v-if="column.filterType === 'boolean'" :options="['','Yes','No']" v-model="columns[ind].filterBy"/>
                <template v-if="column.filterType === 'date_range'">
                  {{ (column.filterBy.from && column.filterBy.to) ? column.filterBy.from + ' to ' + column.filterBy.to : '' }}
                  <q-btn icon="event" flat color="primary" no-caps  label="Pick Date Range">
                    <q-popup-proxy ref="qDateProxy" transition-show="scale" transition-hide="scale">
                      <q-date v-model="columns[ind].filterBy" range mask="YYYY-MM-DD">
                        <div class="row items-center justify-end">
                          <q-btn v-close-popup label="Close" color="primary" flat />
                        </div>
                      </q-date>
                    </q-popup-proxy>
                  </q-btn>
                </template>
              </td>
            </tr>
          </tbody>
        </q-markup-table>
      </div>
    </div>
    <div class="row q-mt-md">
      <div class="col">
        <q-btn unelevated tile color="primary" label="Generate" :disable="selectedColumns.length === 0" @click="exportFn"/>
        <q-btn v-if="download_link" unelevated tile color="green" class="q-ml-md" type="a" :href="download_link" target="_blank" label="Download"/>
      </div>
    </div>
  </q-page>
</template>
<script>
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'ExportsIndex',
  components: {
    Breadcrumbs
  },
  data () {
    return {
      selectedColumns: [],
      download_link: null,
      entity: null,
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Exports', link: '', disabled: true }
      ],
      pagePreferences: {
        page_index_autosearch: 'customers_index_autosearch'
      },
      options: [
        'Products', 'Customers', 'Customer Sale Summary'
      ],
      columns: []
    }
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Exports')
  },
  methods: {
    getTableData () {
      if (this.entity === 'Customers') {
        let typeOptions = []
        let countries = []
        let states = []
        let districts = []
        let representatives = []
        this.$q.loading.show()
        Promise.all([
          this.$axios.get('customers/types').then((res) => {
            typeOptions = res.data
          }),
          this.$axios.get('countries').then((res) => {
            countries = res.data
          }),
          this.$axios.get('states').then((res) => {
            states = res.data
          }),
          this.$axios.get('districts').then((res) => {
            districts = res.data
          }),
          this.$axios.get('representatives').then((res) => {
            representatives = res.data.map(v => v.name)
          })
        ]).then(() => {
          this.columns = [
            { label: 'Name', value: 'name', filterType: 'string', filterBy: null },
            { label: 'Type', value: 'type', filterType: 'selection', filterOptions: typeOptions, filterBy: [] },
            { label: 'Billing Address Line 1', value: 'address_line_1' },
            { label: 'Billing Address Line 2', value: 'address_line_2' },
            { label: 'Billing Address Country', value: 'address_country', filterType: 'selection', filterOptions: countries, filterBy: [] },
            { label: 'Billing Address State', value: 'address_state', filterType: 'selection', filterOptions: states, filterBy: [] },
            { label: 'Billing Address District', value: 'address_district', filterType: 'selection', filterOptions: districts, filterBy: [] },
            { label: 'Billing Address Pin Code', value: 'address_pin' },
            { label: 'Email(s)', value: 'emails' },
            { label: 'Phone(s)', value: 'phones' },
            { label: 'GST No', value: 'gst_number', filterType: 'string', filterBy: null },
            { label: 'Representative(s)', value: 'representatives', filterType: 'selection', filterOptions: representatives, filterBy: [] },
            { label: 'Status', value: 'status', filterType: 'selection', filterOptions: ['Draft', 'Pending Approval', 'Approved', 'Active', 'Disabled'], filterBy: [] }
          ]
        }).finally(() => this.$q.loading.hide())
      } else if (this.entity === 'Products') {
        let typeOptions = []
        let departmentOptions = []
        let categoryOptions = []
        let subCategoryOptions = []
        let brandOptions = []
        this.$q.loading.show()
        Promise.all([
          this.$axios.get('product_types').then((res) => {
            typeOptions = res.data.model.map(v => v.name)
          }),
          this.$axios.get('product_departments').then((res) => {
            departmentOptions = res.data.model.map(v => v.name)
          }),
          this.$axios.get('product_categories').then((res) => {
            categoryOptions = res.data.model.map(v => v.name)
          }),
          this.$axios.get('product_sub_categories').then((res) => {
            subCategoryOptions = res.data.model.map(v => v.name)
          }),
          this.$axios.get('product_brands').then((res) => {
            brandOptions = res.data.model.map(v => v.name)
          })
        ]).then(() => {
          this.columns = [
            { label: 'Name', value: 'name', filterType: 'string', filterBy: null },
            { label: 'SKU', value: 'sku' },
            { label: 'Status', value: 'status', filterType: 'selection', filterOptions: ['Active', 'Pending Approval', 'Approved', 'Draft'], filterBy: [] },
            { label: 'GST', value: 'gst', filterType: 'selection', filterOptions: ['5', '12', '18'], filterBy: [] },
            { label: 'HSN', value: 'hsn' },
            { label: 'GTIN', value: 'gtin' },
            { label: 'MPN / Reorder Code', value: 'mpn' },
            { label: 'weight', value: 'weight' },
            { label: 'Length', value: 'length' },
            { label: 'Breadth', value: 'breadth' },
            { label: 'Height', value: 'height' },
            { label: 'Remarks', value: 'remarks' },
            { label: 'Type', value: 'type', filterType: 'selection', filterOptions: typeOptions, filterBy: [] },
            { label: 'Department', value: 'department', filterType: 'selection', filterOptions: departmentOptions, filterBy: [] },
            { label: 'Category', value: 'category', filterType: 'selection', filterOptions: categoryOptions, filterBy: [] },
            { label: 'Sub Category', value: 'sub_category', filterType: 'selection', filterOptions: subCategoryOptions, filterBy: [] },
            { label: 'Brand', value: 'brand', filterType: 'selection', filterOptions: brandOptions, filterBy: [] },
            { label: 'Stock', value: 'stock' },
            { label: 'Cost', value: 'cost' }
          ]
        }).finally(() => this.$q.loading.hide())
      } else if (this.entity === 'Customer Sale Summary') {
        let representatives = []
        this.$q.loading.show()
        Promise.all([
          this.$axios.get('representatives').then((res) => {
            representatives = res.data.map(v => v.name)
          })
        ]).then(() => {
          this.columns = [
            { label: 'Customer has GST Number', value: 'gst_number', filterType: 'boolean', filterBy: null },
            { label: 'Invoiced At', value: 'invoiced_at', filterType: 'date_range', filterBy: { from: null, to: null } },
            { label: 'Source', value: 'source', filterType: 'selection', filterOptions: ['', 'Apexion', 'Dentodeal'], filterBy: [] },
            { label: 'Item count', value: 'item_count' },
            { label: 'Representative(s)', value: 'representatives', filterType: 'selection', filterOptions: representatives, filterBy: [] }
          ]
        }).finally(() => this.$q.loading.hide())
      } else {
        this.columns = []
      }
    },
    exportFn () {
      const filters = {}
      this.columns.forEach((col) => {
        if (col.filterType === 'boolean' || col.filterType === 'string') {
          if (col.filterBy && this.selectedColumns.includes(col.value)) filters[col.value] = col.filterBy
        } else if (col.filterType === 'selection') {
          if (col.filterBy.length > 0 && this.selectedColumns.includes(col.value)) filters[col.value] = col.filterBy
        } else if (col.filterType === 'date_range') {
          if (col.filterBy.from && col.filterBy.to) filters[col.value] = col.filterBy
        }
      })
      this.$q.loading.show()
      this.$axios({
        url: 'export',
        method: 'POST',
        data: {
          entity: this.entity,
          selected_columns: this.selectedColumns,
          filters: filters
        }
      }).then((response) => {
        this.download_link = this.$axios.defaults.authURL + response.data
      }).finally(() => {
        this.$q.loading.hide()
      })
    }
  }
}
</script>
