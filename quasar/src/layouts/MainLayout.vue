<template>
  <q-layout view="lHh Lpr lFf"  v-if="authenticated">
    <q-header elevated>
      <q-toolbar class="gradient-cyan-10-cyan-9-90deg">
        <q-btn
          v-if="authenticated"
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="leftDrawerOpen = !leftDrawerOpen"
          />
        <q-toolbar-title>
          {{$store.state.pageTitle}}
        </q-toolbar-title>
        <div class="text-subtitle2 q-mr-lg">Hello {{$store.getters.user.name}}</div>
        <q-btn icon="notifications" dense flat to="/notifications">
          <q-badge v-if="unread_notifications_count > 0" color="orange" floating>{{unread_notifications_count}}</q-badge>
        </q-btn>
        <q-btn flat round icon="account_circle"/>
        <q-btn flat round icon="logout" @click="logout()"/>
      </q-toolbar>
    </q-header>
    <q-drawer
      v-if="authenticated"
      :width="200"
      v-model="leftDrawerOpen"
      show-if-above
      no-border
      content-class="text-white no-border gradient-cyan-9-cyan-10-90deg"
      >
      <q-list>
        <EssentialLink
        v-for="link in linksData"
        :key="link.title"
        v-bind="link"
        />
      </q-list>
    </q-drawer>
    <q-page-container :style="{backgroundImage:'linear-gradient(rgba(255,255,255,.9), rgba(255,255,255,.9)), url(/apexion_logo.svg)',backgroundSize:'80%',backgroundPosition:'50% 50%',backgroundRepeat:'no-repeat'}">
      <router-view />
    </q-page-container>
  </q-layout>
