<div class="modal fade" id="updateContactModal" tabindex="-1" role="dialog" aria-labelledby="updateContactModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="updateContactForm" novalidate>

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Edit Contact</h4>
        </div>

        <div class="modal-body">
            <div class="row">

              <input type="hidden" name="id" value="">

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
              <div class="col-sm-8">


                <h3>Custom Fields:</h3>

                <div class="custom-fields-container">
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