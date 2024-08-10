<!DOCTYPE html>
<html lang="en">
<head>
  <title>Machine Test</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

</head>
<body>
    <div class="container mt-3">
    <h2>User form</h2>
    <form action="/action_page.php">
        <div class="mb-3 mt-3">
        <label for="name">Name:</label>
        <input type="text" class="form-control" id="name" placeholder="Enter name" name="name">
        </div>
        <div class="mb-3 mt-3">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
        </div>
        <div class="mb-3">
        <label for="phone">Phone No:</label>
        <input type="number"  class="form-control" id="phone" placeholder="Enter phone no" name="phone">
        </div>
        <div class="mb-3">
            <label for="image">Image</label>
            <input type="file" class="form-control" id="image" name="image">
        </div>
        <div class="mb-3">
            <label for="role">Role</label>
            <select class="form-control" name="role_id" id="role">
                <option value="">Select Role</option>
                @forelse ($all_data as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @empty
                @endforelse

            </select>
        </div>
        <div class="mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <button type="button" class="btn btn-primary submit">Submit</button>
    </form>
    </div>

    <div class="container mt-3">
        <h2>User Data</h2>        
        <table class="table">
          <thead>
            <tr>
              <th>Id</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone No</th>
              <th>Role</th>
              <th>Image</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody class="table-append">

            @forelse ($user_data as $data)
            <tr>
              <td>{{$data->id}}</td>
              <td>{{$data->name}}</td>
              <td>{{$data->email}}</td>
              <td>{{$data->phone}}</td>
              <td>@if(isset($data->role)){{$data->role->name}}@endif</td>
              <td><img src="{{$data->image}}" style="height:50px; width:50px;"></td>
              <td>{{$data->description}}</td>

            </tr>
            @empty
            <tr>
              <td colspan="7"> No Record Found</td>
            </tr>          
            @endforelse
      
            
          </tbody>
        </table>
      </div>
</body>
<script>
    $('body').on('click', '.submit', function(event) {
        event.preventDefault(); 
        $(".error").hide();
        var hasError = false;

        var name = $("#name").val();
        var email = $("#email").val();
        var phone = $("#phone").val();
        var role_id = $("#role").val();
        var description = $("#description").val();
        var image = $("#image").val();
        var emailPattern = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;
        var phonePattern = /^[789]\d{9}$/;

        // alert(role_id + " role");
        if (name == "") {
            $("#name").after("<span class='error' style='color:red'>Name field is required</span>");
            hasError = true;
        }
        if (email == "") {
            $("#email").after("<span class='error' style='color:red'>Email field is required</span>");
            hasError = true;
        } else if (!emailPattern.test(email)) {
            $("#email").after("<span class='error' style='color:red'>Enter a valid email</span>");
            hasError = true;
        }
        if (phone == "") {
            $("#phone").after("<span class='error' style='color:red'>Phone field is required</span>");
            hasError = true;
        } else if (!phonePattern.test(phone)) {
            $("#phone").after("<span class='error' style='color:red'>Enter a valid Indian mobile number</span>");
            hasError = true;
        }
        if (role_id == "") {
            $("#role").after("<span class='error' style='color:red'>Role field is required</span>");
            hasError = true;
        }
        if (description == "") {
            $("#description").after("<span class='error' style='color:red'>Description field is required</span>");
            hasError = true;
        }
        if (image == "") {
            $("#image").after("<span class='error' style='color:red'>Image field is required</span>");
            hasError = true;
        }

        if (hasError) {
            return false;
        }

        var formData = new FormData();
        formData.append('name', name);
        formData.append('email', email);
        formData.append('phone', phone);
        formData.append('role_id', role_id);
        formData.append('description', description);
        formData.append('image', $('#image')[0].files[0]);
        var token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: "{{url('save')}}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': token 
            },
            success: function(response) {
                if(response== 2){
                    toastr.error("Please enter unique email");
                }else{
                    $(".form-control").val('');
                    toastr.success("Data save successfully")
                    tableData(response);
                }

            }
        });
    

        
    });
    function tableData(data){
      var html = ''; 
      data.map(function(ele, index){
          // console.log(ele.name);
          html += '<tr>';
          html += '<td>' + ele.id + '</td>';
          html += '<td>' + ele.name + '</td>';
          html += '<td>' + ele.email + '</td>';
          html += '<td>' + ele.phone + '</td>';
          html += '<td>' + ele.role.name + '</td>';
          html += '<td><img src="' + ele.image + '" style="height:50px; width:50px;"></td>';
          html += '<td>' + ele.description + '</td>';
          html += '</tr>';

      });
      $(".table-append").html(html);
    }

</script>
</html>
