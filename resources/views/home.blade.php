@extends('layout')

@section('body')
<div class="top-bar">
  <div class="top-bar-left">
    <ul class="dropdown menu" data-dropdown-menu>
      <li class="menu-text">Tasks Manager</li>
    </ul>
  </div>
  <div class="top-bar-right">
    <ul class="menu">
      <li class="menu-text">{{$user->name}}</li>
      <li><a href="logout" type="button" class="button">LOGOUT</a></li>
    </ul>
  </div>
</div>

<div class="content">
  <div class="row">
    <div class="large-4 columns">
      <div class="panel" id="addTaskPanel">
        Add New Tasks
        <form id="addTaskForm" method="POST">     
        <input type="hidden" name="user_id" value="{{$user->id}}">     
          <div class="row">
            <div class="medium-12 columns">
              <label for="">Title
                <input type="text" name="title">                
              </label>
            </div>
          </div>
          <div class="row">
            <div class="medium-12 columns">
              <label for="">Description
                <textarea name="description" cols="30" rows="5"></textarea>
              </label>
            </div>
          </div>        
          <div class="row">
            <div class="small-7 columns">
              <label for="">Due Date
                <input type="date" name="due_date">
              </label>              
            </div>
            <div class="small-5 columns">
              <label>Set Priority
                <select name="priority">
                  <option value="low" selected>Low</option>
                  <option value="medium">Medium</option>
                  <option value="high">High</option>
                </select>
              </label>
            </div>
          </div>
          <div class="row">
            <div class="medium-12 columns">
              <button type="submit" class="button" id="button-add-task" value="DONE">DONE</button>              
            </div>
          </div>
        </form>
      </div>      
    </div>
    <div class="large-8 columns">      
      <div class="panel">
      <label>Sort By
        <select name="sort">
          <option value="title" selected>Title</option>
          <option value="date">Date</option>
          <option value="description">Description</option>
          <option value="priority">Priority</option>
        </select>
      </label>
      <ul class="tabs" data-tabs id="tasks-tabs">
        <li class="tabs-title"><a href="#today" aria-selected="true">Today</a></li>
        <li class="tabs-title is-active"><a href="#allTask">All Task</a></li>
        <li class="tabs-title"><a href="#completedTask">Completed</a></li>
      </ul>
      <div data-tabs-content="tasks-tabs">
        <div class="tabs-panel" id="today">
          
        </div>
        <div class="tabs-panel is-active" id="allTask">
          
        </div>
        <div class="tabs-panel" id="completedTask">
          
        </div>
      </div>
      </div>
    </div>
  </div>
  @include('taskmodal')
</div>

@stop

