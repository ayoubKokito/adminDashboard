<template>
    <div class="container">
<div class="row mt-5" v-if="$gate.isAdmin()">
            <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"> <i class="fas fa-users fa-2x"></i>  Table Users  </h3>

                <div class="card-tools">
                    <!-- <button class="btn btn-success" data-toggle="modal" data-target="#addnew" > <i class="fa fa-user-plus"> </i>  </button> -->
                    <button class="btn btn-success" @click="newModal" > <i class="fa fa-user-plus"> </i>  </button>

                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Type</th>
                        <th>Registred At</th>
                      <th>Modify</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="user in users" :key="user.id">
                      <td>{{user.id}}</td>
                      <td>{{user.name}}</td>
                      <td>{{user.email}}</td>

                      <td>{{user.type |upText}} </td>
                      <td>{{user.created_at |myDate}}</td>
                      <td>
                          <a href="" @click.prevent="editModal(user)"><i class="fa fa-edit fa-2x blue "></i></a>
                          <a href="" @click.prevent="deleteUser(user.id)"  ><i class="fa fa-trash fa-2x red ml-4"></i></a>
                      </td>
                    </tr>


                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          </div>



          <div v-if="!$gate.isAdmin()">
              <not-found></not-found>
          </div>

          <!-- Modal -->







<div class="modal fade" id="addnew" tabindex="-1" role="dialog" aria-labelledby="addnewLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5   v-show="!editMode" class="modal-title" id="addnewLabel">Add New User</h5>
        <h5 v-show="editMode" class="modal-title" id="addnewLabel">Edit User Information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>


        <form @submit.prevent="editMode?updateUser():createUser()" method="">
      <div class="modal-body">
           <div class="form-group">
      <label>Name</label>
      <input v-model="form.name" type="text" name="name"
      placeholder="Name"
        class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
      <has-error :form="form" field="name"></has-error>
    </div>


           <div class="form-group">
      <label>Email</label>
      <input v-model="form.email" type="text" name="email"
      placeholder="Email Adress"
        class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
      <has-error :form="form" field="email"></has-error>
    </div>
           <div class="form-group">
      <label>Bio</label>
      <textarea v-model="form.bio" type="text" name="bio"
      placeholder="short bio for user(optional)"
        class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
      <has-error :form="form" field="bio"></has-error>
    </div>
        <div class="form-group">
      <label>User type</label>
      <select v-model="form.type" type="text" id="type" name="type"

        class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
        <option value="">Select user Role</option>
        <option value="admin">Admin</option>
        <option value="user">Stander User</option>
        <option value="author">Author</option>

        </select>
      <has-error :form="form" field="bio"></has-error>
    </div>

       <div class="form-group">
      <label>Password</label>
      <input v-model="form.password" type="password" id="password" name="password"
      placeholder="create password"
        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
      <has-error :form="form" field="bio"></has-error>
    </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
        <button v-show="editMode" type="submit" class="btn btn-success">Update</button>
      </div>
      </form>
    </div>
  </div>

</div>

    </div>
</template>

<script>
    export default {
        data(){
            return{
              editMode:false,
                users:{},
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
        },
        methods:{
          updateUser(){
                this.$Progress.start();
            // console.log('editing data');
            this.form.put('api/user/'+this.form.id)
            .then(()=>{
                //success
                  $('#addnew').modal('hide');

                Swal.fire(
                'Updated!',
                'the User has been Updated.',
                'success'
              )

                this.$Progress.finish();
                 Fire.$emit('afterCreated');

            })
            .catch(()=>{
              //error
              this.$Progress.fail();
            });

          }

             ,editModal(user){
               this.editMode=true;
                this.form.reset();
                  $('#addnew').modal('show');
                  this.form.fill(user);

             },
            newModal(){
              this.editMode=false;
                this.form.reset();
                  $('#addnew').modal('show');

            }
         , deleteUser(id){

          Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            //send request to the server
            this.form.delete('api/user/'+id).then(()=>{
                    if (result.value) {
              Swal.fire(
                'Deleted!',
                'Your file has been deleted.',
                'success'
              )
            }
              Fire.$emit('afterCreated');

            })
            .catch(()=>{

                 Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!'
                    })

            })

          })

          },
            loadUsers(){
                if(this.$gate.isAdmin()){
                axios.get("api/user").then(({data})=>(this.users = data.data));
                }

            },
            createUser(){
                this.$Progress.start();
                  this.form.post('api/user')
              .then(()=>{



                Fire.$emit('afterCreated');

                $('#addnew').modal('hide')
                Toast.fire({
            type: 'success',
                title: 'User Created in successfully'
                           })


              })
              .catch(()=>{

              })
            }

        },
        created() {
            this.loadUsers();
            Fire.$on('afterCreated',()=>{
                this.loadUsers();

            });
            // () in es6 like function
            // setInterval(()=>this.loadUsers(),3000)
        }
    }
</script>
