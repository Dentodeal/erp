<template>
    <q-page>
        <page-toolbar page-title="Phones" :buttons="toolbarButtons"/>
        <breadcrumbs :breadcrumbs="breadcrumbs"/>
        <q-card>
            <q-card-section>
                <div class="row">
                    <div class="col-6">
                        <q-markup-table>
                            <thead>
                                <th></th>
                                <th></th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Country Code</td>
                                    <td>{{country_code}}</td>
                                </tr>
                                <tr>
                                    <td>Phone</td>
                                    <td>{{phone}}</td>
                                </tr>
                                <tr>
                                    <td>Source</td>
                                    <td>{{source}}</td>
                                </tr>
                                <tr>
                                    <td>Tags</td>
                                    <td>{{tags.toString()}}</td>
                                </tr>
                            </tbody>
                        </q-markup-table>
                    </div>
                </div>
            </q-card-section>
            <q-card-section>
                <div class="row">
                    <div class="col">
                        <div class="text-subtitle2 q-mb-md">Related To</div>
                        <q-markup-table>
                            <thead>
                                <th class="text-left">Addressable Type</th>
                                <th class="text-left">Name</th>
                                <th class="text-left">Address</th>
                                <th class="text-left">Type</th>
                            </thead>
                            <tbody>
                                <tr v-for="(address,i) in addresses" :key="i">
                                    <td>{{address.addressable_type.substr(4)}}</td>
                                    <td><router-link :to="address.addressable.is_lead ? '/leads/view/'+address.addressable.id+'/'+phone: '/customers/view/'+address.addressable.id+'/'+phone">{{address.addressable.name}}</router-link></td>
                                    <td>{{getAddress(address)}}</td>
                                    <td>{{address.tag_name}}</td>
                                </tr>
                            </tbody>
                        </q-markup-table>
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
  name: 'PhonesView',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  data () {
    return {
      country_code: null,
      phone: null,
      source: null,
      tags: [],
      addresses: []
    }
  },
  computed: {
    breadcrumbs () {
      return [
        { label: 'Dashboard', link: '/' },
        { label: 'Phones', link: '/phones' },
        { label: this.phone, link: '' }
      ]
    },
    toolbarButtons () {
      const arr = []
      return arr
    }

  },
  mounted () {
    this.$q.loading.show()
    this.$axios.get('phones/' + this.$route.params.id).then((res) => {
      this.country_code = res.data.country_code
      this.phone = res.data.content
      this.source = res.data.source
      this.tags = res.data.tags.map((tag) => {
        return tag.content
      })
      this.addresses = res.data.addresses
    }).finally(() => {
      this.$q.loading.hide()
      this.$store.commit('setPageTitle', 'Phones: ' + this.phone)
    })
  },
  methods: {
    getAddress (addr) {
      let str = ''
      str += addr.line_1 + ', '
      if (addr.line_2) { str += addr.line_2 + ', ' }
      if (addr.pin) { str += addr.pin + ', ' }
      if (addr.district) {
        str += addr.district + ', '
      }
      str += addr.state + ', '
      str += addr.country + ', '
      addr.phones.forEach((item) => {
        str += '(' + item.country_code + ')' + item.content + ', '
      })
      str = str.substr(0, str.length - 2)
      return str
    }
  }
}
</script>
