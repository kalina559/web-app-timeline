<!DOCTYPE HTML>
<!-- (A) CSS & JS -->
<link rel="stylesheet" href="../src/libs/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="../src/libs/bootstrap/css/bootstrap.min.css" media="print">
<link  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" 
  rel="stylesheet"  type='text/css'><link href="../src/css/styles.css" rel="stylesheet" media="all">
<script type="text/javascript" src="../src/libs/jquery.min.js"></script>
<script type="text/javascript" src="../src/libs/moment/moment.min.js"></script>
<script type="text/javascript" src="../src/libs/knockout-3.5.1.min.js"></script>
<script src="../src/js/script.js"></script>
<script src="../src/js/components/category.js"></script>
<script src="../src/js/components/events.js"></script>

<html data-bind="with: appModel, visible: true">

<body>
  <div class="overlay" data-bind="css: {'overlay-visible': isBusy}">
    <span class="fa fa-spin fa-4x fa-cog"></span>
  </div>

  <div class="body-background">
    <div class="col-sm-6 ui-element" id="print-button-layout">
      <div>
        <button class="btn btn-primary ui-element" type="button" data-bind="click: appModel.printTimeline, disable: appModel.isBusy">Print the timeline</button>
      </div>
    </div>

    <!-- login window -->
    <div class="col-sm-12 ui-element">
      <div class="col-sm-6" style="text-align: center; width: 50%; margin: 0 auto" data-bind="visible: !appModel.userLoggedIn()">
        <form class="login-form col-sm-12" data-bind="visible: appModel.showLogin(), submit: appModel.tryLogin">
          <div class="col-sm-12">
            <label class="col-sm-4" for="login-field">Login:</label>
            <input id="login-field" class="input-field col-sm-6" required type="text" data-bind="value: appModel.login" placeholder="Type your login" />
          </div>
          <div class="col-sm-12">
            <label class="col-sm-4" for="password-field">Password:</label>
            <input id="password-field" class="input-field col-sm-6" type="password" required type="text" data-bind="value: appModel.password" placeholder="Type your password" />
          </div>
          <div class="col-sm-12">
            <button class="btn btn-primary" id="submit-button" type="submit" data-bind="disable: appModel.isBusy">Submit</button>
          </div>
        </form>

        <!-- login/logout button -->
        <button class="btn btn-primary" type="button" data-bind="click: appModel.toggleLogin, text: appModel.loginButtonText, disable: appModel.isBusy">Login</button>
      </div>

      <!-- logged in user actions -->
      <div class="col-sm-6 ui-element" id="login-logout-layout" data-bind="visible: appModel.userLoggedIn()">
        <div>
          <div>
            <h5 data-bind="text: 'Logged in as: ' + currentUserName()"></h5>
          </div>
          <div>
            <button class="btn btn-danger" type="button" data-bind="click: appModel.tryLogout, disable: appModel.isBusy">Logout</button>
            <button class="btn btn-info" type="button" data-bind="click: appModel.showChangePasswordModal, disable: appModel.isBusy">Change password</button>
          </div>
        </div>
        <div>
          <button class="btn btn-primary" id="add-new-event-button" type="button" data-bind="click: eventModel.showAddEventModal, visible: appModel.userLoggedIn(), disable: appModel.isBusy">Add a new event</button>
        </div>
      </div>

    </div>

    <!-- list of all categories -->
    <aside class="category-legend col-sm-3" data-bind="with: categoryModel">
      <h1 class="mb-4">Categories:</h1>
      <div data-bind="foreach: categoryModel.categories">
        <div class="row">
          <div class="category-color-box" data-bind="click: showEditCategoryModal, style:{ 'background-color': color_hex, cursor: appModel.userLoggedIn() ? 'pointer' : 'auto'}"></div>
          <p class="category-name" data-bind="text: name"></p>
          <p class="delete-button" data-bind="click: showDeleteCategoryModal, visible: appModel.userLoggedIn()">x</p>
        </div>
      </div>
      <button class="btn btn-primary ui-element" data-bind="visible: appModel.userLoggedIn(), click: showAddCategoryModal, disable: appModel.isBusy">Add a new category</button>
    </aside>

    <!-- timeline with all events -->
    <div class="timeline" data-bind="foreach: eventModel.events">
      <div class="container" data-bind="click: showEventModal">
        <div class="content" data-bind="style:{ 'background-color': categoryModel.categories().length > 0 ? categoryColor() : '#FFFFFF' }">
          <h2 class="centered-div" data-bind="text: name"></h2>
          <div class="image-container">
            <img class="event-image" data-bind="attr:{src: image}, visible: image != null" />
          </div>
          <h4 class="centered-div" data-bind="text: formattedEventPeriod()"></h4>
        </div>
      </div>
    </div>

    <!-- Show event modal -->
    <form class="form-horizontal preview-modal" data-bind="with: eventModel">
      <div class="modal fade" id="show-event-modal" data-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content" data-bind="style:{ 'background-color': categoryModel.categories().length > 0 ? eventModel.eventCategoryColor : '#FFFFFF' }">
            <div class="modal-header">
              <h4 class="modal-name" data-bind="text: eventName"></h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form class="form-horizontal">
              <div class="modal-body" data-bind="style:{ 'background-color': eventModel.categoryLighterColor }">
                <div class="form-group modal-field centered-div">
                  <div class="image-container">
                    <img class="event-image" data-bind="attr:{src: imagePreviewFile}, visible: imagePreviewFile != null" />
                  </div>
                </div>
                <div class="form-group modal-field centered-div">
                  <p data-bind="text: eventPeriod"></p>
                </div>
                <div class="form-group modal-field centered-div">
                  <h3 data-bind="text: eventTitle"></h3>
                </div>
                <div class="form-group modal-field centered-div">
                  <textarea rows="10" class="wide-text-area" data-bind="text: eventDescription, style:{ 'background-color': eventModel.categoryLighterColor }"></textarea>
                </div>
                <div class="form-group modal-field centered-div">
                  <p data-bind="text: eventCategoryName"></p>
                </div>
              </div>
              <div class="modal-footer">
                <button class="btn btn-primary ui-element" data-bind="visible: appModel.userLoggedIn(), click: showEditEventModal, disable: appModel.isBusy">Edit</button>
                <button class="btn btn-danger ui-element" data-bind="visible: appModel.userLoggedIn(), click: showDeleteEventModal, disable: appModel.isBusy">Delete</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" data-bind="disable: appModel.isBusy"><span class="fa fa-times"></span> Close</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </form>

    <!-- Event input modal -->
    <form class="form-horizontal add-update-modal" data-bind="with: eventModel, submit: eventModel.submitEvent">
      <div class="modal fade" id="event-modal" data-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" data-bind="text: eventModalMode() + ' event'"></h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form class="form-horizontal">
              <div class="modal-body">
              <div class="form-group modal-field">
                  <label for="title" class="col-sm-offset-1 col-sm-4 control-label">Name:</label>
                  <div class="col-sm-8">
                    <textarea required id="name" data-bind="value: eventName" class="form-control"></textarea>
                  </div>
                </div>
                <div class="form-group modal-field">
                  <label for="title" class="col-sm-offset-1 col-sm-4 control-label">Title:</label>
                  <div class="col-sm-8">
                    <textarea required id="title" data-bind="value: eventTitle" class="form-control"></textarea>
                  </div>
                </div>
                <div class="form-group modal-field">
                  <label for="description" class="col-sm-offset-1 col-sm-4 control-label">Description:</label>
                  <div class="col-sm-8">
                    <textarea required rows="4" id="description" data-bind="value: eventDescription" class="form-control"></textarea>
                  </div>
                </div>
                <div class="form-group modal-field">
                  <label for="start-date" class="col-sm-offset-1 col-sm-4 control-label">Start date:</label>
                  <div class="col-sm-8">
                    <input required id="start-date" data-bind="value: eventStartDate" type="date" class="form-control"></input>
                  </div>
                </div>
                <div class="form-group modal-field">
                  <label for="end-date" class="col-sm-offset-1 col-sm-4 control-label">End date (optional):</label>
                  <div class="col-sm-8">
                    <input id="end-date" data-bind="value: eventEndDate" type="date" class="form-control"></input>
                  </div>
                </div>
                <div class="form-group modal-field">
                  <label for="category" class="col-sm-offset-1 col-sm-4 control-label">Category:</label>
                  <div class="col-sm-8">
                    <select required id="category" class="form-control" data-bind="options: categoryModel.categories, optionsText: 'name',
                       value: 'id',
                       optionsCaption: 'Choose...', value: eventCategory"></select>
                  </div>
                </div>
                <div class="form-group modal-field">
                  <label for="image" class="col-sm-offset-1 col-sm-4 control-label">Image:</label>
                  <div class="col-sm-8">
                    <input id="image-file-input" type="file" accept="image/*" onchange="eventModel.updateEventImageFile(event)">
                    <img class="col-sm-12" id="addEventImage" data-bind="attr:{src: eventImageFile}, visible: eventImageFile() != null" />
                    <button type="button" class="btn btn-default" data-bind="click: clearImageFile"></span>Clear image</button>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bind="disable: appModel.isBusy" data-dismiss="modal"><span class="fa fa-times"></span> Cancel</button>
                <button data-bind="disable: appModel.isBusy, text: eventModalMode" type="submit" class="btn btn-primary"><span class="fa fa-pencil"></span></button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </form>

    <!-- Delete event confirmation -->
    <form class="form-horizontal add-update-modal" data-bind="with: eventModel, submit: eventModel.deleteEvent">
      <div class="modal fade" id="delete-event-modal" data-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Delete event</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form class="form-horizontal">
              <div class="modal-body">
                <p>Are you sure you want to remove this event?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" data-bind="disable: appModel.isBusy"><span class="fa fa-times"></span> Cancel</button>
                <button data-bind="disable: appModel.isBusy" type="submit" class="btn btn-danger"><span class="fa fa-pencil"></span> Delete</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </form>
    <script type="text/javascript" src="../src/libs/bootstrap/js/bootstrap.min.js"></script>
