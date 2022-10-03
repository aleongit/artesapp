<template>
  <main>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-5">
          <div class="card shadow-lg border-0 rounded-lg mt-5">
            <div class="card-header">
              <h3 class="text-center font-weight-light my-2">
                <img src="img/TOMATO.png" alt="" width="24" height="24" class="img-fluid pb-1" />
                Login Artesapp
              </h3>
            </div>
            <div class="card-body">
              <form v-on:submit.prevent="login">
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
                <div class="form-floating mb-3">
                  <input
                    class="form-control"
                    id="password"
                    type="password"
                    placeholder="Password"
                    v-model="user.password"
                  />
                  <label for="password">Password</label>
                  <!--retorn error -->
                  <div class="invalid-feedback" v-if="errors.password">
                    <strong>{{ errors.password[0] }}</strong>
                  </div>
                 
                </div>
                <div class="form-check mb-3">
                  <input
                    class="form-check-input"
                    id="remember_me"
                    type="checkbox"
                    value=""
                    v-model="user.remember_me"
                  />
                  <label class="form-check-label" for="remember_me">
                    Remember Password
                  </label>
                </div>
                <div
                  class="d-flex align-items-center justify-content-between mt-4 mb-0">
                  <a class="small" href="password.html">Forgot Password?</a>
                  <button type="submit" class="btn btn-primary">
                      Login!
                  </button>
                </div>
              </form>
            </div>
            <div class="card-footer text-center py-3">
              <div class="small">
                <router-link to="/register">
                Need an account? Sign up!</router-link>
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
  name: "Login",
  components: {},
  data() {
    return {
      user: {
        name: "",
        email: "",
        remember_me: false,
      },
      errors: {},
    };
  },
  methods: {
    login: async function () {
      try {
        //await service auth
        const response = await auth.loginUser(this.user);
        //console.log(response);
        this.errors = {};   //netegem errors
        this.$router.push('/home'); //redirigir a home

        //this.errors = {};
        //this.$router.push('/login');

      } catch (error) {
        //depenent codi error
        switch (error.response.status) {
          case 422:    //validació
            this.errors = error.response.data.errors;
            break;
          case 401:   //no autoritzat
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