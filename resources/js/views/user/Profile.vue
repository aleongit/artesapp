<template>
  <div id="content-wrapper">
    <div class="container-fluid py-4">
      <div class="row justify-content-center">
        <div class="col">

          <!--breadcrumb-->
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <i class="fas fa-angle-double-right px-2"></i>
                <router-link to="/home" exact>Home </router-link>
              </li>
              <li class="breadcrumb-item">User</li>
              <li class="breadcrumb-item active" aria-current="page">
                Profile
              </li>
            </ol>
          </nav>

          <div class="row justify-content-center">
            <div class="col-lg-5">

              <!--card table-->
              <div class="card my-2">
                <div class="card-header d-flex">
                  <span><i class="fas fa-user"></i> Profile</span>
                  
                  <!--loading spinner-->
                  <SpinnerLoading :loading="loading"/>

                </div>

                <div class="card-body">
                  <h5 class="card-title">Profile!</h5>

                  <form v-on:submit.prevent="userUpdate">
                    <!--name-->
                    <div class="form-floating mb-3">
                      <input
                        class="form-control"
                        id="name"
                        type="text"
                        placeholder="name"
                        v-model="userData.name"
                      />
                      <label for="email">Nom</label>
                      <!--retorn error -->
                      <div class="invalid-feedback" v-if="errors.name">
                        <strong>{{ errors.name[0] }}</strong>
                      </div>
                    </div>

                    <!--email-->
                    <div class="form-floating mb-3">
                      <input
                        class="form-control"
                        id="email"
                        type="email"
                        placeholder="name@example.com"
                        v-model="userData.email"
                      />
                      <label for="email">Email</label>
                      <!--retorn error -->
                      <div class="invalid-feedback" v-if="errors.email">
                        <strong>{{ errors.email[0] }}</strong>
                      </div>
                    </div>

                    <div
                      class="
                        d-flex
                        align-items-center
                        justify-content-between
                        mt-4
                        mb-0
                      "
                    >
                      <button type="submit" class="btn btn-primary">
                        Update!
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as auth from "../../services/auth_service";
import SpinnerLoading from '../../components/SpinnerLoading.vue';

export default {
  name: "Profile",
  components: {
    SpinnerLoading,
  },
  data() {
    return {
      userData: {
        name: "",
        email: "",
      },
      errors: {},
      loading: true,
    };
  },

  //al muntar component
  mounted() {
    this.getProfile();
  },

  methods: {
    getProfile: async function () {
      try {
        const response = await auth.getProfile();
        console.log(response);
        this.userData.name = response.data.name;
        this.userData.email = response.data.email;
        //console.log(this.userData.name, this.userData.email);

        //parem spinner
        this.loading = false;

      } catch (error) {
        this.$flashMessage.show({
          type: "error",
          text: "alguna cosa ha anat malament :(",
          time: 5000,
        });
      }
    },

    userUpdate: async function () {
      console.log("update profile!");
      
      try {
        //await service auth
        const response = await auth.updateProfile(this.userData);
        console.log(response);

        //flash message
        this.$flashMessage.show({
          type: "success",
          text: "Profile update ok! :)",
          time: 5000,
        });

        //aprofito acció 'authenticate' per canviar el 'profile' guardat a store
        this.$store.dispatch('authenticate', response.data);

        this.errors = {}; //netegem errors

      } catch (error) {
        console.log(error);
        //depenent codi error
        switch (error.response.status) {
          case 422: //validació
            this.errors = error.response.data.errors;
            break;
          case 401: //no autoritzat
            this.$flashMessage.show({
              type: "error",
              text: error.response.data.message,
              time: 5000,
            });
            break;
          case 500:
            this.$flashMessage.show({
              type: "error",
              text: error.response.data.message,
              time: 5000,
            });
            break;
          default:
            //flash message
            this.$flashMessage.show({
              type: "error",
              text: "alguna cosa ha anat malament :(",
              time: 5000,
            });
            break;
        }
      }
      
    },
  },
};
</script>