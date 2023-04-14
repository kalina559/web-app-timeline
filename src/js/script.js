'use strict'

window.addEventListener('error', function (event) {

});

$(document).ready(function () {
    ko.applyBindings(appModel, $('html')[0])
    eventModel.refreshEvents()
    appModel.checkIfUserLoggedIn()
    categoryModel.refreshCategories()
})

var appModel = new function () {
    var self = this;
    this.currentPage = ko.observable('timeline')
    this.userLoggedIn = ko.observable(false)
    this.showLogin = ko.observable(false)
    this.loginButtonText = ko.observable('Login')
    this.login = ko.observable(null)
    this.password = ko.observable(null)
    this.currentUserName = ko.observable(null)
    this.currentPassword = ko.observable(null)
    this.newPassword = ko.observable(null)
    this.newPasswordRepeat = ko.observable(null)
    this.apiCallsInProcess = ko.observable(0)
    this.serverErrorMessage = ko.observable(null)
    this.serverErrorStackTrace = ko.observable(null)
    this.dateFormat = 'DD/MM/YYYY'

    self.changePage = function(pageId) {
        this.currentPage(pageId)
    }

    self.checkIfUserLoggedIn = function () {
        this.makeAjaxCall({}, '../src/php/controllers/account/LoginStateController.php',
            function (data) {
                if (data != null) {
                    self.userLoggedIn(true)
                    self.currentUserName(data)
                }
            })
    }

    self.toggleLogin = function () {
        self.showLogin(!self.showLogin())

        if (self.showLogin()) {
            self.loginButtonText('Close')
        } else {
            self.loginButtonText('Login')
        }
    }

    self.printTimeline = function () {
        window.print();
    }

    self.tryLogin = function () {

        var requestArguments = {
            Login: self.login,
            Password: self.password
        }

        this.makeAjaxCall(requestArguments,
            '../src/php/controllers/account/LoginController.php',
            function (data) {
                self.userLoggedIn(true)
                self.currentUserName(self.login())
                self.login(null)
                self.password(null)

            })
    }

    self.tryLogout = function () {
        this.makeAjaxCall({}, '../src/php/controllers/account/LogoutController.php',
            function (data) {
                self.userLoggedIn(false)
                self.currentUserName(null)

            })
    }

    self.showChangePasswordModal = function () {
        self.currentPassword(null);
        self.newPassword(null);
        self.newPasswordRepeat(null);
        $('#update-password-modal').modal('show');
    }

    self.validatePasswordRepeat = function () {
        var newPassword = document.getElementById("new-password");
        var newPasswordRepeat = document.getElementById("new-password-repeat");
        if (newPassword.value != newPasswordRepeat.value) {
            newPasswordRepeat.setCustomValidity("Passwords don't match");
        } else {
            newPasswordRepeat.setCustomValidity('');
        }
    }

    self.updatePassword = function () {
        var requestArguments = {
            OldPassword: self.currentPassword,
            NewPassword: self.newPassword
        }

        this.makeAjaxCall(requestArguments,
            '../src/php/controllers/account/UpdatePasswordController.php',
            function () {
                $('#update-password-modal').modal('hide');
            })
    }

    self.updatePassword = function () {
        var requestArguments = {
            OldPassword: self.currentPassword,
            NewPassword: self.newPassword
        }

        this.makeAjaxCall(requestArguments,
            '../src/php/controllers/account/UpdatePasswordController.php',
            function () {

                $('#update-password-modal').modal('hide');
            })
    }

    self.isBusy = ko.computed(function () {
        return self.apiCallsInProcess() != 0;
    });

    self.showServerErrorModal = function () {
        $('#server-error-modal').modal('show');
    }

    $(document).on('show.bs.modal', '.modal', function () {
        const zIndex = 1040 + 10 * $('.modal:visible').length;
        $(this).css('z-index', zIndex);
        setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack'));
    });

    self.makeAjaxCall = function (args, url, success) {
        // we're locking the UI everytime an AJAX call is made
        var concatCallback = function (data) {
            success(data)
            self.apiCallsInProcess(self.apiCallsInProcess() - 1)
        };

        self.apiCallsInProcess(self.apiCallsInProcess() + 1)

        var params = args != null ? { arguments: args } : {}

        jQuery.ajax({
            type: 'POST',
            data: params,
            url: url,
            success: concatCallback,
            error: function (data) {

                self.apiCallsInProcess(self.apiCallsInProcess() - 1)
                if (data.responseJSON != null) {
                    self.serverErrorMessage(data.responseJSON.errorMessage)
                    self.serverErrorStackTrace(data.responseJSON.stackTrace)
                } else {
                    self.serverErrorMessage('Unexpected error')
                    self.serverErrorStackTrace(data.responseText)
                }

                self.showServerErrorModal();

                if (data.status == 403) {
                    // user authorization failed
                    self.userLoggedIn(false)
                    self.currentUserName(null)

                    // close all modals
                    $('.modal').modal('hide');
                }
            }
        });
    }
}()