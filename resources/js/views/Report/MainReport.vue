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
      <v-toolbar-title>Laporan perbulan</v-toolbar-title>
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
            <v-flex xs12 sm9 class="text-xs-right">
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
                <td v-html="props.item.Sparepart" />
                <td v-html="props.item.Service"/>
                <td v-html="props.item.Total"/>
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
import { mapActions,mapState } from 'vuex'
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
        { title: 'Penjualan Sparepart', name: 'serviceShow' }

      ],
      name: '',
      date: null,   
      menu: false,
      year: new Date().getFullYear(),
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
            text: 'Service',
            value: 'price'
          },
          {
            text: 'Spareparts',
            value: null
          },
          {
            text: 'Total',
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
          text: 'TransactionPerMonth',
          disabled: true,
        },
      ],
      series: [{
          name: 'Sparepart',
          data: []
        }, {
          name: 'Service',
          data: []
        }, {
          name: 'Total',
          data: []
        }],
        
        chartOptions: {
          chart: {
            height: 350,
            type: 'bar',
          },
          plotOptions: {
            bar: {
              dataLabels: {
              position: 'bottom'
          },	
            },
          },
          dataLabels: {
            enabled: false
          },
          stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
          },

          xaxis: {
            categories: ['Jan','Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov','Dec'],
          },

          fill: {
            opacity: 1

          },
          tooltip: {
            y: {
              formatter: function (val) {
                return "Rp " + val + " "
              }
            }
          }
        },
    }
  },

  watch: {
    menu (val) {
      val && this.$nextTick(() => (this.$refs.picker.activePicker = 'YEAR'))
    }
  },

  computed: {
    ...mapState({
      reports: state => state.Report.reports,
      Error: state => state.Report.error,
      Loading: state => state.Report.loading
    })
  },


  methods: {
    ...mapActions({
      fetchReport: 'Report/transactionperYear',
      printReport: 'Report/printTransactionperMonth'
    }),

    async print(){
        await this.printReport(this.year);
    },

    async fetch () {
      await this.fetchReport(this.year);
      this.series[0].data=[]
      this.series[1].data=[]
      this.series[2].data=[]
      this.reports.forEach(report => {
          this.series[0].data.push(report.Sparepart)
          this.series[1].data.push(report.Service)
          this.series[2].data.push(report.Total)
      });
    },
    async save (date) {
      this.$refs.menu.save(date);
      this.$refs.picker.activePicker = 'YEAR'
      var name = this.date;
      var word = name.split('-');
      this.year=word[0]
      this.menu = false;
      
      await this.fetchReport(this.year);
      this.series[0].data=[]
      this.series[1].data=[]
      this.series[2].data=[]
      this.reports.forEach(report => {
          this.series[0].data.push(report.Sparepart)
          this.series[1].data.push(report.Service)
          this.series[2].data.push(report.Total)
      });
    }
  },

  mounted () {
    this.fetch()
  }
}
</script>

<style scoped>
.loading-section {
  text-align: center;
}
</style>


