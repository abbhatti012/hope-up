<div class="post_col">
    <div class="post_input">
        <form action="{{ url('make-selected-featured/'.$id) }}" method="post">
            @csrf
        <label for="Country" class="slect_fm_label">Select Package
            <span>*</span>
        </label><br>
        <select class="post_slect_input form-control" name="selected_package" id="make_car" required>
            <option></option>
            <?php $count = 0; ?>@foreach($check as $sub)
            <?php $package = DB::table('packages')->where('id',$sub->package_id)->first(); ?>
                <option value="{{ $sub->id }}" >{{ $package->package }}</option>
            @endforeach
        </select><br>
        <div class="form-group">
            <input type="submit" value="Submit" class="btn btn-primary">
        </div>
    </form>
    </div>
</div>
