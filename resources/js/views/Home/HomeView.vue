<template>
  <div>
    <div class="jumbotron" dark>
      <VContainer>
        <VLayout justify-space-around>
          <div class="center-layout">
            <v-img src="/svg/logo.png" aspect-ratio="1.7" contain></v-img>
            
          </div>
        </VLayout>
        <VFlex white--text
            text-xs-center 
            class="my-0">
            <h2 class="display-3">Selamat datang di Atma Motor</h2>
            <span class="title">Penjualan sparepart dan servis motor</span>
          </VFlex>
        <v-container grid-list-md text-xs-center>
      <v-layout row wrap justify-center>
        <v-flex xs12 sm9>
          <v-img class="image-bg" src="/svg/img/img-bg.png"></v-img>
        </v-flex>
      </v-layout>
  </v-container>

  <v-container fluid grid-list-md>
    <v-layout row wrap>
      <v-flex d-flex xs12 sm6 md4>
        <v-card color="white">
          <v-card-title height="75px">
            <v-flex xs6>
              <v-img src="/svg/img/point.png"></v-img>
            </v-flex>
          </v-card-title>
          <v-card-text style="margin-top:-20px; color:#424242">{{ lorem }}</v-card-text>
        </v-card>
      </v-flex>
      <v-flex d-flex xs12 sm6 md4>
        <v-card color="white">
          <v-card-title height="75px">
            <v-flex xs6>
              <v-img src="/svg/img/point.png"></v-img>
            </v-flex>
          </v-card-title>
          <v-card-text style="margin-top:-20px ; color:#424242">{{ lorem }}</v-card-text>
        </v-card>
      </v-flex>
      <v-flex d-flex xs12 sm6 md4>
        <v-card color="white">
          <v-card-title height="75px">
            <v-flex xs6>
              <v-img src="/svg/img/service.png"></v-img>
            </v-flex>
          </v-card-title>
          <v-card-text style="margin-top:-20px ; color:#424242">{{ lorem }}</v-card-text>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
  <v-layout row wrap justify-center>
   <div  class="text-xs-center">
    <v-btn color="warning" dark :to="{ name: 'customer.motor' }">Cek Transaksi</v-btn>
  </div>
  <div class="text-xs-center">
    <v-btn color="primary" dark :to="{ name: 'customer.sparepart' }">Cek Sparepart</v-btn>
  </div>
  </v-layout>
</VContainer>
<Footer/>
    </div>
  </div>
</template>

<script>
import Footer from "../../views/Home/footer";
export default {
  components: {
  Footer
  },
  data () {
      return {
        loader: null,
        loading4: false,
        lorem: `Lorem ipsum dolor sit amet, mel at clita quando. Te sit oratio vituperatoribus, nam ad ipsum posidonium mediocritatem, explicari dissentiunt cu mea. Repudiare disputationi vim in, mollis iriure nec cu, alienum argumentum ius ad. Pri eu justo aeque torquatos.`,
        
      }
    },
    watch: {
      loader () {
        const l = this.loader
        this[l] = !this[l]

        setTimeout(() => (this[l] = false), 3000)

        this.loader = null
      }
    }
};
</script>

<style>
.spinner-btn{
  height:48px;
  margin-top:0px;
}
.image-bg{
  width: 100%;
  height: 100%;
 
}
.title-scope{
  margin-top: -15px;
  color: #fff;
}
 .jumbotron {
   margin-top: -5px;
   min-height: 800px;
   width: 100%;
   background-color:white;
   background-image: url("/svg/bg.svg");
 }
 .center-layout{
  min-width: 150px;
  height: 100%;
  float: right;
  margin-top: -35px
 }
 .custom-loader {
    animation: loader 1s infinite;
    display: flex;
  }
  @-moz-keyframes loader {
    from {
      transform: rotate(0);
    }
    to {
      transform: rotate(360deg);
    }
  }
  @-webkit-keyframes loader {
    from {
      transform: rotate(0);
    }
    to {
      transform: rotate(360deg);
    }
  }
  @-o-keyframes loader {
    from {
      transform: rotate(0);
    }
    to {
      transform: rotate(360deg);
    }
  }
  @keyframes loader {
    from {
      transform: rotate(0);
    }
    to {
      transform: rotate(360deg);
    }
  }
  @media only screen and (max-width: 600px) {
    .spinner-btn{
      margin-top:-20px;
    }
  }
  
</style>