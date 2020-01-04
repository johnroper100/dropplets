<template>
  <div class="product-action-bar">
    <input
      :value="productNameToCreate"
      @input="setProductNameToCreate($event.target.value)"
      @keypress.enter="triggerAddProductAction"
      placeholder="product name..."
      class="product-name-input"
      type="text"
    />
    <div
      :class="{ disabled: productCreationPending }"
      @click="triggerAddProductAction"
      class="create-product-btn"
    >
      add product
    </div>
  </div>
</template>

<script>
import { mapMutations, mapState, mapActions } from 'vuex'
export default {
  computed: mapState('products', [
    'productNameToCreate',
    'productCreationPending'
  ]),
  methods: {
    ...mapMutations('products', ['setProductNameToCreate']),
    ...mapActions('products', ['triggerAddProductAction'])
  }
}
</script>

<style lang="scss" scoped>
@import '@/theme/variables.scss';
.product-action-bar {
  display: flex;
  align-items: center;
  justify-content: center;
  .product-name-input {
    padding-left: 5px;
    height: 30px;
    width: 150px;
    outline: none;
    font: inherit;
    border: 1px solid;
    border-color: #2c3e50;
    border-radius: 3px;
  }
  .create-product-btn {
    cursor: pointer;
    padding: 5px 10px;
    border: 1px solid;
    display: inline-block;
    border-radius: 3px;
    margin-left: 10px;
    border-color: #2c3e50;
    &.disabled {
      pointer-events: none;
      background-color: #aaa;
    }
    &:hover {
      color: $vue-color;
      border-color: $vue-color;
    }
  }
}
</style>
