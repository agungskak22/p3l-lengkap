<template>
   <v-app id="inspire">
    <v-toolbar 
      :clipped-left="$vuetify.breakpoint.lgAndUp"
      color="indigo"
      dark
      fixed>
      <v-avatar size="96px" tile>
        <img
          src="https://yesplis.com/images/yesplislogo_white.svg"
          alt="yesplis"
        >
      </v-avatar>
      <v-flex xs3 style="margin-left:20px">
      <v-text-field
        flat
        solo-inverted
        hide-details
        prepend-inner-icon="search"
        label="Cari Sparepart"
        class="hidden-sm-and-down"
        v-model="search"
      ></v-text-field>
      </v-flex>
      <VSpacer />
      <v-btn :to="{ name: 'customer.motor' }" color="cyan">
        Transaksi saya
      </v-btn>
      <v-btn :to="{ name: 'customer.sparepart' }" color="warning">
        Sparepart
      </v-btn>
    </v-toolbar>
    <v-content>
      <section>
        <v-parallax src="assets/home3.jpg" height="380">
          <v-layout column align-center justify-center>
            <VContainer>
                <VLayout
                row
                wrap
                >
                    <VFlex 
                    md6 
                    xs12
                    text-xs-center 
                    class="pa-4">
                        <VCard
                            class="elevation-0 transparent"
                        >
                            <VCardText class="white--text">
                                <div class="text-xs-left mb-3">
                                <VIcon 
                                    class="mr-2"
                                    dark
                                >
                                    history
                                </VIcon>
                                <span class="title font-weight-light">Cari Riwayat Transaksi Anda</span>
                                </div>

                                <VTextField
                                v-model="form.license_number"
                                required
                                label="Plat Nomor"
                                placeholder="Masukkan plat nomor"
                                dark
                                class="pb-0"
                                />

                                <VTextField
                                v-model="form.phone_number"
                                required
                                label="Nomor Telepon"
                                placeholder="Masukkan nomor telpon"
                                dark
                                />
                                <VBtn
                                color="primary"
                                light
                                @click="submitHandler()"
                                >
                                <VIcon>
                                    search
                                </VIcon>
                                Cari
                                </VBtn>
                            </VCardText>
                        </VCard>
                    </VFlex>
                    <VFlex 
                        md6 
                        xs12
                        text-xs-center 
                        class="my-5">
                        <h2 class="display-3">Selamat Datang di Atma Auto</h2>
                        <span>Layanan Cek Status Pengerjaan dan riwayat transaksi</span>
                    </VFlex>
                </VLayout>
            </VContainer>
          </v-layout>
        </v-parallax>
      </section>

      <section color="white">
        <v-container>
          <p class="text-md-center display-2">Riwayat Transaksi Pelanggan</p>
          <VDataIterator
              :items="details"
              content-tag="v-layout"
              row
              wrap
              class="mt-5"
          >
              <template v-slot:item="props">
              <VFlex
              class="pa-1"
              md12
              >
                <v-list-tile
              class="list"
              avatar
              @click="detailTransaction(props.item.id_transaction)"
            >
              <v-list-tile-avatar>
                <v-icon > motorcycle </v-icon>
              </v-list-tile-avatar>

              <v-list-tile-content>
                <v-list-tile-title >{{ props.item.id_transaction + ' -> ' + props.item.payment }}</v-list-tile-title>
                <v-list-tile-sub-title >{{ props.item.license_number }}</v-list-tile-sub-title>
              </v-list-tile-content>

              <v-list-tile-action>
                <v-btn 
                  v-if="props.item.status.toLowerCase()=='finish'"
                  flat
                  color="success"
                >
                  finish
                </v-btn>
                <v-btn 
                  v-else-if="props.item.status.toLowerCase()=='on progress'"
                  flat
                  color="info"
                >
                  on progress
                </v-btn>
                <v-btn 
                  v-else-if="props.item.status.toLowerCase()=='unprocessed'"
                  flat
                  color="error"
                >
                  unprocessed
                </v-btn>
              </v-list-tile-action>
            </v-list-tile>
              </VFlex>
              </template>
          </VDataIterator>
        </v-container>
      </section>
        <Footer/>
      <v-dialog v-model="dialog" dark max-width="800px">
        <UtilityCard title="Detail Transaksi">
            <VCardText>
              <VLayout>
                <VFlex sm6 class="text-md-left">
                  {{ trans.id_transaction }}
                </VFlex>
                <VFlex sm6 class="text-md-right">
                  {{ trans.transaction_date }}
                </VFlex>
              </VLayout>
              <VLayout>
                <VFlex sm6 class="text-md-left">
                  Cost : {{ trans.customer_name}}
                </VFlex>
                <VFlex sm6 class="text-md-right">
                  Cs : 
                  {{ cs.name }}
                </VFlex>
              </VLayout>
              <VLayout>
                <VFlex sm6 class="text-md-left">
                  Telepon : {{ trans.customer_phone_number}}
                </VFlex>
                <VFlex sm6 class="text-md-right">
                  <VLayout>
                    <VFlex sm10 class="text-md-right">
                         Montir : 
                    </VFlex>
                    <VFlex sm2 class="text-md-right">
                        <li style="list-style: none;" v-for="mechanic in mechanics" :key="mechanic">
                          {{ mechanic }}
                        </li>
                    </VFlex>
                  </VLayout>
                </VFlex>
              </VLayout>
              <VLayout v-if="spareparts.length!=0">
                  <VFlex sm12 class="text-md-center">
                      <h2>DATA SPAREPART</h2>
                  </VFlex>        
              </VLayout>
              <VLayout v-if="spareparts.length!=0">
                <VFlex sm12>
                  <VDataTable
                    :headers="headers2"
                    :items="spareparts"
                    hide-actions
                    v-if="!loading && !error"
                  >
                    <template slot="items" slot-scope="props">
                      <td v-html="props.item.id_sparepart" />
                      <td v-html="props.item.sparepart_name" />
                      <td v-html="props.item.merk" />
                      <td v-html="props.item.detail_sparepart_price" />
                      <td v-html="props.item.detail_sparepart_amount" />
                      <td v-html="props.item.detail_sparepart_subtotal" />
                    </template>
                    <template v-slot:footer>
                      <td :colspan="headers3.length" class="text-md-right">
                        Total Sparepart : Rp  {{ totalSparepart }}
                      </td>
                    </template>
                  </VDataTable>
                </VFlex>
              </VLayout>
              <VLayout v-if="services.length!=0">
                  <VFlex sm12 class="text-md-center">
                      <h2>DATA SERVICE</h2>
                  </VFlex>        
              </VLayout>
              <VLayout v-if="services.length!=0">
                <VFlex sm12>
                  <VDataTable
                    :headers="headers3"
                    :items="services"
                    hide-actions
                    v-if="!loading && !error"
                  >
                    <template slot="items" slot-scope="props">
                      <td v-html="props.item.id_service" />
                      <td v-html="props.item.service_name" />
                      <td v-html="props.item.detail_service_price" />
                      <td v-html="props.item.detail_service_amount" />
                      <td v-html="props.item.detail_service_subtotal" />
                    </template>
                    <template v-slot:footer>
                      <td :colspan="headers3.length" class="text-md-right">
                        Total Service : Rp  {{ totalService }}
                      </td>
                    </template>
                  </VDataTable>
                </VFlex>
              </VLayout>
            </VCardText>
        </UtilityCard>
      </v-dialog>
    </v-content>
  </v-app>
