<template>
  <PageWrapper>
    <div
        id="e3"
        style="max-width: 100%; margin: auto;"
        class="grey lighten-3"
    >
        <v-toolbar
        color="indigo"
        dark
        >
      <v-toolbar-title>Laporan Sisa Stok</v-toolbar-title>
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
                <v-flex xs3>
            <v-menu
              ref="menu"
              :close-on-content-click="false"
              v-model="menu"
              :nudge-right="40"
              lazy
              transition="scale-transition"
              offset-y
              full-width
              min-width="290px"
            >
              <v-text-field
                class="ml-3"
                slot="activator"
                v-model="year"
                label="Tahun"
                readonly
              ></v-text-field>
              <v-date-picker
                ref="picker"
                v-model="date"
                @input="save"
                reactive
                no-title
              ></v-date-picker>
            </v-menu>
        </v-flex>
        <v-flex xs3>
            <v-select
            label="Sparepart type"
            class="ml-3"
            v-model="sparepart_type"
            item-text="sparepart_type_name"
            item-value="id_sparepart_type"
            :items="items"
            @change="fetch()"
            required
          ></v-select>
        </v-flex>
            <v-flex xs6 class="text-xs-right">
                <VBtn
                depressed
                dark
                round
                color = "indigo"
                @click="print()"
                >
                <VIcon>print</VIcon>
                    Print
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
                <v-flex xs12 sm7>
                    <v-card flat>
                    <apexchart type="bar" :options="chartOptions" :series="series"></apexchart>
                    </v-card>
                </v-flex>
                <v-flex xs12 sm5>
                <VDataTable
                :headers="headers"
                :items="reports"
                :search="keyword"
                v-if="!loading && !error"
              >
                <template slot="items" slot-scope="props">
                  <td v-html="props.item.Bulan" />
                  <td v-html="props.item.JumlahStokSisa" />
                </template>
              </VDataTable>
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
import { mapActions,mapState } from 'vuex';
export default {
  data () {
    return {
      itemsToa: [
        { title: 'Transaksi Pertahun', name: 'mainReport' },
        { title: 'Barang Terlaris', name: 'sparepartBestSeller' },
        { title: 'Servis dan jasa', name: 'serviceSelling' },
        { title: 'Sisa stok', name: 'remainingStock' },
        { title: 'Transaksi percabang', name: 'transactionByBranch' },
        { title: 'Pengeluaran Pertahun', name: 'expenseperYear' },

      ],
      name: '',
      date: null,   
      menu: false,
      year: new Date().getFullYear(),
      sparepart_type: 1,
      isFormValid: false,
      dialog: false,
      dialog2: false,
      warning: false,
      keyword: '',
      id_service: '',
      headers: [
          {
            text: 'Bulan',
            value: 'service_name'
          },
          {
            text: 'Sisa Stock',
            value: 'price'
          },
      ],
      breadcrumbs: [
        {
          text: 'Dashboard',
          to: { name: 'dashboard' },
          exact: true,
        },
        {
          text: 'RemainingStock',
          disabled: true,
        },
      ],
      series: [{
            name: "Sisa Stock",
            data: []
        }],
        chartOptions: {
          chart: {
                height: 350,
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight'
            },
            title: {
                text: 'Sisa stock',
                align: 'left'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep','Oct','Nov','Dec'],
            }
        }
    }
  },
  watch: {
    menu (val) {
      val && this.$nextTick(() => (this.$refs.picker.activePicker = 'YEAR'))
    }
  },
  computed: {
    ...mapState({
      items: state => state.SparepartType.sparepartTypes,
      reports: state => state.Report.reports,
      Error: state => state.Report.error,
      Loading: state => state.Report.loading
    })
  },
  methods: {
    ...mapActions({
      fetchType: 'SparepartType/get',
      fetchReport: 'Report/remainingStock',
      printReport: 'Report/printRemainingStock'
    }),
    async print(){
        const payload = {
            year: this.year,
            sparepart: this.sparepart_type,
      }
        await this.printReport(payload);
    },
    async fetch () {
      const payload = {
            year: this.year,
            sparepart: this.sparepart_type,
      }
  
      await this.fetchReport(payload);
      this.series[0].data=[]
    
      this.reports.forEach(report => {
          this.series[0].data.push(report.JumlahStokSisa)
      });
    },
    async save (date) {
      this.$refs.menu.save(date);
      this.$refs.picker.activePicker = 'YEAR'
      var name = this.date;
      var word = name.split('-');
      this.year=word[0]
      this.menu = false;
    
    //   this.series[0].data=[]
    //   this.series[1].data=[]
    //   this.series[2].data=[]
    //   this.reports.forEach(report => {
    //       this.series[0].data.push(report.Sparepart)
    //       this.series[1].data.push(report.Service)
    //       this.series[2].data.push(report.Total)
    //   });
    }
  },
  async mounted () {
    this.fetch()
    await this.fetchType()
  }
}
</script>

<style scoped>
.loading-section {
  text-align: center;
}
</style>