{{ csrf_field() }}

    <div class="col-md-6">
        <div class="form-group">
            <label>@lang('site.name')</label>
            <input type="text" class="form-control @error('name') is-invalid
        @enderror " name="name"
                value="{{ isset($row) ? $row->name : old('name') }}">
            @error('name')
                <small class=" text text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
            @enderror
        </div>
    </div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.parent_id')</label>
        <select name="parent_id"  class='form-control'>
            <option value="">@lang('site.choose_parent_id')</option> 
            @foreach(\App\Models\Category::where('parent_id',null)->get() as $cat)
                <option value="{{$cat->id}}" @if(isset($row) && $row->parent_id==$cat->id) selected  @endif>{{$cat->name}}</option>
            @endforeach
        </select>
        @error('parent_id')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