</template>

<script>
import { mapActions,mapState,mapGetters } from 'vuex';
import Footer from "../../views/Home/footer";
  export default {
    components: {
      Footer
    },
    data () {
      return {
        title: 'ATMA AUTO',
        menus: [
         {
           id: 1,
           to: 'home',
           text: 'Home'
         },
         {
           id: 2,
           to: 'customer.sparepart',
           text: 'Sparepart'
         },
         {
           id: 3,
           to: 'customer.motor',
           text: 'Cek Status'
         },
       ],
       form: {
         license_number: '',
         phone_number: ''
       },
       dialog: false,
       totalSparepart: 0,
       totalService: 0,
       total: 0,
       transaction: [],
       cs: [],
       employees: [],
       spareparts: [],
       services: [],
       mechanics: [],
       headers2: [
          {
            text: 'kode',
            value: 'id_sparepart',
          },
          {
            text: 'Nama',
            value: 'sparepart_name'
          },
          {
            text: 'Merk',
            value: 'merk'
          },
          {
            text: 'Harga',
            value: 'detail_sparepart_price'
          },
          {
            text: 'Jumlah',
            value: 'detail_sparepart_amount'
          },
          {
            text: 'Subtotal',
            value: 'detail_sparepart_subtotal'
          },
      ],
      headers3: [
          {
            text: 'ID Service',
            value: 'id_service',
          },
          {
            text: 'Type',
            value: 'service_name'
          },
          {
            text: 'Harga',
            value: 'detail_service_price'
          },
          {
            text: 'Jumlah',
            value: 'detail_service_amount'
          },
          {
            text: 'Subtotal',
            value: 'detail_service_subtotal'
          },
      ],
      }
    },

    computed: {
      ...mapState({
        transactions: state => state.Transaction.transactions,
        transactionError: state => state.Transaction.error,
        transactionLoading: state => state.Transaction.loading,
      }),

      ...mapGetters({
          details: 'Transaction/details',
          trans: 'Transaction/transaction'
      }),

    },

    methods: {
      ...mapActions({
        searchDetail: 'Transaction/search',
        fetchTransaction: 'Transaction/edit',
      }),

      async submitHandler () {
        console.log(this.form)
        await this.searchDetail(this.form)
      },

      async detailTransaction(id){
          console.log(id)

          await this.fetchTransaction(id)

          this.mechanics=[]
          this.totalService=0
          this.totalSparepart=0
          this.transaction=this.trans
          this.employees=this.trans.employee
          this.spareparts=this.trans.sparepart
          this.services=this.trans.service
          console.log(this.services)
          this.employees.forEach(employee => {
              if(employee.id_role==2 || employee.id_role==1)
              {
                  this.cs=employee
              }
          });
          this.spareparts.forEach(sparepart => {
            this.totalSparepart=this.totalSparepart+sparepart.detail_sparepart_subtotal
            console.log(sparepart.mechanic_name)
            if(this.mechanics.length==0)
            {
              this.mechanics.push(sparepart.mechanic_name)
            }  
            else
            {   
              this.mechanics.forEach(mechanic => {
                  console.log(mechanic)
                  if(sparepart.mechanic_name!=mechanic)
                  {
                      this.mechanics.push(sparepart.mechanic_name)
                  }
              });
            }

      });
      this.services.forEach(service => {
          this.totalService=this.totalService+service.detail_service_subtotal
          if(this.mechanics.length==0)
          {
            this.mechanics.push(service.mechanic_name)
          }  
          else
          {
            this.mechanics.forEach(mechanic => {
                if(service.mechanic_name!=mechanic)
                {
                    this.mechanics.push(service.mechanic_name)
                }
            });
          }
      });
      this.total=this.totalService+this.totalSparepart
      this.dialog=true
      }
    },
  }
</script>

<style scoped>
  .loading-section {
    text-align: center;
  }

  .list {
    background: #455A64
  }

  .list:hover {
    background: #37474F;
  }
  
</style>





