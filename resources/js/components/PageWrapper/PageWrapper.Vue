<template>
  <VContainer grid-list-xl text-xs-center>
    <v-flex >
    <VLayout
      xs10
      row
      wrap
      justify-center
    >
      <VFlex
        :lg12="!narrow"
        :lg10="narrow"
      >
        <VBreadcrumbs
          :items="breadcrumbs">
        <template v-slot:divider>
        <v-icon>forward</v-icon>
      </template>
        </VBreadcrumbs>
      </VFlex>
      <VFlex
        :lg12="!narrow"
        :lg10="narrow"
        md12
        xs12
      >
        <VSlideYTransition>
          <slot v-if="!loading" />
        </VSlideYTransition>
      </VFlex>
    </VLayout>
    </v-flex>
  </VContainer>
</template>

<script>
export default {
  props: {
    narrow: {
      type: Boolean,
      default: false
    },
    breadcrumbs: {
      type: Array,
      default: () => []
    },
    loading: {
      type: Boolean,
      default: false
    }
  },
}
</script>
