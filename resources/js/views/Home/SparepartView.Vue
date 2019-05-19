<template>
   <v-app id="inspire">
    <v-toolbar 
      :clipped-left="$vuetify.breakpoint.lgAndUp"
      color="indigo"
      dark
      app
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
    <v-container fluid>
        <v-layout justify-center align-center>
          <swiper :options="swiperOption" ref="mySwiper" @someSwiperEvent="callback">
            <swiper-slide  v-for="(slide, index) in swiperSlides" :key="index">
              <v-img src="http://localhost:8000/asset/ski.png" tile="tile" height="350px" width="580px"/>
            </swiper-slide>
            <v-flex class="swiper-pagination" slot="pagination"></v-flex>
          </swiper>
        </v-layout>
      </v-container>
      <v-container fluid grid-list-md>
        <v-layout row wrap justify-center align-center>
              <v-flex xs11>
              <VDataIterator
              :items="initsparepart"
              :rows-per-page-items="rowsPerPageItems"
              :search="search"
              content-tag="v-layout"
              row
              wrap
          >
          <template v-slot:header>
            <v-toolbar fluid flat color="#FAFAFA">
              <span style="margin-left: -20px">Paket Sparepart <br> <small class="grey--text">Temukan Informasi sparepart Atma Auto</small></span>
              <v-spacer></v-spacer>
              <v-flex text-xs-right>
                    <v-btn color="info"
                      v-if="sortPrice=='asc' || sortPrice=='none'"
                      @click="sortPrice='dsc',sortStock='none'"
                    >
                      <v-icon left>arrow_drop_up</v-icon>
                      harga
                    </v-btn>
                    <v-btn color="info"
                      v-else
                      @click="sortPrice='asc',sortStock='none'"
                    >
                      <v-icon left>arrow_drop_down</v-icon>
                      harga
                    </v-btn>
                    <v-btn color="indigo" dark 
                      v-if="sortStock=='asc' || sortStock=='none'"
                      @click="sortStock='dsc',sortPrice='none'"
                    >
                      <v-icon left>arrow_drop_up</v-icon>
                      stock
                    </v-btn>
                    <v-btn color="indigo" dark
                      v-else
                      @click="sortStock='asc',sortPrice='none'"
                    >
                      <v-icon left>arrow_drop_down</v-icon>
                      stock
                    </v-btn>
                    </v-flex>
              </v-toolbar>
          </template>
              <template v-slot:item="props">
              <VFlex
              class="pa-1"
              md3
              >
              <VCard>
                  <VImg
                  :src="'/images/'+props.item.image"
                  height="200px"
                  >
                    <v-layout
                      fill-height
                      wrap
                      text-xs-left
                      ma-0
                  >
                      <v-flex xs12>
                          <v-chip
                          label
                          class="mx-0 mb-2 text-uppercase"
                          color="warning"
                          text-color="white"
                          small
                          @click.stop=""
                          >
                          {{ props.item.sparepart_name }}
                          </v-chip>
                      </v-flex>
                    </v-layout>
                  </VImg>
                  <VDivider />
                  <v-layout>
                    <v-card-title primary-title>
                <div>
                  <span>Jumlah Stok Tersedia: {{ props.item.stock }} </span><br>
                  <small class="grey--text">Harga : {{ props.item.sell_price }}</small>
                </div>
              </v-card-title>
              </v-layout>
              </VCard>
              </VFlex>
              </template>
          </VDataIterator>
          </v-flex>
        </v-layout>
      </v-container>
  </v-app>
</template>

