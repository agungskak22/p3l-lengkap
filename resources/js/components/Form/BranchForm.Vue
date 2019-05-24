<template>
  <div>
    <VCardText
      v-if="loading || error"
      class="text-xs-center"
    >
      <VAlert
        v-if="error"
        :value="true"
        dismissible
        type="error"
      >
      {{ error.message }}
      </VAlert>
      <VProgressCircular
        v-if="loading"
        :size="45"
        indeterminate
        class="loading-section"
      />
    </VCardText>
    <VForm>
      <VLayout>
        <VFlex>
          <VTextField
            label="Nama"
            class="pa-1"
            v-model="form.branch_name"
            :error-messages="nameErrors"
            @input="$v.form.branch_name.$touch()"
            required
          />
        </VFlex>
      </VLayout>
      <VLayout>
        <VFlex>
          <VTextField
            label="Alamat"
            class="pa-1"
            v-model="form.branch_address"
            :error-messages="addressErrors"
            @input="$v.form.branch_address.$touch()"
            required
          />
        </VFlex>
      </VLayout>
      <VLayout>
        <VFlex>
          <VTextField
            label="No Telepon"
            class="pa-1"
            v-model="form.branch_phone_number"
            :error-messages="phoneNumberErrors"
            @input="$v.form.branch_phone_number.$touch()"
            required
          />
        </VFlex>
      </VLayout>
      <VSpacer />
      <VLayout>
        <VFlex
          class="text-xs-right"
        >
          <VBtn
            depressed
            color="indigo" v-on:click="dialog=true"
            @click="submitHandler"
            :disabled="!this.value"
            :loading="dialog"
            dark
          >
            simpan
          </VBtn>
        </VFlex>
      </VLayout>
    </VForm>
        <v-dialog
      v-model="dialog"
      hide-overlay
      persistent
      width="300"
    >
      <v-card
        color="primary"
        dark
      >
        <v-card-text>
          Mohon Tunggu Sebentar
          <v-progress-linear
            indeterminate
            color="white"
            class="mb-0"
          ></v-progress-linear>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import validator from '../../validations/branchValidation'
import { mapGetters, mapState } from 'vuex'

export default {
  data() {
    return {
      dialog: false
    }
  },
  props: {
    value: {
      type: Boolean,
      default: false
    }
  },

  computed: {
    ...mapGetters({
      form: 'Branch/branch'
    }),

    ...mapState({
      error: state => state.Branch.error,
      loading: state => state.Branch.loading
    }),
    
    nameErrors () {
      const errors = []

      if (!this.$v.form.branch_name.$invalid) return errors
      
      if (!this.$v.form.branch_name.required && this.$v.form.branch_phone_number.$dirty) {
        errors.push('Inputan nama tidak valid')
      }

      return errors
    },

    addressErrors () {
      const errors = []

      if (!this.$v.form.branch_address.$invalid) return errors 
      
      if (!this.$v.form.branch_address.required && this.$v.form.branch_phone_number.$dirty) {
        errors.push('Inputan alamat tidak valid')
      }

      return errors
    },

    phoneNumberErrors () {
      const errors = []

      if (!this.$v.form.branch_phone_number.$invalid) return errors 

      if (!this.$v.form.branch_phone_number.numeric && this.$v.form.branch_phone_number.$dirty) {
        errors.push('Inputan nomor telepon tidak valid')
      }

      if (!this.$v.form.branch_phone_number.maxLength && this.$v.form.branch_phone_number.$dirty) {
        errors.push('Nomor telepon yang anda masukkan melebihi 12 karakter')
      }

      if (!this.$v.form.branch_phone_number.required && this.$v.form.branch_phone_number.$dirty) {
        errors.push('Anda belum memasukkan nomor telepon cabang!')
      }

      return errors
    }
  },

  validations: validator,

  watch: {
    form: {
      deep: true,
      handler () {
        this.$emit('input', !this.$v.form.$invalid)
      }
    }    
  },

  methods: {
    submitHandler () {
      this.$emit('submitted', this.form)
    },
    reset () {
          this.$refs.form.reset()
    },
  }
}
</script>
