var taskList = {};
var sortKey = 'title';

/*Load Tasks*/
function getAllTask() {
	console.log('fetching tasks');

	$.ajax({
		url: 'tasks',
		type: 'GET',
		dataType: 'json',
	})
	.done(function(data) {
		console.log("success");
		taskList = data.taskList;

		console.log('unsorted');
		console.log(taskList);
		
		sortTask(sortKey);	
		updateView();		
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});	
}

/*Setup csrf token for ajax request, required by Laravel*/
$.ajaxSetup({
	headers : {
		'X-CSRF-TOKEN' : $('#csrfToken').attr('content')
	}
});

/*Add task*/
$("#addTaskForm").submit(function(event) {
	$('.callout').remove();
	event.preventDefault();
	addTask();
});

function addTask() {
	console.log("Adding Task");
	var task = {
		user_id : $('input[name=user_id]').val(),
		title : $('input[name=title]').val(),
		description : $('textarea[name=description]').val(),
		due_date : $('input[name=due_date]').val(),
		priority : $('select[name=priority]').val()
	};

	if(formValidation(task).length == 0){		
		$.ajax({
			url: 'addtask',
			type: 'post',
			dataType: 'json',
			data: task,		
		})
		.done(function(data) {
			console.log("success");
			taskList.push(data.newTask);

			sortTask(sortKey);;
			updateView();
			clearField();
		})
		.fail(function(data) {
			console.log("error");
		})
		.always(function(data) {
			console.log("complete");
		});
	}
	else{
		console.log("Form Validation Error");
		var errorMessage = formValidation(task);
		$('#addTaskPanel').append(
			`<div class='alert callout' data-closable>
			<h5>Whoops!</h5>
			<p class=error-message></p>
			<button class='close-button' aria-label='Dismiss alert' type='button' data-close>
			<span aria-hidden='true'>&times;</span>
			</button></div>`);
		errorMessage.forEach( function(element, index) {
			$('.error-message').append(element);
		});
	}
}

