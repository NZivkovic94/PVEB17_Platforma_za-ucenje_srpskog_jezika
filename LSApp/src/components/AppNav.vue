<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    
    <router-link class="navbar-brand" to="/">Learn Serbian</router-link>
    <button class="navbar-toggler" 
            type="button" 
            data-toggle="collapse" 
            data-target="#navbarSupportedContent" 
            aria-controls="navbarSupportedContent" 
            aria-expanded="false" 
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
    </button>

  

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      

      <ul class="navbar-nav mr-auto">
        <li class="nav-item ">
          <router-link class="nav-link" 
                       to="/" 
                       active-class="active"
                       exact>
          Home
          </router-link>
        </li>
        <li class="nav-item ">
          <router-link class="nav-link" 
                       to="/lesson" 
                       active-class="active"
                       exact>
          Lectures 
          </router-link>
        </li>
         <li class="nav-item ">
          <router-link class="nav-link" 
                       to="/quiz" 
                       active-class="active"
                       exact
                       disabled>
          Quizzes 
          </router-link>
        </li>
          <li class="nav-item ">
          <router-link class="nav-link" 
                       to="/settings" 
                       active-class="active"
                       exact
                       disabled>
          Settings
          </router-link>
        </li>
          <li class="nav-item ">
              <router-link class="nav-link"
                           to="/professormainpage"
                           v-if = "isProfessor === true"
                           active-class="active"
                           exact
                           disabled>
                  ProfessorMainPage
              </router-link>
          </li>
          <li class="nav-item ">
              <router-link class="nav-link"
                           to="/studentmainpage"
                           v-if = "isStudent === true"
                           active-class="active"
                           exact
                           disabled>
                  StudentMainPage
              </router-link>
          </li>
        <li class="nav-item ">
          <router-link class="nav-link" 
                       to="/admindashboard"
                       v-if = "isAdmin === true"                       
                       active-class="active"
                       exact
                       disabled>
          Admin Dashboard
          </router-link>
        </li>



        <!-- dropdown button
        <li class="nav-item dropdown">
          <router-link class="nav-link dropdown-toggle" to="#" 
                        id="navbarDropdown" 
                        role="button" 
                        data-toggle="dropdown" 
                        aria-haspopup="true" 
                        aria-expanded="false">
            MyProfile
          </router-link>
  
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <router-link class="dropdown-item" to="#">Home</router-link>
            <router-link class="dropdown-item" to="#">Lectures</router-link>
            <router-link class="dropdown-item" to="#">Quizzes</router-link>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Settings</a>
          </div>
        </li>
      -->


      </ul>

      <form class="form-inline my-5 my-lg-0" v-if="isLogged === false ">
         <router-link class="nav-link" to='/signin'>Sign in <span class="sr-only">(current)</span></router-link>
         or 
         <router-link class="nav-link" to='/signup'>Sign up <span class="sr-only">(current)</span></router-link>
      </form>

      <router-link class="nav-link" to='/' v-if="isLogged === true" v-on:click.native="logOut" >Sign out <span class="sr-only">(current)</span></router-link>
    
    </div>
  </nav>
</template>



<script>


export default {

  name: "AppNav",
        data(){
          return {
              isLogged: this.isLoggedIn(),
              isAdmin: this.isLoggedAdmin(),
              isProfessor: this.isLoggedProfessor(),
              isStudent: this.isLoggedStudent(),


          }
        },
        created() {
            this.$bus.$on('logged', () => {this.isLogged = this.isLoggedIn()})
        },
        updated(){
             this.setAuthenticatedUser()
        },

        methods: {

            logOut: function (e) {
                    this.$auth.destroyToken();
                    this.isLogged = this.isLoggedIn();
                    this.isAdmin = this.isLoggedAdmin();
                    this.isProfessor = this.isLoggedProfessor();
                    this.$router.push('/');
                    e.preventDefault();
            },
            isLoggedIn: function () {
              return this.$auth.isAuthenticated();
            },
            isLoggedAdmin: function(){
              return this.$auth.isAuthenticatedAsAdmin();
            },
            isLoggedProfessor: function(){
                return this.$auth.isAuthenticatedAsProfessor();
            },
            isLoggedStudent: function(){
                return this.$auth.isAuthenticatedAsStudent();
            },
            setAuthenticatedUser: function () {
                 if(localStorage.getItem('token'))
                    this.$http.get('http://localhost/PVEB17_Platform_for_learning_Serbian/laravel/public/api/user',
                        { headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}})
                        .then(response => {
                            this.$auth.setAuthenticated(response.body)
                            //console.log(this.$auth.getAuthenticated().id_user)
                        }).then(() => {
                            this.setAuthenticatedAsAdmin();
                            this.setAuthenticatedAsProfessor();
                            this.setAuthenticatedAsStudent();
                        });
            },
            
            setAuthenticatedAsAdmin: function(){
                this.$http.post('http://localhost/PVEB17_Platform_for_learning_Serbian/laravel/public/api/isAdmin',
                    {
                        "id_administrator" : this.$auth.getAuthenticated().id_user
                    }).then(response => {
                    return response.body
                }).then(response => {
                    this.$auth.setAdmin(response)
                    this.isAdmin = this.isLoggedAdmin();

                })

            },
            setAuthenticatedAsProfessor: function(){
                this.$http.post('http://localhost/PVEB17_Platform_for_learning_Serbian/laravel/public/api/isProfessor',
                    {
                        "id_professor" : this.$auth.getAuthenticated().id_user
                    }).then(response => {
                    return response.body
                }).then(response => {
                    this.$auth.setProfessor(response)
                    this.isProfessor = this.isLoggedProfessor();

                })

            },
            setAuthenticatedAsStudent: function(){
                this.$http.post('http://localhost/PVEB17_Platform_for_learning_Serbian/laravel/public/api/isStudent',
                    {
                        "id_student" : this.$auth.getAuthenticated().id_user
                    }).then(response => {
                    return response.body
                }).then(response => {
                    this.$auth.setStudent(response)
                    this.isStudent = this.isLoggedStudent();

                })

            },

        }
}
</script>

