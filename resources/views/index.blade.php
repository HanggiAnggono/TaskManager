@extends('layout')

@section('body')
<div class="row" id="login-form">
  <div class="medium-4 medium-centered columns panel" >
  @if (Session::has('register_success'))
    <div class="callout success">
      <h5>Success</h5>
      <p>You can now login with your account</p>
    </div>
  @endif
    <form action="login" method="POST">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <div class="row">
        <div class="medium-12 columns">
          <label>Email
            <input type="text" name="email" value="{{ old('email') }}">
          </label>
        </div>    
      </div>
      <div class="row">
        <div class="medium-12 columns">
          <label>Password
            <input type="password" name="password">
          </label>
        </div>
      </div>
      <div class="row">
        <div class="medium-12 columns">
          <input class="button" type="submit" name="submit" value="LOGIN">
          <a href="{{ url('register') }}" class="button">SIGN UP</a>
        </div>
      </div>
      @if (count($errors) > 0)
      <div class='alert callout' data-closable>
        <h5>Whoops!</h5>
        <p class=error-message>
         @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
         @endforeach
       </p>
       <button class='close-button' aria-label='Dismiss alert' type='button' data-close>
        <span aria-hidden='true'>&times;</span>
      </button></div>
      @endif
    </div>    
  </form>

</div>

</div>


@stop  

