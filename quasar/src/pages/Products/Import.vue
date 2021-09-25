<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <q-card>
        <q-card-section>
            <div class="row q-col-gutter-md">
                <div class="col-12 col-md-6">
                    <q-card>
                        <q-card-section>
                            <q-file v-model="file" ref="upfile" label="Click here to Upload" clearable >
                                <template v-slot:prepend>
                                  <q-icon name="attach_file" />
                                </template>
                            </q-file>
                            <q-select :options="['create','update']" v-model="method" label="Method"/>
                            <q-btn color="teal" icon="backup" label="upload" class="q-mt-md" @click="process()"/>
                        </q-card-section>
                    </q-card>
                </div>
                <div class="col-12 col-md-6">
                    <q-card>
                        <q-card-section>
                            <div class="subtitle2">Log</div>
                            <q-list separator>
                                <q-item v-for="(item,i) in errors" :key="i" class="bg-negative text-white">
                                    <q-item-section>
                                        <q-item-label v-for="(it,j) in item.errors" :key="j">{{it}}</q-item-label>
                                        <q-item-label caption class="text-white">Row: {{item.row}}</q-item-label>
                                    </q-item-section>
                                </q-item>
                            </q-list>
                        </q-card-section>
                    </q-card>
                </div>
            </div>
        </q-card-section>
    </q-card>
  </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'ProductsImport',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Products Import')
  },
  data () {
    return {
      file: null,
      method: null,
      toolbarButtons: [],
      errors: [],
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Products', link: '/products' },
        { label: 'Import', link: '', disabled: true }
      ]
    }
  },
  methods: {
    process () {
      this.$q.loading.show()
      this.errors = []
      const fD = new FormData()
      fD.append('file', this.file)
      fD.append('method', this.method == null ? '' : this.method)
      this.$axios.post('/products/import', fD, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then((res) => {
        if (res.data.message === 'success') {
          this.$q.notify({
            type: 'positive',
            message: 'Saved Succesfully.'
          })
          this.errors = []
        }
      }).catch((error) => {
        if (error.response.status === 422) {
          this.errors = error.response.data.errors
          this.$q.notify({
            type: 'negative',
            message: error.response.data.message
          })
        }
      }).finally(() => {
        this.$q.loading.hide()
      })
    }
  }
}
</script>
