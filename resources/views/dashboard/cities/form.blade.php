{{ csrf_field() }}
<div class="row" style="margin: 0 !important;">
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
        <label>@lang('site.country_id')</label>
        <select name="country_id"  class='form-control'>
            <option value="">@lang('site.choose_country_id')</option> 
            @foreach(\App\Models\Country::all() as $cou)
                <option value="{{$cou->id}}" @if(isset($row) && $row->country_id==$cou->id) selected  @endif>{{$cou->name}}</option>
            @endforeach
        </select>
        @error('country_id')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
</div>
