@extends('layout')

@section('body')
<div class="row" id="register-form" >  
  <div class="medium-4 medium-centered columns panel" >
  <form action="register" method="POST">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="row">
      <div class="medium-12 columns">
        <label>Name
          <input type="text" name="name">
        </label>
      </div>    
    </div>
    <div class="row" >
      <div class=" columns">
        <label>Email
          <input type="text" name="email" placeholder="Some random email...">
        </label>
      </div>    
    </div>
    <div class="row" >
      <div class=" columns">
        <label>Password
          <input type="password" name="password">
        </label>
      </div>
    </div>
    <div class="row" >
      <div class=" columns">
        <label>Confirm Password
          <input type="password" name="password_confirmation">
        </label>
      </div>
    </div>
    <div class="row">
      <div class="medium-12 columns">
        <input class="button" type="submit" name="submit" value="REGISTER">
        <a href="{{ url('/') }}" class="button">BACK</a>
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
  </form> 
  </div>
</div>


@stop  