function deleteTask(index) {
	event.preventDefault();

	var task = taskList[index];

	$.ajax({
		url: 'deletetask/'+task.id,
		type: 'DELETE',
	})
	.done(function() {
		console.log("deleted");
		$('#taskModal').foundation('close');
		taskList.splice(index, 1);
		updateView();
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
}

function saveTask(index) {
	
	var checkbox;

	if ($('#taskModal input[name=completed]').is(':checked')) {
		checkbox = 1;
	}
	else{
		checkbox = 0;
	}

	var task = {
		id : $('#taskModal input[name=id]').val(),
		title : $('#taskModal input[name=title]').val(),
		description : $('#taskModal textarea[name=description]').val(),
		due_date : $('#taskModal input[name=due_date]').val(),
		priority : $('#taskModal select[name=priority]').val(),
		completed : checkbox
	};

	$.ajax({
		url: 'updatetask/'+task.id,
		type: 'PUT',
		dataType: 'json',
		data: task,
	})
	.done(function() {		
		console.log("updated");
		taskList[index] = task;
		$('#taskModal').foundation('close');
		updateView();
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});	
}

/*View task detail in a modal*/
function viewDetail(index) {
	console.log("Opening Task");
	$('#taskModal').foundation('open');

	var task = taskList[index];
	var taskDate = new Date(task.due_date).toDateString();
	
	$('#taskModal #taskTitle').html(task.title);
	$('#taskModal #taskDescription').html(task.description);
	$('#taskModal #taskDate').html(taskDate);
	$('#taskModal #taskPriority').html(task.priority);	
	$('#taskModal #btnDelete').attr('onclick', 'deleteTask('+index+')');
	$('#taskModal #btnEdit').click(function (e) {
		console.log('Start edit');
		showModalForm(task);		
	});
	$('#taskModal #btnSave').attr('onclick', 'saveTask('+index+')');
	$('#taskModal #btnSave').hide();

	if (task.completed == '1') {
		console.log('complete');
		$('#taskModal input[name=completed]').prop('checked', true);
	}
	else{
		$('#taskModal input[name=completed]').removeAttr('checked');
	}

	$('#taskModal input[name=completed]').prop('disabled', true);
}

function showModalForm(task) {
	$('#taskModal').append(`<input type="hidden" name="id" value="`+task.id+`">`);

	$('#taskModal #taskTitle').replaceWith(
		`<label id="taskTitle">Title
            <input type="text" name="title" value="`+task.title+`">
      	</label>`);

	$('#taskModal #taskDescription').replaceWith(
		`<label id="taskDescription">Description
                <textarea name="description" cols="30" rows="5">`+task.description+`</textarea>
      	</label>`);

	$('#taskModal #taskDate').replaceWith(
		`<label id="taskDate">Due Date
                <input type="date" name="due_date" value=`+task.due_date+`>
      	</label> `);

	$('#taskModal #taskPriority').replaceWith(
		`<label id="taskPriority">Set Priority
	        <select name="priority" id="modalSelectPriority">
	          <option value="low">Low</option>
	          <option value="medium">Medium</option>
	          <option value="high">High</option>
	        </select>
	      </label>`);

	$('#taskModal input[name=completed]').prop('disabled', false);

	jQuery.each($('select[id=modalSelectPriority] option'), function(index, val) {

	  console.log(val['value']);
	  if (val['value'] == task.priority) {
	  	val['selected'] = true;
	  }

	});
	
	$('#taskModal #btnEdit').hide();
	$('#taskModal #btnSave').show();
}


/*Called when the modal is closed, change the form in modal back to text fields*/
function closeModalForm(){
	$('label#taskTitle').replaceWith(
		`<p class="lead" id="taskTitle"></p>`);

	$('label#taskDescription').replaceWith(
		`<p id="taskDescription"></p>`);

	$('label#taskDate').replaceWith(
		`<div class="task-date" id="taskDate"></div> `);

	$('label#taskPriority').replaceWith(
		`<div class="task-date" id="taskPriority"></div>`);

	$('#taskModal #btnEdit').show();
	$('#taskModal #btnSave').hide();	

	console.log('closed');
}

/*Event for modal when closed*/
$(document).on('closed.zf.reveal', '[data-reveal]', function(event) {
	event.preventDefault();
	console.log('closing modal');
	closeModalForm();
});

/*Validate form input, return list of errors if there's any*/
function formValidation(task)
{
	var errors = [];
	if(task.title == null || task.title == "")
	{
		errors.push("Please give the task a title<br>");
	}
	if(task.due_date == null || task.due_date == "")
	{
		errors.push("Please set the task due date<br>");
	}
	if(task.priority == null || task.priority == "")
	{
		errors.push("Please set the task priority<br>");
	}

	return errors;
}

function clearField(){
	$('input[name=title]').val('');
	$('textarea[name=description]').val('');
	$('input[name=due_date]').val('');
}

$('select[name=sort]').change(function(event) {
	sortKey = $('select[name=sort]').val();
	sortTask(sortKey);
	updateView();
});

function sortTask(key)
{

	if (key == 'title') {
		taskList.sort(function (a, b) {
			if (a.title > b.title) {
			return 1;
			}
			if (a.title < b.title) {
			return -1;
			}
			// a must be equal to b
			return 0;
		});
	}
	else if (key == 'date'){
		taskList.sort(function (a, b) {
			if (a.due_date > b.due_date) {
			return 1;
			}
			if (a.due_date < b.due_date) {
			return -1;
			}
			// a must be equal to b
			return 0;
		});
	}
	else if (key == 'description'){
		taskList.sort(function (a, b) {
			if (a.description > b.description) {
			return 1;
			}
			if (a.description < b.description) {
			return -1;
			}
			// a must be equal to b
			return 0;
		});
	}
	else if (key == 'priority'){
		taskList.sort(function (a, b) {

			var priorityA, priorityB;

			switch (a.priority) {
				case 'low':
					priorityA = 3;
					break;
				case 'medium':
					priorityA = 2;
					break;
				case 'high':
					priorityA = 1;
					break;
				default:
					// statements_def
					break;
			}

			switch (b.priority) {
				case 'low':
					priorityB = 3;
					break;
				case 'medium':
					priorityB = 2;
					break;
				case 'high':
					priorityB = 1;
					break;
				default:
					// statements_def
					break;
			}

			if (priorityA > priorityB) {
			return 1;
			}
			if (priorityA < priorityB) {
			return -1;
			}
			// a must be equal to b
			return 0;
		});
	}

	console.log('soreted');
	console.log(taskList);
}

function updateView(){
	$('#allTask').empty();
	$('#today').empty();
	$('#completedTask').empty();

	console.log(taskList);

	taskList.forEach( function(element, index) {
		var prior = '';
		var taskDate = new Date(element.due_date).toDateString();
		var itemColor = '';

		if(element.priority == 'medium'){
			prior = 'medium-priority';
		}
		else if(element.priority == 'high'){
			prior = 'high-priority';
		}

		if (element.completed == 1) {
			itemColor = 'green';
		}
		else{
			itemColor = '';
		}

		$('#allTask').append(
			`
			<div onclick=viewDetail(`+index+`) class='row task-item `+itemColor+`'>
			<div class='small-8 large-10 columns'>
				<div class='task-title'>`+element.title+`</div class='task-title'>
				<div class='task-date'>`+taskDate+`</div>
			</div>
			<div class='small-4 large-2 columns `+prior+`'>`+element.priority+`</div>
			</div>

			`);	


		/*If the current task element is today, add to today task*/	
		var todayDate = new Date().toDateString();
		if (taskDate == todayDate) {			
			$('#today').append(`
				<div onclick=viewDetail(`+index+`) class='row task-item `+itemColor+`'>
					<div class='small-8 large-10 columns'>
						<div class='task-title'>`+element.title+`</div class='task-title'>
						<div class='task-date'>`+taskDate+`</div>
					</div>
					<div class='small-4 large-2 columns `+prior+`'>`+element.priority+`</div>
				</div>`);
		}

		/*If the current task element is completed, add to completed task*/
		if (element.completed == 1) {			
			$('#completedTask').append(`
				<div onclick=viewDetail(`+index+`) class='row task-item `+itemColor+`'>
					<div class='small-8 large-10 columns'>
						<div class='task-title'>`+element.title+`</div class='task-title'>
						<div class='task-date'>`+taskDate+`</div>
					</div>
					<div class='small-4 large-2 columns `+prior+`'>`+element.priority+`</div>
				</div>`);
		}
	});
}