</template>
<style lang="sass">
.gradient-cyan-9-cyan-10-90deg
  background-image: linear-gradient(30deg,#2c3e50,#2980b9)
.gradient-cyan-10-cyan-9-90deg
  background-image: linear-gradient(90deg,#2980b9,#2c3e50)
.my-sticky-heder-table
  /* height or max-height is important */
  height: 90vh
  .q-table__top,
  thead tr:first-child th
    /* bg color is important for th; just specify one */
    background-color: #f2f2f2
  thead tr th
    position: sticky
    z-index: 1
  thead tr:first-child th
    top: 0
  /* this is when the loading indicator appears */
  &.q-table--loading thead tr:last-child th
    /* height of all previous header rows */
    top: 48px
</style>
<script>
import EssentialLink from 'components/EssentialLink.vue'
import { mapState } from 'vuex'
export default {
  name: 'MainLayout',
  components: { EssentialLink },
  data () {
    return {
      leftDrawerOpen: false,
      unread_notifications_count: 0
    }
  },
  computed: {
    linksData () {
      const data = []
      data.push({
        title: 'Dashboard',
        icon: 'home',
        link: '/'
      })
      if (
        this.$store.getters.hasPermissionTo('viewAny_product') ||
        this.$store.getters.hasPermissionTo('viewAny_category') ||
        this.$store.getters.hasPermissionTo('viewAny_taxonomy') ||
        this.$store.getters.hasPermissionTo('viewAny_product_attribute') ||
        this.$store.getters.hasPermissionTo('viewAny_product_attribute_set') ||
        this.$store.getters.hasPermissionTo('viewAny_pricelist')
      ) {
        const children = []
        if (this.$store.getters.hasPermissionTo('viewAny_product')) {
          children.push({
            title: 'Products',
            link: '/products'
          })
        }
        if (this.$store.getters.hasPermissionTo('viewAny_product')) {
          children.push({
            title: 'Product Bundles',
            link: '/product_bundles'
          })
          children.push({
            title: 'Product Types',
            link: '/product_types'
          })
          children.push({
            title: 'Department',
            link: '/product_departments'
          })
          children.push({
            title: 'Categories',
            link: '/product_categories'
          })
          children.push({
            title: 'Sub Categories',
            link: '/product_sub_categories'
          })
          children.push({
            title: 'Brands',
            link: '/product_brands'
          })
        }
        if (this.$store.getters.hasPermissionTo('viewAny_pricelist')) {
          children.push({
            title: 'Pricelists',
            link: '/pricelists'
          })
        }
        data.push({
          title: 'Catalog',
          icon: 'archive',
          link: '',
          children: children
        })
      }
      if (
        this.$store.getters.hasPermissionTo('viewAny_customer')
      ) {
        const children = []
        if (this.$store.getters.hasPermissionTo('viewAny_customer')) {
          children.push({
            title: 'Customers',
            link: '/customers'
          })
        }
        if (this.$store.getters.hasPermissionTo('viewAny_lead')) {
          children.push({
            title: 'Leads',
            link: '/leads'
          })
        }
        if (this.$store.getters.hasPermissionTo('viewAny_quotation')) {
          children.push({
            title: 'Quotations',
            link: '/quotations'
          })
        }
        if (this.$store.getters.hasPermissionTo('viewAny_quotation')) {
          children.push({
            title: 'Quotation Templates',
            link: '/quotation_templates'
          })
        }
        data.push({
          title: 'CRM',
          icon: 'supervisor_account',
          link: '',
          children: children
        })
      }
      if (
        this.$store.getters.hasPermissionTo('viewAny_lead')
      ) {
        const children = []
        if (this.$store.getters.hasPermissionTo('viewAny_lead')) {
          children.push({
            title: 'Phones',
            link: '/phones'
          })
        }
        if (this.$store.getters.hasPermissionTo('viewAny_lead')) {
          children.push({
            title: 'Emails',
            link: '/emails'
          })
        }
        data.push({
          title: 'Marketing',
          icon: 'public',
          link: '',
          children: children
        })
      }
      if (
        this.$store.getters.hasPermissionTo('viewAny_sale_order')
      ) {
        const children = []
        if (this.$store.getters.hasPermissionTo('viewAny_sale_order')) {
          children.push({
            title: 'Sale Orders',
            link: '/sale_orders'
          })
          children.push({
            title: 'Sale Order Revisit',
            link: '/sale_order_revisits'
          })
          children.push({
            title: 'Sale Returns',
            link: '/sale_returns'
          })
          children.push({
            title: 'Exports',
            link: '/export_sales'
          })
        }
        data.push({
          title: 'Sales',
          icon: 'point_of_sale',
          link: '',
          children: children
        })
      }
      if (
        this.$store.getters.hasPermissionTo('viewAny_shipment') ||
                this.$store.getters.hasPermissionTo('viewAny_logistic_partner')
      ) {
        const children = []
        if (this.$store.getters.hasPermissionTo('viewAny_sale_order')) {
          children.push({
            title: 'Shipments',
            link: '/shipments'
          })
          children.push({
            title: 'Logistic Partners',
            link: '/logistic_partners'
          })
        }
        data.push({
          title: 'Logistics',
          icon: 'local_shipping',
          link: '',
          children: children
        })
      }
      if (this.$store.getters.hasPermissionTo('viewAny_purchase')) {
        const children = []
        if (this.$store.getters.hasPermissionTo('viewAny_purchase')) {
          children.push({
            title: 'Purchases',
            link: '/purchases'
          })
          children.push({
            title: 'Purchase Returns',
            link: '/purchase_returns'
          })
          children.push({
            title: 'Suppliers',
            link: '/suppliers'
          })
        }
        data.push({
          title: 'Purchases',
          icon: 'receipt',
          link: '',
          children: children
        })
      }
      if (
        this.$store.getters.hasPermissionTo('access_inventory')
      ) {
        const children = []
        if (this.$store.getters.hasPermissionTo('viewAny_warehouse')) {
          children.push({
            title: 'Warehouses',
            link: '/warehouses'
          })
          children.push({
            title: 'Stock Entries',
            link: '/stock_entries'
          })
        }
        if (this.$store.getters.hasPermissionTo('create_purchase')) {
          children.push({
            title: 'Goods Receive Notes',
            link: '/goods_receive_notes'
          })
        }
        /*
        if(this.$store.getters.hasPermissionTo('access_inventory')){
            children.push({
                title:'Inventory Adjustments',
                link:'/inventory_adjustments'
            })
        } */
        data.push({
          title: 'Inventory',
          icon: 'dns',
          link: '',
          children: children
        })
      }
      if (
        this.$store.getters.hasPermissionTo('viewAny_user') ||
                this.$store.getters.hasPermissionTo('viewAny_user_role')
      ) {
        const children = []
        if (this.$store.getters.hasPermissionTo('viewAny_user')) {
          children.push({
            title: 'Users',
            link: '/users'
          })
        }
        if (this.$store.getters.hasPermissionTo('viewAny_user_role')) {
          children.push({
            title: 'User Roles',
            link: '/user_roles'
          })
        }
        data.push({
          title: 'Users',
          icon: 'people',
          link: '',
          children: children
        })
      }
      if (
        this.$store.getters.hasPermissionTo('export_product') ||
        this.$store.getters.hasPermissionTo('export_customer') ||
        this.$store.getters.hasPermissionTo('export_sale_order') ||
        this.$store.getters.hasPermissionTo('export_lead') ||
        this.$store.getters.hasPermissionTo('export_quotation') ||
        this.$store.getters.hasPermissionTo('export_phone')
      ) {
        data.push({
          title: 'Exports',
          icon: 'get_app',
          link: '/exports'
        })
      }
      if (this.$store.getters.hasRole('Super Admin') || this.$store.getters.hasRole('Admin')) {
        data.push({
          title: 'Settings',
          icon: 'settings',
          link: '/settings'
        })
      }
      return data
    },
    ...mapState(['authenticated', 'appLoading'])
  },
  watch: {
    authenticated (newValue, oldValue) {
      if (this.$store.getters.isAuthenticated) {
        this.$router.push({ name: 'Dashboard' })
      } else {
        this.$router.push({ name: 'LoginPage' })
      }
    }
  },
  mounted () {
    this.$store.dispatch('init')
    if (!this.$store.getters.isAuthenticated) {
      this.$router.push({ name: 'LoginPage' })
    }
    window.intercepted.$on('error403', (message) => {
      // this.triggerSb(message,'error')
      this.$q.notify({
        type: 'negative',
        message: message
      })
    })
    window.intercepted.$on('error500', () => {
      this.$q.notify({
        type: 'negative',
        message: 'Something went wrong!!'
      })
    })
    window.intercepted.$on('error404', () => {
      this.$router.push({ name: 'Error404' })
    })
    window.intercepted.$on('error401', () => {
      this.$store.commit('setAuthenticated', false)
    })
    if (this.$store.getters.user) {
      // Defined in store/index.js as window.Echo
      // eslint-disable-next-line no-undef
      /*
      Echo.private('App.User.' + this.$store.getters.user.id)
        .notification((notification) => {
          if (notification.action === 'notification') {
            this.unread_notifications_count = notification.count
          }
        })
        */
    }
  },
  methods: {
    logout () {
      this.$q.dialog({
        title: 'Confirm',
        message: 'Do you really want to logout?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        this.$q.loading.show()
        this.$store.dispatch('logout').then(() => {
          this.$q.loading.hide()
        }).catch((err) => {
          this.$q.loading.hide()
          throw new Error(`Problem handling something: ${err}.`)
        })
      }).onCancel(() => {
        // console.log('>>>> Cancel')
      })
    },
    notifyFn () {
      this.$q.dialog({
        title: 'Confirm',
        message: 'Would you like to continue?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        this.$axios.get('notify').then((res) => {
          this.$q.notify({ message: 'Done Done' })
        })
      })
    }
  }
}
</script>
