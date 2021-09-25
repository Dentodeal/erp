<template>
  <q-layout view="lHh Lpr lFf" v-if="!appLoading">
    <q-page-container class="bg-cyan-9">
      <q-page
        padding
        :style="{
          background: 'url(apexion_logo.svg) no-repeat 50%',
          backgroundSize: 'cover',
        }"
      >
        <div class="row q-mt-lg">
          <div class="col-12 col-md-4"></div>
          <div class="col-12 col-md-4 row">
            <div class="col-12">
              <div class="text-center text-h6 text-white">Welcome</div>
              <div class="text-h3 text-center text-white q-mb-md">ApexionERP</div>
              <q-card style="width: 100%">
                <q-card-section>
                  <div class="row">
                    <div class="col-12">
                      <q-input
                        name="email"
                        outlined
                        v-model="email"
                        label="Email"
                        id="email"
                      />
                    </div>
                  </div>
                  <div class="row q-mt-md">
                    <div class="col-12">
                      <q-input
                        name="password"
                        outlined
                        v-model="password"
                        label="Password"
                        type="password"
                        id="password"
                      />
                    </div>
                  </div>
                </q-card-section>
                <q-separator />
                <q-card-section>
                  <q-btn color="primary" style="width:100%" label="login" @click="login()" />
                </q-card-section>
              </q-card>
            </div>
          </div>
          <div class="col-12 col-md-4"></div>
        </div>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script>
import { mapState } from 'vuex'
export default {
  data () {
    return {
      email: '',
      password: ''
    }
  },
  mounted () {
    this.$store.dispatch('init')
    if (this.$store.getters.isAuthenticated) {
      this.$router.push({ name: 'Dashboard' })
    }
  },
  computed: {
    ...mapState(['authenticated', 'appLoading'])
  },
  watch: {
    authenticated (newValue, oldValue) {
      if (this.$store.getters.isAuthenticated) {
        this.$router.back()
      } else {
        this.$router.push({ name: 'LoginPage' })
      }
    }
  },
  methods: {
    login () {
      this.$q.loading.show()
      this.$store
        .dispatch('login', { email: this.email, password: this.password })
        .then(() => {
          this.$router.push('/')
        })
        .catch((err) => {
          // console.log(err)
          if (err === 'error-422') {
            this.$q.notify({
              type: 'negative',
              message: 'Incorrect credentials'
            })
          } else if (err === 'user-inactive') {
            this.$q.notify({
              type: 'negative',
              message: 'Cannot Login, You have been disabled by admin!!.'
            })
          } else {
            this.$q.notify({
              type: 'negative',
              message: 'Something went wrong.'
            })
          }
        })
        .finally(() => {
          this.$q.loading.hide()
        })
    }
  }
}
</script>
