<template>
  <div>
    <div class="modal-wrapper">
      <div @click="$emit('close')" class="close"></div>
      <div class="main-content">
        <img class="app-logo" src="@/assets/logo.png" />
        <h3 class="title">Install {{ appTitle }}</h3>
        <p class="description">
          Install this application to your home screen for quickly and easy
          access when you're on the go.
        </p>
      </div>

      <div class="footer">
        Just tap
        <img
          class="apple-navigation-action-img"
          src="@/assets/img/apple-navigation-action.png"
        />then 'Add to Home Screen'
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  computed: mapState('app', ['appTitle'])
}
</script>

<style lang="scss" scoped>
.modal-wrapper {
  box-shadow: 0px 0px 20px 0px #656565;
  margin: 30px;
  background: white;
  position: relative;
  .close {
    position: absolute;
    right: 10px;
    top: 10px;
    width: 21px;
    height: 21px;
    opacity: 0.3;
  }
  .close:hover {
    opacity: 1;
  }
  .close:before,
  .close:after {
    position: absolute;
    left: 8px;
    content: ' ';
    height: 22px;
    width: 2px;
    background-color: #333;
  }
  .close:before {
    transform: rotate(45deg);
  }
  .close:after {
    transform: rotate(-45deg);
  }
  .main-content {
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    .app-logo {
      max-width: 50px;
      margin-bottom: 10px;
    }
    .title {
      margin: 5px 0;
      text-align: center;
    }
    .description {
      margin: 5px 0;
      text-align: center;
    }
  }
  .footer {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px;
    background-color: #f9f9f9;
    font-size: 12px;
    .apple-navigation-action-img {
      max-width: 15px;
      margin: 0 10px;
    }
  }
}
</style>
