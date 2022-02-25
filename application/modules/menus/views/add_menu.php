<div class="box">
    <div class="box-header"><h2>Create</h2></div>
    <div class="box-body">
        <form method="post" action="<?php echo site_url('menus/add_menu'); ?>">
            <div class="row">
                <div class="col-md-5">
                <label class="label-control" for="menu-group-title">Menu Name</label>
                <input class="form-control" type="text" name="title" id="menu-group-title">
                </div>
            </div>
            <br>
            <button type="submit" class="btn btn-sm btn-success">Submit</button>
        </form>
    </div>
</div>