<template>
  <q-page>
    <page-toolbar
    page-title="Create User"
    :buttons="toolbarButtons"
    v-on:save="saveFn()"
    v-on:change-password="changePasswordDialog = true"
    />
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <div :class="$q.screen.gt.sm ? 'q-px-lg': 'q-px-xs'">
      <q-card>
        <q-card-section>
            <div class="row q-mt-sm">
                <div class="col-12 col-md-4">
                    <q-toggle label="Active" v-model="active"/>
                </div>
            </div>
            <div class="row q-mt-sm">
                <div class="col-12 col-md-4">
                    <q-input outlined dense square label="Name" v-model="name" :error-message="errors.name" :error="errors.name && errors.name.length > 0" @input="errors.name = null"/>
                </div>
            </div>
            <div class="row q-mt-sm">
                <div class="col-12 col-md-4">
                    <q-input outlined dense square label="E-mail" v-model="email" :error-message="errors.email" :error="errors.email && errors.email.length > 0" @input="errors.email = null"/>
                </div>
            </div>
            <div class="row q-mt-sm" v-if="!$route.params.id">
                <div class="col-12 col-md-4">
                    <q-input
                    outlined
                    dense square
                    label="Password"
                    :type="isPwd ? 'password' : 'text'"
                    v-model="password"
                    :error-message="errors.password"
                    :error="errors.password && errors.password.length > 0"
                    @input="errors.password = null"
                    >
                        <template v-slot:append>
                            <q-icon
                            :name="isPwd ? 'visibility_off' : 'visibility'"
                            class="cursor-pointer"
                            @click="isPwd = !isPwd"
                            />
                        </template>
                    </q-input>
                </div>
            </div>
            <div class="row q-mt-sm">
                <div class="col-12 col-md-4">
                    <q-select
                    :options="roleOptions"
                    outlined dense square multiple
                    label="Roles"
                    v-model="roles"
                    :error-message="errors.roles"
                    :error="errors.roles && errors.roles.length > 0"
                    @input="errors.roles = null; updatePermissions();"
                    option-value="id"
                    option-label="name"
                    />
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
                  <div class="text-h6">Permissions</div>
                  <q-banner inline-actions class="text-white bg-red" v-if="errors.permissions && errors.permissions.length > 0">
                    Permissions should not be empty
                    <template v-slot:action>
                      <q-btn flat color="white" label="Dismiss" @click="errors.permissions = null"/>
                    </template>
                  </q-banner>
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
                      <q-separator class="q-mt-md" :key="index+'a'"/>
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
    <q-dialog v-if="$route.params.id" persistent position="bottom" v-model="changePasswordDialog">
        <q-card style="width:500px;">
            <q-card-section>
                <div class="text-h6">Change Password</div>
                <q-input outlined dense square label="New Password" type="password" v-model="password" :error-message="errors.password" :error="errors.password && errors.password.length > 0" @input="errors.password = null"/>
            </q-card-section>
            <q-card-actions align="right" class="bg-white text-teal">
                <q-btn flat label="Cancel" color="red" v-close-popup />
                <q-btn flat label="Save" @click="changePassword()" />
            </q-card-actions>
        </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'UsersCreate',
  components: {
    Breadcrumbs,
    PageToolbar
  },
  mounted () {
    // console.log(this.$route.params.id)
    if (!this.$store.getters.hasPermissionTo('create_user')) {
      this.$router.push({ name: 'Error403' })
    } else {
      this.$q.loading.show()
      Promise.all([
        this.$axios.get('all-permissions').then((res) => {
          this.permissionsCollection = res.data
        }),
        this.$axios.get('user_roles').then((res) => {
          this.roleOptions = res.data.model
        }),
        this.$axios.get('notification_types').then((res) => {
          this.notificationCollection = res.data
        })
      ]).then(() => {
        if (this.$route.params.id) {
          this.$axios.get('users/' + this.$route.params.id).then((res) => {
            this.name = res.data.name
            this.email = res.data.email
            this.roles = res.data.roles
            this.active = res.data.active
            this.permissions = res.data.permissions
            this.notification_types = Object.keys(this.$_.groupBy(res.data.notification_types, 'id')).map(Number)
            this.toolbarButtons.unshift(
              {
                label: 'Change Password',
                id: 'change-password',
                type: 'button',
                color: 'orange-9'
              }
            )
          }).finally(() => {
            this.$store.commit('setPageTitle', 'Edit User: ' + this.name)
            this.$q.loading.hide()
          })
        } else {
          this.$store.commit('setPageTitle', 'Create User')
          this.$q.loading.hide()
        }
      })
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
      isPwd: true,
      changePasswordDialog: false,
      active: true,
      name: null,
      email: null,
      password: null,
      roleOptions: [],
      permissionsCollection: [],
      notificationCollection: [],
      tab: 'permissions',
      permissions: [],
      notification_types: [],
      roles: [],
      errors: {
        name: null,
        email: null,
        roles: null,
        password: null,
        permissions: null
      }
    }
  },
  computed: {
    breadcrumbs () {
      return [
        { label: 'Dashboard', link: '/' },
        { label: 'Users', link: '/users' },
        { label: this.$route.params.id ? this.name : 'Create', link: '', disabled: true }
      ]
    }
  },
  methods: {
    saveFn () {
      let route = 'users'
      if (this.$route.params.id) {
        route = 'users/' + this.$route.params.id
      }
      const postObj = {
        permissions: JSON.stringify(this.permissions),
        active: this.active,
        name: this.name,
        email: this.email,
        roles: JSON.stringify(Object.keys(this.$_.groupBy(this.roles, 'id'))),
        _method: this.$route.params.id ? 'PUT' : 'POST'
      }
      if (!this.$route.params.id) {
        postObj.password = this.password
      }
      this.$axios.post(route, postObj).then((res) => {
        if (res.data.message === 'success') {
          this.$q.notify({
            type: 'positive',
            message: 'User Saved Successfully'
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
      })
    },
    changePassword () {
      this.$axios.post('users/' + this.$route.params.id, {
        password: this.password,
        _method: 'PUT'
      }).then((res) => {
        if (res.data.message === 'success') {
          this.$q.notify({
            type: 'positive',
            message: 'Password CHnaged Successfully'

          })
          this.changePasswordDialog = false
        }
      }).catch((error) => {
        if (error.response.status === 422) {
          this.$q.notify({
            type: 'negative',

            message: error.response.data.message

          })
          this.errors.password = error.response.data.errors.password[0]
        }
      })
    },
    selectAll () {
      this.permissionsCollection.forEach((item) => {
        this.permissions.push(item.id)
      })
    },
    resetFn () {
      this.permissions = []
    },
    updatePermissions () {
      this.resetFn()
      this.$q.loading.show()
      this.roles.forEach((item, i) => {
        this.$axios.get('user_roles/' + item.id).then((res) => {
          this.permissions = this.$_.uniq(
            this.$_.concat(
              this.permissions,
              Object.keys(this.$_.groupBy(res.data.permissions, 'id'))
            ).map(Number)
          )
          this.notification_types = this.$_.uniq(
            this.$_.concat(
              this.notification_types,
              Object.keys(this.$_.groupBy(res.data.notification_types, 'id'))
            ).map(Number)
          )
        })
      })
      this.$q.loading.hide()
    }
  }
}
</script>
