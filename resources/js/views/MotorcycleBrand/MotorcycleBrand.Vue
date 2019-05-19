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
      <v-toolbar-title>Daftar Merek Motor</v-toolbar-title>
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
              <MotorcycleBrandControl ref="motorcycleBrandControl">
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
              <td v-html="props.item.id_motorcycle_brand" />
              <td v-html="props.item.motorcycle_brand_name" />
              <td class="text-xs-center">
                <VBtn 
                  flat
                  icon
                  color="indigo"
                  dark
                  @click="editHandler(props.item.id_motorcycle_brand)"
                >
                  <v-icon>edit</v-icon>
                </VBtn>
                <VBtn 
                  flat
                  icon
                  color="error"
                  dark
                  @click="deleteWarning(props.item.id_motorcycle_brand)"
                >
                  <v-icon>delete</v-icon>
                </VBtn>
              </td>
            </template>
          </VDataTable>
        </template>
      </MotorcycleBrandControl>
      <v-dialog v-model="dialog" max-width="600px">
        <UtilityCard title="Tambah Brand Motor">
            <VCardText>
                <VAlert
                v-if="motorcycleBrandError"
                v-html="motorcycleBrandError.message"
                :value="true"
                dismissible
                type="error"
                />
                <MotorcycleBrandForm 
                v-model="isFormValid"
                @submitted="submitHandler"
                />
            </VCardText>
        </UtilityCard>
      </v-dialog>
      <v-dialog v-model="dialog2" max-width="600px">
        <UtilityCard title="Ubah Brand Motor">
            <VCardText>
                <VAlert
                v-if="motorcycleBrandError"
                v-html="motorcycleBrandError.message"
                :value="true"
                dismissible
                type="error"
                />
                <MotorcycleBrandForm 
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
              v-if="motorcycleBrandLoading || motorcycleBrandError"
              class="text-xs-center"
            >
              <VAlert
                v-if=" motorcycleBrandError"
                :value="true"
                dismissible
                type="error"
              >
              {{  motorcycleBrandError.message }}
              </VAlert>
              <VProgressCircular
                v-if="motorcycleBrandLoading"
                :size="45"
                indeterminate
                class="loading-section"
              />
            </VCardText>
            <v-card-text v-else class="text-md-center" style="font-size:15px">Anda yakin ingin menghapus data ini?</v-card-text>
            <v-card-actions>
            <v-spacer/>
            <VBtn class="mb-4" depressed dark color="danger" @click="warning = false">Disagree</VBtn>
            <VBtn class="mb-4" depressed dark color="warning" @click="deleteHandler()">Agree</VBtn>
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
import MotorcycleBrandForm from '../../components/Form/MotorcycleBrandForm'
import MotorcycleBrandControl from '../../components/Renderless/MotorcycleBrandControl'
import motorcycleBrandService from '../../service/MotorcycleBrand'
import { mapActions,mapState } from 'vuex';

export default {
  components: {
    MotorcycleBrandControl,
    MotorcycleBrandForm
  },
  data () {
    return {
      itemsToa: [
        { title: 'Merek Motor', name: 'motorcycleBrands' },
        { title: 'Tipe Motor', name: 'motorcycleTypes' }
      ],
      name: '',
      isFormValid: false,
      dialog: false,
      dialog2: false,
      warning: false,
      keyword: '',
      id_motorcycle_brand: '',
      headers: [
          {
            text: 'ID',
            value: 'id_motorcycle_brand',
          },
          {
            text: 'Nama',
            value: 'motorcycle_brand_name'
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
          text: 'Brand Motor',
          disabled: true,
        },
      ]
    }
  },

  computed: {
    ...mapState({
      motorcycleBrandError: state => state.MotorcycleBrand.error,
      motorcycleBrandLoading: state => state.MotorcycleBrand.loading
    })
  },


  methods: {
    ...mapActions({
      deleteMotorcycleBrand: 'MotorcycleBrand/delete',
      storeMotorcycleBrand: 'MotorcycleBrand/store',
      resetForm: 'MotorcycleBrand/resetForm',
      updateMotorcycleBrand: 'MotorcycleBrand/update',
      fetchMotorcycleBrand: 'MotorcycleBrand/edit',
    }),

    fetch () {
      this.$refs.motorcycleBrandControl.fetch()
    },

    async editHandler(id){
      this.id_motorcycle_brand=id
      await this.fetchMotorcycleBrand(id)
      this.dialog2=true
    },

    deleteWarning(id){
        this.id_motorcycle_brand=id
        this.warning=true
    },

    async deleteHandler () {
      console.log(this.id_motorcycle_brand);
      await this.deleteMotorcycleBrand(this.id_motorcycle_brand)
      this.fetch()
      this.warning=false
    },

    async submitHandler (value) {
      console.log(value)
      const payload = {
        motorcycle_brand_name: value.motorcycle_brand_name
      }

      console.log(payload)
      await this.storeMotorcycleBrand(payload)
    
      if (!this.motorcycleBrandError) {
        this.dialog=false
        this.fetch()
      }
    },

    async submitHandler2 (value) {
      console.log(this.id_motorcycle_brand);
      const payload = {
        id_motorcycle_brand: this.id_motorcycle_brand,
        motorcycle_brand_name: value.motorcycle_brand_name
      }

      await this.updateMotorcycleBrand(payload)
    
      if (!this.motorcycleBrandError) {
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


