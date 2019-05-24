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
      <v-toolbar-title>laporan Sparepart</v-toolbar-title>
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
                class="ml-3"
                ref="menu"
                v-model="menu"
                :close-on-content-click="false"
                :nudge-right="40"
                :return-value.sync="date"
                lazy
                transition="scale-transition"
                offset-y
                full-width
                @change="fetch()"
                max-width="290px"
                min-width="290px"
            >
                <template v-slot:activator="{ on }">
                <v-text-field
                     class="ml-3"
                    v-model="date"
                    label="Bulan dan Tahun"
                    readonly
                    v-on="on"
                    @change="fetch()"
                ></v-text-field>
                </template>
                <v-date-picker
                v-model="date"
                @change="fetch()"
                type="month"
                no-title
                scrollable
                >
                <v-spacer></v-spacer>
                <v-btn flat color="primary" @click="menu = false">Cancel</v-btn>
                <v-btn flat color="primary" @click="$refs.menu.save(date)">OK</v-btn>
                </v-date-picker>
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
                <v-flex xs12>
                <VDataTable
                  :headers="headers"
                  :items="reports"
                  :search="keyword"
                  v-if="!loading && !error"
                >
                  <template slot="items" slot-scope="props">
                    <td v-html="props.item.motorcycle_brand_name" />
                    <td v-html="props.item.motorcycle_type_name" />
                    <td v-html="props.item.jumlah_jasa"/>
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
        { title: 'Penjualan Sparepart', name: 'serviceShow' }
      ],
      name: '',
      date: new Date().toISOString().substr(0, 7),
      menu: false,
      modal: false,
      isFormValid: false,
      dialog: false,
      dialog2: false,
      warning: false,
      keyword: '',
      id_service: '',
      headers: [
          {
            text: 'Merk',
            value: 'service_name'
          },
          {
            text: 'Tipe Sparepart',
            value: 'price'
          },
          {
            text: 'Jumlah Penjualan',
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
      fetchReport: 'Report/sparepartSelling',
      printReport: 'Report/printsparepartSelling'
    }),

    async print(){
        var name = this.date;
        var word = name.split('-');

        const payload = {
                year: word[0],
                month: word[1],
        }
        await this.printReport(payload);
    },

    async fetch () {
      var name = this.date;
      var word = name.split('-');

      const payload = {
            year: word[0],
            month: word[1],
      }
      await this.fetchReport(payload);
    },

    async fetchData(){
        var name = this.date;
        var word = name.split('-');

        const payload = {
          year: word[0],
          month: word[1],
        }

        await this.fetchReport(payload);
    },

    save (date) {
      this.$refs.menu.save(date);
      this.$refs.picker.activePicker = 'YEAR'
      this.menu = false;
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


