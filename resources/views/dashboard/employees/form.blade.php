{{ csrf_field() }}
<div class="row" style="margin: 0 !important;">
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.fullName')</label>
        <input type="text" class="form-control  @error('fullName') is-invalid @enderror" name="fullName"
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
        <input type="email" class="form-control  @error('email') is-invalid @enderror" name="email"
            value="{{ isset($row) ? $row->email : old('email') }}">
        @error('email')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>


<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.password')</label>
        <input type="password" class="form-control  @error('password') is-invalid @enderror" name="password" value="">
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
        <input type="password" class="form-control  @error('password_confirmation') is-invalid @enderror"
            name="password_confirmation" value="">
        @error('password_confirmation')
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
            <option>@lang('site.choose_country_id')</option> 
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
        <label>@lang('site.skills')</label>
        <input type="text" class="form-control  @error('skills') is-invalid @enderror" name="skills"
            value="{{ isset($row) ? $row->skills : old('skills') }}">
        @error('skills')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.languages')</label>
        <input type="text" class="form-control  @error('languages') is-invalid @enderror" name="languages"
            value="{{ isset($row) ? $row->languages : old('languages') }}">
        @error('languages')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.title')</label>
        <input type="text" class="form-control  @error('title') is-invalid @enderror" name="title"
            value="{{ isset($row) ? $row->title : old('title') }}">
        @error('title')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.university')</label>
        <input type="text" required class="form-control  @error('university') is-invalid @enderror" name="university"
            value="{{ isset($row) ? $row->university : old('university') }}">
        @error('university')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

<div class="col-md-12">
    <div class="form-group">
        <label>@lang('site.qualification')</label>
        <textarea name="qualification" class='form-control'>{{isset($row) ? $row->qualification : old('qualification')}}</textarea>
        @error('qualification')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>


<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.graduation_year')</label>
        <select name="graduation_year" class='form-control'>
            @for($i=1990;$i<=2500;$i++)
                <option value="{{$i}}" @if(isset($row) && $row->graduation_year==$i) selected @endif>{{$i}}</option>
            @endfor    
        </select>
        @error('graduation_year')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.deriving_licence')</label>
        <select name="deriving_licence" class='form-control'>
            <option value="0" @if(isset($row) && $row->deriving_licence==0) selected  @endif>@lang('site.no')</option>
            <option value="1" @if(isset($row) && $row->deriving_licence==1) selected  @endif>@lang('site.yes')</option>
        </select> 
    </div>
</div>



<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.category_id')</label>
        <select name="category_id" class='form-control'>
            @foreach(\App\Models\Category::all() as $cat)
                <option value="{{$cat->id}}" @if(isset($row) && ($row->category_id===$cat->id)) selected  @endif>{{$cat->name}}</option>
            @endforeach    
        </select> 
    </div>
</div>



<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.birth')</label>
        <input type="date" required class="form-control  @error('birth') is-invalid @enderror" name="birth"
            value="{{ isset($row) ? $row->birth : old('birth') }}">
        @error('birth')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.gender')</label>
        <select name="gender" class='form-control'>
            <option value="0" @if(isset($row) && $row->gender==0) selected  @endif>@lang('site.male')</option>
            <option value="1" @if(isset($row) && $row->gender==1) selected  @endif>@lang('site.female')</option>
            <option value="2" @if(isset($row) && $row->gender==2) selected  @endif>@lang('site.other')</option>
        </select> 
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.status')</label>
        <select name="block" class='form-control'>
            <option value="0" @if(isset($row) && $row->block==0) selected  @endif>@lang('site.unblocked')</option>
            <option value="1" @if(isset($row) && $row->block==1) selected  @endif>@lang('site.blocked')</option>
        </select> 
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.socialite_id')</label>
        <input type="text" required class="form-control  @error('socialite_id') is-invalid @enderror" name="socialite_id"
            value="{{ isset($row) ? $row->socialite_id : old('socialite_id') }}">
        @error('socialite_id')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.study_field')</label>
        <input type="text" required class="form-control  @error('study_field') is-invalid @enderror" name="study_field"
            value="{{ isset($row) ? $row->study_field : old('study_field') }}">
        @error('study_field')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label>@lang('site.video')</label>
        <input type="file" name="video" class="form-control  @error('video') is-invalid @enderror">
        @error('video')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
<div class="row" style="margin: 0 !important;">
<div class="col-md-3">
    <div class="form-group">
        <label>@lang('site.audio')</label>
        <input type="file" name="audio" class="form-control  @error('audio') is-invalid @enderror">
        @error('audio')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label>@lang('site.cv')</label>
        <input type="file" name="cv" class="form-control  @error('cv') is-invalid @enderror">
        @error('cv')
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
    <img src="{{ isset($row) ? $row->image_path : asset('uploads/employee/image/default.jpg') }}" style="width: 115px;height: 80px;position: relative;
                    top: 14px;" class="img-thumbnail image-preview">
</div>
</div>




