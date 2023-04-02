'use strict'
var EventItemModel = function (event) {
    this.id = event.id;
    this.start_date = event.start_date;
    this.end_date = event.end_date;
    this.category_id = event.category_id;
    this.name = event.name;
    this.title = event.title;
    this.description = event.description;
    this.image = event.base64String

    this.formattedEventPeriod = function () {
        if (this.end_date == null) {
            return moment.utc(event.start_date).format(appModel.dateFormat);
        } else if (this.start_date != null) {
            return `${moment.utc(this.start_date).format(appModel.dateFormat)} - ${moment.utc(this.end_date).format(appModel.dateFormat)}`;
        } else {
            return '';
        }
    }

    this.categoryColor = function () {
        var currentCategory = categoryModel.categories().find(c => c.id == this.category_id)
        if (currentCategory != null) {
            return currentCategory.color_hex
        } else {
            return '#FFFFFF';
        }
    }

    this.categoryName = function () {
        var currentCategory = categoryModel.categories().find(c => c.id == this.category_id)
        if (currentCategory != null) {
            return currentCategory.name
        } else {
            return '';
        }
    }

    this.showEventModal = function () {
        eventModel.eventModalMode('Edit');
        eventModel.editedEventId(this.id);
        eventModel.eventName(this.name)
        eventModel.eventTitle(this.title)
        eventModel.eventDescription(this.description)
        eventModel.eventStartDate(this.start_date)
        eventModel.eventEndDate(this.end_date)
        eventModel.eventImageFile(this.image)
        eventModel.imagePreviewFile(this.image)
        eventModel.eventPeriod(this.formattedEventPeriod())
        eventModel.eventCategoryColor(this.categoryColor())
        eventModel.eventCategoryName(this.categoryName())
        setLighterCategoryColor(40)
        eventModel.eventCategory(selectedCategory)

        var selectedCategory = categoryModel.categories().find(c => c.id == this.category_id)
        eventModel.eventCategory(selectedCategory)
        $('#show-event-modal').modal('show');
    }

    function setLighterCategoryColor(percent) {

        var color = eventModel.eventCategoryColor != null ? eventModel.eventCategoryColor() : "#FFFFFF";
        var R = parseInt(color.substring(1, 3), 16);
        var G = parseInt(color.substring(3, 5), 16);
        var B = parseInt(color.substring(5, 7), 16);

        R = parseInt(R * (100 + percent) / 100);
        G = parseInt(G * (100 + percent) / 100);
        B = parseInt(B * (100 + percent) / 100);

        R = (R < 255) ? R : 255;
        G = (G < 255) ? G : 255;
        B = (B < 255) ? B : 255;

        var RR = ((R.toString(16).length == 1) ? "0" + R.toString(16) : R.toString(16));
        var GG = ((G.toString(16).length == 1) ? "0" + G.toString(16) : G.toString(16));
        var BB = ((B.toString(16).length == 1) ? "0" + B.toString(16) : B.toString(16));

        var lighterColor =  "#" + RR + GG + BB;
       
        eventModel.categoryLighterColor(lighterColor)
    }
}

var eventModel = new function () {
    var self = this;
    this.eventModalMode = ko.observable(null)
    this.eventName = ko.observable(null)
    this.eventTitle = ko.observable(null)
    this.eventDescription = ko.observable(null)
    this.eventStartDate = ko.observable(null)
    this.eventEndDate = ko.observable(null)
    this.eventCategory = ko.observable(null)
    this.eventImageFile = ko.observable(null)
    this.imagePreviewFile = ko.observable(null)
    this.eventPeriod = ko.observable(null)
    this.categoryColor = ko.observable(null)
    this.eventCategoryName = ko.observable(null)
    this.eventCategoryColor = ko.observable(null)
    this.categoryLighterColor = ko.observable(null)
    this.editedEventId = ko.observable(null)
    this.events = ko.observableArray()

    self.resetEventFields = function () {
        self.eventName(null)
        self.eventTitle(null)
        self.eventDescription(null)
        self.eventStartDate(null)
        self.eventEndDate(null)
        self.eventCategory(null)
        self.eventImageFile(null)
    }

    self.refreshEvents = function () {
        appModel.makeAjaxCall({}, '../src/php/controllers/event/EventGetController.php',
            function (data) {
                if (data != null) {
                    self.events.removeAll();
                    var events = data

                    var eventArray = [];

                    for (var i = 0; i < events.length; i++) {
                        eventArray[i] = new EventItemModel(events[i])
                    }

                    self.events(eventArray);
                } else {
                    // shouldn't really happen, but just in case
                    alert('Get events failed');
                }
            })
    }

    self.showAddEventModal = function () {
        self.resetEventFields();
        self.eventModalMode('Add');
        $('#event-modal').modal('show');
    }

    self.showDeleteEventModal = function () {
        $('#delete-event-modal').modal('show');
    }

    self.showEditEventModal = function () {
        $('#event-modal').modal('show');
    }

    self.updateEventImageFile = function (value) {
        const file = event.target.files[0];
        const reader = new FileReader();
        reader.onloadend = () => {
            const base64String = reader.result
                .replace('data:', '')
                .replace(/^.+,/, '');

            self.eventImageFile(reader.result);
        };
        reader.readAsDataURL(file);
    }

    self.submitEvent = function () {
        if (self.eventModalMode() == 'Add') {
            addEvent()
        } else if (self.eventModalMode() == 'Edit') {
            editEvent()
        }
    }

    function addEvent() {
        var requestArguments = {
            Name: self.eventName,
            Title: self.eventTitle,
            Description: self.eventDescription,
            StartDate: self.eventStartDate,
            EndDate: self.eventEndDate,
            CategoryId: self.eventCategory().id,
            ImageFile: self.eventImageFile
        }
        appModel.makeAjaxCall(requestArguments,
            '../src/php/controllers/event/EventAddController.php',
            function (data) {
                $('#event-modal').modal('hide');
                self.refreshEvents()

            })
    }

    function editEvent() {
        var requestArguments = {
            Id: self.editedEventId,
            Name: self.eventName,
            Title: self.eventTitle,
            Description: self.eventDescription,
            StartDate: self.eventStartDate,
            EndDate: self.eventEndDate,
            CategoryId: self.eventCategory().id,
            ImageFile: self.eventImageFile
        }
        appModel.makeAjaxCall(requestArguments,
            '../src/php/controllers/event/EventUpdateController.php',
            function (data) {
                $('#show-event-modal').modal('hide');
                $('#event-modal').modal('hide');
                self.refreshEvents()

            })
    }

    self.clearImageFile = function () {
        eventModel.eventImageFile(null)
        document.getElementById("image-file-input").value = "";
    }

    self.deleteEvent = function () {
        var requestArguments = {
            Id: self.editedEventId
        }
        appModel.makeAjaxCall(requestArguments,
            '../src/php/controllers/event/EventDeleteController.php',
            function (data) {
                $('#show-event-modal').modal('hide');
                $('#delete-event-modal').modal('hide');
                self.refreshEvents()

            })
    }
}