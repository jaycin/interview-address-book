var ViewModel = function () {
    var self = this;
    self.contactId = ko.observable();
    self.firstName = ko.observable();
    self.lastName = ko.observable();
    self.emails = ko.observable();
    self.contactNumber = ko.observable();
    self.errorMessage = ko.observable();
    self.contactId = ko.observable();
    self.contacts = ko.observableArray();
    self.search = ko.observable("");

    self.lastPage = ko.observable();
    self.firstPage = ko.observable();
    self.pageText = ko.observable();
    self.page = ko.observable(0);
    self.limit = ko.observable(20);
    self.editing = ko.observable(false);



    self.searchContacts = function () {
        var data =
        {
            page: self.page(),
            limit: self.limit(),
            search: self.search()
        }

        $.ajax({
            type: "GET",
            dataType: "json",
            url: 'api/Contact/SearchContacts',
            data: data,
            success: function (dataBack) {
                self.handleContactData(dataBack);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
            }
        });
    }
    self.handleContactData = function (dataBack) {
        if (typeof dataBack != "undefined") {
            self.contacts(dataBack.Contacts);
            var page = 1;
            if (self.page() != 0) {
                page = self.page() + 1;
                self.firstPage(true);
            } else {
                self.firstPage(false);
            }
            if (dataBack.total > page * self.limit()) {

                self.lastPage(true);
            } else {
                self.lastPage(false);
            }
            self.pageText("Showing " + dataBack.length + " of " + dataBack.total + " page:" + page);
        }
    }
    self.cancelForm = function () {
        self.firstName(null);
        self.lastName(null);
        self.contactNumber(null);
        self.errorMessage(null);
        self.contactId(null);
        self.emails(null);
        self.editing(false);
    }
    self.edit = function (value) {
        if (typeof value != "undefined") {
            self.editing(true);
            $("#edit").tab('show');
            self.contactId(value.ContactId)
            self.firstName(value.FirstName);
            self.lastName(value.LastName);
            self.contactNumber(value.ContactNumbers);
            self.emails(value.Emails);
        }
    }
    self.delete = function (value) {
        if (typeof value != "undefined") {
            var data =
            {
                id: value.ContactId
            }

            $.ajax({
                type: "DELETE",
                dataType: "json",
                url: 'api/Contact',
                data: data,
                success: function (dataBack) {
                    self.getContacts();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                }
            });
        }
    }
    self.pageForward = function () {

        self.page(self.page() + 1);
        self.getContacts();

    }
    self.pageBack = function () {
        if (self.page() != 0) {
            self.page(self.page() - 1);
            self.getContacts();
        }
    }
    self.getContacts = function () {
        if (self.search() != "") {
            self.searchContacts();
            return;
        }
        var data =
        {
            page: self.page(),
            limit: self.limit(),
        }

        $.ajax({
            type: "GET",
            dataType: "json",
            url: 'api/Contact/GetContacts',
            data: data,
            success: function (dataBack) {
                self.handleContactData(dataBack);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
            }
        });
    }
    self.updateContact = function () {
        var data =
        {
            FirstName: self.firstName(),
            LastName: self.lastName(),
            ContactNumbers: self.contactNumber(),
            Emails: self.emails(),
            ContactId: self.contactId(),
        }
        if (data.firstname != "") {
            self.submitForm(data);
        }
    }
    self.submitForm = function (editData) {
        if (self.firstName() == null) {
            self.errorMessage("First name is required");
            return null;
        }
        if (self.lastName() == null) {
            self.errorMessage("Last name is required");
            return null;
        }
        if (self.contactNumber() == null) {
            self.errorMessage("Contact number is required");
            return null;
        }
        if (self.emails() == null) {
            self.errorMessage("Emails are required");
            return null;
        }
        if (!/^[0-9,.]*$/.test(self.contactNumber())) {
            self.errorMessage("Contact numbers must be numeric");
            return null;
        }
        if (!self.emails().includes("@")) {
            self.errorMessage("Emails must contain @ symbol");
            return null;
        }
        var data =
        {
            FirstName: self.firstName(),
            LastName: self.lastName(),
            ContactNumbers: self.contactNumber(),
            Emails: self.emails(),
            ContactId: 0
        }
        if (self.editing()) {
            data = editData;
        }
        $.ajax({
            type: "POST",
            dataType: "json",
            url: 'api/Contact',
            data: data,
            success: function (data) {
                self.cancelForm();
                self.getContacts();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.responseText == "") {
                    self.cancelForm();
                    self.getContacts();
                }
                self.errorMessage(jqXHR.responseText);

            }
        });
    }
    self.getContacts();
}

ko.applyBindings(new ViewModel());