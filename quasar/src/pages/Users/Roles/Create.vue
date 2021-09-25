<template>
  <q-page>
    <page-toolbar
    :buttons="toolbarButtons"
    v-on:save="saveFn()"
    />
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <div :class="$q.screen.gt.sm ? 'q-px-lg': 'q-px-xs'">
        <q-card>
            <q-card-section>
                <div class="row q-mt-sm">
                    <div class="col-12 col-md-3">
                        <q-input outlined dense square label="Name" v-model="name" :error-message="errors.name" :error="errors.name && errors.name.length > 0" @input="errors.name = null"/>
                    </div>
                </div>
            </q-card-section>
        </q-card>
    </div>
    <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
        <q-card>
          <q-tabs
            v-model="tab"
            dense
            class="text-grey"
            active-color="primary"
            indicator-color="primary"
            align="justify"
            narrow-indicator
          >
            <q-tab name="permissions" label="Permissions" />
            <q-tab name="notifications" label="Notification Subscriptions" />
          </q-tabs>
          <q-separator />
          <q-tab-panels v-model="tab" animated>
            <q-tab-panel name="permissions">
              <q-card-section>
                  <div class="row q-mt-sm">
                      <div class="col-12">
                          <q-btn label="Select All" tile dark color="primary" @click="selectAll()"/>
                          <q-btn label="Reset" class="q-ml-md" tile dark color="secondary" @click="resetFn()"/>
                      </div>
                  </div>
                  <template v-for="(items,index) in $_.groupBy(permissionsCollection,'model')">
                      <div class="row q-mt-sm q-col-gutter-md" :key="index">
                          <div class="col-12">
                              <div class="text-h5">{{index}}</div>
                          </div>
                          <div class="" v-for="(item,index) in items" :key="index">
                              <q-checkbox :label="item.name" :val="item.id" v-model="permissions"/>
                          </div>
                      </div>
                      <q-separator class="q-mt-md" :key="'a'+index"/>
                  </template>
              </q-card-section>
            </q-tab-panel>
            <q-tab-panel name="notifications">
              <q-card-section>
                <div class="row q-mt-sm">
                    <div class="col-12">
                      <q-btn label="Select All" tile dark color="primary" @click="selectAllNotifications()"/>
                      <q-btn label="Reset" class="q-ml-md" tile dark color="secondary" @click="resetNotifications()"/>
                    </div>
                </div>
                <q-markup-table>
                  <thead>
                    <th></th>
                    <th class="text-left">Name</th>
                    <th class="text-left">Description</th>
                  </thead>
                  <tbody>
                    <q-tr v-for="(item,index) in notificationCollection" :key="index">
                      <q-td>
                        <q-checkbox :val="item.id" v-model="notification_types" />
                      </q-td>
                      <q-td>{{item.name}}</q-td>
                      <q-td>{{item.description}}</q-td>
                    </q-tr>
                  </tbody>
                </q-markup-table>
              </q-card-section>
            </q-tab-panel>
          </q-tab-panels>
        </q-card>
    </div>
  </q-page>
</template>

<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'UserRolesCreate',
  components: {
    Breadcrumbs,
    PageToolbar
  },
  mounted () {
    // console.log(this.$route.params.id)
    if (!this.$store.getters.hasPermissionTo('create_user_role')) {
      this.$router.push({ name: 'Error403' })
    } else {
      Promise.all([
        this.$axios.get('all-permissions').then((res) => {
          this.permissionsCollection = res.data
          // console.log()
        }),
        this.$axios.get('notification_types').then((res) => {
          this.notificationCollection = res.data
          // console.log()
        })
      ])
      if (this.$route.params.id) {
        this.$axios.get('user_roles/' + this.$route.params.id).then((res) => {
          this.name = res.data.name
          this.permissions = Object.keys(this.$_.groupBy(res.data.permissions, 'id')).map(Number)
          this.notification_types = Object.keys(this.$_.groupBy(res.data.notification_types, 'id')).map(Number)
          this.$store.commit('setPageTitle', 'Edit User Role ' + this.name)
        })
      } else {
        this.$store.commit('setPageTitle', 'Create User Role')
      }
    }
  },
  data () {
    return {
      toolbarButtons: [
        {
          label: 'Save',
          id: 'save',
          type: 'button',
          color: 'teal'
        }
      ],
      name: null,
      permissionsCollection: [],
      permissions: [],
      notification_types: [],
      headers: ['Model', 'Create', 'Update', 'View', 'View Any', 'Delete'],
      errors: {
        name: null
      },
      tab: 'permissions',
      notificationCollection: []
    }
  },
  computed: {
    breadcrumbs () {
      return [
        { label: 'Dashboard', link: '/' },
        { label: 'User Roles', link: '/user_roles' },
        { label: this.$route.params.id ? this.name : 'Create', link: '', disabled: true }
      ]
    }
  },
  methods: {
    saveFn () {
      this.$q.loading.show()
      let route = 'user_roles'
      if (this.$route.params.id) {
        route = 'user_roles/' + this.$route.params.id
      }
      this.$axios.post(route, {
        permissions: this.permissions,
        notification_types: this.notification_types,
        name: this.name,
        _method: this.$route.params.id ? 'PUT' : 'POST'
      }).then((res) => {
        if (res.data.message === 'success') {
          this.$q.notify({
            type: 'positive',
            message: 'Role Saved Successfully'
          })
          this.$router.back()
        }
      }).catch((error) => {
        if (error.response.status === 422) {
          this.$q.notify({
            type: 'negative',
            message: error.response.data.message
          })
          Object.keys(error.response.data.errors).forEach((key, i) => {
            this.errors[key] = error.response.data.errors[key][0]
          })
        }
      }).finally(() => {
        this.$q.loading.hide()
      })
    },
    selectAll () {
      this.permissionsCollection.forEach((item) => {
        this.permissions.push(item.id)
      })
    },
    selectAllNotifications () {
      this.notificationCollection.forEach((item) => {
        this.notification_types.push(item.id)
      })
    },
    resetFn () {
      this.permissions = []
    },
    resetNotifications () {
      this.notification_types = []
    }
  }
}
</script>
