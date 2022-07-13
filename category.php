<?php
require('./partial/connection.php');

session_start();

if (!isset($_SESSION['adminid'])) {
    header("location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Manager (GE)</title>
    <?php
    require('./partial/link.php');
    require('./tools/dt/style.php');
    ?>
    <style>
        .reverse {
            display: none;
        }

        .btn_refresh_x {
            transition: transform 2s ease-in-out;
            z-index: 5;
            position: absolute;
            right: -1.2em;
            top: 1em;

        }

        .btn_refresh {
            float: right;
            transition: transform 2s ease-in-out;
        }

        .btn_close {
            z-index: 5;
            position: absolute;
            left: -1em;
            top: 0.6em;
            font-size: 1.3em;
        }

        .btn_refresh:hover,
        .btn_refresh_x:hover {
            transform: rotate(+360deg);
        }

        #rel_category {
            position: relative;
        }
    </style>
</head>

<body>
    <?php
    require('./partial/anav.php');
    if ($_SERVER['REQUEST_METHOD'] == "GET" && count($_GET) >= 1) {
        $msg;
        if (isset($_GET['u']) && $_GET['u'] == true) {
            $msg = "Category name changed to: <b>" . $_GET['cat'] . "</b>.";
        } else if (isset($_GET['d']) && $_GET['d'] == true && isset($_GET['cat'])) {
            // $msg = "Category: <b>" . $_GET['cat'] . "</b> is deleted successfully.";
            $msg = "Category is deleted successfully.";
            if (isset($_GET['sub'])) {
                // $msg = "Category: <b>" . $_GET['cat'] . "</b> with Sub-category: <b>" . $_GET['sub'] . "</b> relation is deleted successfully";
                $msg = "Category with Sub-category relation is deleted successfully";
            }
        } else {
            $msg = "Hope you are enjoing <b>Grocery Express</b>.";
        }
        echo '<div class="alert alert-success shadow a-msg" role="alert" id="alert-msg">
    ' . $msg . '
            </div>';

        echo '     <script>   
        setTimeout(() => {
            var myAlert = document.getElementById("alert-msg");
            var bsAlert = new bootstrap.Alert(myAlert);
            bsAlert.close();
        }, 5000);
        </script>
        ';
    }
    ?>

    <!-- cat sub-cat reverse err Modal -->
    <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModal1Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal1Label">oops! Something went wrong.</h5>
                </div>
                <div class="modal-body">
                    Category: <b id="err_c_name"></b> with Sub-category: <b id="err_s_name"></b> is not <i id="reverseActiveStatus"></i>.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Ok! I will do it again.</button>
                </div>
            </div>
        </div>
    </div>

    <!-- cat insert err Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">oops! Something went wrong.</h5>
                </div>
                <div class="modal-body">
                    Category: <b id="new_cat_name"></b> is not added to list. <br>
                    due to: <i id="new_cat_err"></i>
                    <div class="small">(if you can't understand it, please contact to developer.)</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Ok! I will do it again.</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit-modalTitle">Update Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form autocomplete="off" method="POST" id="form_cat" action="partial/update_category.php">
                        <div class="input-group mb-3">
                            <span class="input-group-text">Category Name:</span>
                            <input type="text" class="form-control" id="uc_name" name="uc_name" onkeyup="u_c_nameValidation()">
                            <input type="hidden" name="uc_id" id="uc_id">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary mt-5 mx-auto d-flex" id="update_cat" form="form_cat">Update Category</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">

        <div class="card border-primary">
            <h5 class="card-header">Insert Category or Sub-Category</h5>
            <div class="card-body">
                <form autocomplete="off">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Category Name:</span>
                        <input type="text" class="form-control" id="i_c_name" name="category" onkeyup="i_c_nameValidation()">
                        <button class="btn btn-outline-secondary" id="i_c">Insert</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-primary mt-3 table-responsive">
            <h5 class="card-header">List of Category(Sub-Category)</h5>
            <div class="card-body">
                <table class="table table-hover display" id="myTable">
                    <thead>
                        <tr>
                            <th>Edit</th>
                            <th>Name</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "select * from category";
                        $result = mysqli_query($conn, $query);
                        if (is_nan(mysqli_num_rows($result)) || mysqli_num_rows($result) == 0) {
                            echo "<p class='display-4 text-primary text-center mt-5'>Not Any Category is added.</p>";
                        } else {
                            while ($data = mysqli_fetch_assoc($result)) {
                                echo "<tr id='" . $data['c_id'] . "'>";
                                echo "<td> <button type='button' class='btn btn-outline-primary edit' data-toggle='modal' data-target='#edit-modal'>Edit</button> </td>";
                                echo "<td>" . $data['c_name'] . "</td>";
                                echo "<td> <a type='button' onclick='return delete_confirm()' href='./partial/c_delete.php?delete=" . $data['c_id'] . "' class='btn btn-outline-danger'>Delete</a> </td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-dark mt-3">
            <h5 class="card-header">Insert Category and Sub-Category Relation</h5>
            <div class="card-body">
                <form method="POST" action="./partial/insert_csr.php" name="csr_insert" id="csr_insert">
                    <button type="button" class="btn text-info rounded-circle btn_refresh" onclick="ref_cat('1')">&#x21bb;</button>
                    <div class="row">
                        <div class="input-group mb-3 col">
                            <label class="input-group-text" for="item_cat">Category</label>
                            <select name="category" class="form-select category" id="item_cat1" onchange="set_reverse_cat(this)" required>

                            </select>
                        </div>
                        <div class="input-group mb-3 col">
                            <!-- <div class="input-group-text">
                                <input class="form-check-input" type="checkbox" onclick="visibilityToggle(this)" aria-label="Checkbox for following text input">
                            </div> -->
                            <select name="subcategory" class="form-select sub-category" id="item_sub_cat1" onchange="set_reverse_sub(this)" required>

                            </select>
                        </div>
                    </div>
                    <div class="form-check form-check-inline reverse d-block">
                        <input name="reverse" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="inlineCheckbox1"> <b class="b1">Category</b> can be(or is part of) <b class="b2">Sub-Category</b>.</label><br>
                        <small>(If the answer of this question is Yes then 'tick/mark' this.)</small>
                    </div>
                    <button type="submit" class="btn btn-primary mx-auto d-flex" id="Insert_rel">Insert</button>
                </form>
            </div>

        </div>

        <div class="card border-dark mt-3 table-responsive">
            <h5 class="card-header">Category - Sub-Category Relation</h5>
            <div class="card-body">
                <table class="table table-hover display" id="myTable1">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Sub-Category</th>
                            <th>Reverse</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "select c1.c_id,c1.c_name,s_id,c2.c_name as s_name,reverse from category as c1 join c_dependency as a1 on c1.c_id=a1.c_id join category as c2 on s_id=c2.c_id";
                        $result = mysqli_query($conn, $query);
                        if (is_nan(mysqli_num_rows($result)) || mysqli_num_rows($result) == 0) {
                            echo "<p class='display-4 text-primary text-center mt-5'>Not Any Category & Sub-category relation is added.</p>";
                        } else {
                            while ($data = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $data['c_name'] . "</td>";
                                echo "<td>" . $data['s_name'] . "</td>";
                                echo "<td><div class='form-check form-switch'>";

                        ?>
                                <input type="checkbox" class="form-check-input mx-auto" id="<?php echo $data['c_id'] . "|" . $data['s_id']; ?>" onclick='activation(this,"<?php echo  $data['c_name']; ?>","<?php echo  $data['s_name']; ?>")' <?php
                                                                                                                                                                                                                                                if ($data['reverse'] == 1) {
                                                                                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                ?>>
                        <?php
                                echo "</td>";
                                echo "<td> <a type='button' onclick='return delete_confirm()' href='./partial/c_delete.php?cat=" . $data['c_id'] . "&sub=" . $data['s_id'] . "' class='btn btn-outline-danger'>Delete</a> </td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>


    <?php
    require('./partial/script.php');
    require('./tools/dt/script.php');
    ?>

    <script>
        function delete_confirm() {
            if (confirm("Are you sure to delete it.")) {
                return true;
            } else {
                return false;
            }
        }

        $(document).ready(function() {
            $('#myTable').DataTable({
                "dom": ' <"#length"l><"#search"f>rt<"info"i><"page"p>',
                "columns": [{
                    "width": "10%"
                }, {
                    "width": "80%"
                }, {
                    "width": "10%"
                }],
                "stateSave": true
            });
        });
        /* Initialise the table1 with the required column ordering data types */

        $.fn.dataTable.ext.order['dom-checkbox'] = function(settings, col) {
            return this.api().column(col, {
                order: 'index'
            }).nodes().map(function(td, i) {
                return $('input', td).prop('checked') ? '1' : '0';
            });
        }

        $(document).ready(function() {
            $('#myTable1').DataTable({
                "columns": [
                    null,
                    null,
                    {
                        "orderDataType": "dom-checkbox"
                    },
                    null
                ],
                "dom": ' <"#length"l><"#search"f>rt<"info"i><"page"p>',
                "stateSave": true
            });
        });
        // active link highlight 
        let link = document.getElementById('category');
        link.classList.add('active');


        // i_c_name validation ***** starts *****
        let i_c_name = document.getElementById('i_c_name');

        const i_c_nameRegEx = /^[a-zA-Z- ]{3,50}$/; //can not use special characters

        function i_c_nameValidation() {
            i_c_nameVal = i_c_name.value;
            if (!i_c_nameRegEx.test(i_c_nameVal)) {
                i_c_name.classList.add('is-invalid');
            } else {
                i_c_name.classList.remove('is-invalid');
            }
        }
        // i_c_name validation ***** ends *****
        // u_c_name validation ***** starts *****
        let u_c_name = document.getElementById('uc_name');

        function u_c_nameValidation() {
            u_c_nameVal = u_c_name.value;
            if (!i_c_nameRegEx.test(u_c_nameVal)) {
                u_c_name.classList.add('is-invalid');
            } else {
                u_c_name.classList.remove('is-invalid');
            }
        }
        // u_c_name validation ***** ends *****

        //  name validation ***** Starts *****
        async function phpi_c_name() {
            i_c_nameVal = i_c_name.value;
            let postData = {
                method: 'post',
                headers: {
                    accept: 'application/text',
                    'content-type': 'application/json'
                },
                body: JSON.stringify({
                    "name": i_c_nameVal
                })
            }

            let response = await fetch('./partial/i_c.php', postData);

            let fdata = await response.text();

            if (response.ok) {
                return fdata;
            } else {
                console.log('error');
            }
        }
        //  name validation ***** ends *****
        let alertMsg = new bootstrap.Modal(document.getElementById('exampleModal'), {
            backdrop: 'static',
            keyboard: false
        });


        const i_c = document.getElementById('i_c');
        let new_cat_err = document.getElementById('new_cat_err');
        let new_cat_name = document.getElementById('new_cat_name');
        i_c.addEventListener('click', (e) => {
            e.preventDefault();
            i_c_nameValidation();
            if (i_c_name.classList.contains('is-invalid')) {
                alert("Invalid Category Name.");
            } else {
                phpi_c_name().then((data) => {
                    if (data.trim() == "true") {
                        i_c_name.value = "";
                        location.reload();
                    } else {
                        alertMsg.show();
                        new_cat_name.innerText = i_c_name.value;
                        new_cat_err.innerText = data;
                    }
                });
            }
        });

        const update_cat = document.getElementById('update_cat');
        update_cat.addEventListener('click', (e) => {
            u_c_nameValidation();
            if (u_c_name.classList.contains('is-invalid')) {
                e.preventDefault();
                alert("Invalid Category Name.");
            }
        });


        let edit = document.querySelectorAll(".edit");
        let uc_name = document.getElementById("uc_name");
        let uc_id = document.getElementById("uc_id");
        Array.from(edit).forEach((edit_bt) => {
            edit_bt.addEventListener('click', (data) => {
                let td_id = data.target.parentNode.parentNode.id;
                let td_name = data.target.parentNode.parentNode.children[1].innerText;
                uc_name.value = td_name;
                uc_id.value = td_id;
            })
        });


        //10 12 2020
        async function sendActivation(cs_id, reverse) {
            let postData = {
                method: 'post',
                headers: {
                    accept: 'application/text',
                    'content-type': 'application/json'
                },
                body: JSON.stringify({
                    "cs_id": cs_id,
                    "reverse": reverse
                })
            }

            let response = await fetch('./partial/cat_sub_reverse.php', postData);

            let fdata = await response.text();

            if (response.ok) {
                return fdata;
            } else {
                console.log('error');
            }
        }
        let alertMsg1 = new bootstrap.Modal(document.getElementById('exampleModal1'), {
            backdrop: 'static',
            keyboard: false
        });

        let err_c_name = document.getElementById('err_c_name');
        let err_s_name = document.getElementById('err_s_name');
        let reverseActiveStatus = document.getElementById('reverseActiveStatus');

        function activation(x, cname, sname) {
            err_c_name.innerText = cname;
            err_s_name.innerText = sname;

            if (x.checked) {
                sendActivation(x.id, 1).then((data) => {
                    //console.log(data);
                    reverseActiveStatus.innerText = "Activated";
                    if (data == "false") {
                        alertMsg1.show();
                        x.checked = false;
                    }

                });
            } else {
                sendActivation(x.id, 0).then((data) => {
                    //console.log(data);
                    reverseActiveStatus.innerText = "Deactivated";
                    if (data == "false") {
                        alertMsg1.show();
                        x.checked = true;
                    }
                });
            }
        }

        //insert new category sub-category relation starts
        function ref_cat(Num) {
            popCategories(Num, "cat");
            popCategories(Num, "sub");
        }

        function set_reverse_cat(cat_select) {
            cat_select.parentNode.parentNode.parentNode.querySelectorAll(".b1")[0].innerText = cat_select.options[cat_select.selectedIndex].text;

            if(cat_select.value == "Choose...")
            {
                cat_select.classList.add("is-invalid");
                cat_select.classList.remove("is-valid");
            }else{
                cat_select.classList.add("is-valid");
                cat_select.classList.remove("is-invalid");
            }
        }

        function set_reverse_sub(cat_select) {
            cat_select.parentNode.parentNode.parentNode.querySelectorAll(".b2")[0].innerText = cat_select.options[cat_select.selectedIndex].text;

            if(cat_select.value == "Choose...")
            {
                cat_select.classList.add("is-invalid");
                cat_select.classList.remove("is-valid");
            }else{
                cat_select.classList.add("is-valid");
                cat_select.classList.remove("is-invalid");
            }
        }

        function visibilityToggle(chkbox) {
            let select_sub_cat = chkbox.parentNode.nextElementSibling;
            let r_div = chkbox.parentNode.parentNode.parentNode.nextElementSibling;
            if (chkbox.checked) {
                select_sub_cat.disabled = false;
                r_div.style.display = "block";
            } else {
                select_sub_cat.disabled = true;
                r_div.style.display = "none";
            }
        }

        // getting categories starts
        async function get_categories() {
            let postData = {
                method: 'post',
                headers: {
                    accept: 'application/text',
                    'content-type': 'application/json'
                },
                body: JSON.stringify({
                    "name": "send"
                })
            }

            let response = await fetch('./partial/get_categories.php', postData);

            let fdata = await response.text();

            if (response.ok) {
                return fdata;
            } else {
                console.log('error');
            }
        }


        function popCategories(selectNum, cs) {
            let generateId;
            if (cs == "cat") {
                generateId = 'item_cat' + selectNum;
            } else {
                generateId = 'item_sub_cat' + selectNum;
            }
            let item_cat = document.getElementById(generateId);
            item_cat.innerHTML = "<option selected disabled>Choose...</option>";

            get_categories().then((data) => {
                let jdata = JSON.parse(data);
                let foo;
                for (foo in jdata) {
                    if (cs == "cat") {
                        item_cat.innerHTML += `<option value=c${jdata[foo]['c_id']} > ${jdata[foo]['c_name']} </option>`;
                    } else {
                        item_cat.innerHTML += `<option value=s${jdata[foo]['c_id']} > ${jdata[foo]['c_name']} </option>`;
                    };
                }
            })
        }

        popCategories('1', "cat");
        popCategories('1', "sub");
        //insert new category sub-category relation ends

        let Insert_rel = document.getElementById('Insert_rel');
        Insert_rel.addEventListener('click', (e) => {

            let item_sub_cat1 = document.getElementById('item_sub_cat1');
            let item_cat1 = document.getElementById('item_cat1');

            if (item_cat1.value == "Choose...") {
                e.preventDefault();
                item_cat1.classList.add("is-invalid");
                item_cat1.classList.remove("is-valid");
            }else{
                item_cat1.classList.add("is-valid");
                item_cat1.classList.remove("is-invalid");
            }

            if(item_sub_cat1.value == "Choose..."){
                e.preventDefault();
                item_sub_cat1.classList.add("is-invalid");
                item_sub_cat1.classList.remove("is-valid");
            }else{
                item_sub_cat1.classList.add("is-valid");
                item_sub_cat1.classList.remove("is-invalid");
            }

        });
    </script>

</body>

</html>