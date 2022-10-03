<template>
  <main>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-5">
          <div class="card shadow-lg border-0 rounded-lg mt-5">
            <div class="card-header">
              <h3 class="text-center font-weight-light my-2">
                <img src="img/TOMATO.png" alt="" width="24" height="24"
                  class="img-fluid pb-1"
                />
                Registre Artesapp
              </h3>
            </div>
            <div class="card-body">

              <form v-on:submit.prevent="registre">
                <div class="form-floating mb-3">
                  <input
                    class="form-control"
                    id="name"
                    type="text"
                    placeholder="Nom"
                    v-model="user.name"
                  />
                  <label for="name">Nom</label>
                  <!--retorn error -->
                  <div class="invalid-feedback" v-if="errors.name">
                    <strong>{{ errors.name[0] }}</strong>
                  </div>
                </div>

                <div class="form-floating mb-3">
                  <input
                    class="form-control"
                    id="email"
                    type="email"
                    placeholder="name@example.com"
                    v-model="user.email"
                  />
                  <label for="email">Email address</label>
                  <!--retorn error -->
                  <div class="invalid-feedback" v-if="errors.email">
                    <strong>{{ errors.email[0] }}</strong>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-6">
                    <div class="form-floating mb-3 mb-md-0">
                      <input
                        class="form-control"
                        id="password"
                        type="password"
                        placeholder="Create a password"
                        v-model="user.password"
                      />
                      <label for="password">Password</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-floating mb-3 mb-md-0">
                      <input
                        class="form-control"
                        id="password_confirmation"
                        type="password"
                        placeholder="Confirm password"
                        v-model="user.password_confirmation"
                      />
                      <label for="password_confirmation"
                        >Confirma Password</label
                      >
                    </div>
                  </div>
                  <!--retorn errors pass -->
                  <div class="invalid-feedback" v-if="errors.password">
                    <strong>{{ errors.password[0] }}</strong>
                  </div>
                  <div
                    class="invalid-feedback"
                    v-if="errors.password_confirmation"
                  >
                    <strong>{{ errors.password_confirmation[0] }}</strong>
                  </div>
                </div>
                <div class="mt-4 mb-0">
                  <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-block">
                      Crea Compte!
                    </button>
                  </div>
                </div>
              </form>
            </div>
            <div class="card-footer text-center py-3">
              <div class="small">
                <router-link to="/login">
                Have an account? Go to login
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import * as auth from "../../services/auth_service";
export default {
  name: "Register",
  components: {},
  data() {
    return {
      user: {
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
      },
      errors: {},
    };
  },
  methods: {
    registre: async function () {
      try {
        //await service auth
        await auth.registreUser(this.user);

        //netegem errors
        this.errors = {};

        //redirigim a login
        this.$router.push('/login');

      } catch (error) {
        //depenent codi error
        switch (error.response.status) {
          //error validació
          case 422:
            this.errors = error.response.data.errors;
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