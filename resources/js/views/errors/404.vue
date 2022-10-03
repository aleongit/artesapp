<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="text-center mt-4">
          <h1 class="display-1 m-0">404</h1>
          <img
            :src="`${$store.state.serverPath}/img/TOMATO.png`"
            alt=""
            width="100"
            height="100"
            class="img-fluid pb-1"
          />
          <h3>* FATAL ERROR ARTESAPP *</h3>
          <h2 class="py-2">Not Found</h2>
          <p class="lead">This requested URL was not found on this server.</p>
          <router-link to="/home">
            <i class="fas fa-arrow-left me-1"></i>
            Return to Dashboard
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>