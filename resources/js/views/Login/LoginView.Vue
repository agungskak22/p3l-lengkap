<template>
  <v-app id="inspire">
    <v-content>
      <v-container fluid fill-height>
        <v-layout align-center justify-center>
          <v-flex xs12 sm8 md4>
            <v-card class="elevation-12">
              <v-toolbar dark color="indigo">
                <v-toolbar-title>Login form</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-tooltip right>
                  <template v-slot:activator="{ on }">
                    <v-btn icon large flat v-on="on">
                      <v-icon large>error_outline</v-icon>
                    </v-btn>
                  </template>
                  <v-alert
                  :value="alert"
                  type="warning"
                  flat
                  transition="scale-transition"
                >
                  masuk dengan akun role masing masing
                </v-alert>
                </v-tooltip>
              </v-toolbar>
              <v-card-text>
              <ErrorAlert ref="errorAlert" />
                <v-form @submit.prevent="loginHandler()">
                  <v-text-field v-model="form.username" prepend-icon="person" name="login" label="Login" type="text"></v-text-field>
                  <v-text-field v-model="form.password" id="password" prepend-icon="lock" name="password" label="Password" type="password" @keyup.enter="loginHandler(); dialog =true"></v-text-field>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn dark color="indigo" v-on:click='loginHandler()' @click="dialog=true" :disabled="dialog" :loading="dialog" load=true type="submit">Login</v-btn>
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-content>
    <v-dialog
      v-model="dialog"
      hide-overlay
      persistent
      width="300"
    >
      <v-card
        color="primary"
        dark
      >
        <v-card-text>
          Mohon Tunggu Sebentar
          <v-progress-linear
            indeterminate
            color="white"
            class="mb-0"
          ></v-progress-linear>
        </v-card-text>
      </v-card>
    </v-dialog>
</v-app>
</template>
<script>
  import auth from '../../service/Auth'
  export default {
    name: 'LoginView',

    data () {
      return {
        dialog: false,
        alert: true,
        drawer: null,
        form: {
          username: '',
          password: ''
        },
      }
    },

    watch: {
      form: {
        deep: true,
        handler (val) {
          this.$refs.errorAlert.reset()
        }
      },
      dialog (val) {
        if (!val) return

        setTimeout(() => (this.dialog = false), 1000)
      }
    },

    methods: {
      async loginHandler(){
        try {
          await auth.authenticate(this.form)
          this.$router.push({ name: 'dashboard' })
        } catch (err) {
          this.$refs.errorAlert.trigger({ message: 'Password atau Username tidak valid' })
        }
      },
      loadUpdate(){
          this.load = true
      },
      reset () {
          this.$refs.form.reset()
      },
    props: {
      source: String
    }
  }
  }
</script>