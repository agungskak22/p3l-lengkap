<template>
  <div>
    <VForm>
      <VLayout>
        <VFlex>
          <VTextField
            label="Password Baru"
            prepend-icon="lock" 
            type="password"
            class="pa-1"
            v-model="form.password"
            :error-messages="passwordErrors"
            @input="$v.form.password.$touch()"
            required
          />
        </VFlex>
      </VLayout>
      <VLayout>
        <VFlex>
          <VTextField
            label="Konfirmasi Password"
            prepend-icon="lock" 
            type="password"
            class="pa-1"
            v-model="form.confirmPassword"
            :error-messages="passwordErrors"
            @input="$v.form.password.$touch()"
            required
          />
        </VFlex>
      </VLayout>
      <VSpacer />
      <VLayout>
        <VFlex
          class="text-xs-center"
        >
          <VBtn
            depressed
            color="success"
            @click="submitHandler"
            :disabled="!this.value"
          >
            Save
          </VBtn>
        </VFlex>
      </VLayout>
    </VForm>
   </div>
</template>

<script>
import validator from '../../validations/userValidation'
import { mapGetters,mapState, mapActions } from 'vuex'

export default {
  props: {
    value: {
      type: Boolean,
      default: false
    }
  },

  computed: {
    ...mapGetters({
      form: 'User/user'
    }),

    ...mapState({
      error: state => state.User.error,
      loading: state => state.User.loading
    }),
   
    passwordErrors () {
      const errors = []

      if (!this.$v.form.password.$invalid) return errors 

      if (!this.$v.form.password.numeric && this.$v.form.password.$dirty) {
        errors.push('Inputan password tidak valid')
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
    }
  }
}
</script>
