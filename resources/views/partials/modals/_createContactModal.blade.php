<div class="modal fade" id="createContactModal" tabindex="-1" role="dialog" aria-labelledby="createContactModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="createContactForm" novalidate>

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Create New Contact</h4>
        </div>

        <div class="modal-body">

            <div class="row">

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label" for="name" class="">Name*</label>
                  <input type="text" name="name" class="form-control">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label" for="surname">Surname*</label>
                  <input type="text" name="surname" class="form-control">
                </div>
              </div>

            </div>
            <div class="row">

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label" for="email">Email*</label>
                  <input type="email" name="email" class="form-control">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label" for="phone">Phone*</label>
                  <input type="text" name="phone" class="form-control">
                </div>
              </div>

            </div><!-- /.row -->
            <div class="row">
              <div class="col-sm-10">


                <h3>Custom Fields:</h3>

                <div class="custom-fields-container">
                  <div class="form-group">
                    <div class="input-group">
                      <input type="text" name="customFields[custom_1]" class="form-control">
                      <span class="input-group-btn">
                        <button
                          type="button"
                          add-custom-field
                          class="btn btn-success">
                          <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        <button 
                          type="button"
                          remove-custom-field
                          class="btn btn-danger">
                          <span class="glyphicon glyphicon-minus"></span>
                        </button>
                      </span>
                    </div>
                  </div>
                </div>

                <span id="helpBlock" class="help-block">* Indicates required fields</span>
              </div>
            </div>
          
        </div><!-- /.modal-body -->

        <div class="modal-footer">
          <button type="button" cancel-contact class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" save-contact class="btn btn-primary">Save</button>
        </div>

      </form>
    </div><!-- /.modal-content -->
  </div>
</div>