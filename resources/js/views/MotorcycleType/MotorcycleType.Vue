<template>
  <PageWrapper :narrow="true" :breadcrumbs="breadcrumbs">
    <div
        id="e3"
        style="max-width: 100%; margin: auto;"
        class="grey lighten-3"
    >
        <v-toolbar
        color="indigo"
        dark
        >
      <v-toolbar-title>daftar Tipe Motor</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-menu>
            <template v-slot:activator="{ on }">
              <v-btn
                dark
                icon
                v-on="on"
              >
                <v-icon>more_vert</v-icon>
              </v-btn>
            </template>

            <v-list>
              <v-list-tile
                v-for="(item, i) in itemsToa"
                :key="i"
                @click="$router.push({ name: item.name })"
              >
                <v-list-tile-title>{{ item.title }}</v-list-tile-title>
              </v-list-tile>
            </v-list>
          </v-menu>
    </v-toolbar>
        <v-card>
        <v-container
            fluid
            grid-list-lg
        >
            <v-layout row wrap>
            <v-flex xs12>
                <v-card flat color="grey lighten-5" class="black--text">
                <v-layout row wrap>
            <v-flex xs12 class="text-xs-right">
                <VBtn
                depressed
                dark
                round
                color = "indigo"
                @click="dialog = true"
                >
                <VIcon>add</VIcon>
                add
                </VBtn>
                <VBtn
                icon
                @click="fetch()"
                >
                    <VIcon>refresh</VIcon>
                </VBtn>
                <CardSearchBar v-model="keyword" />
                </v-flex>
                </v-layout>
                <v-layout row wrap>
                <v-flex xs12>
              <MotorcycleTypeControl ref="motorcycleTypeControl">
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
              <td v-html="props.item.id_motorcycle_type" />
              <td v-html="props.item.motorcycle_type_name" />
              <td v-html="props.item.motorcycle_brand" />
              <td class="text-xs-center">
                <VBtn 
                  flat
                  icon
                  color="indigo"
                  dark
                  @click="editHandler(props.item.id_motorcycle_type)"
                >
                  <v-icon>edit</v-icon>
                </VBtn>
                <VBtn 
                  flat
                  icon
                  color="error"
                  dark
                  @click="deleteWarning(props.item.id_motorcycle_type)"
                >
                  <v-icon>delete</v-icon>
                </VBtn>
              </td>
            </template>
          </VDataTable>
        </template>
      </MotorcycleTypeControl>
      <v-dialog v-model="dialog" max-width="600px">
        <UtilityCard title="Tambah Type Motor">
            <VCardText>
                <VAlert
                v-if="motorcycleTypeError"
                v-html="motorcycleTypeError.message"
                :value="true"
                dismissible
                type="error"
                />
                <MotorcycleTypeForm 
                v-model="isFormValid"
                @submitted="submitHandler"
                />
            </VCardText>
        </UtilityCard>
      </v-dialog>
      <v-dialog v-model="dialog2" max-width="600px">
        <UtilityCard title="Ubah Type Motor">
            <VCardText>
                <VAlert
                v-if="motorcycleTypeError"
                v-html="motorcycleTypeError.message"
                :value="true"
                dismissible
                type="error"
                />
                <MotorcycleTypeForm 
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
              v-if="motorcycleTypeLoading || motorcycleTypeError"
              class="text-xs-center"
            >
              <VAlert
                v-if="motorcycleTypeError"
                :value="true"
                dismissible
                type="error"
              >
              {{ motorcycleTypeError.message }}
              </VAlert>
              <VProgressCircular
                v-if="motorcycleTypeLoading"
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
            </v-flex>
            </v-layout>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-card>
  </div>
  </PageWrapper>
</template>

<script>
import MotorcycleTypeForm from '../../components/Form/MotorcycleTypeForm'
import MotorcycleTypeControl from '../../components/Renderless/MotorcycleTypeControl'
import motorcycleTypeService from '../../service/MotorcycleType'
import { mapActions,mapState } from 'vuex';

export default {
  components: {
    MotorcycleTypeControl,
    MotorcycleTypeForm
  },
  data () {
    return {
      name: '',
      isFormValid: false,
      dialog: false,
      dialog2: false,
      warning: false,
      keyword: '',
      id_motorcycle_type: '',
      headers: [
          {
            text: 'ID',
            value: 'id_motorcycle_type',
          },
          {
            text: 'Nama',
            value: 'motorcycle_type_name'
          },
          {
            text: 'Brand',
            value: 'motorcycle_brand'
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
          text: 'Type Motor',
          disabled: true,
        },
      ]
    }
  },

  computed: {
    ...mapState({
      motorcycleTypeError: state => state.MotorcycleType.error,
      motorcycleTypeLoading: state => state.MotorcycleType.loading
    })
  },


  methods: {
    ...mapActions({
      deleteMotorcycleType: 'MotorcycleType/delete',
      storeMotorcycleType: 'MotorcycleType/store',
      resetForm: 'MotorcycleType/resetForm',
      updateMotorcycleType: 'MotorcycleType/update',
      fetchMotorcycleType: 'MotorcycleType/edit',
    }),

    fetch () {
      this.$refs.motorcycleTypeControl.fetch()
    },

    async editHandler(id){
      this.id_motorcycle_type=id
      await this.fetchMotorcycleType(id)
      this.dialog2=true
    },

    deleteWarning(id){
        this.id_motorcycle_type=id
        this.warning=true
    },

    async deleteHandler () {
      console.log(this.id_motorcycle_type);
      await this.deleteMotorcycleType(this.id_motorcycle_type)
      this.fetch()
      this.warning=false
    },

    async submitHandler (value) {
      console.log(value)
      const payload = {
        motorcycle_type_name: value.motorcycle_type_name,
        id_motorcycle_brand: value.id_motorcycle_brand
      }

      console.log(payload)
      await this.storeMotorcycleType(payload)
    
      if (!this.motorcycleTypeError) {
        this.dialog=false
        this.fetch()
      }
    },

    async submitHandler2 (value) {
      console.log(this.id_motorcycle_type);
      const payload = {
        id_motorcycle_type: this.id_motorcycle_type,
        motorcycle_type_name: value.motorcycle_type_name,
        id_motorcycle_brand: value.id_motorcycle_brand
      }

      await this.updateMotorcycleType(payload)
    
      if (!this.motorcycleTypeError) {
        this.dialog2=false
        this.fetch()
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


