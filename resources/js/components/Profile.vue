<style scoped>
.widget-user-header{
    background-position: center center;
    background-size: cover;
    height: 250px;
}
</style>

<template>
    <div class="container mt-5">
        <div class="row ">
            <div class="col-md-12">

            <!-- Widget: user widget style 1 -->
            <div class="card card-widget widget-user">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header text-white" style="background-image:url('./img/back.jpg');">
                <h3 class="widget-user-username text-right">Aaloub</h3>
                <h5 class="widget-user-desc text-right">Web Designer</h5>
              </div>
              <div class="widget-user-image">
                <img class="img-circle" :src="getprofilephoto()" alt="User Avatar">
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">3,200</h5>
                      <span class="description-text">SALES</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">13,000</h5>
                      <span class="description-text">FOLLOWERS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4">
                    <div class="description-block">
                      <h5 class="description-header">35</h5>
                      <span class="description-text">PRODUCTS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
            </div>
            <!-- /.widget-user -->
          </div>

            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="col-md-12">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>

                  <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane" id="activity">

                      </div>


                  <!-- /.tab-pane -->

                  <div class="tab-pane active" id="settings">
                    <form class="form-horizontal">
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                          <input :class="{ 'is-invalid': form.errors.has('name') }" v-model="form.name"  name="name" type="text" class="form-control" id="inputName" placeholder="Name">
                          <has-error :form="form" field="name"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input :class="{ 'is-invalid': form.errors.has('email') }"  v-model="form.email" name="email"  type="email" class="form-control" id="inputEmail" placeholder="Email">
                          <has-error :form="form" field="email"></has-error>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">Experience</label>
                        <div class="col-sm-10">
                          <textarea :class="{ 'is-invalid': form.errors.has('bio') }" v-model="form.bio" class="form-control" name="bio" id="inputExperience" placeholder="Experience"></textarea>
                          <has-error :form="form" field="bio"></has-error>
                        </div>
                      </div>
                       <div class="form-group row">
                        <label for="photo" class="col-sm-2 col-form-label">Photo</label>
                        <div class="col-sm-10">
                          <input :class="{ 'is-invalid': form.errors.has('photo') }" type="file" @change="updateProfile" name="photo" class="form-control" id="photo">
                           <has-error :form="form" field="photo"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="passport" class="col-sm-2 col-form-label">passport</label>
                        <div class="col-sm-10">
                          <input :class="{ 'is-invalid': form.errors.has('password') }" v-model="form.password" type="text" name="password" class="form-control" id="passport" placeholder="passport(leave empty if not changing)">
                          <has-error :form="form" field="password"></has-error>
                        </div>
                      </div>
                      <!-- <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                            </label>
                          </div>
                        </div>
                      </div> -->
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" @click.prevent="updateInfo" class="btn btn-success">update</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
                </div>
            </div>


        </div>

</template>

<script>
    export default {
        data(){
            return{
                   form:new Form({
                    id:'',
                    name:'',
                    email:'',
                    password:'',
                    type:'',
                    bio:'',
                    photo:''

                })
            }
        }

        ,mounted() {
            console.log('Component mounted.')
        },
        methods:{
            getprofilephoto(){

                //fix the probleme when change photo  photo not change just in when  refresh the page
                var photo = (this.form.photo.length > 200) ? this.form.photo : "img/profile/"+ this.form.photo ;
                return photo ;
                // return "img/profile/"+this.form.photo;

            },
            updateInfo(){
                this.form.put('api/profile/')
                .then(()=>{
                      this.$Progress.start();
                      Fire.$emit('afterupdated');
                      this.$Progress.finish();
                                        Toast.fire({
                            type: 'success',
                                title: 'profile Updated in successfully'
                                        })

                                })
                .catch(()=>{
                     this.$Progress.fail();

                })

            },
          updateProfile(e){
            // console.log('uploading');
            let file=e.target.files[0];
            console.log(file);
            let reader = new FileReader();
            if(file['size'] < 2111775){
            reader.onloadend = (file) =>{
              // console.log('result',reader.result);
              this.form.photo= reader.result;

            }
             reader.readAsDataURL(file);
          }else{
                   Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'the size is not good for uploading!'
                    })

          }
          }

        },
        created(){
            axios.get("api/profile")
            .then(({data})=>(this.form.fill(data)));
               Fire.$on('afterupdated',()=>{
              axios.get("api/profile").then(({data})=>(this.form.fill(data)));

            });

        }
    }
</script>
