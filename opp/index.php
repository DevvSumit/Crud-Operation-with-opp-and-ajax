<?php
require "session.php";
$user = new User();
$users = $user->readAll();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CRUD App with Modals (Bootstrap 3.7)</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">

</head>


<body>

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="text-left">CRUD Application</h2>
            </div>
            <div class="col-md-6 text-right" style="margin-top:20px;">
                <button class="btn btn-danger" data-toggle="modal" data-target="#logoutModal">
                    <i class="glyphicon glyphicon-log-out"></i> Logout
                </button>
            </div>

        </div>

        <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">+ Add User</button>
        <br><br>

        <table class="table table-bordered table-striped">
            <thead>
                <tr class="info">
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Image</th>
                    <th width="160" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="userTable">
                <?php
                foreach ($users as $u):
                ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= $u['name'] ?></td>
                        <td><?= $u['email'] ?></td>
                        <td><?= $u['password'] ?></td>
                        <td><img src="upload/<?= $u['image'] ?>" alt="" width="100px" height="100px"></td>
                        <td>
                            <a href="#" class="btn-lg text-success editBtn" data-id="<?= $u['id'] ?>" data-name="<?= $u['name'] ?>" data-email="<?= $u['email'] ?>"><i class="fa fa-edit"></i></a>
                            <a href="#" class="btn-lg text-danger deleteBtn" data-id="<?= $u['id'] ?>"><i class="fa fa-trash"></i></a>
                        </td>

                    </tr>
                <?php
                endforeach;
                ?>
            </tbody>
        </table>
    </div>







    <!-- Edit Modal -->
    <div id="editModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit User</h4>
                </div>
                <div class="modal-body">
                    <form id="editForm" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="editId">
                        <input type="hidden" name="type" value="3">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" id="editName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" id="editEmail" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Password (Leave blank to keep current)</label>
                            <input type="password" name="pass" id="editPass" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Image (Optional)</label>
                            <input type="file" name="image" id="editImage" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-warning">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Delete Modal -->
    <div id="deleteModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delete User</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this user?</p>
                    <button class="btn btn-danger" id="confirmDelete">Yes, Delete</button>
                    <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Modal -->
    <div id="logoutModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Logout</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to logout?</p>
                    <div class="text-right">
                        <a href="logout.php" class="btn btn-danger btn-sm">Yes, Logout</a>
                        <button class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            let id = null;
            //create user with ajax
            $("#addForm").on("submit", function(e) {
                e.preventDefault();
                $.ajax({
                    url: "save.php",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        if (res == 1) {
                            alert("User Created Successfully");
                            $("#addModal").modal("hide");
                            location.reload();
                        } else {
                            alert(res);
                        }
                    }
                })
            })

            //delete user 
            $(document).on("click", ".deleteBtn", function(e) {
                e.preventDefault();
                id = $(this).data("id"); // user id lo
                $("#deleteModal").modal("show");
            });
            $("#confirmDelete").on("click", function() {
                if (id) {
                    $.ajax({
                        url: "save.php",
                        type: "POST",
                        data: {
                            type: 2,
                            id: id
                        },
                        success: function(res) {
                            if (res.trim() == "1") {
                                alert("User deleted successfully");
                                $("#deleteModal").modal("hide");
                                // table row remove
                                $("a.deleteBtn[data-id='" + id + "']").closest("tr").fadeOut();
                            } else {
                                alert("Failed to delete user: " + res);
                                location.reload();
                            }
                        }
                    });
                }
            });

            //edit 
            // Edit button click
            $(document).on("click", ".editBtn", function(e) {
                e.preventDefault();
                let id = $(this).data("id");
                let name = $(this).data("name");
                let email = $(this).data("email");

                $("#editId").val(id);
                $("#editName").val(name);
                $("#editEmail").val(email);
                $("#editPass").val("");
                $("#editImage").val("");

                $("#editModal").modal("show");
            });

            // Submit edit form
            $("#editForm").on("submit", function(e) {
                e.preventDefault();
                $.ajax({
                    url: "save.php",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        if (res.trim() == "1") {
                            alert("User updated successfully");
                            $("#editModal").modal("hide");
                            location.reload(); // reload table
                        } else {
                            alert(res);
                        }
                    }
                });
            });




        })
    </script>


</body>

</html>
<!-- Add Modal -->
<div id="addModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add User</h4>
            </div>
            <div class="modal-body">
                <form id="addForm" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                        <input type="hidden" name="type" value="1">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>password</label>
                        <input type="password" name="pass" id="addEmail" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>image</label>
                        <input type="file" id="image" name="image" class="form-control" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-success">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>