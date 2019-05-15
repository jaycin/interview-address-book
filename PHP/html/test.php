<?php
include "../inc/serverLogicTest.php";
$serverLogic = new ServerLogicTest();
?>
<head>
    <script src="/Php/inc/jquery.js"></script>
    <script src="/Php/inc/popper.js"></script>
    <script src="/Php/inc/bootstrap.js"></script>
    <script src="/Php/inc/knockout.js"></script>
    <link rel="stylesheet" type="text/css" href="/Php/inc/bootstrap.css">
    <div class="container">
        <div class="row">
            <div class="col-4">&nbsp;</div>
            <div class="col-3"><h1 class="modal-title">Test1</h1></div>
        </div>
        <div class="row">
            <div class="col-4">&nbsp;</div>
            <div class="col-3"><h5 class="modal-title">Candidate: Jaycin van Vuuren</h5></div>
        </div>
    </div>
</head>
<body>
<div class="container">
    <nav class="nav">
            <a class="btn-success nav-link active" data-bind="click:cancelForm" href="#addContact" data-toggle="tab">Add Contact</a>
            <a class="btn-success nav-link" href="#viewContacts" data-toggle="tab">View Contacts</a>
            <a class="btn-success nav-link" id="edit" data-bind="visible:editing" href="#addContact" data-toggle="tab">Edit Contact</a>
    </nav>

    <div class="tab-content ">
        <div class="tab-pane active" id="addContact">
            <form>
                <div class="row"><div class="col-4">&nbsp;</div>
                    <div class="col-2 form-label-group">
                        <input class="form-control" required="required" placeholder="First Name" type="text" data-bind="value: firstName"/>
                    </div>
                </div>
                <div class="row"><div class="col-4">&nbsp;</div>
                    <div class="col-2 form-label-group">
                        <input class="form-control" required="required" placeholder="Last Name" type="text" data-bind="value: lastName"/>
                    </div>
                </div>
                <div class="row"><div class="col-4">&nbsp;</div>
                    <div class="col-2 form-label-group">
                <textarea rows="5" cols="10" class="form-control" required="required" placeholder="Contact Numbers (comma separated)" data-bind="value: contactNumber">
                </textarea>
                    </div>
                </div>
                <div class="row"><div class="col-4">&nbsp;</div>
                    <div class="col-2 form-label-group">
                <textarea rows="5" cols="10" class="form-control" required="required" placeholder="Emails (comma separated)" data-bind="value: emails">
                </textarea>
                    </div>
                </div>

                <div class="row"><div class="col-4">&nbsp;</div><div class="col-2"><label class="text-danger" data-bind="text: errorMessage"></label></div></div>
                <div class="row"><div class="col-4">&nbsp;</div>
                    <div class="col-1 form-label-group" data-bind="visible:!editing()">
                        <button class="btn btn-primary" data-bind="click: submitForm">Submit</button>
                    </div>
                    <div class="col-1 form-label-group" data-bind="visible:editing()">
                        <button class="btn btn-primary" data-bind="click: updateContact">Edit</button>
                    </div>
                    <div class="col-1 form-label-group">
                        <button class="btn btn-danger" data-bind="click: cancelForm">Cancel</button>
                    </div>
                    <div class="col-1 form-label-group">
                        <a class="btn btn-success" href="/Php/index.php">Home</a>
                    </div>
                </div>

            </form>
        </div>
        <div class="tab-pane" id="viewContacts">

            <ul class="pagination justify-content-center" >
                <li class="page-item">
                    <a class="page-link" href="#" data-bind="click:pageBack, visible:firstPage" tabindex="1">Previous</a>
                </li>
                <li class="page-item">
                    <a class="page-link" data-bind="click:pageForward, visible:lastPage" href="#">Next</a>
                </li>
            </ul>
            <p class="page-item" data-bind="text:pageText"></p>
            <div class="row">
                <div class="col-6">&nbsp;</div>
                <div class="col-6">
                <input type="text" class="form-control" placeholder="Contact name number or email" data-bind="value:search"/>
                <input type="button" class="btn btn-primary" data-bind="click:searchContacts" value="Search"/></div>
            </div>
            <br>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">First</th>
                    <th scope="col">Last</th>
                    <th scope="col">Contact Numbers</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody data-bind="foreach: contacts">
                <tr>
                    <td data-bind="text: ContactId"></td>
                    <td data-bind="text: Name"></td>
                    <td data-bind="text: Surname"></td>
                    <td data-bind="text: contacts"></td>
                    <td data-bind="text: emails"></td>
                    <td>
                        <input type="button" data-bind="click:$parent.edit" class="btn btn-success" value="Edit"/>
                    </td>
                    <td>
                        <input type="button" data-bind="click:$parent.delete" class="btn btn-danger" value="Delete"/>
                    </td>
                </tr>
                </tbody>

            </table>
        </div>

</div>
</div>


<script src="/Php/inc/test.js"></script>
</body>