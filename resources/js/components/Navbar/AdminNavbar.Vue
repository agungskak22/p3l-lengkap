<template>
  <v-flex id="inspire">
    <VNavigationDrawer
      v-model="drawer"
      app
      :clipped="$vuetify.breakpoint.lgAndUp"
      fixed
    >
      <VList dense>
        <VListTile avatar>
          <VListTileAvatar>
            <VIcon>account_circle</VIcon>
          </VListTileAvatar>
          <VListTileContent>
            <VListTileTitle>{{ name }}</VListTileTitle>
            <VListTileTitle>{{ role }}</VListTileTitle>
          </VListTileContent>
        </VListTile>
      </VList>

      <VList>
        <VDivider />

        <VListTile 
          v-for="menu in menus"
          :key="menu.id"
          class="ml-1"
          @click="$router.push({ name: menu.to })"
        >
          <VListTileAction>
            <VIcon>{{ menu.icon }}</VIcon>
          </VListTileAction>

          <VListTileContent>
            <VListTileTitle v-html="menu.text" />
          </VListTileContent>
        </VListTile>
      </VList>
    </VNavigationDrawer>

    <VToolbar 
      :clipped-left="$vuetify.breakpoint.lgAndUp"
      color="indigo"
      dark
      app
      fixed>
      <v-toolbar-title style="width: 400px" class="ml-0 pl-3">
        <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
        <span class="hidden-sm-and-down">{{ role }} Dashboard</span>
      </v-toolbar-title>
      <VSpacer />
      <VMenu 
        offset-y
        bottom
        left
      >
        <VBtn 
          icon
          slot="activator"
        >
          <VIcon>more_vert</VIcon>
        </VBtn>

        <VList>
          <VListTile @click="dialog=true">
            <VListTileTitle>Ubah Password</VListTileTitle>
          </VListTile>
          <VListTile @click="logoutHandler">
            <VListTileTitle>Logout</VListTileTitle>
          </VListTile>
        </VList>
      </VMenu>
    </VToolbar>
    <v-dialog v-model="dialog" max-width="600px">
      <UtilityCard title="Ubah Password">
          <VAlert
            v-if="err"
            :value="true"
            dismissible
            type="error"
          >
          Password Tidak Cocok
          </VAlert>
          <VAlert
            v-else-if="success"
            :value="true"
            dismissible
            type="success"
          >
          Succes
          </VAlert>
          <VCardText>
            <UserForm 
              v-model="isFormValid"
              @submitted="submitHandler"
            />
          </VCardText>
      </UtilityCard>
    </v-dialog>
  </v-flex>
</template>

<script>
  import UserForm from '../Form/UserForm'
  import { mapGetters,mapActions } from 'vuex'
  import Auth from '../../service/Auth'

  export default {
    components: {
      UserForm
    },
    data() {
      return {
        drawer: null,
        isFormValid: false,
        dialog: false,
        err: false,
        success: false
      }
    },

    computed: {
      ...mapGetters({
        id: 'LoggedUser/id',
        name: 'LoggedUser/name',
        username: 'LoggedUser/username',
        role: 'LoggedUser/role'
      }),

      menus () {
        switch (this.role) {
          case 'Administrator': 
            return [
              { id: 1, to: 'dashboard', icon: 'dashboard', text: 'Dashboard', name: 'Dashboard'},
              { id: 2, to: 'branches', icon: 'share', text: 'Branch', name: 'Branches'},
              { id: 3, to: 'employees', icon: 'person', text: 'Employee', name: 'Employees'},
              { id: 4, to: 'users', icon: 'person_outline', text: 'User', name: 'Users'},
              { id: 13, to: 'motorcycleBrands', icon: 'motorcycle', text: 'Motorcycle', name: 'MotorcycleBrand'},
              { id: 5, to: 'suppliers', icon: 'store_mall_directory', text: 'Supplier', name: 'Suppliers'},
              { id: 6, to: 'sales', icon: 'people', text: 'Sales', name: 'Sales'},
              { id: 7, to: 'services', icon: 'trending_up', text: 'Services', name: 'Service'},
              { id: 8, to: 'spareparts', icon: 'build', text: 'Spareparts', name: 'Sparepart'},
              { id: 9, to: 'customers', icon: 'face', text: 'Customers', name: 'Customer'},
              { id: 11, to: 'transactions', icon: 'add_shopping_cart', text: 'Transactions', name: 'transaction'},
              { id: 10, to: 'procurements', icon: 'compare_arrows', text: 'Sparepart procurement', name: 'procurement'},

            ]
            break

          case 'Customer Service': 
            return [
              { id: 8, to: 'spareparts', icon: 'build', text: 'Spareparts', name: 'Sparepart'},
              { id: 13, to: 'motorcycleBrands', icon: 'motorcycle', text: 'Motorcycle', name: 'MotorcycleBrand'},
              { id: 2, to: 'transactions', icon: 'euro_symbol', text: 'Transaksi', name: 'transaction'},
            ]
            break

          case 'Cashier': 
            return [
              { id: 2, to: 'transactions', icon: 'euro_symbol', text: 'Transaksi', name: 'transaction'},
            ]
            break
        }
      }
    },

    methods: {
      ...mapActions({
        resetForm: 'User/resetForm',
        updateUser: 'User/update',
      }),
      logoutHandler() {
        Auth.logout()
        this.$router.push({ name: 'login' })
      },
      async submitHandler (value) {
        this.err = false
        if(value.password === value.confirmPassword)
        {
            console.log(this.id);
            const payload = {
              id: this.id,
              password: value.password
            }
            console.log(payload)
            await this.updateUser(payload)
            this.success=true
            this.dialog=false  
            this.err = false      
        }
        else
        {
            this.err = true
        }
        
      },

    } 
  }
</script>