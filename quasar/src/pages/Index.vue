<template>
  <q-page class="q-pa-md">
    <div class="row q-col-gutter-md">
      <div class="col-12 col-md-6">
        <div class="row">
          <div class="col-12">
            <q-card style="" dark class="bg-grey-10">
              <q-item>
                <q-item-section>
                  <q-item-label>
                    <div class="text-h6" >Today Sales</div>
                  </q-item-label>
                  <q-item-label caption>
                    <div class="text-right text-white text-h6">OTC: <span class="text-green">{{parseFloat(sale_data.otc).toLocaleString('en',{minimumFractionDigits:2,maximumFractionDigits:2})}}</span></div>
                    <div class="text-right text-white text-h6">Non OTC: <span class="text-green">{{parseFloat(sale_data.non_otc).toLocaleString('en',{minimumFractionDigits:2,maximumFractionDigits:2})}}</span></div>
                    <div class="text-right text-white text-h6">Total: <span class="text-green">{{parseFloat(sale_data.total).toLocaleString('en',{minimumFractionDigits:2,maximumFractionDigits:2})}}</span></div>
                  </q-item-label>
                </q-item-section>
              </q-item>
            </q-card>
          </div>
          <div class="col-12 col-md-6">
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6">
        <div class="row">
          <div class="col-12">
            <q-card style="" dark class="bg-blue-grey-10">
              <q-item>
                <q-item-section>
                  <q-item-label>
                    <div class="text-h6" >Today Transactions</div>
                  </q-item-label>
                  <q-item-label caption>
                    <div class="text-right text-white text-h6">Cash: <span class="text-green">{{parseFloat(sale_data.cash).toLocaleString('en',{minimumFractionDigits:2,maximumFractionDigits:2})}}</span></div>
                  </q-item-label>
                </q-item-section>
              </q-item>
            </q-card>
          </div>
          <div class="col-12 col-md-6">
          </div>
        </div>
      </div>
    </div>
    <div class="q-col-gutter-md row q-mt-sm">
      <div class="col-12 col-md-6">
        <q-card style="" dark class="bg-cyan-7">
          <q-item>
            <q-item-section>
              <q-item-label>
                <div class="text-h6" >Sale Orders Status</div>
              </q-item-label>
              <q-markup-table dark separator="cell" class="bg-cyan-10" flat dense style="width:100%">
                <thead>
                  <th><q-btn flat dark no-caps @click="$router.push({path:'/sale_orders',query:{source:'Apexion'}})">Apexion</q-btn></th>
                  <th><q-btn flat dark no-caps @click="$router.push({path:'/sale_orders',query:{source:'Dentodeal'}})">Dentodeal</q-btn></th>
                </thead>
                <tbody v-if="sales_count">
                  <tr v-for="(key,i) in Object.keys(sales_count)" :key="i">
                    <template v-for="(item,j) in sales_count[key]">
                      <template v-if="sales_count[key].length === 2">
                        <template v-if="item.source === 'Apexion'">
                          <td :key="j+'ap'">
                            <q-btn flat dark no-caps @click="$router.push({path:'/sale_orders',query:{status: key,source:'Apexion'}})">
                                {{key}}: <q-badge color="white" class="q-ml-sm q-pa-xs"  text-color="grey-10">{{item.count || '0'}}</q-badge>
                            </q-btn>
                          </td>
                        </template>
                        <template v-if="item.source === 'Dentodeal'">
                          <td :key="j+'de'">
                            <q-btn flat dark no-caps @click="$router.push({path:'/sale_orders',query:{status: key,source:'Dentodeal'}})">
                                {{key}}: <q-badge color="white" class="q-ml-sm q-pa-xs"  text-color="grey-10">{{item.count || '0'}}</q-badge>
                            </q-btn>
                          </td>
                        </template>
                      </template>
                      <template v-else>
                        <template v-if="item.source === 'Apexion'">
                          <td :key="j+'en'">
                            <q-btn flat dark no-caps @click="$router.push({path:'/sale_orders',query:{status: key,source:'Apexion'}})">
                                {{key}}: <q-badge color="white" class="q-ml-sm q-pa-xs"  text-color="grey-10">{{item.count || '0'}}</q-badge>
                            </q-btn>
                          </td>
                          <td :key="j+'em'"></td>
                        </template>
                        <template v-if="item.source === 'Dentodeal'">
                          <td :key="j+'em'"></td>
                          <td :key="j+'en'">
                            <q-btn flat dark no-caps @click="$router.push({path:'/sale_orders',query:{status: key,source:'Dentodeal'}})">
                                {{key}}: <q-badge color="white" class="q-ml-sm q-pa-xs"  text-color="grey-10">{{item.count || '0'}}</q-badge>
                            </q-btn>
                          </td>
                        </template>
                      </template>
                    </template>
                  </tr>
                </tbody>
              </q-markup-table>
            </q-item-section>
          </q-item>
        </q-card>
      </div>
      <div class="col-12 col-md-6">
        <q-card style="" dark class="bg-blue-7">
          <q-item>
            <q-item-section>
              <q-item-label>
                <div class="text-h6" >Shipments Status</div>
              </q-item-label>
              <q-markup-table dark separator="cell" class="bg-blue -10" flat dense style="width:100%">
                <thead>
                  <th><q-btn flat dark no-caps @click="$router.push({path:'/shipments',query:{source:'Apexion'}})">Apexion</q-btn></th>
                  <th><q-btn flat dark no-caps @click="$router.push({path:'/shipments',query:{source:'Dentodeal'}})">Dentodeal</q-btn></th>
                </thead>
                <tbody v-if="shipments_count">
                  <tr v-for="(key,i) in Object.keys(shipments_count)" :key="i">
                    <template v-for="(item,j) in shipments_count[key]">
                      <template v-if="shipments_count[key].length === 2">
                        <template v-if="item.source === 'Apexion'">
                          <td :key="j+'ap'">
                            <q-btn flat dark no-caps @click="$router.push({path:'/shipments',query:{status: key,source:'Apexion'}})">
                                {{key}}: <q-badge color="white" class="q-ml-sm q-pa-xs"  text-color="grey-10">{{item.count || '0'}}</q-badge>
                            </q-btn>
                          </td>
                        </template>
                        <template v-if="item.source === 'Dentodeal'">
                          <td :key="j+'de'">
                            <q-btn flat dark no-caps @click="$router.push({path:'/shipments',query:{status: key,source:'Dentodeal'}})">
                                {{key}}: <q-badge color="white" class="q-ml-sm q-pa-xs"  text-color="grey-10">{{item.count || '0'}}</q-badge>
                            </q-btn>
                          </td>
                        </template>
                      </template>
                      <template v-else>
                        <template v-if="item.source === 'Apexion'">
                          <td :key="j+'en'">
                            <q-btn flat dark no-caps @click="$router.push({path:'/shipments',query:{status: key,source:'Apexion'}})">
                                {{key}}: <q-badge color="white" class="q-ml-sm q-pa-xs"  text-color="grey-10">{{item.count || '0'}}</q-badge>
                            </q-btn>
                          </td>
                          <td :key="j+'em'"></td>
                        </template>
                        <template v-if="item.source === 'Dentodeal'">
                          <td :key="j+'em'"></td>
                          <td :key="j+'en'">
                            <q-btn flat dark no-caps @click="$router.push({path:'/shipments',query:{status: key,source:'Dentodeal'}})">
                                {{key}}: <q-badge color="white" class="q-ml-sm q-pa-xs"  text-color="grey-10">{{item.count || '0'}}</q-badge>
                            </q-btn>
                          </td>
                        </template>
                      </template>
                    </template>
                  </tr>
                </tbody>
              </q-markup-table>
            </q-item-section>
          </q-item>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script>
export default {
  name: 'Dashboard',
  data () {
    return {
      today_sale: null,
      sale_data: {
        otc: null,
        non_otc: null,
        total: null
      },
      sales_count: null,
      shipments_count: null
    }
  },
  mounted () {
    this.updateSaleStatus()
    this.updateTodaySale()
    this.updateShipmentStatus()
    if (this.$store.getters.user) {
      // eslint-disable-next-line no-undef
      /*
      Echo.private('App.User.' + this.$store.getters.user.id).notification((notification) => {
        if (notification.action === 'invoiced') {
          this.updateTodaySale()
          this.updateSaleStatus()
        }
      })
      */
    }
    this.$store.commit('setPageTitle', 'ApexionERP')
  },
  methods: {
    updateSaleStatus () {
      this.$axios.get('sales/count').then((res) => {
        this.sales_count = this.$_.groupBy(res.data, 'status')
      })
    },
    updateTodaySale () {
      this.$axios.get('sales/today').then((res) => {
        this.sale_data = res.data
      })
    },
    updateShipmentStatus () {
      this.$axios.get('shipments/count').then((res) => {
        this.shipments_count = this.$_.groupBy(res.data, 'status')
      })
    }
  }
}
</script>
