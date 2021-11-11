{{ csrf_field() }}

<div class="row" style="margin: 0 !important;">
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.fullName')</label>
        <input required type="text" class="form-control  @error('fullName') is-invalid @enderror" name="fullName"
            value="{{ isset($row) ? $row->fullName : old('fullName') }}">
        @error('fullName')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>


<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.email')</label>
        <input type="email" required class="form-control  @error('email') is-invalid @enderror" name="email"
            value="{{ isset($row) ? $row->email : old('email') }}">
        @error('email')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
</div>

<div class="row" style="margin: 0 !important;">
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.password')</label>
        <input @if(!isset($row)) required @endif  type="password" class="form-control  @error('password') is-invalid @enderror" name="password" value="">
        @error('password')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.password_confirmation')</label>
        <input type="password" @if(!isset($row)) required @endif class="form-control  @error('password_confirmation') is-invalid @enderror"
            name="password_confirmation" value="">
        @error('password_confirmation')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
</div>
<div class="row" style="margin: 0 !important;">
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.mobile_number1')</label>
        <input type="tel"  required
            class="form-control  @error('mobile_number1') is-invalid @enderror" name="mobile_number1" value="{{ isset($row) ? $row->mobile_number1 : old('mobile_number1') }}">
        @error('mobile_number1')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.mobile_number2')</label>
        <input type="tel"  
            class="form-control  @error('mobile_number2') is-invalid @enderror" name="mobile_number2" value="{{ isset($row) ? $row->mobile_number2 : old('mobile_number2') }}">
        @error('mobile_number2')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
</div>
<div class="row" style="margin: 0 !important;">
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.company_name')</label>
        <input type="text" required class="form-control  @error('company_name') is-invalid @enderror" name="company_name"
            value="{{ isset($row) ? $row->company_name : old('company_name') }}">
        @error('company_name')
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
<div class="row" style="margin: 0 !important;">
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.city_id')</label>
        <select name="city_id"  class='form-control'>
        
        </select>
        @error('city_id')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.title')</label>
        <input type="text" required class="form-control  @error('title') is-invalid @enderror" name="title"
            value="{{ isset($row) ? $row->title : old('title') }}">
        @error('title')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
</div>
<div class="row" style="margin: 0 !important;">
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.category_id')</label>
        <select name="business" required class='form-control'>
            @foreach(\App\Models\Category::all() as $cat)
                <option value="{{$cat->id}}" @if(isset($row) && $row->business==$cat->id) selected  @endif>{{$cat->name}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.established_at')</label>
        <select name="established_at" required class='form-control'>
            @for($i=1800; $i<=date('Y');$i++)
                <option value="{{$i}}" @if(isset($row) && $row->established_at==$i) selected  @endif>{{$i}}</option>
            @endfor
        </select>
        @error('established_at')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
</div>
<div class="row" style="margin: 0 !important;">
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.website')</label>
        <input type="url" required class="form-control  @error('website') is-invalid @enderror" name="website"
            value="{{ isset($row) ? $row->website : old('website') }}">
        @error('website')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.choose_active')</label>
        <select name="active"  class='form-control'>
            <option value="0" style='color:red'  @if(isset($row) && $row->active==0) selected @endif>@lang('site.notACTIVE')</option>
            <option value="1" style='color:blue' @if(isset($row) && $row->active==1) selected @endif>@lang('site.ACTIVE')</option>
        </select>
    </div>
</div>
</div>
<div class="row" style="margin: 0 !important;">
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
    <img src="{{ isset($row) ? $row->image_path :  asset('uploads/employer/image/default.jpg') }}" style="width: 115px;height: 80px;position: relative;
                    top: 14px;" class="img-thumbnail image-preview">
</div>
 </div>

