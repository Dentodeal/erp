<template>
  <q-page>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
      <div class="row bg-grey-4">
        <div class="col q-pa-md">
          <div class="text-subtitle1 text-center">Items</div>
          <div class="row">
            <div class="col q-px-sm bg-white">
              <q-input label="Search" outlines square v-model="search" class="" @input="searchFn"/>
            </div>
          </div>
          <q-scroll-area
            :thumb-style="thumbStyle"
            :bar-style="barStyle"
            style="height: calc(100vh - 300px);"
            ref="scroll_1"
          >
            <q-list padding class="text-primary bg-white">
              <template v-for="(item,i) in model.items">
                <q-item :key="i"
                clickable :active="selected_item === i"
                @click="selected_item = i" active-class="bg-primary text-white">
                  <q-item-section>{{item.product.name}}</q-item-section>
                </q-item>
                <q-separator :key="i+'a'" v-if="(model.items.length - 1) != i"/>
              </template>
            </q-list>
          </q-scroll-area>
        </div>
        <div class="col-2 column items-center justify-center">
          <q-btn round icon="keyboard_arrow_right" class="" color="grey-9" @click="clickRight()"/>
          <q-btn round icon="keyboard_arrow_left" class="q-mt-sm" color="grey-9" @click="clickLeft()"/>
          <q-btn label="Generate" color="primary" class="q-mt-md" @click="generate"/>
          <q-btn label="Clear" color="grey-8" class="q-mt-md" @click="clearList"/>
          <q-btn label="Reload" color="secondary" class="q-mt-md" @click="reload"/>
        </div>
        <div class="col q-pa-md">
          <div class="text-subtitle1 text-center">Packed Items</div>
          <div class="row">
            <div class="col q-px-sm bg-white">
              <q-input label="Box Label" outlines square v-model="box_label" class=""
              :rules="[v => !!v || 'Required']"
              ref="box_label"/>
            </div>
          </div>
          <q-scroll-area
            :thumb-style="thumbStyle"
            :bar-style="barStyle"
            style="height: calc(100vh - 340px);"
          >
            <q-list padding class="text-primary bg-white">
              <template v-for="(item,i) in packed_items">
                <q-item :key="i"
                clickable :active="selected_packed === i"
                @click="selected_packed = i" active-class="bg-primary text-white">
                  <q-item-section>{{item.product.name}}</q-item-section>
                </q-item>
                <q-separator :key="i+'a'" v-if="(packed_items.length - 1) != i"/>
              </template>
            </q-list>
          </q-scroll-area>
        </div>
      </div>
    </div>
  </q-page>
</template>
<script>
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'SaleOrdersPackaging',
  components: {
    Breadcrumbs
  },
  data () {
    return {
      model: {
        items: []
      },
      box_label: null,
      packed_items: [],
      selected_item: null,
      selected_packed: null,
      search: null,
      thumbStyle: {
        right: '4px',
        borderRadius: '5px',
        backgroundColor: '#027be3',
        width: '5px',
        opacity: 0.75
      },
      barStyle: {
        right: '2px',
        borderRadius: '9px',
        backgroundColor: '#027be3',
        width: '9px',
        opacity: 0.2
      }
    }
  },
  computed: {
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Sale Orders', link: '/sale_orders' },
        { label: this.model.serial_no }
      ]
      return arr
    }
  },
  mounted () {
    this.getData()
  },
  methods: {
    searchFn (val) {
      this.selected_item = this.$_.findIndex(this.model.items, v => v.product.name.toLowerCase().indexOf(val) > -1)
      console.log(this.$refs.scroll_1.$el.offsetHeight)
      const pos = (this.selected_item + 1) * 45
      this.$refs.scroll_1.setScrollPosition(pos, 300)
    },
    generate () {
      if (this.$refs.box_label.validate()) {
        this.$q.dialog({
          title: 'Confirm',
          message: 'Continue?'
        }).onOk(() => {
          this.$q.loading.show()
          this.$axios({
            url: 'sale_orders/packaging/' + this.$route.params.id,
            method: 'POST',
            responseType: 'blob',
            data: {
              label: this.box_label,
              items: this.packed_items.map((item) => {
                return item.product.name
              })
            }
          }).then((response) => {
            const url = window.URL.createObjectURL(new Blob([response.data]))
            const link = document.createElement('a')
            link.href = url
            link.setAttribute('download', this.model.serial_no.replace(/\//g, '') + '_box.pdf')
            document.body.appendChild(link)
            link.click()
          }).finally(() => {
            this.$q.loading.hide()
          })
        })
      }
    },
    clearList () {
      this.$q.dialog({
        title: 'Confirm',
        message: 'Continue?'
      }).onOk(() => {
        this.packed_items = []
      })
    },
    reload () {
      this.$q.dialog({
        title: 'Confirm',
        message: 'Continue?'
      }).onOk(() => {
        this.getData()
      })
    },
    getData () {
      this.$q.loading.show()
      this.$axios.get('sale_orders/' + this.$route.params.id).then((res) => {
        this.model = res.data
      }).finally(() => {
        this.$store.commit('setPageTitle', 'Packaging for: ' + this.model.serial_no)
        this.$q.loading.hide()
      })
    },
    clickLeft () {
      if (this.selected_packed !== null) {
        this.model.items.push(this.packed_items[this.selected_packed])
        this.packed_items.splice(this.selected_packed, 1)
        this.selected_packed = null
      }
    },
    clickRight () {
      if (this.selected_item !== null) {
        this.packed_items.push(this.model.items[this.selected_item])
        this.model.items.splice(this.selected_item, 1)
        this.selected_item = null
      }
    }
  }
}
</script>