<script>
import 'swiper/dist/css/swiper.css'
import { swiper, swiperSlide } from 'vue-awesome-swiper'
import SparepartControl from '../../components/Renderless/SparepartControl'
import { mapActions,mapState } from 'vuex';
export default {
    components: {
      SparepartControl,
      swiper,
      swiperSlide
    },
    data () {
        return {
          items: [
            {
              src: 'https://cdn.vuetifyjs.com/images/carousel/squirrel.jpg'
            },
            {
              src: 'https://cdn.vuetifyjs.com/images/carousel/sky.jpg'
            },
            {
              src: 'https://cdn.vuetifyjs.com/images/carousel/bird.jpg'
            },
            {
              src: 'https://cdn.vuetifyjs.com/images/carousel/planet.jpg'
            }
          ],
          swiperSlides: [
          "indigo darken-4",
          "indigo accent-4",
          "cyan darken-4",
          "cyan accent-4",
          "light-green accent-4",
          "amber darken-4",
          "amber accent-4"],
          swiperOption: {
      initialSlide: 0,
      slidesPerView: 3,
      spaceBetween: 20,
      freeMode: true,
      watchOverflow: true,
      breakpoints: {
        1904: {
          slidesPerView: 2.5,
          spaceBetween: 20 },

        1264: {
          slidesPerView: 2.5,
          spaceBetween: 20 },

        960: {
          slidesPerView: 2.5,
          spaceBetween: 50 },

        600: {
          slidesPerView: 1,
          spaceBetween: 150 },

        400: {
          slidesPerView: 1,
          spaceBetween: 150 } },
      pagination: {
        el: ".swiper-pagination",
        clickable: true },

      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev" } },
      drawer: null,
            title: 'ATMA AUTO',
            menus: [
                {
                id: 1,
                to: 'home',
                text: 'Home'
                },
                {
                id: 2,
                to: '',
                text: 'Sparepart'
                },
                {
                id: 3,
                to: 'customer.motor',
                text: 'Cek Status'
                },
            ],
            sortPrice: 'none',   
            sortStock: 'none',
            search: '',  
            select: '',
            rowsPerPageItems: [8, 16, 24, 32],
      }
    },
props: {
  source: String
},
computed: {
...mapState({
  spareparts: state => state.Sparepart.spareparts,
  sparepartError: state => state.Sparepart.error,
  sparepartLoading: state => state.Sparepart.loading,
}),
swiper() {
      return this.$refs.mySwiper.swiper;
    },
initsparepart(){
    if(this.sortPrice=='none' && this.sortStock=='none')
    {
        return this.spareparts
    }
    else if(this.sortPrice=='asc'){
        var sparepartData =  this.spareparts.sort(function(obj1, obj2) {
        // Ascending: first age less than the previous
        return obj2.sell_price - obj1.sell_price;
    });
                                
        return sparepartData
    }
    else if(this.sortPrice=='dsc'){
        var sparepartData =  this.spareparts.sort(function(obj1, obj2) {
        // Ascending: first age less than the previous
        return obj1.sell_price - obj2.sell_price;
    });
        return sparepartData
    }
    else if(this.sortStock=='asc'){
        var sparepartData =  this.spareparts.sort(function(obj1, obj2) {
        // Ascending: first age less than the previous
        return obj2.stock - obj1.stock;
    });
        return sparepartData
    }
    else if(this.sortStock=='dsc'){
        var sparepartData =  this.spareparts.sort(function(obj1, obj2) {
        // Ascending: first age less than the previous
        return obj1.stock - obj2.stock;
    });
        return sparepartData
    }
},
getSpareparts() {

  var spareparts = this.spareparts.filter((sparepart) => {
      return sparepart.sparepart_name.toLowerCase().includes(this.search.toLowerCase());
    });
  
  if (this.descPrice == true) {
    return spareparts.sort(function(a, b) {
      return b.sparepart_name - a.sparepart_name
    });
  } else {
    return spareparts;
  }
  
  },
  filteredItems(){
      var spareparts = this.spareparts.filter((sparepart) => {
        return sparepart.sparepart_name.includes(this.select);
      });
  },
  filtered(){
    let filter = this.spareparts.filter( b => b.sparepart_name === this.select)
    return filter
  },
  },
  methods: {
  ...mapActions({
    fetch: 'Sparepart/get' 
  }),
  callback() {
    }
  },
    mounted () {
      this.swiper.slideTo(3, 1000, false),
      this.fetch()
    }
      
  }
</script>

<style>
 .jumbotron {
   min-height: 400px;
   width: 100%;
   color: white;
 }
</style>