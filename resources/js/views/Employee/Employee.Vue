<template>
  <PageWrapper :breadcrumbs="breadcrumbs">
    <div id="pageTable">
      <v-container grid-list-xl fluid>
        <v-layout row wrap>
        <v-flex lg12>
          <v-card>
            <v-toolbar card color="white">
              <v-text-field flat
              solo
              prepend-icon="search"
              placeholder="Type something"
              v-model="keyword"
              hide-details
              class="hidden-sm-and-down">
              </v-text-field>
              <v-btn icon @click="dialog = true">
                <v-icon>add</v-icon>
              </v-btn>
              <v-btn icon @click="fetch()">
                <v-icon>refresh</v-icon>
              </v-btn>
            </v-toolbar>
            <v-divider></v-divider>
            <v-card-text class="pa-0">
             <EmployeeControl ref="employeeControl">
              <template slot-scope="{ loading, error, items }">
                <VCardText
                  v-if="loading || error"
                  class="text-xs-center"
                >
                  <VAlert
                    v-if="error"
                    type="error"
                    dismissible
                    :value="true"
                  >
                    {{ error.message }}
                  </VAlert>
                  <VProgressCircular
                    :size="45"
                    indeterminate
                    class="loading-section"
                  />
                </VCardText>
                <VDataTable
                  :headers="headers"
                  :items="items"
                  :search="keyword"
                  v-if="!loading && !error"
                >
                  <template slot="items" slot-scope="props">
                    <td v-html="props.item.id_employee" />
                    <td v-html="props.item.name" />
                    <td v-html="props.item.address" />
                    <td v-html="props.item.phone_number" />
                    <td v-html="props.item.salary" />
                    <td v-html="props.item.branch" />
                    <td v-html="props.item.role" />
                    <td class="text-xs-center" width="200px">
                      <VBtn 
                        flat
                        icon
                        color="indigo"
                        dark
                        @click="editHandler(props.item.id_employee)"
                      >
                        <v-icon>edit</v-icon>
                      </VBtn>
                      <VBtn 
                        flat
                        icon
                        color="error"
                        dark
                        @click="deleteWarning(props.item.id_employee)"
                      >
                        <v-icon>delete</v-icon>
                      </VBtn>
                    </td>
                  </template>
                </VDataTable>
              </template>
            </EmployeeControl>
            <v-dialog v-model="dialog" max-width="800px">
              <UtilityCard title="Tambah Pegawai">
                  <VCardText>
                      <VAlert
                      v-if="employeeError"
                      v-html="employeeError.message"
                      :value="true"
                      dismissible
                      type="error"
                      />
                      <EmployeeForm 
                      v-model="isFormValid"
                      @submitted="submitHandler"
                      />
                  </VCardText>
              </UtilityCard>
            </v-dialog>
            <v-dialog v-model="dialog2" max-width="800px">
              <UtilityCard title="Ubah Pegawai">
                  <VCardText>
                      <VAlert
                      v-if="employeeError"
                      v-html="employeeError.message"
                      :value="true"
                      dismissible
                      type="error"
                      />
                      <EmployeeForm 
                      v-model="isFormValid"
                      @submitted="submitHandler2"
                      />
                  </VCardText>
              </UtilityCard>
            </v-dialog>
            <v-dialog v-model="warning" persistent max-width="500">
              <v-card>
                  <v-card-title class="headline">Warning!</v-card-title>
                  <VCardText
                    v-if="employeeLoading || employeeError"
                    class="text-xs-center"
                  >
                    <VAlert
                      v-if="employeeError"
                      :value="true"
                      dismissible
                      type="error"
                    >
                    {{ employeeError.message }}
                    </VAlert>
                    <VProgressCircular
                      v-if="employeeLoading"
                      :size="45"
                      indeterminate
                      class="loading-section"
                    />
                  </VCardText>
                  <v-card-text v-else class="text-md-center" style="font-size:15px">Anda yakin ingin menghapus data ini?</v-card-text>
                  <v-card-actions>
                  <v-spacer/>
                  <VBtn class="mb-4" depressed dark color="red accent-3" @click="warning = false">Disagree</VBtn>
                  <VBtn class="mb-4" depressed dark color="indigo" @click="deleteHandler()">Agree</VBtn>
                  <v-spacer/>
                  </v-card-actions>
              </v-card>
            </v-dialog>
            </v-card-text>
          </v-card>
        </v-flex>
        </v-layout>
      </v-container>
    </div>
  </PageWrapper>
</template>

<script>
import EmployeeForm from '../../components/Form/EmployeeForm'
import EmployeeControl from '../../components/Renderless/EmployeeControl'
import employeeService from '../../service/Employee'
import { mapActions,mapState } from 'vuex';

export default {
  components: {
    EmployeeControl,
    EmployeeForm
  },
  data () {
    return {
      name: '',
      isFormValid: false,
      dialog: false,
      dialog2: false,
      warning: false,
      keyword: '',
      id_branch: '',
      headers: [
          {
            text: 'ID',
            value: 'id_employee',
          },
          {
            text: 'Nama',
            value: 'name'
          },
          {
            text: 'Alamat',
            value: 'address'
          },
          {
            text: 'Nomor Telepon',
            value: 'phone_number'
          },
          {
            text: 'Gaji',
            value: 'salary'
          },
          {
            text: 'Cabang',
            value: 'branch'
          },   
          {
            text: 'Jabatan',
            value: 'role'
          },
          {
            text: 'Aksi',
            value: null
          },
      ],
      breadcrumbs: [
        {
          text: 'Dashboard',
          to: { name: 'dashboard' },
          exact: true,
        },
        {
          text: 'Pegawai',
          disabled: true,
        },
      ]
    }
  },

  computed: {
    ...mapState({
      employeeError: state => state.Employee.error,
      employeeLoading: state => state.Employee.loading
    })
  },


  methods: {
    ...mapActions({
      deleteEmployee: 'Employee/delete',
      storeEmployee: 'Employee/store',
      resetForm: 'Employee/resetForm',
      updateEmployee: 'Employee/update',
      fetchEmployee: 'Employee/edit',
    }),

    fetch () {
      this.$refs.employeeControl.fetch()
    },

    async editHandler(id){
      this.id_employee=id
      await this.fetchEmployee(id)
      this.dialog2=true
    },

    deleteWarning(id){
        this.id_employee=id
        this.warning=true
    },

    async deleteHandler () {
      console.log(this.id_employee);
      await this.deleteEmployee(this.id_employee)
      this.fetch()
      this.warning=false
    },

    async submitHandler (value) {
      const payload = {
        first_name: value.first_name,
        last_name: value.last_name,
        address: value.address,
        phone_number: value.phone_number,
        salary: value.salary,
        id_branch: value.id_branch,
        id_role: value.id_role
      }

      await this.storeEmployee(payload)
    
      if (!this.branchError) {
        this.dialog=false
        this.fetch()
        this.resetForm()
      }
    },

    async submitHandler2 (value) {
      console.log(this.id_employee);
      const payload = {
        id_employee: this.id_employee,
        first_name: value.first_name,
        last_name: value.last_name,
        address: value.address,
        phone_number: value.phone_number,
        salary: value.salary,
        id_branch: value.id_branch,
        id_role: value.id_role
      }

      await this.updateEmployee(payload)
    
      if (!this.branchError) {
        this.dialog2=false
        this.fetch()
        this.resetForm()
      }
    },
  },

  mounted () {
    this.resetForm()
  }

}
</script>

<style scoped>
.loading-section {
  text-align: center;
}
</style>