</body>

<!-- Category input modal -->
<form class="form-horizontal add-update-modal" data-bind="with: categoryModel, submit: categoryModel.submitCategory">
  <div class="modal fade" id="category-modal" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" data-bind="text: categoryModalMode() + ' category'"></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <form class="form-horizontal">
          <div class="modal-body">
            <div class="form-group modal-field">
              <label for="category-name" class="col-sm-offset-1 col-sm-4 control-label">Name:</label>
              <div class="col-sm-8">
                <textarea required id="category-name" data-bind="value: categoryName" class="form-control"></textarea>
              </div>
            </div>
            <div class="form-group modal-field">
              <label for="category-color" class="col-sm-offset-1 col-sm-4 control-label">Color:</label>
              <div class="col-sm-8">
                <input type="color" id="category-color" data-bind="value: categoryColorHex" class="form-control">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-bind="disable: appModel.isBusy" data-dismiss="modal"><span class="fa fa-times"></span> Cancel</button>
            <button data-bind="disable: appModel.isBusy, text: categoryModalMode" type="submit" class="btn btn-primary"><span class="fa fa-pencil"></span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</form>

<!-- Delete category confirmation -->
<form class="form-horizontal add-update-modal" data-bind="with: categoryModel, submit: categoryModel.deleteCategory">
  <div class="modal fade" id="delete-category-modal" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Delete category</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <form class="form-horizontal">
          <div class="modal-body">
            <p>Are you sure you want to remove this category?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" data-bind="disable: appModel.isBusy"><span class="fa fa-times"></span> Cancel</button>
            <button data-bind="disable: appModel.isBusy" type="submit" class="btn btn-danger"><span class="fa fa-pencil"></span> Delete</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</form>

