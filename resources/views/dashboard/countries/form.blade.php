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
            <label>@lang('site.code')</label>
            <input type="text" class="form-control @error('code') is-invalid
        @enderror " name="code"
                value="{{ isset($row) ? $row->code : old('code') }}">
            @error('code')
                <small class=" text text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
    <div class="form-group">
        <label>@lang('site.image')</label>
        <input type="file" name="image" class="form-control image @error('image') is-invalid @enderror">
        @error('image')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

<div class="form-group col-md-3">
    <img src="{{ isset($row) ? $row->image_path : asset('uploads/country/default.png') }}" style="width: 115px;height: 80px;position: relative;
                    top: 14px;" class="img-thumbnail image-preview">
</div>
</div>