<!-- Update account password -->
<form class="form-horizontal add-update-modal" data-bind="submit: updatePassword">
  <div class="modal fade" id="update-password-modal" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Update password</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <form class="form-horizontal">
          <div class="form-group modal-field">
            <label for="current-password" class="col-sm-offset-1 col-sm-4 control-label">Current password</label>
            <div class="col-sm-8">
              <input type="password" required id="current-password" data-bind="value: currentPassword" class="form-control"></textarea>
            </div>
          </div>
          <div class="form-group modal-field">
            <label for="new-password" class="col-sm-offset-1 col-sm-4 control-label">New password:</label>
            <div class="col-sm-8">
              <input type="password" required id="new-password" data-bind="value: newPassword" class="form-control" onkeyup="appModel.validatePasswordRepeat()">
            </div>
          </div>
          <div class="form-group modal-field">
            <label for="new-password-repeat" class="col-sm-offset-1 col-sm-4 control-label">Repeat new password:</label>
            <div class="col-sm-8">
              <input type="password" required id="new-password-repeat" data-bind="value: newPasswordRepeat" class="form-control" onkeyup="appModel.validatePasswordRepeat()">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" data-bind="disable: appModel.isBusy"><span class="fa fa-times"></span> Cancel</button>
            <button data-bind="disable: appModel.isBusy" type="submit" class="btn btn-primary"><span class="fa fa-pencil"></span> Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</form>

<!-- Server error modal -->
<form class="form-horizontal error-modal">
  <div class="modal fade" id="server-error-modal" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="error-modal-title">Server error</h2>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <form class="form-horizontal">
          <div class="modal-body">
            <h3 data-bind="text: serverErrorMessage"></h3>
            <textarea class="error-message-content" rows="25" data-bind="text: serverErrorStackTrace"></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" data-bind="disable: appModel.isBusy"><span class="fa fa-times"></span> Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</form>

<script type="text/javascript" src="../src/libs/bootstrap/js/bootstrap.min.js"></script>
</div>
</body>

</html>